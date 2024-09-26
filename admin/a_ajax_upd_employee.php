<?php
// Start session
session_start();

require '../connect.php'; // Include database connection file
date_default_timezone_set('Asia/Bangkok'); // เวลาไทย

$updDate = date("Y-m-d H:i:s");

$usercode = $_POST['usercode'];
$name = $_POST['name'];
$department = $_POST['department'];
$yearexp = $_POST['yearexp'];
$level = $_POST['level'];
$email = $_POST['email'];
$id_line = $_POST['id_line'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];
$updUsername = $_POST['updUsername'];
$personal = $_POST['personal'];
$personalNo = $_POST['personalNo'];
$sick = $_POST['sick'];
$sickWork = $_POST['sickWork'];
$annual = $_POST['annual'];
$other = $_POST['other'];
$workplaceName = $_POST['workplaceName'];

$sql = "UPDATE employees SET e_name = '$name', e_department = '$department', e_yearexp = '$yearexp', e_level = '$level', e_email = '$email',
e_phone = '$phone' , e_username = '$username' , e_password = '$password' , e_upd_name = '$updUsername' , e_upd_datetime = '$updDate' ,
e_leave_personal = '$personal', e_leave_personal_no = '$personalNo', e_leave_sick = '$sick' , e_leave_sick_work = '$sickWork' , e_leave_annual = '$annual' , e_other = '$other'
, e_workplace = '$workplaceName'
WHERE e_usercode = '$usercode'";

$conn->query($sql) === true;
echo 'Employee data updated successfully.';