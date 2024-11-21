<?php
include 'connect.php';

try {
    // คำสั่ง SQL ดึงข้อมูลที่ต้องการ (e_id, e_work_start_date)
    $sql = "SELECT e_usercode, e_work_start_date FROM employees WHERE e_work_start_date IS NOT NULL";
    $stmt = $conn->query($sql);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($stmt->rowCount() > 0) {
        // ลูปผ่านข้อมูลทั้งหมดและคำนวณอายุงาน
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $e_usercode = $row['e_usercode'];
            $e_work_start_date = $row['e_work_start_date'];

            // คำนวณอายุงาน (ปี, เดือน, วัน)
            $startDate = new DateTime($e_work_start_date);
            $currentDate = new DateTime();

            // คำนวณปี, เดือน, วัน
            $years = $currentDate->diff($startDate)->y;
            $months = $currentDate->diff($startDate)->m;
            $days = $currentDate->diff($startDate)->d;

            // สร้างข้อความอายุงาน
            $e_yearexp = $years . "Y " . $months . "M " . $days . "D";

            // คำสั่ง SQL เพื่ออัปเดต e_yearexp
            $update_sql = "UPDATE employees SET e_yearexp = :e_yearexp WHERE e_usercode = :e_usercode";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':e_yearexp', $e_yearexp);
            $update_stmt->bindParam(':e_usercode', $e_usercode);

            // ทำการอัปเดตข้อมูล
            $update_stmt->execute();
        }

        echo "Updated successfully!";
    } else {
        echo "No records found!";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// ปิดการเชื่อมต่อ
$conn = null;