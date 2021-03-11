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
 * Strings for component 'enrol_payir', language 'en'.
 *
 * @package    enrol_payir
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['payircat'] = 'Pay.ir';
$string['toman'] = 'Toman';
$string['cardnumber'] = 'Card Number';
$string['factornumber'] = 'Factor Number';
$string['buyid'] = 'Buy ID';
$string['amounttoman'] = 'Amount (Toman)';
$string['paymentsorry'] = 'The payment was not successful!';
$string['assignrole'] = 'Assign role';
$string['api'] = 'Pay.ir API';
$string['api_desc'] = 'The api key of your pay.ir account';
$string['cost'] = 'Enrol cost';
$string['costerror'] = 'The enrolment cost is not numeric';
$string['costorkey'] = 'Please choose one of the following methods of enrolment.';
$string['currency'] = 'Currency';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select role which should be assigned to users during Pay.ir enrolments';
$string['enrolenddate'] = 'End date';
$string['enrolenddate_help'] = 'If enabled, users can be enrolled until this date only.';
$string['enrolenddaterror'] = 'Enrolment end date cannot be earlier than start date';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can be enrolled from this date onward only.';
$string['errdisabled'] = 'The Pay.ir enrolment plugin is disabled and does not handle payment notifications.';
$string['erripninvalid'] = 'Instant payment notification has not been verified by Pay.ir.';
$string['errpayirconnect'] = 'Could not connect to {$a->url} to verify the instant payment notification: {$a->result}';
$string['expiredaction'] = 'Enrolment expiry action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['mailadmins'] = 'Notify admin';
$string['mailstudents'] = 'Notify students';
$string['mailteachers'] = 'Notify teachers';
$string['messageprovider:payir_enrolment'] = 'Pay.ir enrolment messages';
$string['nocost'] = 'There is no cost associated with enrolling in this course!';
$string['payir:config'] = 'Configure Pay.ir enrol instances';
$string['payir:manage'] = 'Manage enrolled users';
$string['payir:unenrol'] = 'Unenrol users from course';
$string['payir:unenrolself'] = 'Unenrol self from the course';
$string['payiraccepted'] = 'Pay.ir payments accepted';
$string['pluginname'] = 'Pay.ir';
$string['pluginname_desc'] = 'The Pay.ir module allows you to set up paid courses.  If the cost for any course is zero, then students are not asked to pay for entry.  There is a site-wide cost that you set here as a default for the whole site and then a course setting that you can set for each course individually. The course cost overrides the site cost.';
$string['processexpirationstask'] = 'Pay.ir enrolment send expiry notifications task';
$string['sendpaymentbutton'] = 'Send payment via Pay.ir';
$string['status'] = 'Allow Pay.ir enrolments';
$string['status_desc'] = 'Allow users to use Pay.ir to enrol into a course by default.';
$string['transactions'] = 'Pay.ir transactions';
$string['unenrolselfconfirm'] = 'Do you really want to unenrol yourself from course "{$a}"?';
