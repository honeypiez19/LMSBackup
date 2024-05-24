<?php
// Start session
session_start();

require 'connect.php'; // Include database connection file
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

$sql = "UPDATE employee SET Emp_name = '$name', Emp_department = '$department', Emp_yearexp = '$yearexp', Emp_level = '$level', Emp_email = '$email',
Emp_id_line = '$id_line', Emp_phone = '$phone' , Emp_username = '$username' , Emp_password = '$password' , Update_name = '$updUsername' , Update_datetime = '$updDate' ,
Leave_personal = '$personal', Leave_personal_no = '$personalNo', Leave_sick = '$sick' , Leave_sick_work = '$sickWork' , Leave_annual = '$annual' , Other = '$other'
WHERE Emp_usercode = '$usercode'";

$conn->query($sql) === true;
echo 'Employee data updated successfully.';
