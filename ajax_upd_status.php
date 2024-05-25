<?php
// Start session
session_start();

require 'connect.php'; // Include database connection file
date_default_timezone_set('Asia/Bangkok'); // เวลาไทย

$firmDate = date("Y-m-d H:i:s");

// รับค่าที่ส่งมาจาก AJAX
$userCode = $_POST['userCode'];
$createDate = $_POST['createDate'];
$checkFirm = $_POST['checkFirm'];
$userName = $_POST['userName'];

$pass = '1';
$passNo = '2';

if ($checkFirm == '1') {
    $sql = "UPDATE leave_items SET Confirm_status = '$pass' , Confirm_datetime = '$firmDate' , Confirm_name = '$userName'
    WHERE Emp_usercode = '$userCode' AND Create_datetime = '$createDate'";

    if ($conn->query($sql)) {
        echo "อัปเดตสถานะผ่านสำเร็จ";
    } else {
        echo 'อัปเดตสถานะผ่านไม่สำเร็จ';
    }
} else if ($checkFirm == '2') {
    $sql = "UPDATE leave_items SET Confirm_status = '$passNo' , Confirm_datetime = '$firmDate' , Confirm_name = '$userName'
    WHERE Emp_usercode = '$userCode' AND Create_datetime = '$createDate'";

    if ($conn->query($sql)) {
        echo "อัปเดตสถานะไม่ผ่านสำเร็จ";
    } else {
        echo "อัปเดตสถานะไม่ผ่านไม่สำเร็จ";
    }
} else {
    echo "ไม่มีสถานะนี้";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn = null;