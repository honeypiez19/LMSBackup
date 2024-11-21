<?php
// add_employee.php
require 'connect.php'; // Include your database connection file

$usercode = $_POST['add_usercode'];
$username = $_POST['add_username'];
$password = $_POST['add_password'];
$name = $_POST['add_name'];
$department = $_POST['add_department'];
$yearexp = $_POST['add_yearexp'];
$add_level = $_POST['add_level'];
$email = $_POST['add_email'];
$phone = $_POST['add_phone'];
$personal = $_POST['add_personal'];
$personal_no = $_POST['add_personal_no'];
$sick = $_POST['add_sick'];
$sick_work = $_POST['add_sick_work'];
$annual = $_POST['add_annual'];
$other = $_POST['add_other'];

// Map department ID to department name
$department_names = [
    1 => 'RD',
    2 => 'HR',
    3 => 'Sales',
    4 => 'Purchase',
    5 => 'Store',
    6 => 'CAD1',
    7 => 'CAD2',
    8 => 'CAM',
    9 => 'Production',
    10 => 'QC',
    11 => 'Account',
    12 => 'Machine',
    13 => 'Finishing',
];

$add_department_name = $department_names[$department];

// Map level ID to level name
$level_names = [
    1 => 'user',
    2 => 'chief',
    3 => 'manager',
    4 => 'admin',
];

$add_level_name = $level_names[$add_level];
// Insert into database
$sql = "INSERT INTO employees (e_usercode, e_username, e_password, e_name, e_department, e_yearexp, e_level, e_email, e_phone, e_leave_personal, e_leave_personal_no, e_leave_sick, e_leave_sick_work, e_leave_annual, e_other)
VALUES (:usercode, :username, :password, :name, :department, :yearexp, :add_level, :email, :phone, :personal, :personal_no, :sick, :sick_work, :annual, :other)";
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bindParam(':usercode', $usercode);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':department', $add_department_name); // Use department name here
$stmt->bindParam(':yearexp', $yearexp);
$stmt->bindParam(':add_level', $add_level_name); // Use level name here
$stmt->bindParam(':email', $email);
$stmt->bindParam(':phone', $phone);
$stmt->bindParam(':personal', $personal);
$stmt->bindParam(':personal_no', $personal_no);
$stmt->bindParam(':sick', $sick);
$stmt->bindParam(':sick_work', $sick_work);
$stmt->bindParam(':annual', $annual);
$stmt->bindParam(':other', $other);

if ($stmt->execute()) {
    echo "พนักงานถูกเพิ่มเรียบร้อยแล้ว";
} else {
    echo "เกิดข้อผิดพลาดในการเพิ่มพนักงาน";
}
