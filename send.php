<?php

/**
 * Payir utils
 *
 * @package    enrol_payir
 * @copyright  2021 Geraked
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once("pay.php");

global $CFG, $SESSION;

// PayIr does not like when we return error messages here,
// the custom handler just logs exceptions and stops.
set_exception_handler(\enrol_payir\util::get_exception_handler());

$userName       = $_POST['first_name'] . ' ' . $_POST['last_name'];
$courseName     = $_POST['course_short_name'];
$description    = "$courseName - $userName";
$factorNumber   = $_POST['custom'];
$amount         = $_POST['amount'] * 10;
$api            = $SESSION->payir_api;
$mobile         = "";

$currency_code = $_POST['currency_code'];
$redirect = "$CFG->wwwroot/enrol/payir/ipn.php?custom=$factorNumber&currency_code=$currency_code";

$SESSION->payir_return = $_POST['return'];

$result = send($api, $amount, $redirect, $mobile, $factorNumber, $description);
$result = json_decode($result);

if ($result->status) {
    $go = "https://pay.ir/pg/$result->token";
    header("Location: $go");
} else {
    throw new moodle_exception('invalidrequest', 'core_error');
    // echo $result->errorMessage;
}
