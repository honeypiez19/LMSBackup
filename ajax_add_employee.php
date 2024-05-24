<?php
// Include your database connection file
require 'connect.php';
date_default_timezone_set('Asia/Bangkok'); // Set the timezone to Asia/Bangkok

$add_date = date("Y-m-d H:i:s");
$addUsername = $_POST['addUsername'];

$add_usercode = $_POST['add_usercode'];
$add_username = $_POST['add_username'];
$add_password = $_POST['add_password'];
$add_name = $_POST['add_name'];
$add_department = $_POST['add_department'];
$add_yearexp = $_POST['add_yearexp'];
$add_level = $_POST['add_level'];
$add_email = $_POST['add_email'];
$add_id_line = $_POST['add_id_line'];
$add_phone = $_POST['add_phone'];
$add_status = 1;

$add_personal = $_POST['add_personal'];
$add_personal_no = $_POST['add_personal_no'];
$add_sick = $_POST['add_sick'];
$add_sick_work = $_POST['add_sick_work'];
$add_annual = $_POST['add_annual'];
$add_other = $_POST['add_other'];

$sql = "INSERT INTO employee (Emp_usercode, Emp_username, Emp_password, Emp_name, Emp_department,
        Emp_yearexp, Emp_level, Emp_email, Emp_id_line, Emp_phone, Emp_status, Add_datetime , Add_name, Leave_personal , Leave_personal_no,Leave_sick,Leave_sick_work,Leave_annual,Other)
        VALUES ('$add_usercode', '$add_username', '$add_password', '$add_name', '$add_department',
        '$add_yearexp', '$add_level', '$add_email', '$add_id_line', '$add_phone', '$add_status', '$add_date', '$addUsername'
        , '$add_personal', '$add_personal_no', '$add_sick', '$add_sick_work', '$add_annual', '$add_other')";

// Execute the SQL query
if ($conn->exec($sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->errorInfo()[2];
}
