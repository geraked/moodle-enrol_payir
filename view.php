<?php

/**
 * Payir transactions table view
 *
 * @package    enrol_payir
 * @copyright  2021 Geraked
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once("$CFG->libdir/tablelib.php");
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/authlib.php');
require_once("view_table.php");

admin_externalpage_setup('enrol_payir_view');

$download = optional_param('download', '', PARAM_ALPHA);

if (!is_siteadmin($USER)) {
    print_error('nopermissions', 'error', '', 'view payir');
}

$context = context_system::instance();
$url = new moodle_url('/enrol/payir/view.php', []);

$PAGE->set_context($context);
$PAGE->set_url($url);

$table = new view_table('uniqueid');
$table->is_downloading($download, 'payir', 'payir123');
$table->sort_default_column = 'timeupdated';
$table->sort_default_order = SORT_DESC;

if (!$table->is_downloading()) {
    $PAGE->set_title(get_string('payments'));
    $PAGE->set_heading(get_string('payments'));
    echo $OUTPUT->header();
}

$table->set_sql('*', "{enrol_payir}", '1=1');
$table->define_baseurl($url);
$table->out(10, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}
