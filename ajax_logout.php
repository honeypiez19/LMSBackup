<?php
// เชื่อมต่อฐานข้อมูล
include 'connect.php';
session_start();

date_default_timezone_set('Asia/Bangkok'); // เวลาไทย

$dateLogout = date("Y-m-d H:i:s");

// รับค่าชื่อผู้ใช้จาก Ajax request
$username = $_POST['Username'];

// ทำการลบ Session ที่มีชื่อว่า "username"
// unset($_SESSION['Username']);

// ส่งคำตอบกลับไปยังเว็บไซต์

// ทำการ insert dateLogout ลง table users คอลัมน์ Date_logout
$updDateLogout = "UPDATE session SET s_logout_datetime = '$dateLogout' WHERE s_username = '$username'";
$conn->query($updDateLogout);

echo "success";

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn = null;
