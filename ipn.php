<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Listens for Instant Payment Notification from PayIr
 *
 * This script waits for Payment notification from PayIr,
 * then double checks that data by sending it back to PayIr.
 * If PayIr verifies this then it sets up the enrolment for that
 * user.
 *
 * @package     enrol_payir
 * @copyright   2021 Geraked
 * @author      Rabist
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Disable moodle specific debug messages and any errors in output,
// comment out when debugging or better look into error log!
define('NO_DEBUG_DISPLAY', true);

// @codingStandardsIgnoreLine This script does not require login.
require_once("../../config.php");
require_once("lib.php");
require_once("pay.php");
require_once("sapak.php");
require_once($CFG->libdir . '/enrollib.php');
require_once($CFG->libdir . '/filelib.php');

global $CFG, $SESSION;

// PayIr does not like when we return error messages here,
// the custom handler just logs exceptions and stops.
set_exception_handler(\enrol_payir\util::get_exception_handler());

// Make sure we are enabled in the first place.
if (!enrol_is_enabled('payir')) {
    http_response_code(503);
    throw new moodle_exception('errdisabled', 'enrol_payir');
}

// Keep out casual intruders
if (!empty($_POST) or empty($_GET)) {
    http_response_code(400);
    throw new moodle_exception('invalidrequest', 'core_error');
}

$data = new stdClass();

foreach ($_GET as $key => $value) {
    if ($key !== clean_param($key, PARAM_ALPHANUMEXT)) {
        throw new moodle_exception('invalidrequest', 'core_error', '', null, $key);
    }
    if (is_array($value)) {
        throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Unexpected array param: ' . $key);
    }
    $data->$key = fix_utf8($value);
}

if (!isset($data->status)) {
    throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Missing request param: status');
}

if (!isset($data->token)) {
    throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Missing request param: token');
}

if (!isset($data->custom) || !isset($data->currency_code)) {
    throw new moodle_exception('invalidrequest', 'core_error', '', null, 'Missing request param: SESSION');
}

$custom         = explode('-', $data->custom);
$currency_code  = $data->currency_code;
$status         = $data->status;
$token          = $data->token;

unset($data->custom);
unset($data->currency_code);
unset($data->token);

$data->userid           = (int) $custom[0];
$data->courseid         = (int) $custom[1];
$data->instanceid       = (int) $custom[2];
$data->timeupdated      = time();

$SESSION->payir_return = "$CFG->wwwroot/enrol/payir/return.php?id=$data->courseid";
if ($status == 0) {
    throw new moodle_exception('erripninvalid', 'enrol_payir', '', null, 'Status is 0');
}

$user       = $DB->get_record("user", array("id" => $data->userid), "*", MUST_EXIST);
$course     = $DB->get_record("course", array("id" => $data->courseid), "*", MUST_EXIST);
$context    = context_course::instance($course->id, MUST_EXIST);

$PAGE->set_context($context);

$plugin_instance = $DB->get_record("enrol", array("id" => $data->instanceid, "enrol" => "payir", "status" => 0), "*", MUST_EXIST);
$plugin = enrol_get_plugin('payir');

/// Open a connection back to Pay.ir to validate the data
$result = json_decode(verify($plugin->get_config('api'), $token));
if (isset($result->status)) {
    if ($result->status == 1) {
        $data->status           = $result->status;
        $data->amount           = $result->amount / 10;
        $data->transid          = $result->transId;
        $data->factornumber     = $result->factorNumber;
        $data->description      = $result->description;
        $data->cardnumber       = $result->cardNumber;

        // If currency is incorrectly set then someone maybe trying to cheat the system
        if ($currency_code != $plugin_instance->currency) {
            \enrol_payir\util::message_payir_error_to_admin(
                "Currency does not match course settings, received: " . $currency_code,
                $data
            );
            die;
        }

        // Make sure this transaction doesn't exist already.
        if ($existing = $DB->get_record("enrol_payir", array("transid" => $data->transid), "*", IGNORE_MULTIPLE)) {
            \enrol_payir\util::message_payir_error_to_admin("Transaction $data->transid is being repeated!", $data);
            die;
        }

        // Check that user exists
        if (!$user = $DB->get_record('user', array('id' => $data->userid))) {
            \enrol_payir\util::message_payir_error_to_admin("User $data->userid doesn't exist", $data);
            die;
        }

        // Check that course exists
        if (!$course = $DB->get_record('course', array('id' => $data->courseid))) {
            \enrol_payir\util::message_payir_error_to_admin("Course $data->courseid doesn't exist", $data);
            die;
        }

        $coursecontext = context_course::instance($course->id, IGNORE_MISSING);

        // Check that amount paid is the correct amount
        if ((int) $plugin_instance->cost <= 0) {
            $cost = (int) $plugin->get_config('cost');
        } else {
            $cost = (int) $plugin_instance->cost;
        }

        // Use the same rounding of floats as on the enrol form.
        if ($data->amount < $cost) {
            \enrol_payir\util::message_payir_error_to_admin("Amount paid is not enough ($data->amount < $cost))", $data);
            die;
        }

        // ALL CLEAR !
        $DB->insert_record("enrol_payir", $data);

        if ($plugin_instance->enrolperiod) {
            $timestart = time();
            $timeend   = $timestart + $plugin_instance->enrolperiod;
        } else {
            $timestart = 0;
            $timeend   = 0;
        }

        // Enrol user
        $plugin->enrol_user($plugin_instance, $user->id, $plugin_instance->roleid, $timestart, $timeend);

        // Pass $view=true to filter hidden caps if the user cannot see them
        if ($users = get_users_by_capability(
            $context,
            'moodle/course:update',
            'u.*',
            'u.id ASC',
            '',
            '',
            '',
            '',
            false,
            true
        )) {
            $users = sort_by_roleassignment_authority($users, $context);
            $teacher = array_shift($users);
        } else {
            $teacher = false;
        }

        $mailstudents = $plugin->get_config('mailstudents');
        $mailteachers = $plugin->get_config('mailteachers');
        $mailadmins   = $plugin->get_config('mailadmins');
        $shortname = format_string($course->shortname, true, array('context' => $context));

        if (!empty($mailstudents)) {
            $a = new stdClass();
            $a->coursename = format_string($course->fullname, true, array('context' => $coursecontext));
            $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id";

            $eventdata = new \core\message\message();
            $eventdata->courseid          = $course->id;
            $eventdata->modulename        = 'moodle';
            $eventdata->component         = 'enrol_payir';
            $eventdata->name              = 'payir_enrolment';
            $eventdata->userfrom          = empty($teacher) ? core_user::get_noreply_user() : $teacher;
            $eventdata->userto            = $user;
            $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
            $eventdata->fullmessage       = get_string('welcometocoursetext', '', $a);
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml   = '';
            $eventdata->smallmessage      = '';
            message_send($eventdata);
        }

        if (!empty($mailteachers) && !empty($teacher)) {
            $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
            $a->user = fullname($user);

            $eventdata = new \core\message\message();
            $eventdata->courseid          = $course->id;
            $eventdata->modulename        = 'moodle';
            $eventdata->component         = 'enrol_payir';
            $eventdata->name              = 'payir_enrolment';
            $eventdata->userfrom          = $user;
            $eventdata->userto            = $teacher;
            $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
            $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml   = '';
            $eventdata->smallmessage      = '';
            message_send($eventdata);
        }

        if (!empty($mailadmins)) {
            $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
            $a->user = fullname($user);
            $admins = get_admins();
            foreach ($admins as $admin) {
                $eventdata = new \core\message\message();
                $eventdata->courseid          = $course->id;
                $eventdata->modulename        = 'moodle';
                $eventdata->component         = 'enrol_payir';
                $eventdata->name              = 'payir_enrolment';
                $eventdata->userfrom          = $user;
                $eventdata->userto            = $admin;
                $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
                $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml   = '';
                $eventdata->smallmessage      = '';
                message_send($eventdata);
            }

            $url = $SESSION->payir_return;
            unset($SESSION->payir_return);
            redirect($url);
        }
    } else {
        throw new moodle_exception('erripninvalid', 'enrol_payir', '', null, json_encode($result));
    }
} else {
    throw new moodle_exception(
        'errpayirconnect',
        'enrol_payir',
        '',
        array('url' => 'pay.ir', 'result' => $result),
        json_encode($data)
    );
}
