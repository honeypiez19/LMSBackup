<?php
// Start session
session_start();

require '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการลาพนักงาน</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="../logo/logo.png">
    <script src="https://kit.fontawesome.com/84c1327080.js" crossorigin="anonymous"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>

    <script src="../js/html2canvas.js"></script>
    <script src="../js/html2canvas.min.js"></script>
    <script src="../js/jspdf.umd.min.js"></script>

</head>

<body>
    <?php require 'admin_navbar.php'?>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fa-solid fa-folder-open fa-2xl"></i>
                </div>
                <div class="col-auto">
                    <h3>ข้อมูลการลาพนักงาน</h3>
                </div>
            </div>
        </div>
    </nav>
    <!-- ตารางข้อมูลพนักงาน -->
    <div class="mt-3 container-fluid">
        <div class="d-flex justify-content-end">
            <button onclick="capture()">Capture</button>
        </div>
        <table class="mt-3 table table-hover table-bordered" id="leaveEmpTable">
            <thead>
                <tr class="text-center align-middle">
                    <th rowspan="3">ลำดับ</th>
                    <th rowspan="2">รหัสพนักงาน</th>
                    <th rowspan="2">ชื่อ - นามสกุล</th>
                    <th rowspan="3">แผนก</th>
                    <th rowspan="3">อายุงาน</th>
                    <th rowspan="3">ระดับ</th>
                    <th rowspan="1" colspan="22" class="table-secondary">ประเภทการลาและจำนวนวัน</th>
                </tr>
                <tr class="text-center align-middle">
                    <th colspan="3">ลากิจได้รับค่าจ้าง</th>
                    <th colspan="3">ลากิจไม่ได้รับค่าจ้าง</th>
                    <th colspan="3">ลาป่วย</th>
                    <th colspan="3">ลาป่วยจากงาน</th>
                    <th colspan="3">ลาพักร้อน</th>
                    <th colspan="3">อื่น ๆ (ระบุ)</th>
                    <th colspan="1" rowspan="2">มาสาย (ครั้ง)</th>
                    <th colspan="1" rowspan="2">ขาดงาน</th>
                    <th colspan="1" rowspan="2">รวมวันลาที่ใช้ (ยกเว้นพักร้อน)</th>
                </tr>
                <tr class="text-center align-middle">
                    <th>
                        <div class="input-group">
                            <input type="text" class="form-control" aria-describedby="resetCode" id="codeSearch">
                            <button class="btn btn-outline-primary" id="resetCode"
                                onclick="resetInput('codeSearch')">X</button>

                        </div>
                        <!-- <input type="text" class="form-control" id="codeSearch"> -->
                    </th>

                    <th>
                        <div class="input-group">
                            <input type="text" class="form-control" aria-describedby="resetName" id="nameSearch">
                            <button class="btn btn-outline-primary" id="resetName"
                                onclick="resetInput('nameSearch')">X</button>
                        </div>
                        <!-- <input type="text" class="form-control" id="nameSearch"> -->
                    </th>
                    <!-- ลากิจได้ค่าจ้าง -->
                    <th>จำนวนวันที่ได้</th>
                    <th>ใช้ไป</th>
                    <th>คงเหลือ</th>
                    <!-- ลากิจไม่ได้ค่าจ้าง -->
                    <th>จำนวนวันที่ได้</th>
                    <th>ใช้ไป</th>
                    <th>คงเหลือ</th>
                    <!-- ลาป่วย -->
                    <th>จำนวนวันที่ได้</th>
                    <th>ใช้ไป</th>
                    <th>คงเหลือ</th>
                    <!-- ลาป่วยจากงาน -->
                    <th>จำนวนวันที่ได้</th>
                    <th>ใช้ไป</th>
                    <th>คงเหลือ</th>
                    <!-- ลาพักร้อน -->
                    <th>จำนวนวันที่ได้</th>
                    <th>ใช้ไป</th>
                    <th>คงเหลือ</th>
                    <!-- อื่น ๆ -->
                    <th>จำนวนวันที่ได้</th>
                    <th>ใช้ไป</th>
                    <th>คงเหลือ</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
$sql = "SELECT * FROM employee WHERE Emp_usercode <> '999999'";
$result = $conn->query($sql);

$rowNumber = 1;

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>';
    echo '<td>' . $rowNumber . '</td>';
    echo '<td>' . $row['Emp_usercode'] . '</td>';
    echo '<td>' . $row['Emp_name'] . '</td>';
    echo '<td>' . $row['Emp_department'] . '</td>';
    echo '<td>' . $row['Emp_yearexp'] . '</td>';
    echo '<td>' . $row['Emp_level'] . '</td>';

    $selectedYear = date('Y');
    $sql_leave = "SELECT
        -- ลากิจไม่ได้รับค่าจ้าง
    SUM(
        CASE
            WHEN Leave_ID = '1' AND DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 5 THEN
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
    (SELECT Leave_personal FROM employee WHERE Emp_usercode = :userCode ) AS total_personal,
    -- ลากิจไม่ได้รับค่าจ้าง
    SUM(
        CASE
            WHEN Leave_ID = '2' AND DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
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
    (SELECT Leave_personal_no FROM employee WHERE Emp_usercode = :userCode ) AS total_personal_no,
    -- ลาป่วย
    SUM(
        CASE
            WHEN Leave_ID = '3' AND DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 30 THEN
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
    (SELECT Leave_sick FROM employee WHERE Emp_usercode = :userCode ) AS total_sick,
    -- ลาป่วยจากงาน
    SUM(
        CASE
            WHEN Leave_ID = '4' AND DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
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
    (SELECT Leave_sick_work FROM employee WHERE Emp_usercode = :userCode ) AS total_leave_sick_work,
    -- ลาพักร้อน
    SUM(
        CASE
            WHEN Leave_ID = '5' AND DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 10 THEN
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
        (SELECT Leave_annual FROM employee WHERE Emp_usercode = :userCode ) AS total_annual,

    -- อื่น ๆ
    SUM(
        CASE
            WHEN Leave_ID = '8' AND DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
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
        (SELECT Other FROM employee WHERE Emp_usercode = :userCode ) AS total_other

        FROM leave_items
    WHERE (Leave_ID = '1' OR Leave_ID = '2' OR Leave_ID = '3' OR Leave_ID = '4' OR Leave_ID = '5'  OR Leave_ID = '8')
    AND YEAR(Leave_date_start) = :selectedYear
    AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00')
    AND Leave_status = '1'";

    $stmt_leave = $conn->prepare($sql_leave);
    $stmt_leave->bindParam(':userCode', $row['Emp_usercode']);
    $stmt_leave->bindParam(':selectedYear', $selectedYear);
    $stmt_leave->execute();
    $result_leave = $stmt_leave->fetch(PDO::FETCH_ASSOC);

    // คำนวณเวลาที่เหลือของการลากิจประเภทต่าง ๆ
    $total_personal = $result_leave['total_personal'];
    $leave_personal_hours = $result_leave['leave_personal_count'];
    $leave_personal_days = floor($leave_personal_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_personal_hours_remain = $leave_personal_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_personal_hours_remain2 = fmod($leave_personal_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    // echo $leave_personal_hours;
    if (in_array($leave_personal_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_personal_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_personal_minutes_remain = 0;
    }

    $total_personal_no = $result_leave['total_personal_no'];
    $leave_personal_no_hours = $result_leave['leave_personal_no_count'];
    $leave_personal_no_days = floor($leave_personal_no_hours / 8);
    $leave_personal_no_hours_remain = $leave_personal_no_hours % 8;
    $leave_personal_no_hours_remain2 = fmod($leave_personal_no_hours, 8);
    if (in_array($leave_personal_no_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_personal_no_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_personal_no_minutes_remain = 0;
    }

    $total_sick = $result_leave['total_sick'];
    $leave_sick_hours = $result_leave['leave_sick_count'];
    $leave_sick_days = floor($leave_sick_hours / 8);
    $leave_sick_hours_remain = $leave_sick_hours % 8;
    $leave_sick_hours_remain2 = fmod($leave_sick_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    // echo $leave_sick_hours;
    if (in_array($leave_sick_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_sick_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_sick_minutes_remain = 0;
    }

    $total_sick_work = $result_leave['total_leave_sick_work'];
    $leave_sick_work_hours = $result_leave['leave_sick_work_count'];
    $leave_sick_work_days = floor($leave_sick_work_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_sick_work_hours_remain = $leave_sick_work_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_sick_work_hours_remain2 = fmod($leave_sick_work_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    // echo $leave_sick_hours;
    if (in_array($leave_sick_work_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_sick_work_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_sick_work_minutes_remain = 0;
    }

    $total_annual = $result_leave['total_annual'];
    $leave_annual_hours = $result_leave['leave_annual_count'];
    $leave_annual_days = floor($leave_annual_hours / 8); // หาจำนวนวันที่เหลือ
    $leave_annual_hours_remain = $leave_annual_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $leave_annual_hours_remain2 = fmod($leave_annual_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

    // คำนวณนาทีที่เหลือ
    if (in_array($leave_annual_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $leave_annual_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $leave_annual_minutes_remain = 0;
    }

    $total_other = $result_leave['total_other'];
    $other_hours = $result_leave['other_count'];
    $other_days = floor($other_hours / 8); // หาจำนวนวันที่เหลือ
    $other_hours_remain = $other_hours % 8; // หาจำนวนชั่วโมงที่เหลือไม่เอาเศษ
    $other_hours_remain2 = fmod($leave_sick_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

// echo $leave_sick_hours;
    if (in_array($other_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
        $other_minutes_remain = 5; // 30 นาที คือ 0.5
    } else {
        $other_minutes_remain = 0;
    }
    // แสดงผลลัพธ์
    echo '<td>' . $total_personal . '</td>';
    echo '<td>' . $leave_personal_days . '(' . $leave_personal_hours_remain . '.' . $leave_personal_minutes_remain . ')' . '</td>';
    echo '<td>' . ($total_personal - $leave_personal_days) . '</td>';

    echo '<td>' . $total_personal_no . '</td>';
    echo '<td>' . $leave_personal_no_days . '(' . $leave_personal_no_hours_remain . '.' . $leave_personal_no_minutes_remain . ')' . '</td>';
    echo '<td>' . ($total_personal_no - $leave_personal_no_days) . '</td>';

    echo '<td>' . $total_sick . '</td>';
    echo '<td>' . $leave_sick_days . '(' . $leave_sick_hours_remain . '.' . $leave_sick_minutes_remain . ')' . '</td>';
    echo '<td>' . ($total_sick - $leave_sick_days) . '</td>';

    echo '<td>' . $total_sick_work . '</td>';
    echo '<td>' . $leave_sick_work_days . '(' . $leave_sick_work_hours_remain . '.' . $leave_sick_work_minutes_remain . ')' . '</td>';
    echo '<td>' . ($total_sick_work - $leave_sick_work_days) . '</td>';

    echo '<td>' . $total_annual . '</td>';
    echo '<td>' . $leave_annual_days . '(' . $leave_annual_hours_remain . '.' . $leave_annual_minutes_remain . ')' . '</td>';
    echo '<td>' . ($total_annual - $leave_annual_days) . '</td>';

    echo '<td>' . $total_other . '</td>';
    echo '<td>' . $other_days . '(' . $other_hours_remain . '.' . $other_minutes_remain . ')' . '</td>';
    echo '<td>' . ($total_other - $other_days) . '</td>';

    echo '<td>' . '</td>';
    echo '<td>' . '</td>';

    $sum_day = $leave_personal_days + $leave_personal_no_days + $leave_sick_days + $leave_sick_work_days;
    $sum_hours = $leave_personal_hours_remain + $leave_personal_no_hours_remain + $leave_sick_hours_remain + $leave_sick_work_hours_remain;
    $sum_minutes = $leave_personal_minutes_remain + $leave_personal_no_minutes_remain + $leave_sick_minutes_remain + $leave_sick_work_minutes_remain;

    echo '<td>' . $sum_day . '(' . $sum_hours . '.' . $sum_minutes . ')' . '</td>';

    echo '</tr>';
    $rowNumber++;
}

?>
            </tbody>
        </table>
    </div>
    <script>
    async function capture() {
        const {
            jsPDF
        } = window.jspdf;
        const element = document.querySelector("#leaveEmpTable");
        const canvas = await html2canvas(element);
        const imgData = canvas.toDataURL('image/png');

        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'px',
            format: 'a4'
        });

        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save("capture.pdf");
    }
    $(document).ready(function() {
        $("#nameSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        $("#codeSearch").on("keyup", function() {
            var value2 = $(this).val().toLowerCase();
            $("tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value2) > -1);
            });
        });

    });

    function resetInput(inputId) {
        document.getElementById(inputId).value = '';
        var codeValue = document.getElementById("codeSearch").value.toLowerCase();
        var nameValue = document.getElementById("nameSearch").value.toLowerCase();

        $("tbody tr").each(function() {
            var code = $(this).find("td:nth-child(2)").text().toLowerCase();
            var name = $(this).find("td:nth-child(3)").text().toLowerCase();
            if (code.includes(codeValue) && name.includes(nameValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>