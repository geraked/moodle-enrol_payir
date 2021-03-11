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
 * Strings for component 'enrol_payir', language 'fa'.
 *
 * @package    enrol_payir
 * @copyright  2021 Geraked
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['payircat'] = 'درگاه پرداخت Pay.ir';
$string['toman'] = 'تومان';
$string['cardnumber'] = 'شماره کارت';
$string['factornumber'] = 'شماره فاکتور';
$string['buyid'] = 'شناسه خرید';
$string['amounttoman'] = 'مبلغ (تومان)';
$string['paymentsorry'] = 'عملیات ثبت‌نام ناموفق بود. خطایی رخ داده است!';
$string['assignrole'] = 'انتصاب نقش';
$string['api'] = 'مقدار API';
$string['api_desc'] = 'مقدار api که از سایت pay.ir دریافت کرده‌اید را وارد کنید.';
$string['cost'] = 'هزینه ثبت‌نام';
$string['costerror'] = 'هزینه ثبت‌نام، مقداری عددی است.';
$string['costorkey'] = 'لطفاً یکی از روش‌های ثبت‌نام را انتخاب کنید';
$string['currency'] = 'واحد';
$string['defaultrole'] = 'نقش پیش‌فرض';
$string['defaultrole_desc'] = 'نقشی که هنگام ثبت‌نام باید به کاربران داده شود را انتخاب نمایید.';
$string['enrolenddate'] = 'تاریخ پایان';
$string['enrolenddate_help'] = 'اگر فعال باشد، تنها در بازه این تاریخ امکان ثبت‌نام دارند.';
$string['enrolenddaterror'] = 'تاریخ پایان ثبت‌نام نمی‌تواند زودتر از تاریخ شروع باشد';
$string['enrolperiod'] = 'مدت ثبت‌نام';
$string['enrolperiod_desc'] = 'مقدار پیش‌فرض مدت ثبت‌نام. اگر صفر باشد، به معنای عدم محدودیت است.';
$string['enrolperiod_help'] = 'مدت زمانی که ثبت‌نام کاربر معتبر است. شروع از زمانی که کاربر ثبت‌نام می‌کند. اگر غیرفعال باشد، محدودیتی برای مدت زمان ثبت‌نام وجود ندارد.';
$string['enrolstartdate'] = 'تاریخ شروع';
$string['enrolstartdate_help'] = 'اگر فعال باشد، تنها در بازه این تاریخ امکان ثبت‌نام دارند.';
$string['errdisabled'] = 'افزونه Pay.ir غیرفعال است و.';
$string['erripninvalid'] = 'پرداخت با Pay.ir وریفای نشده است.';
$string['errpayirconnect'] = 'برقراری ارتباط با {$a->url} جهت وریفای تراکنش، ناموفق بود: {$a->result}';
$string['expiredaction'] = 'اقدام پس از انقضای ثبت‌نام';
$string['expiredaction_help'] = 'انتخاب اینکه وقتی ثبت‌نام منقضی می‌شود، چه عملی انجام شود. مراقب باشید، ممکن است اطلاعات ثبت‌نامی کابر در دوره حذف شود.';
$string['mailadmins'] = 'اطلاع‌رسانی به مدیر';
$string['mailstudents'] = 'اطلاع‌رسانی به دانش‌آموزان';
$string['mailteachers'] = 'اطلاع‌رسانی به معلمان';
$string['messageprovider:payir_enrolment'] = 'پیام‌های ثبت‌نام Pay.ir';
$string['nocost'] = 'هیچ هزینه‌ای جهت ثبت‌نام در این دوره درنظر گرفته نشده است!';
$string['payir:config'] = 'پیکربندی نمونه‌های ثبت‌نام Pay.ir';
$string['payir:manage'] = 'مدیریت کاربران ثبت‌نام شده';
$string['payir:unenrol'] = 'لغو ثبت‌نام کاربران از دوره';
$string['payir:unenrolself'] = 'لغو ثبت‌نام خود از دوره';
$string['payiraccepted'] = 'پرداخت‌های Pay.ir پذیرفته می‌شود';
$string['pluginname'] = 'Pay.ir';
$string['pluginname_desc'] = 'این افزونه، امکان ثبت‌نام کاربران در دوره‌ها را با پرداخت از طریق درگاه Pay.ir فراهم می‌کند.';
$string['processexpirationstask'] = 'ارسال اعلانات انقضای ثبت‌نام Pay.ir';
$string['sendpaymentbutton'] = 'پرداخت';
$string['status'] = 'اجازه پرداخت از طریق Pay.ir';
$string['status_desc'] = 'به کاربران اجازه دهید از Pay.ir به‌طور پیش‌فرض برای ثبت‌نام در دوره استفاده کنند.';
$string['transactions'] = 'تراکنش‌های Pay.ir';
$string['unenrolselfconfirm'] = 'آیا واقعاً می‌خواهید از دوره "{$a}" لغو ثبت‌نام کنید؟';
