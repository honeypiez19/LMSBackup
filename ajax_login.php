<?php
session_start();
date_default_timezone_set('Asia/Bangkok'); // Set the timezone to Asia/Bangkok

include 'connect.php';

// $usercode = '6608418';
// $password = '1234';

$userCode = $_POST['userCode'];
$passWord = $_POST['passWord'];

// สร้างคำสั่ง SQL เพื่อตรวจสอบ usercode และ password
$sql = "SELECT * FROM employee_session WHERE Emp_usercode ='$userCode' AND Emp_password='$passWord'";
$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    // อัปเดตเวลาที่เข้าสู่ระบบ
    $loginTime = date('Y-m-d H:i:s');
    $updateSql = "UPDATE employee_session SET Login_datetime = '$loginTime' WHERE Emp_usercode ='$userCode'";
    $conn->query($updateSql);

    // กำหนด session สำหรับ usercode
    $_SESSION['Emp_usercode'] = $userCode;

    // ตรวจสอบระดับการเข้าใช้งาน (admin/user)
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if ($row['Emp_level'] == 'admin') {
        echo 'admin';
    } elseif ($row['Emp_level'] == 'user') {
        echo 'user';
    }
} else {
    echo 'error';
}

$conn = null;