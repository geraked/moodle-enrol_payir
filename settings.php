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
 * Payir enrolments plugin settings and presets.
 *
 * @package    enrol_payir
 * @copyright  2021 Geraked
 * @author     Rabist
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    //--- settings ------------------------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_payir_settings', '', get_string('pluginname_desc', 'enrol_payir')));

    $settings->add(new admin_setting_configtext('enrol_payir/api', get_string('api', 'enrol_payir'), get_string('api_desc', 'enrol_payir'), 'test', PARAM_ALPHANUMEXT));

    $settings->add(new admin_setting_configcheckbox('enrol_payir/mailstudents', get_string('mailstudents', 'enrol_payir'), '', 0));

    $settings->add(new admin_setting_configcheckbox('enrol_payir/mailteachers', get_string('mailteachers', 'enrol_payir'), '', 0));

    $settings->add(new admin_setting_configcheckbox('enrol_payir/mailadmins', get_string('mailadmins', 'enrol_payir'), '', 0));

    // Note: let's reuse the ext sync constants and strings here, internally it is very similar,
    //       it describes what should happen when users are not supposed to be enrolled any more.
    $options = array(
        ENROL_EXT_REMOVED_KEEP           => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol'),
        ENROL_EXT_REMOVED_UNENROL        => get_string('extremovedunenrol', 'enrol'),
    );
    $settings->add(new admin_setting_configselect('enrol_payir/expiredaction', get_string('expiredaction', 'enrol_payir'), get_string('expiredaction_help', 'enrol_payir'), ENROL_EXT_REMOVED_SUSPENDNOROLES, $options));

    //--- enrol instance defaults ----------------------------------------------------------------------------
    $settings->add(new admin_setting_heading(
        'enrol_payir_defaults',
        get_string('enrolinstancedefaults', 'admin'),
        get_string('enrolinstancedefaults_desc', 'admin')
    ));

    $options = array(
        ENROL_INSTANCE_ENABLED  => get_string('yes'),
        ENROL_INSTANCE_DISABLED => get_string('no')
    );
    $settings->add(new admin_setting_configselect(
        'enrol_payir/status',
        get_string('status', 'enrol_payir'),
        get_string('status_desc', 'enrol_payir'),
        ENROL_INSTANCE_DISABLED,
        $options
    ));

    $settings->add(new admin_setting_configtext('enrol_payir/cost', get_string('cost', 'enrol_payir'), '', 0, PARAM_INT, 8));

    $payircurrencies = enrol_get_plugin('payir')->get_currencies();
    $settings->add(new admin_setting_configselect('enrol_payir/currency', get_string('currency', 'enrol_payir'), '', 'IRT', $payircurrencies));

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect(
            'enrol_payir/roleid',
            get_string('defaultrole', 'enrol_payir'),
            get_string('defaultrole_desc', 'enrol_payir'),
            $student->id ?? null,
            $options
        ));
    }

    $settings->add(new admin_setting_configduration(
        'enrol_payir/enrolperiod',
        get_string('enrolperiod', 'enrol_payir'),
        get_string('enrolperiod_desc', 'enrol_payir'),
        0
    ));
};

$ADMIN->add('root', new admin_category('payir', get_string('payircat', 'enrol_payir')));
$ADMIN->add('payir', new admin_externalpage('enrol_payir_view', get_string('payments'), $CFG->wwwroot . '/enrol/payir/view.php'));
$ADMIN->add('payir', new admin_externalpage('enrol_payir_settings', get_string('settings'), $CFG->wwwroot . '/admin/settings.php?section=enrolsettingspayir'));
