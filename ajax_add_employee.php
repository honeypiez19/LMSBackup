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

if ($add_department == 1) {
    $add_department_name = 'RD';
} elseif ($add_department == 2) {
    $add_department_name = 'HR';
} elseif ($add_department == 3) {
    $add_department_name = 'Sales';
} elseif ($add_department == 4) {
    $add_department_name = 'Purchase';
} elseif ($add_department == 5) {
    $add_department_name = 'Store';
} elseif ($add_department == 6) {
    $add_department_name = 'CAD1';
} elseif ($add_department == 7) {
    $add_department_name = 'CAD2';
} elseif ($add_department == 8) {
    $add_department_name = 'CAM';
} elseif ($add_department == 9) {
    $add_department_name = 'Production';
} elseif ($add_department == 10) {
    $add_department_name = 'QC';
} elseif ($add_department == 11) {
    $add_department_name = 'Account';
} elseif ($add_department == 12) {
    $add_department_name = 'Machine';
} elseif ($add_department == 13) {
    $add_department_name = 'Finishing';
} else {
    $add_department_name = 'Unknown';
}

if ($add_level == 1) {
    $add_level_name = 'user';
} elseif ($add_level == 2) {
    $add_level_name = 'chief';
} elseif ($add_level == 3) {
    $add_level_name = 'manager';
} elseif ($add_level == 4) {
    $add_level_name = 'admin';
} else {
    $add_level_name = 'Unknown';
}

// Insert into employee table
$sql_employee = "INSERT INTO employee (Emp_usercode, Emp_username, Emp_password, Emp_name, Emp_department,
        Emp_yearexp, Emp_level, Emp_email, Emp_id_line, Emp_phone, Emp_status, Add_datetime , Add_name, Leave_personal , Leave_personal_no,Leave_sick,Leave_sick_work,Leave_annual,Other)
        VALUES ('$add_usercode', '$add_username', '$add_password', '$add_name', '$add_department_name',
        '$add_yearexp', '$add_level_name', '$add_email', '$add_id_line', '$add_phone', '$add_status', '$add_date', '$addUsername',
        '$add_personal', '$add_personal_no', '$add_sick', '$add_sick_work', '$add_annual', '$add_other')";

// Execute the SQL query for employee table
if ($conn->exec($sql_employee)) {
    // Insert into employee_session table
    $sql_session = "INSERT INTO employee_session (Emp_usercode, Emp_username, Emp_password, Emp_name, Emp_department, Emp_level)
                    VALUES ('$add_usercode', '$add_username', '$add_password', '$add_name', '$add_department_name', '$add_level_name')";

    // Execute the SQL query for employee_session table
    if ($conn->exec($sql_session)) {
        echo "New record created successfully in both tables";
    } else {
        echo "Error: " . $sql_session . "<br>" . $conn->errorInfo()[2];
    }
} else {
    echo "Error: " . $sql_employee . "<br>" . $conn->errorInfo()[2];
}