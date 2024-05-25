<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

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
    <title>หน้าหลัก</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <link rel="stylesheet" href="../css/flatpickr.min.css">

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/flatpickr"></script>
    <script src="../js/sweetalert2.all.min.js"></script>

    <script src="../js/fontawesome.js"></script>

    <style>
    @media (max-width: 576px) {
        .filter-card {
            /* มีขนาด flex ความกว้าง 100% แต่ไม่สามารถย่อหรือขยายได้ */
            flex: 0 0 100%;
            /* ความกว้าง 100% ของพื้นที่ที่หน้าจอมีความกว้างไม่เกิน 576px */
            max-width: 100%;
        }
    }
    </style>
</head>

<body>
    <?php include 'user_navbar.php'?>

    <div class="mt-3 container-fluid">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center">
                <form class="mt-3 mb-3 row" method="post">
                    <label for="" class="mt-2 col-auto">เลือกเดือน</label>
                    <div class="col-auto">
                        <?php
$selectedMonth = date('m'); // เดือนปัจจุบัน

if (isset($_POST['month'])) {
    $selectedMonth = $_POST['month'];
}
echo "<select class='form-select' name='month' id='selectedMonth'>";
for ($i = 0; $i <= 11; $i++) { // แสดงทุกเดือน
    $month = date('m', strtotime("first day of -$i month"));
    echo "<option value='$month'" . ($month == $selectedMonth ? " selected" : "") . ">$month</option>";
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

                <!-- Button trigger modal -->
                <button type="button" class="button-shadow btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#leaveRule">
                    <i class="fa-solid fa-file-shield"></i> ระเบียบการลา
                </button>
                <!-- Modal -->
                <div class="modal fade" id="leaveRule" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="leaveRuleLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="leaveRuleLabel">ระเบียบการลา</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h5><b>- ลาป่วย</b></h5>
                                        <p>พนักงานมีสิทธิ์ลาได้ 30 วัน <span
                                                class="red-text">(มีผลเรื่องการหักโบนัส)</span>
                                        </p>
                                        <h5><b>- ลาป่วยเนื่องจากการทำงานให้บริษัทฯ</b></h5>
                                        <p>พนักงานมีสิทธิ์ลาได้ แต่ถ้าซึ่งปรากฎว่ายังไม่หายจากอาการเจ็บป่วย
                                            หรือยังไม่สามารถทำงานให้บริษัทได้เกินกว่า 60 วันทำงานปกติ
                                            บริษัทจะปลดออกจากงานฐานป่วยนานเกินกำหนดทั้งนี้โดยได้รับค่าชดเชย
                                            และสิทธิอื่นใดตามที่กฎหมายว่าด้วยแรงงาน <span
                                                class="red-text">(ไม่มีผลเรื่องการหักโบนัส)</span></p>
                                        <h5><b>- ลากิจได้รับค่าจ้าง</b></h5>
                                        <p>พนักงานที่มีอายุงานครบ 1 ปี สามารถลาได้ 5 วัน <span
                                                class="red-text">(มีผลเรื่องการหักโบนัส)</span></p>
                                        <h5><b>- ลากิจไม่ได้รับค่าจ้าง</b></h5>
                                        <p>พนักงานที่มีอายุงานไม่ถึง 1
                                            ปีและพนักงานประจำที่ใช้สิทธิ์ลากิจได้รับค่าจ้างครบ 5
                                            วันแล้ว
                                            ไม่ได้จำกัดลาได้กี่วัน <span class="red-text">(มีผลเรื่องการหักโบนัส)</span>
                                        </p>
                                        <h5><b>- ลาพักร้อน</b></h5>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="text-center align-middle">
                                                    <th>อายุงานของพนักงาน (ปี)</th>
                                                    <th>จำนวนวันหยุดพักผ่อนประจำปี
                                                        (วันทำงานปกติ)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-center align-middle">
                                                    <td>ครบ 1 ปี แต่ไม่ถึง 2 ปี</td>
                                                    <td>6</td>
                                                </tr>
                                                <tr class="text-center align-middle">
                                                    <td>ครบ 2 ปี แต่ไม่ถึง 3 ปี</td>
                                                    <td>7</td>
                                                </tr>
                                                <tr class="text-center align-middle">
                                                    <td>ครบ 3 ปี แต่ไม่ถึง 4 ปี</td>
                                                    <td>8</td>
                                                </tr>
                                                <tr class="text-center align-middle">
                                                    <td>ครบ 4 ปี แต่ไม่ถึง 5 ปี</td>
                                                    <td>9</td>
                                                </tr>
                                                <tr class="text-center align-middle">
                                                    <td>ครบ 5 ปี ขึ้นไป</td>
                                                    <td>10</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-6">
                                        <h5><b>- ลาเพื่อทำหมัน</b></h5>
                                        <p>พนักงานมีสิทธิ์ลาได้ตามที่ระบุไว้ในใบรับรองแพทย์
                                            ลาได้ครั้งเดียวตลอดอายุการเป็นพนักงาน
                                            ยื่นล่วงหน้า 1 วัน</p>
                                        <h5><b>- ลาคลอด</b></h5>
                                        <p>ครรภ์ไม่เกิน 98 วัน ให้นับรวมวันหยุดที่มีอยู่ในระหว่างวันลา
                                            ยื่นใบลาล่วงหน้า 15
                                            วัน
                                            ได้รับค่าจ้างเท่ากับอัตราค่าจ้างต่อชั่วโมงในเวลาทำงานปกติตลอดระยะเวลาที่ลาเพื่อการคลอดแต่ไม่เกิน
                                            45 วัน</p>
                                        <h5><b>- ลาอุปสมบท</b></h5>
                                        <p>พนักงานประจำที่มีอายุงานติดต่อกันครบ 2
                                            ปีบริบูรณ์ขึ้นไปสามารถขอลาอุปสมบทได้ 15
                                            วันทำงานปกติโดยได้รับค่าจ้างและให้ลาได้เพียงครั้งเดียวตลอดระยะเวลาที่เป็นพนักงานของบริษัท
                                            ยื่นใบลาล่วงหน้า 15 วัน</p>
                                        <h5><b>- ลาเพื่อรับราชการทหาร</b></h5>
                                        <p>พนักงานลาได้ไม่เกิน 60 วันต่อปี ได้รับค่าจ้าง ยื่นใบลาล่วงหน้า 15 วัน</p>
                                        <h5><b>- ลาเพื่อจัดการงานศพ</b></h5>
                                        <p>พนักงานประจำสามารถลาเพื่อจัดการงานศพในกรณีที่ บิดา มารดา
                                            คู่สมรสหรือบุตรโดยชอบด้วยกฎหมายถึงแก่กรรม
                                            โดยได้รับค่าจ้าง ลาหยุดงานไม่เกินครั้งละ 3 วัน</p>
                                        <h5><b>- ลาเพื่อพัฒนาและเรียนรู้</b></h5>
                                        <p>พนักงานสามารถขอลาเพื่อพัฒนาและเรียนรู้ได้ตามที่ผู้บังคับบัญชาจะพิจารณาเห็นเป็นการสมควรและอนุมัติให้เป็นคราว
                                            ๆ ไปโดยได้รับค่าจ้างเท่ากับอัตราค่าจ้างต่อชั่วโมงในเวลาทำงานปกติ
                                            ไม่เกินปีละ 3
                                            ครั้ง ลาล่วงหน้า 7 วัน</p>
                                        <h5><b>- ลาเพื่อการสมรส</b></h5>
                                        <p>พนักงานประจำที่มีอายุงานติดต่อกันครบ 1 ปี
                                            บริบูรณ์ขึ้นไปสามารถขอลาเพื่อการสมรสได้
                                            3 วันทำงานปกติโดยได้รับค่าจ้าง
                                            ให้ลาได้เพียงครั้งเดียวตลอดระยะเวลาที่เป็นพนักงานของบริษัท</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="mt-3 row">
            <div class="col-3 filter-card">
                <div class="card text-light mb-3" style="background-color: #072ac8; ">
                    <div class="card-body">
                        <div class="card-title">
                            <?php
// ลากิจได้รับค่าจ้าง
$selectedYear = date('Y');
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
WHERE Leave_ID = '1' AND Emp_usercode = :userCode AND YEAR(Leave_date_start) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Month(Create_datetime) = '$selectedMonth' AND Leave_status = '0'";

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

// echo $leave_personal_hours;
if (in_array($leave_personal_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
    $leave_personal_minutes_remain = 5; // 30 นาที คือ 0.5
} else {
    $leave_personal_minutes_remain = 0;
}

echo '<div class="d-flex justify-content-between">';
echo '<div>';
echo '<h5>' . $leave_personal_days . '(' . $leave_personal_hours_remain . '.' . $leave_personal_minutes_remain . ')' . ' / ' . $total_personal . '</h5>';
echo '</div>';
echo '<div>';
echo '<i class="mx-2 fa-solid fa-sack-dollar fa-2xl"></i>';
if ($leave_personal_days == 3) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: #99beff;"></i>';
} elseif ($leave_personal_days == 4) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: yellow;"></i>';
} elseif ($leave_personal_days == 5) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: red;"></i>';
} else {
}
echo '</div>';

echo '</div>';

?>
                            <p class="card-text">
                                ลากิจได้รับค่าจ้าง
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3 filter-card">
                <div class="card text-light mb-3" style="background-color: #1360e2;">
                    <div class="card-body">
                        <div class="card-title">
                            <?php
// ลากิจไม่ได้รับค่าจ้าง
$selectedYear = date('Y');
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
WHERE Leave_ID = '2' AND Emp_usercode = :userCode AND YEAR(Leave_date_start) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Month(Create_datetime) = '$selectedMonth' AND Leave_status = '0'";

$stmt_leave_personal_no = $conn->prepare($sql_leave_personal_no);
$stmt_leave_personal_no->bindParam(':userCode', $userCode);
$stmt_leave_personal_no->bindParam(':selectedYear', $selectedYear);
$stmt_leave_personal_no->execute();
$result_leave_personal_no = $stmt_leave_personal_no->fetch(PDO::FETCH_ASSOC);

// คำนวณเวลาที่เหลือ
$total_personal_no = $result_leave_personal_no['total_personal_no'];
$leave_personal_no_hours = $result_leave_personal_no['leave_personal_no_count'];
$leave_personal_no_days = floor($leave_personal_no_hours / 8); // หาจำนวนวันที่เหลือ
$leave_personal_no_hours_remain = $leave_personal_no_hours % 8; // หาจำนวนชั่วโมงที่เหลือ
$leave_personal_no_hours_remain2 = fmod($leave_personal_no_hours, 8); // เก็บเศษทศนิยมจากการหาร $leave_personal_hours ด้วย 8

if (in_array($leave_personal_no_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
    $leave_personal_no_minutes_remain = 5; // 30 นาที คือ 0.5
} else {
    $leave_personal_no_minutes_remain = 0;
}
// แสดงผล
echo '<div class="d-flex justify-content-between">';
echo '<div>';
echo '<h5>' . $leave_personal_no_days . '(' . $leave_personal_no_hours_remain . '.' . $leave_personal_no_minutes_remain . ')' . ' / ' . $total_personal_no . '</h5>';
echo '</div>';
echo '<div>';
echo '<i class="fa-solid fa-sack-xmark fa-2xl"></i>';
if ($leave_personal_no_days == 3) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: #99beff;"></i>';
} elseif ($leave_personal_no_days == 4) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: yellow;"></i>';
} elseif ($leave_personal_no_days == 5) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: red;"></i>';
} else {
}
echo '</div>';

echo '</div>';
?>
                            <p class="card-text">
                                ลากิจไม่ได้รับค่าจ้าง
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3 filter-card">
                <div class="card text-light mb-3" style="background-color: #1e96fc;">
                    <div class="card-body">
                        <div class="card-title">
                            <?php
// ลาป่วย
$selectedYear = date('Y');
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
WHERE Leave_ID = '3' AND Emp_usercode = :userCode AND YEAR(Create_datetime) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Month(Create_datetime) = '$selectedMonth' AND Leave_status = '0'";

$stmt_leave_sick = $conn->prepare($sql_leave_sick);
$stmt_leave_sick->bindParam(':userCode', $userCode);
$stmt_leave_sick->bindParam(':selectedYear', $selectedYear);
$stmt_leave_sick->execute();
$result_leave_sick = $stmt_leave_sick->fetch(PDO::FETCH_ASSOC);

// คำนวณเวลาที่เหลือสำหรับลาป่วย
$total_sick = $result_leave_sick['total_sick'];
$leave_sick_hours = $result_leave_sick['leave_sick_count'];
$leave_sick_days = floor($leave_sick_hours / 8);
$leave_sick_hours_remain = $leave_sick_hours % 8;
$leave_sick_hours_remain2 = fmod($leave_sick_hours, 8); // หาจำนวนชั่วโมงที่เหลือเอาเศษ

// echo $leave_sick_hours;
if (in_array($leave_sick_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
    $leave_sick_minutes_remain = 5; // 30 นาที คือ 0.5
} else {
    $leave_sick_minutes_remain = 0;
}

// แสดงผล
echo '<div class="d-flex justify-content-between">';
echo '<div>';
echo '<h5>' . $leave_sick_days . '(' . $leave_sick_hours_remain . '.' . $leave_sick_minutes_remain . ')' . ' / ' . $total_sick . '</h5>';
echo '</div>';
echo '<div>';
echo '<i class="fa-solid fa-syringe fa-2xl"></i>';
if ($leave_sick_days == 3) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: #99beff;"></i>';
} elseif ($leave_sick_days == 4) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: yellow;"></i>';
} elseif ($leave_sick_days == 5) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: red;"></i>';
}
echo '</div>';
echo '</div>';

?>
                            <p class="card-text">
                                ลาป่วย
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3 filter-card">
                <div class="card text-light mb-3" style="background-color: #60b6fb;">
                    <div class="card-body">
                        <div class="card-title">
                            <?php
// ลาป่วยจากงาน
$selectedYear = date('Y');
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
WHERE Leave_ID = '4' AND Emp_usercode = :userCode AND YEAR(Leave_date_start) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Month(Create_datetime) = '$selectedMonth' AND Leave_status = '0'";

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

// echo $leave_sick_hours;
if (in_array($leave_sick_work_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
    $leave_sick_work_minutes_remain = 5; // 30 นาที คือ 0.5
} else {
    $leave_sick_work_minutes_remain = 0;
}

echo '<div class="d-flex justify-content-between">';
echo '<div>';
echo '<h5>' . $leave_sick_work_days . '(' . $leave_sick_work_hours_remain . '.' . $leave_sick_work_minutes_remain . ')' . ' / ' . $total_sick_work . '</h5>';

echo '</div>';
echo '<div>';
echo '<i class="mx-2 fa-solid fa-syringe fa-2xl"></i>';
if ($leave_sick_work_days == 3) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: #99beff;"></i>';
} elseif ($leave_sick_work_days == 4) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: yellow;"></i>';
} elseif ($leave_sick_work_days == 5) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: red;"></i>';
} else {
}
echo '</div>';

echo '</div>';

?>
                            <p class="card-text">
                                ลาป่วยจากงาน
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 row">
            <div class="col-3 filter-card">
                <div class="card text-light mb-3" style="background-color: #0D47A1; ">
                    <div class="card-body">
                        <div class="card-title">
                            <?php
// ลาพักร้อน
$selectedYear = date('Y');
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
WHERE Leave_ID = '5' AND Emp_usercode = :userCode AND YEAR(Leave_date_start) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00')  AND Month(Create_datetime) = '$selectedMonth' AND Leave_status = '0'";

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

// echo $leave_sick_hours;
if (in_array($leave_annual_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
    $leave_annual_minutes_remain = 5; // 30 นาที คือ 0.5
} else {
    $leave_annual_minutes_remain = 0;
}

echo '<div class="d-flex justify-content-between">';
echo '<div>';
echo '<h5>' . $leave_annual_days . '(' . $leave_annual_hours_remain . '.' . $leave_annual_minutes_remain . ')' . ' / ' . $total_annual . '</h5>';
echo '</div>';
echo '<div>';
echo '<i class="mx-2 fa-solid fa-syringe fa-2xl"></i>';
if ($leave_annual_days == 3) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: #99beff;"></i>';
} elseif ($leave_annual_days == 4) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: yellow;"></i>';
} elseif ($leave_annual_days == 5) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: red;"></i>';
} else {
}
echo '</div>';

echo '</div>';

?>
                            <p class="card-text">
                                ลาพักร้อน
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3 filter-card">
                <div class="card text-light mb-3" style="background-color: #1976D2; ">
                    <div class="card-body">
                        <div class="card-title">
                            <?php
// ขาดงาน
$selectedYear = date('Y');
$sql_absence_work = "SELECT COUNT(Items_ID) AS absence_work_count FROM leave_items WHERE Leave_ID = '6' AND Emp_usercode = '$userCode' AND YEAR(Leave_date_start) = '$selectedYear'";
$result_absence_work = $conn->query($sql_absence_work)->fetch(PDO::FETCH_ASSOC);
$sum_absence_work = $row['Absence_work'] - $result_absence_work['absence_work_count'];

// แสดงผล
echo '<div class="d-flex justify-content-between">';
echo '<div>';
echo '<h5>' . $result_absence_work['absence_work_count'] . '</h5>';
echo '</div>';
echo '<div>';
echo '<i class="fa-solid fa-circle-minus fa-2xl"></i></i>';
// if ($leave_annual_days == 3) {
//     echo '<i class="fa-solid fa-circle-exclamation" style="color: #99beff;"></i>';
// } elseif ($leave_annual_days == 4) {
//     echo '<i class="fa-solid fa-circle-exclamation" style="color: yellow;"></i>';
// } elseif ($leave_annual_days == 5) {
//     echo '<i class="fa-solid fa-circle-exclamation" style="color: red;"></i>';
// } else {
// }
echo '</div>';

echo '</div>';
?>
                            <p class="card-text">
                                ขาดงาน
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card">
                <div class="card text-light mb-3" style="background-color: #2196F3; ">
                    <div class="card-body">
                        <div class="card-title">
                            <?php
// มาสาย
$selectedYear = date('Y');
$sql_late = "SELECT COUNT(Items_ID) AS late_count FROM leave_items WHERE Leave_ID = '7' AND Emp_usercode = '$userCode' AND YEAR(Leave_date_start) = '$selectedYear'";
$result_late = $conn->query($sql_late)->fetch(PDO::FETCH_ASSOC);
$sum_late = $row['Late'] - $result_late['late_count'];

// แสดงผล
echo '<div class="d-flex justify-content-between">';
echo '<div>';
echo '<h5>' . $result_late['late_count'] . '</h5>';
echo '</div>';
echo '<div>';
echo '<i class="fa-solid fa-person-running fa-2xl"></i></i>';
// if ($leave_annual_days == 3) {
//     echo '<i class="fa-solid fa-circle-exclamation" style="color: #99beff;"></i>';
// } elseif ($leave_annual_days == 4) {
//     echo '<i class="fa-solid fa-circle-exclamation" style="color: yellow;"></i>';
// } elseif ($leave_annual_days == 5) {
//     echo '<i class="fa-solid fa-circle-exclamation" style="color: red;"></i>';
// } else {
// }
echo '</div>';

echo '</div>';
?>
                            <p class="card-text">
                                มาสาย (ครั้ง)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card">
                <div class="card text-light mb-3" style="background-color: #64B5F6; ">
                    <div class="card-body">
                        <div class="card-title">
                            <?php
// อื่น ๆ
$selectedYear = date('Y');
$sql_other = "SELECT
        SUM(
        CASE
            WHEN DATEDIFF(Leave_date_end, Leave_date_start) BETWEEN 0 AND 365 THEN
                CASE
                    WHEN DATEDIFF(Leave_date_end, Leave_date_start) = 0 THEN
                        CASE
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) = '08:40:00' THEN 8
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('07:30:00', '07:00:00', '06:30:00', '06:00:00', '05:30:00', '05:00:00', '03:30:00', '03:00:00', '02:30:00', '02:00:00', '01:30:00', '01:00:00', '00:30:00') THEN ROUND(TIME_TO_SEC(TIMEDIFF(Leave_time_end, Leave_time_start)) / 3600, 1)
                            WHEN TIMEDIFF(Leave_time_end, Leave_time_start) IN ('03:45:00', '0
                            3:55:00') THEN 4
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
WHERE Leave_ID = '8' AND Emp_usercode = :userCode AND YEAR(Leave_date_start) = :selectedYear AND NOT (TIME(Leave_time_start) >= '11:45:00' AND TIME(Leave_time_end) <= '12:45:00') AND Month(Create_datetime) = '$selectedMonth' AND Leave_status = '0'";
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

// echo $leave_sick_hours;
if (in_array($other_hours_remain2, [0.5, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5, 10.5])) {
    $other_minutes_remain = 5; // 30 นาที คือ 0.5
} else {
    $other_minutes_remain = 0;
}

echo '<div class="d-flex justify-content-between">';
echo '<div>';
echo '<h5>' . $other_days . '(' . $other_hours_remain . '.' . $other_minutes_remain . ')' . ' / ' . $total_other . '</h5>';
echo '</div>';
echo '<div>';
echo '<i class="mx-2 fa-solid fa-syringe fa-2xl"></i>';
if ($leave_annual_days == 3) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: #99beff;"></i>';
} elseif ($leave_annual_days == 4) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: yellow;"></i>';
} elseif ($leave_annual_days == 5) {
    echo '<i class="fa-solid fa-circle-exclamation" style="color: red;"></i>';
} else {
}
echo '</div>';

echo '</div>';
?>
                            <p class="card-text">
                                อื่น ๆ
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="mb-3 d-flex justify-content-end">
                <!-- ปุ่มยื่นใบลา -->
                <button type="button" class="button-shadow btn btn-primary mt-3" data-bs-toggle="modal"
                    data-bs-target="#leaveModal">
                    ยื่นใบลา
                </button>
            </div>
        </div>
        <!-- Modal ยื่นใบลา -->
        <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveModalLabel">รายละเอียดคำขอ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="leaveForm" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <label for="leaveType" class="form-label">ประเภทการลา</label>
                                    <span style="color: red;">*</span>
                                    <select class="form-select" id="leaveType" required
                                        onchange="updateLeaveReasonField()">
                                        <option selected>เลือกประเภทการลา</option>
                                        <option value="1">ลากิจได้รับค่าจ้าง</option>
                                        <option value="2">ลากิจไม่ได้รับค่าจ้าง</option>
                                        <option value="3">ลาป่วย</option>
                                        <option value="4">ลาป่วยจากงาน</option>
                                        <option value="5">ลาพักร้อน</option>
                                        <option value="8">อื่น ๆ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-12">
                                    <label for="leaveReason" class="form-label">เหตุผลการลา</label>
                                    <span style="color: red;">*</span>
                                    <select class="form-select" id="leaveReason" onchange="checkOther(this)" required>
                                        <option selected>เลือกเหตุผลการลา</option>
                                        <!-- <option value=" กิจส่วนตัว">กิจส่วนตัว</option>
                                        <option value="ป่วย">ป่วย</option>
                                        <option value="พักร้อน">พักร้อน</option>
                                        <option value="อื่น ๆ">อื่น ๆ</option> -->
                                    </select>
                                    <textarea class="form-control mt-2 d-none" id="otherReason" rows="3"
                                        placeholder="กรุณาระบุเหตุผล"></textarea>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-6">
                                    <label for="startDate" class="form-label">วันที่เริ่มต้น</label>
                                    <span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="startDate" required>
                                </div>
                                <div class="col-6">
                                    <label for="startTime" class="form-label">เวลาที่เริ่มต้น</label>
                                    <span style="color: red;">*</span>
                                    <select class="form-select" id="startTime" name="startTime" required>
                                        <option value="08:00">08:00</option>
                                        <option value="08:30">08:30</option>
                                        <option value="09:00">09:00</option>
                                        <option value="09:30">09:30</option>
                                        <option value="10:00">10:00</option>
                                        <option value="10:30">10:30</option>
                                        <option value="11:00">11:00</option>
                                        <option value="11:30">11:30</option>
                                        <option value="11:45">11:45</option>
                                        <option value="12:45">12:45</option>
                                        <option value="13:00">13:00</option>
                                        <option value="13:30">13:30</option>
                                        <option value="14:00">14:00</option>
                                        <option value="14:30">14:30</option>
                                        <option value="15:00">15:00</option>
                                        <option value="15:30">15:30</option>
                                        <option value="16:00">16:00</option>
                                        <option value="16:30">16:30</option>
                                        <option value="16:40">16:40</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3 row">
                                <div class="col-6">
                                    <label for="endDate" class="form-label">วันที่สิ้นสุด</label>
                                    <span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="endDate" required>
                                </div>
                                <div class="col-6">
                                    <label for="endTime" class="form-label">เวลาที่สิ้นสุด</label>
                                    <span style="color: red;">*</span>
                                    <select class="form-select" id="endTime" name="endTime" required>
                                        <option value="08:00">08:00</option>
                                        <option value="08:30">08:30</option>
                                        <option value="09:00">09:00</option>
                                        <option value="09:30">09:30</option>
                                        <option value="10:00">10:00</option>
                                        <option value="10:30">10:30</option>
                                        <option value="11:00">11:00</option>
                                        <option value="11:30">11:30</option>
                                        <option value="11:45">11:45</option>
                                        <option value="12:45">12:45</option>
                                        <option value="13:00">13:00</option>
                                        <option value="13:30">13:30</option>
                                        <option value="14:00">14:00</option>
                                        <option value="14:30">14:30</option>
                                        <option value="15:00">15:00</option>
                                        <option value="15:30">15:30</option>
                                        <option value="16:00">16:00</option>
                                        <option value="16:30">16:30</option>
                                        <option value="16:40" selected>16:40</option>
                                    </select>
                                </div>
                            </div>
                            <div class=" mt-3 row">
                                <div class="col-12">
                                    <label for="telPhone" class="form-label">เบอร์โทรสำหรับการติดต่อ</label>
                                    <?php
$sql2 = "SELECT Emp_phone FROM employee WHERE Emp_usercode = '$userCode'";
$result2 = $conn->query($sql2);

if ($result2->rowCount() > 0) {
    while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
        echo '<input type="text" class="form-control" id="telPhone" value="' . $row2['Emp_phone'] . '">';
    }
} else {
    // กรณีไม่พบข้อมูล
}
?>
                                </div>
                            </div>
                            <div class=" mt-3 row">
                                <div class="col-12">
                                    <label for="file" class="form-label">ไฟล์แนบ (PNG , JPG, JPEG)</label>
                                    <input class="form-control" type="file" id="file" name="file" />
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success" name="submit">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-hover" style="border-top: 1px solid rgba(0, 0, 0, 0.1);" id="leaveTable">
            <thead class="table table-secondary">
                <tr class="text-center align-middle">
                    <th rowspan="2">ลำดับ</th>
                    <th rowspan="2">ประเภทการลา</th>
                    <th rowspan="2">วันที่ยื่นใบลา</th>
                    <th colspan="2">วันเวลาที่ลา</th>
                    <th rowspan="2">ไฟล์แนบ</th>
                    <th rowspan="2">สถานะใบลา</th>
                    <th rowspan="2">สถานะอนุมัติ</th>
                    <th rowspan="2">สถานะ (เฉพาะ HR)</th>
                    <th rowspan="2"></th>
                </tr>
                <tr class="text-center">
                    <th>จาก</th>
                    <th>ถึง</th>
                </tr>
            </thead>
            <tbody>
                <?php
// กำหนดจำนวนรายการต่อหน้า
$itemsPerPage = 10;

// คำนวณหน้าปัจจุบัน
if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

// สร้างคำสั่ง SQL
$sql = "SELECT * FROM leave_items WHERE Emp_usercode = '$userCode' AND Month(Create_datetime) = '$selectedMonth' ORDER BY Create_datetime DESC ";

// หาจำนวนรายการทั้งหมด
$result = $conn->query($sql);
$totalRows = $result->rowCount();

// คำนวณหน้าทั้งหมด
$totalPages = ceil($totalRows / $itemsPerPage);

// คำนวณ offset สำหรับ pagination
$offset = ($currentPage - 1) * $itemsPerPage;

// เพิ่ม LIMIT และ OFFSET ในคำสั่ง SQL
$sql .= " LIMIT $itemsPerPage OFFSET $offset";

// ประมวลผลคำสั่ง SQL
$result = $conn->query($sql);

// แสดงผลลำดับของแถว
$rowNumber = $totalRows - ($currentPage - 1) * $itemsPerPage; // กำหนดลำดับของแถว

// แสดงข้อมูลในตาราง
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr class="text-center align-middle">';
        echo '<td>' . $rowNumber . '</td>';

        echo '<td>';
        if ($row['Leave_ID'] == 1) {
            echo '<span class="text-primary">' . 'ลากิจได้รับค่าจ้าง' . '</span>' . '<br>' . 'เหตุผล : ' . $row['Leave_reason'];
        } elseif ($row['Leave_ID'] == 2) {
            echo '<span class="text-primary">' . 'ลากิจไม่ได้รับค่าจ้าง' . '</span>' . '<br>' . 'เหตุผล : ' . $row['Leave_reason'];
        } elseif ($row['Leave_ID'] == 3) {
            echo '<span class="text-primary">' . 'ลาป่วย' . '</span>' . '<br>' . 'เหตุผล : ' . $row['Leave_reason'];
        } elseif ($row['Leave_ID'] == 4) {
            echo '<span class="text-primary">' . 'ลาป่วยจากงาน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['Leave_reason'];
        } elseif ($row['Leave_ID'] == 5) {
            echo '<span class="text-primary">' . 'ลาพักร้อน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['Leave_reason'];
        } elseif ($row['Leave_ID'] == 6) {
            echo '<span class="text-primary">' . 'ขาดงาน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['Leave_reason'];
        } elseif ($row['Leave_ID'] == 7) {
            echo '<span class="text-primary">' . 'มาสาย' . '</span>' . '<br>' . 'เหตุผล : ' . $row['Leave_reason'];
        } elseif ($row['Leave_ID'] == 8) {
            echo '<span class="text-primary">' . 'อื่น ๆ' . '</span>' . '<br>' . 'เหตุผล : ' . $row['Leave_reason'];
        } else {
            echo $row['Leave_reason'];
        }
        echo '</td>';

        echo '<td>' . $row['Create_datetime'] . '</td>';
        echo '<td>' . $row['Leave_date_start'] . '<br> ' . $row['Leave_time_start'] . '</td>';
        echo '<td>' . $row['Leave_date_end'] . '<br> ' . $row['Leave_time_end'] . '</td>';

        if (!empty($row['Img_file'])) {
            echo '<td><button id="imgBtn" class="btn btn-primary" onclick="window.open(\'../upload/' . $row['Img_file'] . '\', \'_blank\')"><i class="fa-solid fa-file"></i></button></td>';
        } else {
            echo '<td><button id="imgNoBtn" class="btn btn-primary" disabled><i class="fa-solid fa-file-excel"></i></button></td>';
        }

        echo '<td>';
        if ($row['Leave_status'] == 1) {
            echo '<span class="text-danger">ยกเลิกใบลา</span>';
        } else {
        }
        echo '</td>';

        echo '<td>';
        if ($row['Approve_status'] == 0) {
            echo '<span class="text-primary">รออนุมัติ</span>';
        } elseif ($row['Approve_status'] == 1) {
            echo '<span class="text-success">หัวหน้าอนุมัติ</span>';
        } elseif ($row['Approve_status'] == 2) {
            echo '<span class="text-danger">ระดับผู้จัดการขึ้นไปอนุมัติ</span>';
        } else {
            echo '';
        }
        echo '</td>';

        echo '<td>';
        if ($row['Confirm_status'] == 0) {
            echo '<span class="text-primary">รอตรวจสอบ</span>';
        } elseif ($row['Confirm_status'] == 1) {
            echo '<span class="text-success">ตรวจสอบผ่าน</span>';
        } else {
            echo '<span class="text-danger">ตรวจสอบไม่ผ่าน</span>';
        }
        echo '</td>';

        $disabled = $row['Leave_status'] == 1 ? 'disabled' : '';
        echo '<td><button type="button" class="button-shadow btn btn-danger cancel-leave-btn" data-leaveid="' . $row['Leave_ID'] . '" data-createdatetime="' . $row['Create_datetime'] . '" data-usercode="' . $userCode . '" ' . $disabled . '><i class="fa-solid fa-times"></i> ยกเลิกใบลา</button></td>';

        echo '</tr>';
        $rowNumber--;
        // echo '<td><img src="../upload/' . $row['Img_file'] . '" id="img" width="100" height="100"></td>';
    }
} else {
    echo "<tr>
                    <td colspan='8' style='color: red;'>ไม่พบข้อมูล</td>
                </tr>";
}
// ปิดการเชื่อมต่อ
// $conn = null;
?>

            </tbody>
        </table>
        <?php
echo '<div class="pagination">';
echo '<ul class="pagination">';

// สร้างลิงก์ไปยังหน้าแรกหรือหน้าก่อนหน้า
if ($currentPage > 1) {
    echo '<li class="page-item"><a class="page-link" href="?page=1">&laquo;</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">&lt;</a></li>';
}

// สร้างลิงก์สำหรับแต่ละหน้า
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $currentPage) {
        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
    } else {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
}

// สร้างลิงก์ไปยังหน้าถัดไปหรือหน้าสุดท้าย
if ($currentPage < $totalPages) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">&gt;</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">&raquo;</a></li>';
}

echo '</ul>';
echo '</div>';

?>
        <div class="modal fade" id="imageModal<?=$rowNumber?>" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">รูปภาพ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- แสดงรูปภาพ โดยเรียกใช้ชื่อฟิลด์ที่เก็บชื่อไฟล์ภาพ -->
                        <img src="../upload/<?=$row['Img_file']?>" class="img-fluid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#leaveForm').submit(function(e) {
            e.preventDefault(); // ป้องกันฟอร์มจากการส่งอย่างปกติ

            var fd = new FormData(this);

            // เพิ่มข้อมูลจาก PHP variables
            fd.append('userCode', '<?php echo $userCode; ?>');
            fd.append('userName', '<?php echo $userName; ?>');
            fd.append('name', '<?php echo $name; ?>');
            fd.append('telPhone', '<?php echo $telPhone; ?>');
            fd.append('depart', '<?php echo $depart; ?>');

            // ดึงค่าจากฟอร์ม
            var leaveType = $('#leaveType').val();
            var leaveReason = $('#leaveReason').val();
            var startDate = $('#startDate').val();
            var startTime = $('#startTime').val();
            var endDate = $('#endDate').val();
            var endTime = $('#endTime').val();
            var files = $('#file')[0].files;

            // เช็คว่าหากเหตุผลในการลาเป็น "อื่น ๆ" ให้ใช้ค่าจาก input ที่มี id="otherReason"
            if (leaveReason === 'อื่น ๆ') {
                leaveReason = $('#otherReason').val();
            }

            // เพิ่มข้อมูลจากฟอร์มลงใน FormData object
            fd.append('leaveType', leaveType);
            fd.append('leaveReason', leaveReason);
            fd.append('startDate', startDate);
            fd.append('startTime', startTime);
            fd.append('endDate', endDate);
            fd.append('endTime', endTime);
            fd.append('file', files[0]);

            if (leaveType == '1') {
                var leave_personal_days = <?php echo $leave_personal_days; ?>;
                var total_personal = <?php echo $total_personal; ?>;

                if (leave_personal_days >= total_personal) {
                    Swal.fire({
                        title: "ไม่สามารถลาได้",
                        text: "เนื่องจากเกินสิทธิ์",
                        icon: "error"
                    });
                    return false;
                    location.reload()
                }
            } else if (leaveType == '2') {
                var leave_personal_no_days = <?php echo $leave_personal_no_days; ?>;
                var total_personal_no = <?php echo $total_personal_no; ?>;

                if (leave_personal_no_days >= total_personal_no) {
                    Swal.fire({
                        title: "ไม่สามารถลาได้",
                        text: "เนื่องจากเกินสิทธิ์",
                        icon: "error"
                    });
                    return false;
                    location.reload()

                }
            } else if (leaveType == '3') {
                var leave_sick_days = <?php echo $leave_sick_days; ?>;
                var total_sick = <?php echo $total_sick; ?>;

                if (leave_sick_days >= total_sick) {
                    Swal.fire({
                        title: "ไม่สามารถลาได้",
                        text: "เนื่องจากเกินสิทธิ์",
                        icon: "error"
                    });
                    return false;
                    location.reload()
                }
            }

            $.ajax({
                url: '../ajax_add_leave.php',
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('บันทึกคำขอลาสำเร็จ');
                    location.reload();
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึกคำขอลา');
                }
            });
        });
        $('.cancel-leave-btn').click(function() {
            var leaveId = $(this).data('leaveid');
            var createDatetime = $(this).closest('tr').find('td:eq(2)').text();
            var usercode = $(this).data('usercode');
            var name = "<?php echo $name ?>";

            $.ajax({
                url: '../ajax_delete_leave.php',
                method: 'POST',
                data: {
                    leaveId: leaveId,
                    createDatetime: createDatetime,
                    usercode: usercode,
                    name: name
                },
                success: function(response) {
                    // alert('ยกเลิกใบลาสำเร็จ');
                    Swal.fire({
                        title: "ต้องการยกเลิกใบลา ?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ใช่',
                        cancelButtonText: 'ไม่'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function() {
                    alert('มีบางอย่างผิดพลาด');
                }
            });
        });
    });
    $(function() {
        $.datepicker.regional['th'] = {
            closeText: 'ปิด',
            prevText: '&#xAB;&#xA0;ย้อน',
            nextText: 'ถัดไป&#xA0;&#xBB;',
            currentText: 'วันนี้',
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ],
            monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
                'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
            ],
            dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
            dayNamesShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            dayNamesMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            weekHeader: 'Wk',
            dateFormat: 'dd-mm-yy',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['th']);

        $('#startDate').datepicker({
            showButtonPanel: true, // แสดงปุ่มกดตรง datepicker
            changeMonth: true, // ให้แสดงเลือกเดือน
            defaultDate: new Date() // กำหนดให้วันที่เริ่มต้นเป็นวันปัจจุบัน
        }).datepicker("setDate", new Date()); // ให้แสดงวันที่ปัจจุบัน

        $('#endDate').datepicker({
            showButtonPanel: true, // แสดงปุ่มกดตรง datepicker
            changeMonth: true, // ให้แสดงเลือกเดือน
            defaultDate: new Date() // กำหนดให้วันที่เริ่มต้นเป็นวันปัจจุบัน
        }).datepicker("setDate", new Date()); // ให้แสดงวันที่ปัจจุบัน
    });

    function checkOther(select) {
        var otherReasonInput = document.getElementById('otherReason');
        if (select.value === 'อื่น ๆ') {
            otherReasonInput.classList.remove('d-none');
        } else {
            otherReasonInput.classList.add('d-none');
        }
    }

    function updateLeaveReasonField() {
        var leaveType = document.getElementById('leaveType').value;
        var leaveReasonField = document.getElementById('leaveReason');
        var otherReasonField = document.getElementById('otherReason');

        if (leaveType === '1') { // ลากิจได้รับค่าจ้าง
            leaveReasonField.innerHTML = '<option value="กิจส่วนตัว">กิจส่วนตัว</option>' +
                '<option value="อื่น ๆ">อื่น ๆ</option>';
            if (select.value === 'อื่น ๆ') {
                otherReasonField.classList.remove('d-none');
            } else {
                otherReasonField.classList.add('d-none');
            }
        } else if (leaveType === '2') { // ลากิจได้รับค่าจ้าง
            leaveReasonField.innerHTML = '<option value="กิจส่วนตัว">กิจส่วนตัว</option>' +
                '<option value="อื่น ๆ">อื่น ๆ</option>';
            if (select.value === 'อื่น ๆ') {
                otherReasonField.classList.remove('d-none');
            } else {
                otherReasonField.classList.add('d-none');
            }

        } else if (leaveType === '3') { // ลาป่วย
            leaveReasonField.innerHTML = '<option value="ป่วย">ป่วย</option>' +
                '<option value="อื่น ๆ">อื่น ๆ</option>';
            if (select.value === 'อื่น ๆ') {
                otherReasonField.classList.remove('d-none');
            } else {
                otherReasonField.classList.add('d-none');
            }
        } else if (leaveType === '4') { // ลาป่วยจากงาน
            leaveReasonField.innerHTML = '<option value="ป่วย">ป่วย</option>' +
                '<option value="อื่น ๆ">อื่น ๆ</option>';
            if (select.value === 'อื่น ๆ') {
                otherReasonField.classList.remove('d-none');
            } else {
                otherReasonField.classList.add('d-none');
            }
        } else if (leaveType === '5') { // ลาพักร้อน
            leaveReasonField.innerHTML = '<option value="พักร้อน">พักร้อน</option>' +
                '<option value="อื่น ๆ">อื่น ๆ</option>';
            if (select.value === 'อื่น ๆ') {
                otherReasonField.classList.remove('d-none');
            } else {
                otherReasonField.classList.add('d-none');
            }
        } else if (leaveType === '8') { // อื่น ๆ
            leaveReasonField.innerHTML = '<option value="ลาเพื่อทำหมัน">ลาเพื่อทำหมัน</option>' +
                '<option value="ลาคลอด">ลาคลอด</option>' +
                '<option value="ลาอุปสมบท">ลาอุปสมบท</option>' +
                '<option value="ลาเพื่อรับราชการทหาร">ลาเพื่อรับราชการทหาร</option>' +
                '<option value="ลาเพื่อจัดการงานศพ">ลาเพื่อจัดการงานศพ</option>' +
                '<option value="ลาเพื่อพัฒนาและเรียนรู้">ลาเพื่อพัฒนาและเรียนรู้</option>' +
                '<option value="ลาเพื่อการสมรส">ลาเพื่อการสมรส</option>' +
                '<option value="อื่น ๆ">อื่น ๆ</option>';
            if (select.value === 'อื่น ๆ') {
                otherReasonField.classList.remove('d-none');
            } else {
                otherReasonField.classList.add('d-none');
            }
        } else {
            leaveReasonField.innerHTML = '<option selected disabled>เลือกเหตุผลการลา</option>';
            otherReasonField.classList.add('d-none');
        }
    }
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>