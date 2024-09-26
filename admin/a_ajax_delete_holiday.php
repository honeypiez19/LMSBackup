<?php
include '../connect.php'; // รวมการเชื่อมต่อฐานข้อมูล
date_default_timezone_set('Asia/Bangkok'); // ตั้งโซนเวลา

// เตรียมข้อมูลสำหรับการลบ
$startDate = $_POST['start'];

// สร้างคำสั่ง SQL สำหรับการลบเหตุการณ์
$sql = "UPDATE holiday SET h_status = 1 WHERE h_start_date = :startDate";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR); // เปลี่ยนเป็น PDO::PARAM_STR แทน

// ดำเนินการลบและตรวจสอบผลลัพธ์
if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'failure';
}
