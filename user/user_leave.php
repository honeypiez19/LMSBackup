<?php
session_start();
date_default_timezone_set('Asia/Bangkok'); // เวลาไทย

include '../connect.php';
if (!isset($_SESSION['Emp_usercode'])) {
    header('Location: ../login.php');
    exit();
}

$userCode = $_SESSION['Emp_usercode'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถิติการลา</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="../logo/logo.png">
    <script src="https://kit.fontawesome.com/84c1327080.js" crossorigin="anonymous"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
</head>

<body>
    <?php require 'user_navbar.php'?>
    <nav class="navbar bg-body-tertiary" style="background-color: #072ac8; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
  border: none;">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fa-solid fa-chart-line fa-2xl"></i>
                </div>
                <div class="col-auto">
                    <h3>สถิติการลา</h3>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <form class="mt-3 mb-3 row" method="post">
            <label for="" class="mt-2 col-auto">เลือกปี</label>
            <div class="col-auto">
                <?php
$selectedYear = date('Y'); // ปีปัจจุบัน
if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];
}
echo "<select class='form-select' name='year' id='selectYear'>";
for ($i = 0; $i <= 2; $i++) {
    $year = date('Y', strtotime("last day of -$i year"));
    echo "<option value='$year'" . ($year == $selectedYear ? " selected" : "") . ">$year</option>";
}
echo "</select>";
?>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary button-shadow">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>

        <table class="mt-3 table table-hover table-bordered" style="border-top: 1px solid rgba(0, 0, 0, 0.1);"
            id="leaveTable">
            <thead>
                <tr class="table-dark text-center align-middle">
                    <th style="width: 40%;">ประเภทการลา</th>
                    <th>จำนวนวันลาที่ใช้ไป</th>
                    <th>จำนวนวันลาที่เหลือ</th>
                </tr>
            </thead>
            <tbody>
                <?php
// ถ้าเลือกปี
if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];

    // ลากิจได้รับค่าจ้าง ------------------------------------------------------------------------------
    $sql_leave_personal = "SELECT
    SUM(
        CASE
            WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 5 THEN
                CASE
                    WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                        CASE
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                            ELSE 1
                        END
                    WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                    WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                    ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                END
            ELSE 0
        END
    ) AS leave_personal_count,
    (SELECT Leave_personal FROM employee WHERE Emp_usercode = :userCode) AS total_personal
FROM leave_items
WHERE Leave_ID = '1' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Leave_status = '0'";

    $stmt_leave_personal = $conn->prepare($sql_leave_personal);
    $stmt_leave_personal->bindParam(':userCode', $userCode);
    $stmt_leave_personal->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_personal->execute();
    $result_leave_personal = $stmt_leave_personal->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_personal = $result_leave_personal['total_personal'];
    $leave_personal_hours = $result_leave_personal['leave_personal_count'];
    $leave_personal_days = floor($leave_personal_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_personal_hours_remain = $leave_personal_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_personal_hours_remain2 = fmod($leave_personal_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_personal_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_personal_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_personal_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>ลากิจได้รับค่าจ้าง</td>';
    echo '<td>' . $leave_personal_days . ' วัน ' . $leave_personal_hours_remain . ' ชั่วโมง ' . $leave_personal_minutes_remain . ' นาที</td>';
    echo '<td>' . ($total_personal - $leave_personal_days) . ' วัน</td>';
    echo '</tr>';

    // ลากิจไม่ได้รับค่าจ้าง ------------------------------------------------------------------------------
    $sql_leave_personal_no = "SELECT
    SUM(
        CASE
            WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
                CASE
                    WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                        CASE
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                            ELSE 1
                        END
                    WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                    WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                    ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                END
            ELSE 0
        END
    ) AS leave_personal_no_count,
    (SELECT Leave_personal_no FROM employee WHERE Emp_usercode = :userCode) AS total_personal_no
FROM leave_items
WHERE Leave_ID = '2' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00')  AND Leave_status = '0'";

    $stmt_leave_personal_no = $conn->prepare($sql_leave_personal_no);
    $stmt_leave_personal_no->bindParam(':userCode', $userCode);
    $stmt_leave_personal_no->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_personal_no->execute();
    $result_leave_personal_no = $stmt_leave_personal_no->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_personal_no = $result_leave_personal_no['total_personal_no'];
    $leave_personal_no_hours = $result_leave_personal_no['leave_personal_no_count'];
    $leave_personal_no_days = floor($leave_personal_no_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_personal_no_hours_remain = $leave_personal_no_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_personal_no_hours_remain2 = fmod($leave_personal_no_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_personal_no_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_personal_no_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_personal_no_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลากิจไม่ได้รับค่าจ้าง' . '</td>';
    echo '<td>' . $leave_personal_no_days . ' วัน ' . $leave_personal_no_hours_remain . ' ชั่วโมง ' . $leave_personal_no_minutes_remain . ' นาที ' . '</td>';
    echo '<td>' . ($total_personal_no - $leave_personal_no_days) . ' วัน' . '</td>';
    echo '</tr>';

    // ลาป่วย ------------------------------------------------------------------------------
    $sql_leave_sick = "SELECT
        SUM(
            CASE
                WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 30 THEN
                    CASE
                        WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                            CASE
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                                ELSE 1
                            END
                        WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                        WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                        ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                    END
                ELSE 0
            END
        ) AS leave_sick_count,
        (SELECT Leave_sick FROM employee WHERE Emp_usercode = :userCode) AS total_sick
    FROM leave_items
    WHERE Leave_ID = '3' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Leave_status = '0'";

    $stmt_leave_sick = $conn->prepare($sql_leave_sick);
    $stmt_leave_sick->bindParam(':userCode', $userCode);
    $stmt_leave_sick->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_sick->execute();
    $result_leave_sick = $stmt_leave_sick->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_sick = $result_leave_sick['total_sick'];
    $leave_sick_hours = $result_leave_sick['leave_sick_count'];
    $leave_sick_days = floor($leave_sick_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_sick_hours_remain = $leave_sick_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_sick_hours_remain2 = fmod($leave_sick_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_sick_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_sick_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_sick_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาป่วย' . '</td>';
    echo '<td>' . $leave_sick_days . ' วัน ' . $leave_sick_hours_remain . ' ชั่วโมง ' . $leave_sick_minutes_remain . ' นาที ' . '</td>';
    echo '<td>' . ($total_sick - $leave_sick_days) . ' วัน' . '</td>';
    echo '</tr>';

    // ลาป่วยจากงาน ------------------------------------------------------------------------------
    $sql_leave_sick_work = "SELECT
    SUM(
        CASE
            WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
                CASE
                    WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                        CASE
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                            ELSE 1
                        END
                    WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                    WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                    ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                END
            ELSE 0
        END
    ) AS leave_sick_work_count,
    (SELECT Leave_sick_work FROM employee WHERE Emp_usercode = :userCode) AS total_leave_sick_work
FROM leave_items
WHERE Leave_ID = '4' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Leave_status = '0'";

    $stmt_leave_sick_work = $conn->prepare($sql_leave_sick_work);
    $stmt_leave_sick_work->bindParam(':userCode', $userCode);
    $stmt_leave_sick_work->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_sick_work->execute();
    $result_leave_sick_work = $stmt_leave_sick_work->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_sick_work = $result_leave_sick_work['total_leave_sick_work'];
    $leave_sick_work_hours = $result_leave_sick_work['leave_sick_work_count'];
    $leave_sick_work_days = floor($leave_sick_work_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_sick_work_hours_remain = $leave_sick_work_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_sick_work_hours_remain2 = fmod($leave_sick_work_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_sick_work_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_sick_work_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_sick_work_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาป่วยจากงาน' . '</td>';
    echo '<td>' . $leave_sick_work_days . ' วัน ' . $leave_sick_work_hours_remain . ' ชั่วโมง ' . $leave_sick_work_minutes_remain . ' นาที ' . '</td>';
    echo '<td>' . ($total_sick_work - $leave_sick_work_days) . ' วัน' . '</td>';
    echo '</tr>';

    // ลาพักร้อน ------------------------------------------------------------------------------
    $sql_leave_annual = "SELECT
        SUM(
            CASE
                WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 5 THEN
                    CASE
                        WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                            CASE
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                                ELSE 1
                            END
                        WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                        WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                        ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                    END
                ELSE 0
            END
        ) AS leave_annual_count,
        (SELECT Leave_annual FROM employee WHERE Emp_usercode = :userCode) AS total_annual
    FROM leave_items
    WHERE Leave_ID = '5' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00')  AND Leave_status = '0'";

    $stmt_leave_annual = $conn->prepare($sql_leave_annual);
    $stmt_leave_annual->bindParam(':userCode', $userCode);
    $stmt_leave_annual->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_annual->execute();
    $result_leave_annual = $stmt_leave_annual->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_annual = $result_leave_annual['total_annual'];
    $leave_annual_hours = $result_leave_annual['leave_annual_count'];
    $leave_annual_days = floor($leave_annual_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_annual_hours_remain = $leave_annual_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_annual_hours_remain2 = fmod($leave_annual_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_annual_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_annual_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_annual_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาพักร้อน' . '</td>';
    echo '<td>' . $leave_annual_days . ' วัน ' . $leave_annual_hours_remain2 . ' ชั่วโมง ' . $leave_annual_minutes_remain . ' นาที' . '</td>';
    echo '<td>' . ($total_annual - $leave_annual_days) . ' วัน' . '</td>';

    echo '</tr>';

    // ขาดงาน ------------------------------------------------------------------------------
    $sql_absence_work = "SELECT COUNT(Items_ID) AS absence_work_count FROM leave_items WHERE Leave_ID = '6' AND YEAR(Leave_date_start) = '$selectedYear'";
    $result_absence_work = $conn->query($sql_absence_work)->fetch(PDO::FETCH_ASSOC);
    $sum_absence_work = $row['Absence_work'] - $result_absence_work['absence_work_count'];
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ขาดงาน' . '</td>';
    echo '<td>' . 'ขาดงาน ' . $result_absence_work['absence_work_count'] . ' วัน' . '</td>';
    echo '<td>' . '-' . '</td>';

    echo '</tr>';

    // มาสาย ------------------------------------------------------------------------------
    $sql_late = "SELECT COUNT(Items_ID) AS late_count FROM leave_items WHERE Leave_ID = '7' AND YEAR(Leave_date_start) = '$selectedYear'";
    $result_late = $conn->query($sql_late)->fetch(PDO::FETCH_ASSOC);
    $sum_late = $row['Late'] - $result_late['late_count'];
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'มาสาย' . '</td>';
    echo '<td>' . 'มาสาย ' . $result_late['late_count'] . ' ครั้ง' . '</td>';
    echo '<td>' . '-' . '</td>';

    echo '</tr>';

    // อื่น ๆ ------------------------------------------------------------------------------
    $sql_other = "SELECT
            SUM(
            CASE
                WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
                    CASE
                        WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                            CASE
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                                ELSE 1
                            END
                        WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                        WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                        ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                    END
                ELSE 0
            END
        ) AS other_count,
        (SELECT Other FROM employee WHERE Emp_usercode = :userCode) AS total_other
    FROM leave_items
    WHERE Leave_ID = '8' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Leave_status = '0'";

    $stmt_other = $conn->prepare($sql_other);
    $stmt_other->bindParam(':userCode', $userCode);
    $stmt_other->bindParam(':selectedYear', $selectedYear);
    $stmt_other->execute();
    $result_other = $stmt_other->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_other = $result_other['total_other'];
    $other_hours = $result_other['other_count'];
    $other_days = floor($other_hours / 8); // หาจำนวนวันที่เหลือ
    $other_hours_remain = $other_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $other_hours_remain2 = fmod($leave_sick_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($other_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $other_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $other_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'อื่น ๆ' . '</td>';
    echo '<td>' . $other_days . ' วัน ' . $other_hours_remain . ' ชั่วโมง ' . $other_minutes_remain . ' นาที' . '</td>';
    echo '<td>' . ($total_other - $other_days) . ' วัน' . '</td>';
    echo '</tr>';
// ------------------------------------------------------------------------------
    // ถ้าไม่เลือกปี
} else {
    $selectedYear = date('Y');

    // ลากิจได้รับค่าจ้าง ------------------------------------------------------------------------------
    $sql_leave_personal = "SELECT
    SUM(
        CASE
            WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 5 THEN
                CASE
                    WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                        CASE
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                            ELSE 1
                        END
                    WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                    WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                    ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                END
            ELSE 0
        END
    ) AS leave_personal_count,
    (SELECT Leave_personal FROM employee WHERE Emp_usercode = :userCode) AS total_personal
FROM leave_items
WHERE Leave_ID = '1' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00')  AND Leave_status = '0'";

    $stmt_leave_personal = $conn->prepare($sql_leave_personal);
    $stmt_leave_personal->bindParam(':userCode', $userCode);
    $stmt_leave_personal->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_personal->execute();
    $result_leave_personal = $stmt_leave_personal->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_personal = $result_leave_personal['total_personal'];
    $leave_personal_hours = $result_leave_personal['leave_personal_count'];
    $leave_personal_days = floor($leave_personal_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_personal_hours_remain = $leave_personal_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_personal_hours_remain2 = fmod($leave_personal_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_personal_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_personal_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_personal_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>ลากิจได้รับค่าจ้าง</td>';
    echo '<td>' . $leave_personal_days . ' วัน ' . $leave_personal_hours_remain . ' ชั่วโมง ' . $leave_personal_minutes_remain . ' นาที</td>';
    echo '<td>' . ($total_personal - $leave_personal_days) . ' วัน</td>';
    echo '</tr>';

    // ลากิจไม่ได้รับค่าจ้าง ------------------------------------------------------------------------------
    $sql_leave_personal_no = "SELECT
    SUM(
        CASE
            WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
                CASE
                    WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                        CASE
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                            ELSE 1
                        END
                    WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                    WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                    ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                END
            ELSE 0
        END
    ) AS leave_personal_no_count,
    (SELECT Leave_personal_no FROM employee WHERE Emp_usercode = :userCode) AS total_personal_no
FROM leave_items
WHERE Leave_ID = '2' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00')  AND Leave_status = '0'";

    $stmt_leave_personal_no = $conn->prepare($sql_leave_personal_no);
    $stmt_leave_personal_no->bindParam(':userCode', $userCode);
    $stmt_leave_personal_no->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_personal_no->execute();
    $result_leave_personal_no = $stmt_leave_personal_no->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_personal_no = $result_leave_personal_no['total_personal_no'];
    $leave_personal_no_hours = $result_leave_personal_no['leave_personal_no_count'];
    $leave_personal_no_days = floor($leave_personal_no_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_personal_no_hours_remain = $leave_personal_no_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_personal_no_hours_remain2 = fmod($leave_personal_no_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_personal_no_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_personal_no_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_personal_no_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลากิจไม่ได้รับค่าจ้าง' . '</td>';
    echo '<td>' . $leave_personal_no_days . ' วัน ' . $leave_personal_no_hours_remain . ' ชั่วโมง ' . $leave_personal_no_minutes_remain . ' นาที ' . '</td>';
    echo '<td>' . ($total_personal_no - $leave_personal_no_days) . ' วัน' . '</td>';
    echo '</tr>';

    // ลาป่วย ------------------------------------------------------------------------------
    $sql_leave_sick = "SELECT
        SUM(
            CASE
                WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 30 THEN
                    CASE
                        WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                            CASE
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                                ELSE 1
                            END
                        WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                        WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                        ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                    END
                ELSE 0
            END
        ) AS leave_sick_count,
        (SELECT Leave_sick FROM employee WHERE Emp_usercode = :userCode) AS total_sick
    FROM leave_items
    WHERE Leave_ID = '3' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Leave_status = '0'";

    $stmt_leave_sick = $conn->prepare($sql_leave_sick);
    $stmt_leave_sick->bindParam(':userCode', $userCode);
    $stmt_leave_sick->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_sick->execute();
    $result_leave_sick = $stmt_leave_sick->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_sick = $result_leave_sick['total_sick'];
    $leave_sick_hours = $result_leave_sick['leave_sick_count'];
    $leave_sick_days = floor($leave_sick_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_sick_hours_remain = $leave_sick_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_sick_hours_remain2 = fmod($leave_sick_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_sick_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_sick_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_sick_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาป่วย' . '</td>';
    echo '<td>' . $leave_sick_days . ' วัน ' . $leave_sick_hours_remain . ' ชั่วโมง ' . $leave_sick_minutes_remain . ' นาที ' . '</td>';
    echo '<td>' . ($total_sick - $leave_sick_days) . ' วัน' . '</td>';
    echo '</tr>';

    // ลาป่วยจากงาน ------------------------------------------------------------------------------
    $sql_leave_sick_work = "SELECT
    SUM(
        CASE
            WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
                CASE
                    WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                        CASE
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                            ELSE 1
                        END
                    WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                    WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                    ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                END
            ELSE 0
        END
    ) AS leave_sick_work_count,
    (SELECT Leave_sick_work FROM employee WHERE Emp_usercode = :userCode) AS total_leave_sick_work
FROM leave_items
WHERE Leave_ID = '4' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Leave_status = '0'";

    $stmt_leave_sick_work = $conn->prepare($sql_leave_sick_work);
    $stmt_leave_sick_work->bindParam(':userCode', $userCode);
    $stmt_leave_sick_work->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_sick_work->execute();
    $result_leave_sick_work = $stmt_leave_sick_work->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_sick_work = $result_leave_sick_work['total_leave_sick_work'];
    $leave_sick_work_hours = $result_leave_sick_work['leave_sick_work_count'];
    $leave_sick_work_days = floor($leave_sick_work_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_sick_work_hours_remain = $leave_sick_work_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_sick_work_hours_remain2 = fmod($leave_sick_work_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_sick_work_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_sick_work_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_sick_work_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาป่วยจากงาน' . '</td>';
    echo '<td>' . $leave_sick_work_days . ' วัน ' . $leave_sick_work_hours_remain . ' ชั่วโมง ' . $leave_sick_work_minutes_remain . ' นาที ' . '</td>';
    echo '<td>' . ($total_sick_work - $leave_sick_work_days) . ' วัน' . '</td>';
    echo '</tr>';

    // ลาพักร้อน ------------------------------------------------------------------------------
    $sql_leave_annual = "SELECT
        SUM(
            CASE
                WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 5 THEN
                    CASE
                        WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                            CASE
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                                ELSE 1
                            END
                        WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                        WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                        ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                    END
                ELSE 0
            END
        ) AS leave_annual_count,
        (SELECT Leave_annual FROM employee WHERE Emp_usercode = :userCode) AS total_annual
    FROM leave_items
    WHERE Leave_ID = '5' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00')  AND Leave_status = '0'";

    $stmt_leave_annual = $conn->prepare($sql_leave_annual);
    $stmt_leave_annual->bindParam(':userCode', $userCode);
    $stmt_leave_annual->bindParam(':selectedYear', $selectedYear);
    $stmt_leave_annual->execute();
    $result_leave_annual = $stmt_leave_annual->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_annual = $result_leave_annual['total_annual'];
    $leave_annual_hours = $result_leave_annual['leave_annual_count'];
    $leave_annual_days = floor($leave_annual_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_annual_hours_remain = $leave_annual_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_annual_hours_remain2 = fmod($leave_annual_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($leave_annual_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_annual_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_annual_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาพักร้อน' . '</td>';
    echo '<td>' . $leave_annual_days . ' วัน ' . $leave_annual_hours_remain2 . ' ชั่วโมง ' . $leave_annual_minutes_remain . ' นาที' . '</td>';
    echo '<td>' . ($total_annual - $leave_annual_days) . ' วัน' . '</td>';

    echo '</tr>';

    // ขาดงาน ------------------------------------------------------------------------------
    $sql_absence_work = "SELECT COUNT(Items_ID) AS absence_work_count FROM leave_items WHERE Leave_ID = '6' AND YEAR(Leave_date_start) = '$selectedYear'";
    $result_absence_work = $conn->query($sql_absence_work)->fetch(PDO::FETCH_ASSOC);
    $sum_absence_work = $row['Absence_work'] - $result_absence_work['absence_work_count'];
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ขาดงาน' . '</td>';
    echo '<td>' . 'ขาดงาน ' . $result_absence_work['absence_work_count'] . ' วัน' . '</td>';
    echo '<td>' . '-' . '</td>';

    echo '</tr>';

    // มาสาย ------------------------------------------------------------------------------
    $sql_late = "SELECT COUNT(Items_ID) AS late_count FROM leave_items WHERE Leave_ID = '7' AND YEAR(Leave_date_start) = '$selectedYear'";
    $result_late = $conn->query($sql_late)->fetch(PDO::FETCH_ASSOC);
    $sum_late = $row['Late'] - $result_late['late_count'];
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'มาสาย' . '</td>';
    echo '<td>' . 'มาสาย ' . $result_late['late_count'] . ' ครั้ง' . '</td>';
    echo '<td>' . '-' . '</td>';

    echo '</tr>';

    // อื่น ๆ ------------------------------------------------------------------------------
    $sql_other = "SELECT
            SUM(
            CASE
                WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
                    CASE
                        WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                            CASE
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                                WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '03:55:00') THEN 4
                                ELSE 1
                            END
                        WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN DATEDIFF(Leave_date_end, Leave_date_start) * 8
                        WHEN (TIME(Leave_time_start) >= '08:00:00' AND TIME(Leave_time_end) <= '11:45:00') OR (TIME(Leave_time_start) >= '12:45:00' AND TIME(Leave_time_end) <= '16:40:00') THEN (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8 + 4
                        ELSE (DATEDIFF(Leave_date_end, Leave_date_start) + 1) * 8
                    END
                ELSE 0
            END
        ) AS other_count,
        (SELECT Other FROM employee WHERE Emp_usercode = :userCode) AS total_other
    FROM leave_items
    WHERE Leave_ID = '8' AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Leave_status = '0'";

    $stmt_other = $conn->prepare($sql_other);
    $stmt_other->bindParam(':userCode', $userCode);
    $stmt_other->bindParam(':selectedYear', $selectedYear);
    $stmt_other->execute();
    $result_other = $stmt_other->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือ
    $total_other = $result_other['total_other'];
    $other_hours = $result_other['other_count'];
    $other_days = floor($other_hours / 8); // หาจำนวนวันที่เหลือ
    $other_hours_remain = $other_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $other_hours_remain2 = fmod($leave_sick_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    if (in_array($other_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $other_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $other_minutes_remain = 0;
    }

    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'อื่น ๆ' . '</td>';
    echo '<td>' . $other_days . ' วัน ' . $other_hours_remain . ' ชั่วโมง ' . $other_minutes_remain . ' นาที' . '</td>';
    echo '<td>' . ($total_other - $other_days) . ' วัน' . '</td>';

    echo '</tr>';
}
// รวมวันลาที่ใช้ไปทั้งหมด
// $sum_all = $leave_personal_days + $leave_personal_no_days + $leave_sick_days + $leave_sick_work_days;
$sum_day = $leave_personal_days + $leave_personal_no_days + $leave_sick_days + $leave_sick_work_days;
$sum_hours = $leave_personal_hours_remain + $leave_personal_no_hours_remain + $leave_sick_hours_remain + $leave_sick_work_hours_remain;
$sum_minutes = $leave_personal_minutes_remain + $leave_personal_no_minutes_remain + $leave_sick_minutes_remain + $leave_sick_work_minutes_remain;

echo '<tr class="text-center align-middle">';

if ($sum_day < 10) {
    echo '<td style="font-weight: bold;">' . 'รวมจำนวนวันลาทั้งหมด (ยกเว้นลาพักร้อน / อื่น ๆ)' . '</td>';
    echo '<td colspan="2" style="font-weight: bold;">' . $sum_day . ' วัน ' . $sum_hours . ' ชั่วโมง ' . $sum_minutes . ' นาที' . '</td>';
} else if ($sum_day == 10) {
    echo '<div class="alert alert-primary" role="alert">';
    echo '<i class="fa-solid fa-circle-exclamation"></i>' . ' รวมจำนวนวันลาทั้งหมด ' . $sum_day . ' วัน (ยกเว้นลาพักร้อน / อื่น ๆ)';
    echo '</div>';
    echo '<td style="font-weight: bold;">' . 'รวมจำนวนวันลาทั้งหมด (ยกเว้นลาพักร้อน / อื่น ๆ)' . '</td>';
    echo '<td colspan="2" style="font-weight: bold;" class="text-primary">' . $sum_day . ' วัน ' . $sum_hours . ' ชั่วโมง ' . $sum_minutes . ' นาที' . '</td>';
} else if ($sum_day == 11) {
    echo '<div class="alert alert-primary" role="alert">';
    echo '<i class="fa-solid fa-circle-exclamation"></i>' . ' รวมจำนวนวันลาทั้งหมด ' . $sum_day . ' วัน (ยกเว้นลาพักร้อน / อื่น ๆ)';
    echo '</div>';
    echo '<td style="font-weight: bold;">' . 'รวมจำนวนวันลาทั้งหมด (ยกเว้นลาพักร้อน / อื่น ๆ)' . '</td>';
    echo '<td colspan="2" style="font-weight: bold;" class="text-primary">' . $sum_day . ' วัน ' . $sum_hours . ' ชั่วโมง ' . $sum_minutes . ' นาที' . '</td>';
} else if ($sum_day == 12) {
    echo '<div class="alert alert-warning" role="alert">';
    echo '<i class="fa-solid fa-circle-exclamation"></i>' . ' รวมจำนวนวันลาทั้งหมด ' . $sum_day . ' วัน (ยกเว้นลาพักร้อน / อื่น ๆ)';
    echo '</div>';
    echo '<td style="font-weight: bold;">' . 'รวมจำนวนวันลาทั้งหมด (ยกเว้นลาพักร้อน / อื่น ๆ)' . '</td>';
    echo '<td colspan="2" style="font-weight: bold;" class="text-warning">' . $sum_day . ' วัน ' . $sum_hours . ' ชั่วโมง ' . $sum_minutes . ' นาที' . '</td>';
} else if ($sum_day == 13) {
    echo '<div class="alert alert-danger" role="alert">';
    echo '<i class="fa-solid fa-circle-exclamation"></i>' . ' รวมจำนวนวันลาทั้งหมด ' . $sum_day . ' วัน (ยกเว้นลาพักร้อน / อื่น ๆ)';
    echo '</div>';
    echo '<td style="font-weight: bold;">' . 'รวมจำนวนวันลาทั้งหมด (ยกเว้นลาพักร้อน / อื่น ๆ)' . '</td>';
    echo '<td colspan="2" style="font-weight: bold;" class="text-danger">' . $sum_day . ' วัน ' . $sum_hours . ' ชั่วโมง ' . $sum_minutes . ' นาที' . '</td>';
} else if ($sum_day >= 14) {
    echo '<div class="alert alert-danger" role="alert">';
    echo '<i class="fa-solid fa-circle-exclamation"></i>' . ' รวมจำนวนวันลาทั้งหมด ' . $sum_day . ' วัน (ยกเว้นลาพักร้อน / อื่น ๆ)';
    echo '</div>';
    echo '<td style="font-weight: bold;">' . 'รวมจำนวนวันลาทั้งหมด (ยกเว้นลาพักร้อน / อื่น ๆ)' . '</td>';
    echo '<td colspan="2" style="font-weight: bold;" class="text-danger">' . $sum_day . ' วัน ' . $sum_hours . ' ชั่วโมง ' . $sum_minutes . ' นาที' . '</td>';
} else {
    // echo '<td colspan="2" style="font-weight: bold;" class="text-danger">' . $sum_day . ' วัน ' . $sum_hours . ' ชั่วโมง ' . $sum_minutes . ' นาที' . '</td>';
}
echo '</tr>';

?>

            </tbody>
        </table>
    </div>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>