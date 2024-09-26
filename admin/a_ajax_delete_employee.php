<?php
// Start session
// session_start();

require '../connect.php';
// date_default_timezone_set('Asia/Bangkok'); // เวลาไทย

$usercode = $_POST["usercode"];
$status = 1;

try {
    // เริ่มต้นการเชื่อมต่อฐานข้อมูล
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // เริ่มต้นการทำธุรกรรม (transaction)
    $conn->beginTransaction();

    // อัพเดตสถานะของพนักงานในตาราง employee เป็น 0
    $sqlEmployee = "UPDATE employees SET e_status = :status WHERE e_usercode = :usercode";
    $stmtEmployee = $conn->prepare($sqlEmployee);
    $stmtEmployee->bindParam(':status', $status, PDO::PARAM_INT);
    $stmtEmployee->bindParam(':usercode', $usercode, PDO::PARAM_STR);
    $stmtEmployee->execute();

    // อัพเดตสถานะของพนักงานในตาราง employee_session เป็น 0
    $sqlSession = "UPDATE session SET s_status = :status WHERE s_usercode = :usercode";
    $stmtSession = $conn->prepare($sqlSession);
    $stmtSession->bindParam(':status', $status, PDO::PARAM_INT);
    $stmtSession->bindParam(':usercode', $usercode, PDO::PARAM_STR);
    $stmtSession->execute();

    // ยืนยันการทำธุรกรรม (commit)
    $conn->commit();

    echo "Success";
} catch (PDOException $e) {
    // ยกเลิกการทำธุรกรรม (rollback) ถ้ามีข้อผิดพลาดเกิดขึ้น
    $conn->rollBack();
    echo "Error updating data: " . $e->getMessage();
}

$conn = null;
