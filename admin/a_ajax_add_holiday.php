<?php
include '../connect.php'; // รวมการเชื่อมต่อฐานข้อมูล
date_default_timezone_set('Asia/Bangkok'); // ตั้งโซนเวลา

// อ่านข้อมูล JSON จากการร้องขอ
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$status = 0;
$holiStatus = 'วันหยุด';

if (isset($data['holidays'])) {
    // เวลาปัจจุบัน
    $addDate = date('Y-m-d H:i:s');

    // การจัดการข้อมูล JSON
    $holidays = $data['holidays'];

    // เตรียมคำสั่ง SQL สำหรับการ INSERT
    $stmtInsert = $conn->prepare("INSERT INTO holiday (h_name, h_start_date, h_end_date, h_hr_name, h_hr_datetime, h_status, h_holiday_status) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // เตรียมคำสั่ง SQL สำหรับตรวจสอบว่ามีวันหยุดนี้อยู่แล้วหรือไม่
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM holiday WHERE h_name = ? AND h_start_date = ? AND h_end_date = ?");

    foreach ($holidays as $holiday) {
        // ตรวจสอบข้อมูลก่อนบันทึก
        $checkStmt->bindParam(1, $holiday['eventTitle']);
        $checkStmt->bindParam(2, $holiday['h_start_date']);
        $checkStmt->bindParam(3, $holiday['h_end_date']);
        $checkStmt->execute();
        $existingCount = $checkStmt->fetchColumn(); // นับจำนวนแถวที่ตรงกับเงื่อนไข

        if ($existingCount == 0) { // ถ้ายังไม่มี ให้ทำการ INSERT
            $stmtInsert->bindParam(1, $holiday['eventTitle']);
            $stmtInsert->bindParam(2, $holiday['h_start_date']);
            $stmtInsert->bindParam(3, $holiday['h_end_date']);
            $stmtInsert->bindParam(4, $holiday['h_hr_name']);
            $stmtInsert->bindParam(5, $addDate); // เพิ่มวันที่บันทึกลงในฐานข้อมูล
            $stmtInsert->bindParam(6, $status);
            $stmtInsert->bindParam(7, $holiStatus);
            $stmtInsert->execute();
        }
    }
    echo 'success';
} else {
    // การจัดการกรณีไม่ใช่ JSON
    $eventTitle = $_POST['eventTitle'];
    $h_start_date = $_POST['h_start_date'];
    $h_end_date = $_POST['h_end_date'];
    $h_hr_name = $_POST['h_hr_name'];
    $h_hr_datetime = date('Y-m-d H:i:s');

    $sql = "INSERT INTO holiday (h_name, h_start_date, h_end_date, h_hr_name, h_hr_datetime, h_status, h_holiday_status)
            VALUES (:eventTitle, :h_start_date, :h_end_date, :h_hr_name, :h_hr_datetime, :status, :holiStatus)";

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);

    // ผูกค่าพารามิเตอร์
    $stmt->bindParam(':eventTitle', $eventTitle);
    $stmt->bindParam(':h_start_date', $h_start_date);
    $stmt->bindParam(':h_end_date', $h_end_date);
    $stmt->bindParam(':h_hr_name', $h_hr_name);
    $stmt->bindParam(':h_hr_datetime', $h_hr_datetime);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':holiStatus', $holiStatus);

    // ดำเนินการคำสั่ง
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}