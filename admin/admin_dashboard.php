<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

include '../connect.php';
include '../session_lang.php';

if (!isset($_SESSION['s_usercode'])) {
    header('Location: ../login.php');
    exit();
}

$userCode = $_SESSION['s_usercode'];
// echo $userCode;
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

    <!-- <script src="https://kit.fontawesome.com/84c1327080.js" crossorigin="anonymous"></script> -->

    <script src="../js/fontawesome.js"></script>
</head>

<body>
    <?php require 'admin_navbar.php'?>

    <div class="container-fluid">
        <form class="mt-3 mb-3 row" method="post">
            <label for="" class="mt-2 col-auto">เลือกปี</label>
            <div class="col-auto">
                <?php
$currentYear = date('Y'); // ปีปัจจุบัน

if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];

    $startDate = date("Y-m-d", strtotime(($selectedYear - 1) . "-12-01"));
    $endDate = date("Y-m-d", strtotime($selectedYear . "-11-30"));
} else {
    $selectedYear = $currentYear;
}

echo "<select class='form-select' name='year' id='selectedYear'>";

// เพิ่มตัวเลือกของปีหน้า
$nextYear = $currentYear + 1;
echo "<option value='$nextYear'" . ($nextYear == $selectedYear ? " selected" : "") . ">$nextYear</option>";

for ($i = 0; $i <= 4; $i++) {
    $year = $currentYear - $i;
    echo "<option value='$year'" . ($year == $selectedYear ? " selected" : "") . ">$year</option>";
}
echo "</select>";
?>
            </div>

            <label for="" class="mt-2 col-auto">เลือกเดือน</label>
            <div class="col-auto">
                <?php
$months = [
    'All' => $strAllMonth,
    '01' => $strJan,
    '02' => $strFeb,
    '03' => $strMar,
    '04' => $strApr,
    '05' => $strMay,
    '06' => $strJun,
    '07' => $strJul,
    '08' => $strAug,
    '09' => $strSep,
    '10' => $strOct,
    '11' => $strNov,
    '12' => $strDec,
];

$selectedMonth = 'All';

if (isset($_POST['month'])) {
    $selectedMonth = $_POST['month'];
}

echo "<select class='form-select' name='month' id='selectedMonth'>";
foreach ($months as $key => $monthName) {
    echo "<option value='$key'" . ($key == $selectedMonth ? " selected" : "") . ">$monthName</option>";
}
echo "</select>";
?>
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="container">
        <div class="mt-3 row">
            <div class="col-3 filter-card" data-status="all">
                <div class="card text-bg-primary mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
// $sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE Month(l_leave_end_date) = '$selectedMonth'
// AND Year(l_leave_end_date) = '$selectedYear'
// AND l_leave_id <> 6 AND l_leave_id <> 7";

$sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_leave_id NOT IN (6,7) ";

if ($selectedMonth != "All") {
    $sql .= " AND Month(l_leave_start_date) = '$selectedMonth'";
}

$sql .= " AND Year(l_leave_start_date) = '$selectedYear'";

$totalLeaveItems = $conn->query($sql)->fetchColumn();
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <i class="mt-4 fas fa-file-alt ml-2 fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            รายการลาทั้งหมด
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="0">
                <div class="card text-bg-warning mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
// $sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_hr_status = 0 AND Month(l_leave_end_date) = '$selectedMonth'
// AND Year(l_leave_end_date) = '$selectedYear'
// AND l_leave_id <> 6 AND l_leave_id <> 7";
$sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_leave_id NOT IN (6,7)
AND l_hr_status = 0";

if ($selectedMonth != "All") {
    $sql .= " AND Month(l_leave_start_date) = '$selectedMonth'";
}

$sql .= " AND Year(l_leave_start_date) = '$selectedYear'";

$totalLeaveItems = $conn->query($sql)->fetchColumn();

?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <i class="mt-4 fa-solid fa-clock-rotate-left fa-2xl" style="color: #ffffff;"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            รายการลาที่รอตรวจสอบ
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="1">
                <div class="card text-bg-success mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
// $sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_hr_status = 1 AND Month(l_leave_end_date) = '$selectedMonth'
// AND Year(l_leave_end_date) = '$selectedYear'
// AND l_leave_id <> 6 AND l_leave_id <> 7";
$sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_leave_id NOT IN (6,7)
AND l_hr_status = 1";

if ($selectedMonth != "All") {
    $sql .= " AND Month(l_leave_start_date) = '$selectedMonth'";
}

$sql .= " AND Year(l_leave_start_date) = '$selectedYear'";
$totalLeaveItems = $conn->query($sql)->fetchColumn();
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <i class="mt-4 fa-solid fa-thumbs-up fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            รายการลาที่ตรวจสอบผ่าน
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="2">
                <div class="card text-bg-danger mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
// $sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_hr_status = 2 AND Month(l_leave_end_date) = '$selectedMonth'
// AND Year(l_leave_end_date) = '$selectedYear'
// AND l_leave_id <> 6 AND l_leave_id <> 7";
$sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_leave_id NOT IN (6,7)
AND l_hr_status = 2";

if ($selectedMonth != "All") {
    $sql .= " AND Month(l_leave_start_date) = '$selectedMonth'";
}

$sql .= " AND Year(l_leave_start_date) = '$selectedYear'";
$totalLeaveItems = $conn->query($sql)->fetchColumn();
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <i class="mt-4 fa-solid fa-thumbs-down fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            รายการลาที่ตรวจสอบไม่ผ่าน
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ตารางข้อมูลการลา -->
    <div class="container-fluid">
        <table class="table table-hover" style="border-top: 1px solid rgba(0, 0, 0, 0.1);" id="leaveTable">
            <thead>
                <tr class="text-center align-middle">
                    <th rowspan="2">ลำดับ</th>
                    <th rowspan="1">รหัสพนักงาน</th>
                    <th rowspan="1">ชื่อ - นามสกุล</th>
                    <th rowspan="2">วันที่ยื่นใบลา</th>
                    <th rowspan="1">รายการลา</th>
                    <th colspan="2" class="text-center">วันเวลาที่ลา</th>
                    <th rowspan="2">ไฟล์แนบ</th>
                    <th rowspan="2">สถานะใบลา</th>
                    <th rowspan="2">สถานะอนุมัติ_1</th>
                    <th rowspan="2">วันเวลาอนุมัติ_1</th>
                    <th rowspan="2">เหตุผล_1</th>
                    <th rowspan="2">หัวหน้า</th>
                    <th rowspan="2">สถานะอนุมัติ_2</th>
                    <th rowspan="2">วันเวลาอนุมัติ_2</th>
                    <th rowspan="2">เหตุผล_2</th>
                    <th rowspan="2">ผู้จัดการขึ้นไป</th>
                    <th rowspan="2">สถานะ (เฉพาะ HR)</th>
                    <th rowspan="2"></th>
                </tr>
                <tr class="text-center">
                    <!-- <th><input type="text" class="form-control" id="codeSearch"></th>
                      -->
                    <?php $searchCode = isset($_GET['codeSearch']) ? $_GET['codeSearch'] : '';
?>
                    <th><input type="text" class="form-control" id="codeSearch"
                            value="<?php echo htmlspecialchars($searchCode); ?>"></th>
                    <th><input type="text" class="form-control" id="nameSearch"></th>
                    <th><input type="text" class="form-control" id="leaveSearch"></th>
                    <!-- <th><input type="text" class="form-control" id="leaveSearch"></th> -->
                    <th>จาก</th>
                    <th>ถึง</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
$itemsPerPage = 15;

// คำนวณหน้าปัจจุบัน
if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

$sql = "SELECT * FROM leave_list WHERE l_leave_id NOT IN (6,7)
AND l_usercode LIKE '%" . $searchCode . "%'";

if ($selectedMonth != "All") {
    $sql .= " AND Month(l_leave_start_date) = '$selectedMonth'";
}

$sql .= " AND Year(l_leave_start_date) = '$selectedYear' ORDER BY l_create_datetime DESC";

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
        echo '<tr class="align-middle">';

        // 0
        echo '<td hidden>';
        if ($row['l_leave_id'] == 1) {
            echo '<span class="text-primary">' . 'ลากิจได้รับค่าจ้าง' . '</span>';
        } elseif ($row['l_leave_id'] == 2) {
            echo '<span class="text-primary">' . 'ลากิจไม่ได้รับค่าจ้าง' . '</span>';
        } elseif ($row['l_leave_id'] == 3) {
            echo '<span class="text-primary">' . 'ลาป่วย' . '</span>';
        } elseif ($row['l_leave_id'] == 4) {
            echo '<span class="text-primary">' . 'ลาป่วยจากงาน' . '</span>';
        } elseif ($row['l_leave_id'] == 5) {
            echo '<span class="text-primary">' . 'ลาพักร้อน' . '</span>';
        } elseif ($row['l_leave_id'] == 6) {
            echo '<span class="text-primary">' . 'ขาดงาน' . '</span>';
        } elseif ($row['l_leave_id'] == 7) {
            echo '<span class="text-primary">' . 'มาสาย' . '</span>';
        } elseif ($row['l_leave_id'] == 8) {
            echo '<span class="text-primary">' . 'อื่น ๆ' . '</span>';
        } else {
            echo $row['l_leave_id'];
        }
        echo '</td>';

        // 1
        echo '<td hidden>' . $row['l_name'] . '</td>';

        // 2
        echo '<td hidden>' . $row['l_department'] . '</td>';

        // 3
        echo '<td hidden>' . $row['l_leave_reason'] . '</td>';

        // 4
        echo '<td>' . $rowNumber . '</td>';

        // 5
        echo '<td>' . $row['l_usercode'] . '</td>';

        // 6
        echo '<td>' . '<span class="text-primary">' . $row['l_name'] . '</span>' . '<br>' . 'แผนก : ' . $row['l_department'] . '</td>'; // คอลัมน์ 2 ชื่อพนักงาน + แผนก

        // 7
        echo '<td>' . $row['l_create_datetime'] . '</td>';

        // 8
        echo '<td>';
        if ($row['l_leave_id'] == 1) {
            echo '<span class="text-primary">' . 'ลากิจได้รับค่าจ้าง' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 2) {
            echo '<span class="text-primary">' . 'ลากิจไม่ได้รับค่าจ้าง' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 3) {
            echo '<span class="text-primary">' . 'ลาป่วย' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 4) {
            echo '<span class="text-primary">' . 'ลาป่วยจากงาน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 5) {
            echo '<span class="text-primary">' . 'ลาพักร้อน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 6) {
            echo '<span class="text-primary">' . 'ขาดงาน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 7) {
            echo '<span class="text-primary">' . 'มาสาย' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 8) {
            echo '<span class="text-primary">' . 'อื่น ๆ' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } else {
            echo 'ไม่พบประเภทการลาและเหตุผลการลา';
        }
        echo '</td>';

        // 9
        // 08:45
        if ($row['l_leave_start_time'] == '09:00:00' && $row['l_remark'] == '08:45:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 08:45:00</td>';
        }
        // 09:45
        else if ($row['l_leave_start_time'] == '10:00:00' && $row['l_remark'] == '09:45:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 09:45:00</td>';
        }
        // 10:45
        else if ($row['l_leave_start_time'] == '11:00:00' && $row['l_remark'] == '10:45:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 10:45:00</td>';
        }
        // 11:45
        else if ($row['l_leave_start_time'] == '12:00:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 11:45:00</td>';
        }
        // 12:45
        else if ($row['l_leave_start_time'] == '13:00:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 12:45:00</td>';
        }
        // 13:10
        else if ($row['l_leave_start_time'] == '13:30:00' && $row['l_remark'] == '13:10:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 13:10:00</td>';
        }
        // 13:40
        else if ($row['l_leave_start_time'] == '14:00:00' && $row['l_remark'] == '13:40:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 13:40:00</td>';
        }
        // 14:10
        else if ($row['l_leave_start_time'] == '14:30:00' && $row['l_remark'] == '14:10:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 14:10:00</td>';
        }
        // 14:40
        else if ($row['l_leave_start_time'] == '15:00:00' && $row['l_remark'] == '14:40:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 14:40:00</td>';
        }
        // 15:10
        else if ($row['l_leave_start_time'] == '15:30:00' && $row['l_remark'] == '15:10:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 15:10:00</td>';
        }
        // 15:40
        else if ($row['l_leave_start_time'] == '16:00:00' && $row['l_remark'] == '15:40:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 15:40:00</td>';
        }
        // 16:10
        else if ($row['l_leave_start_time'] == '16:30:00' && $row['l_remark'] == '16:10:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 16:10:00</td>';
        }
        // 16:40
        else if ($row['l_leave_start_time'] == '17:00:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> 16:40:00</td>';
        } else {
            // กรณีอื่น ๆ แสดงเวลาตาม l_leave_start_time
            echo '<td>' . $row['l_leave_start_date'] . '<br> ' . $row['l_leave_start_time'] . '</td>';
        }

        // 10
        // 08:45
        if ($row['l_leave_end_time'] == '09:00:00' && $row['l_remark'] == '08:45:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 08:45:00</td>';
        }
        // 09:45
        else if ($row['l_leave_end_time'] == '10:00:00' && $row['l_remark'] == '09:45:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 09:45:00</td>';
        }
        // 10:45
        else if ($row['l_leave_end_time'] == '11:00:00' && $row['l_remark'] == '10:45:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 10:45:00</td>';
        }
        // 11:45
        else if ($row['l_leave_end_time'] == '12:00:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 11:45:00</td>';
        }
        // 12:45
        else if ($row['l_leave_end_time'] == '13:00:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 12:45:00</td>';
        }
        // 13:10
        else if ($row['l_leave_end_time'] == '13:30:00' && $row['l_remark'] == '13:10:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 13:10:00</td>';
        }
        // 13:40
        else if ($row['l_leave_end_time'] == '14:00:00' && $row['l_remark'] == '13:40:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 13:40:00</td>';
        }
        // 14:10
        else if ($row['l_leave_end_time'] == '14:30:00' && $row['l_remark'] == '14:10:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 14:10:00</td>';
        }
        // 14:40
        else if ($row['l_leave_end_time'] == '15:00:00' && $row['l_remark'] == '14:40:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 14:40:00</td>';
        }
        // 15:10
        else if ($row['l_leave_end_time'] == '15:30:00' && $row['l_remark'] == '15:10:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 15:10:00</td>';
        }
        // 15:40
        else if ($row['l_leave_end_time'] == '16:00:00' && $row['l_remark'] == '15:40:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 15:40:00</td>';
        }
        // 16:10
        else if ($row['l_leave_end_time'] == '16:30:00' && $row['l_remark'] == '16:10:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 16:10:00</td>';
        }
        // 16:40
        else if ($row['l_leave_end_time'] == '17:00:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> 16:40:00</td>';
        } else {
            // กรณีอื่น ๆ แสดงเวลาตาม l_leave_start_time
            echo '<td>' . $row['l_leave_end_date'] . '<br> ' . $row['l_leave_end_time'] . '</td>';
        }

        // 11
        echo '</td>';
        if (!empty($row['l_file'])) {
            echo '<td><button id="imgBtn" class="btn btn-primary" onclick="window.open(\'../upload/' . $row['l_file'] . '\', \'_blank\')"><i class="fa-solid fa-file"></i></button></td>';
        } else {
            echo '<td><button id="imgNoBtn" class="btn btn-primary" disabled><i class="fa-solid fa-file-excel"></i></button></td>';
        }
        echo '</td>';

        // 12
        echo '<td>';
        if ($row['l_leave_status'] == 0) {
            echo '<span class="text-success">ปกติ</span>';
        } else {
            echo '<span class="text-danger">ยกเลิกใบลา</span>';
        }
        echo '</td>';

        // 13
        echo '<td>';
        // รอหัวหน้าอนุมัติ
        if ($row['l_approve_status'] == 0) {
            echo '<div class="text-warning"><b>รอหัวหน้าอนุมัติ</b></div>';
        }
        // รอผจกอนุมัติ
        elseif ($row['l_approve_status'] == 1) {
            echo '<div class="text-warning"><b>รอผู้จัดการอนุมัติ</b></div>';
        }
        // หัวหน้าอนุมัติ
        elseif ($row['l_approve_status'] == 2) {
            echo '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
        }
        // หัวหน้าไม่อนุมัติ
        elseif ($row['l_approve_status'] == 3) {
            echo '<div class="text-danger"><b>หัวหน้าไม่อนุมัติ</b></div>';
        }
        //  ผจก อนุมัติ
        elseif ($row['l_approve_status'] == 4) {
            echo '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
        }
        //  ผจก ไม่อนุมัติ
        elseif ($row['l_approve_status'] == 5) {
            echo '<div class="text-danger"><b>ผู้จัดการไม่อนุมัติ</b></div>';
        }
        // อื่น ๆ
        elseif ($row['l_approve_status'] == 6) {
            echo '';
        }
        // ไม่มีสถานะ
        else {
            echo 'ไม่พบสถานะ';
        }
        echo '</td>';

        // 14
        echo '<td>' . $row['l_approve_datetime'] . '</td>';

        // 15
        echo '<td>' . $row['l_reason'] . '</td>';

        // 16
        echo '<td>' . $row['l_approve_name'] . '</td>';

        // 17
        echo '<td>';
        // รอหัวหน้าอนุมัติ
        if ($row['l_approve_status2'] == 0) {
            echo '<div class="text-warning"><b>รอหัวหน้าอนุมัติ</b></div>';
        }
        // รอผจกอนุมัติ
        elseif ($row['l_approve_status2'] == 1) {
            echo '<div class="text-warning"><b>รอผู้จัดการอนุมัติ</b></div>';
        }
        // หัวหน้าอนุมัติ
        elseif ($row['l_approve_status2'] == 2) {
            echo '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
        }
        // หัวหน้าไม่อนุมัติ
        elseif ($row['l_approve_status2'] == 3) {
            echo '<div class="text-danger"><b>หัวหน้าไม่อนุมัติ</b></div>';
        }
        //  ผจก อนุมัติ
        elseif ($row['l_approve_status2'] == 4) {
            echo '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
        }
        //  ผจก ไม่อนุมัติ
        elseif ($row['l_approve_status2'] == 5) {
            echo '<div class="text-danger"><b>ผู้จัดการไม่อนุมัติ</b></div>';
        } elseif ($row['l_approve_status2'] == 6) {
            echo '';
        }
        // ไม่มีสถานะ
        else {
            echo 'ไม่พบสถานะ';
        }
        echo '</td>';

        // 18
        echo '<td>' . $row['l_approve_datetime2'] . '</td>';

        // 19
        echo '<td>' . $row['l_reason2'] . '</td>';

        // 20
        echo '<td>' . $row['l_approve_name2'] . '</td>';

        // 21
        echo '<td >';
        if ($row['l_hr_status'] == 0) {
            echo '<div class="text-warning"><b>รอตรวจสอบ</b></div>';
        } elseif ($row['l_hr_status'] == 1) {
            echo '<div class="text-success"><b>ผ่าน</b></div>';
        } elseif ($row['l_hr_status'] == 2) {
            echo '<div class="text-danger"><b>ไม่ผ่าน</b></div>';
        } else {
            echo $row['l_hr_status'];
        }
        echo '</td>';

        // 22 ปุ่มตรวจสอบ
        if ($row['l_approve_status'] == 2 || $row['l_approve_status'] == 3) {
            echo "<td><button type='button' class='btn btn-primary leaveChk' data-bs-toggle='modal' data-bs-target='#leaveModal'>ตรวจสอบ</button></td>";
        } else {
            echo "<td><button type='button' class='btn btn-primary leaveChk' data-bs-toggle='modal' data-bs-target='#leaveModal'>ตรวจสอบ</button></td>";
        }
        echo '</tr>';
        $rowNumber--;
    }
} else {
    echo '<tr><td colspan="18" style="text-align: left; color:red;">ไม่พบข้อมูล</td></tr>';
}
?>
            </tbody>
        </table>
        <?php
echo '<div class="pagination">';
echo '<ul class="pagination">';

if ($currentPage > 1) {
    echo '<li class="page-item"><a class="page-link" href="?page=1&month=' . urlencode($selectedMonth) . '&codeSearch=' . urlencode($searchCode) . '">&laquo;</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '&month=' . urlencode($selectedMonth) . '&codeSearch=' . urlencode($searchCode) . '">&lt;</a></li>';
}

$startPage = max(1, $currentPage - 2);
$endPage = min($totalPages, $currentPage + 2);

for ($i = $startPage; $i <= $endPage; $i++) {
    if ($i == $currentPage) {
        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
    } else {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '&month=' . urlencode($selectedMonth) . '&codeSearch=' . urlencode($searchCode) . '">' . $i . '</a></li>';
    }
}

if ($currentPage < $totalPages) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '&month=' . urlencode($selectedMonth) . '&codeSearch=' . urlencode($searchCode) . '">&gt;</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '&month=' . urlencode($selectedMonth) . '&codeSearch=' . urlencode($searchCode) . '">&raquo;</a></li>';
}

echo '</ul>';

// Input to jump to a specific page
echo '<input type="number" id="page-input" max="' . $totalPages . '" class="mx-2 form-control d-inline" style="width: 100px; height: 40px; text-align: center;" placeholder="เลขหน้า" value="' . $currentPage . '" onchange="changePage(this.value, \'' . $selectedMonth . '\', \'' . $searchCode . '\')">';

echo '</div>';

?>

        <!-- Modal เช็คการลา -->
        <div class="modal fade" id="leaveModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title
                        01f s-5" id="staticBackdropLabel">ข้อมูลการลา</h4>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">X</button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger">ไม่ผ่าน</button>
                        <button type="button" class="btn btn-success">ผ่าน</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(".leaveChk").click(function() {
        var rowData = $(this).closest("tr").find("td");

        $('#leaveModal .modal-body').html(
            '<table class="table table-bordered">' +
            '<tr>' +
            '<th>รหัสพนักงาน</th>' +
            '<td>' + $(rowData[5]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>ชื่อ - นามสกุล</th>' +
            '<td>' + $(rowData[1]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>แผนก</th>' +
            '<td>' + $(rowData[2]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>วันที่ยื่นใบลา</th>' +
            '<td>' + $(rowData[7]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>ประเภทการลา</th>' +
            '<td>' + $(rowData[0]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>เหตุผลการลา</th>' +
            '<td>' + $(rowData[3]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>วันเวลาที่ลา</th>' +
            '<td>' + $(rowData[9]).text() + ' ถึง ' + $(rowData[10]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>สถานะใบลา</th>' +
            '<td>' + $(rowData[12]).html() + '</td>' +
            '</tr>' +
            '</table>'
        );

        $('.modal-footer .btn-success').off('click').on('click', function() {
            var userCode = $(rowData[5]).text(); // รหัสพนักงาน
            var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
            var leaveType = $(rowData[0]).text(); // ประเภทการลา
            var empName = $(rowData[1]).text(); // ชื่อพนักงาน
            var depart = $(rowData[2]).text(); // แผนก
            var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
            var leaveStartDate = $(rowData[9]).text(); // วันเวลาที่ลาเริ่มต้น
            var leaveEndDate = $(rowData[10]).text(); // วันเวลาที่ลาสิ้นสุด
            var leaveStatus = $(rowData[12]).text(); // สถานะใบลา
            var checkFirm = '1'; // ผ่าน

            var userName = '<?php echo $userName; ?>';

            // alert(userCode)
            $.ajax({
                url: 'a_ajax_upd_status.php',
                method: 'POST',
                data: {
                    createDate: createDate,
                    userCode: userCode,
                    userName: userName,
                    leaveType: leaveType,
                    leaveReason: leaveReason,
                    leaveStartDate: leaveStartDate,
                    leaveEndDate: leaveEndDate,
                    depart: depart,
                    checkFirm: checkFirm,
                    empName: empName,
                    leaveStatus: leaveStatus

                },
                success: function(response) {
                    $('#leaveModal').modal('hide');
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'ตรวจสอบผ่านสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location
                            .reload(); // Reload the page after user clicks confirm
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
        $('.modal-footer .btn-danger').off('click').on('click', function() {
            var userCode = $(rowData[5]).text(); // รหัสพนักงาน
            var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
            var leaveType = $(rowData[0]).text(); // ประเภทการลา
            var empName = $(rowData[1]).text(); // ชื่อพนักงาน
            var depart = $(rowData[2]).text(); // แผนก
            var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
            var leaveStartDate = $(rowData[9]).text(); // วันเวลาที่ลาเริ่มต้น
            var leaveEndDate = $(rowData[10]).text(); // วันเวลาที่ลาสิ้นสุด
            var leaveStatus = $(rowData[12]).text(); // สถานะใบลา

            var checkFirm = '2'; // ไม่ผ่าน
            var userName = '<?php echo $userName; ?>';

            $.ajax({
                url: 'a_ajax_upd_status.php',
                method: 'POST',
                data: {
                    createDate: createDate,
                    userCode: userCode,
                    userName: userName,
                    leaveType: leaveType,
                    leaveReason: leaveReason,
                    leaveStartDate: leaveStartDate,
                    leaveEndDate: leaveEndDate,
                    depart: depart,
                    checkFirm: checkFirm,
                    empName: empName,
                    leaveStatus: leaveStatus

                },
                success: function(response) {
                    $('#leaveModal').modal('hide');
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'ตรวจสอบไม่ผ่านสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location
                            .reload(); // Reload the page after user clicks confirm
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });

    $(".filter-card").click(function() {
        /* edit by pim */
        // ลบ active จากการ์ดทั้งหมด
        $(".filter-card .card").removeClass("active");

        // เพิ่ม active ให้การ์ดภายใน filter-card ที่คลิก
        $(this).find(".card").addClass("active");

        var status = $(this).data("status");
        var selectedMonth = $("#selectedMonth").val();
        var selectedYear = $("#selectedYear").val();

        // alert(status)
        $.ajax({
            url: 'a_ajax_get_leave_data.php',
            method: 'GET',
            data: {
                status: status,
                month: selectedMonth,
                year: selectedYear
            },
            dataType: 'json',
            success: function(data) {
                $("tbody").empty();
                if (data.length === 0) {
                    $("tbody").append(
                        '<tr><td colspan="19" class="text-danger" style="text-align: left;">ไม่พบข้อมูล</td></tr>'
                    );
                } else {
                    var totalItems = data.length;

                    $.each(data, function(index, row) {
                        var leaveType = '';
                        if (row['l_leave_id'] == 1) {
                            leaveType = 'ลากิจได้รับค่าจ้าง';
                        } else if (row['l_leave_id'] == 2) {
                            leaveType = 'ลากิจไม่ได้รับค่าจ้าง';
                        } else if (row['l_leave_id'] == 3) {
                            leaveType = 'ลาป่วย';
                        } else if (row['l_leave_id'] == 4) {
                            leaveType = 'ลาป่วยจากงาน';
                        } else if (row['l_leave_id'] == 5) {
                            leaveType = 'ลาพักร้อน';
                        } else if (row['l_leave_id'] == 6) {
                            leaveType = 'ขาดงาน';
                        } else if (row['l_leave_id'] == 7) {
                            leaveType = 'มาสาย';
                        } else if (row['l_leave_id'] == 8) {
                            leaveType = 'อื่น ๆ';
                        } else {
                            leaveType = row['l_leave_id'];
                        }

                        // สถานะใบลา
                        var leaveStatus = '';
                        if (row['l_leave_status'] == 0) {
                            leaveStatus = '<div class="text-success">ปกติ</div>';
                        } else if (row['l_leave_status'] == 1) {
                            leaveStatus = '<div class="text-danger">ยกเลิกใบลา</div>';
                        } else {
                            leaveStatus = 'ไม่พบสถานะใบลา';
                        }

                        var confirmStatus = '';
                        if (row['l_hr_status'] == 0) {
                            confirmStatus =
                                '<div class="text-warning"><b>รอตรวจสอบ</b></div>';
                        } else if (row['l_hr_status'] == 1) {
                            confirmStatus = '<div class="text-success"><b>ผ่าน</b></div>';
                        } else if (row['l_hr_status'] == 2) {
                            confirmStatus = '<div class="text-danger"><b>ไม่ผ่าน</b></div>';
                        } else {
                            confirmStatus = row['l_hr_status'];
                        }

                        var approveStatus;
                        if (row['l_approve_status'] == 0) {
                            approveStatus =
                                '<div class="text-warning"><b>รอหัวหน้าอนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 1) {
                            approveStatus =
                                '<div class="text-warning"><b>รอผู้จัดการอนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 2) {
                            approveStatus =
                                '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 3) {
                            approveStatus =
                                '<div class="text-danger"><b>หัวหน้าไม่อนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 4) {
                            approveStatus =
                                '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 5) {
                            approveStatus =
                                '<div class="text-danger"><b>ผู้จัดการไม่อนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 6) {
                            approveStatus =
                                '';
                        } else {
                            approveStatus = 'ไม่พบสถานะ';
                        }

                        var approveReason = row['l_reason'] !== null ? row['l_reason'] : '';

                        var approveStatus2;
                        if (row['l_approve_status2'] == 0) {
                            approveStatus2 =
                                '<div class="text-warning"><b>รอหัวหน้าอนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 1) {
                            approveStatus2 =
                                '<div class="text-warning"><b>รอผู้จัดการอนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 2) {
                            approveStatus2 =
                                '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 3) {
                            approveStatus2 =
                                '<div class="text-danger"><b>หัวหน้าไม่อนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 4) {
                            approveStatus2 =
                                '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 5) {
                            approveStatus2 =
                                '<div class="text-danger"><b>ผู้จัดการไม่อนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 6) {
                            approveStatus2 =
                                '';
                        } else {
                            approveStatus2 = 'ไม่พบสถานะ';
                        }

                        // เวลาเริ่มต้น
                        var startTime;
                        if (row['l_leave_start_time'] == '12:00:00') {
                            startTime = '11:45:00';
                        } else if (row['l_leave_start_time'] == '13:00:00') {
                            startTime = '12:45:00';
                        } else if (row['l_leave_start_time'] == '17:00:00') {
                            startTime = '16:40:00';
                        } else {
                            startTime = row['l_leave_start_time'];
                        }

                        // เวลาสิ้นสุด
                        var endTime;
                        if (row['l_leave_end_time'] == '12:00:00') {
                            endTime = '11:45:00';
                        } else if (row['l_leave_end_time'] == '13:00:00') {
                            endTime = '12:45:00';
                        } else if (row['l_leave_end_time'] == '17:00:00') {
                            endTime = '16:40:00';
                        } else {
                            endTime = row['l_leave_end_time'];
                        }

                        var newRow = '<tr class="align-middle">' +
                            // 0
                            '<td hidden>' +
                            (row['l_leave_id'] == 1 ?
                                '<span class="text-primary">ลากิจได้รับค่าจ้าง</span>' :
                                '') +
                            (row['l_leave_id'] == 2 ?
                                '<span class="text-primary">ลากิจไม่ได้รับค่าจ้าง</span>' :
                                '') +
                            (row['l_leave_id'] == 3 ?
                                '<span class="text-primary">ลาป่วย</span>' :
                                '') +
                            (row['l_leave_id'] == 4 ?
                                '<span class="text-primary">ลาป่วยจากงาน</span>' :
                                '') +
                            (row['l_leave_id'] == 5 ?
                                '<span class="text-primary">ลาพักร้อน</span>' :
                                '') +
                            (row['l_leave_id'] == 6 ?
                                '<span class="text-primary">ขาดงาน</span>' :
                                '') +
                            (row['l_leave_id'] == 7 ?
                                '<span class="text-primary">มาสาย</span>' :
                                '') +
                            (row['l_leave_id'] == 8 ?
                                '<span class="text-primary">อื่น ๆ</span>' :
                                '') +
                            '</td>' +

                            // 1
                            '<td hidden>' + (row['l_name'] ? row['l_name'] : '') +
                            '</td>' +

                            // 2
                            '<td hidden>' + (row['l_department'] ? row['l_department'] :
                                '') +
                            '</td>' +

                            // 3
                            '<td hidden>' + (row['l_leave_reason'] ? row['l_leave_reason'] :
                                '') +
                            '</td>' +

                            // 4
                            '<td>' + (totalItems - index) + '</td>' +

                            // 5
                            '<td>' + (row['l_usercode'] ? row['l_usercode'] : '') +
                            '</td>' +

                            // 6
                            '<td>' + '<span class="text-primary">' + (row['l_name'] ? row[
                                'l_name'] : '') + '</span><br>' +
                            'แผนก : ' + (row['l_department'] ? row['l_department'] : '') +
                            '</td>' +

                            // 7
                            '<td>' + (row['l_create_datetime'] ? row['l_create_datetime'] :
                                '') + '</td>' + // Creation Date Time
                            '<td>';

                        // 8
                        if (row['l_leave_id'] == 1) {
                            newRow +=
                                '<span class="text-primary">ลากิจได้รับค่าจ้าง</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 2) {
                            newRow +=
                                '<span class="text-primary">ลากิจไม่ได้รับค่าจ้าง</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 3) {
                            newRow +=
                                '<span class="text-primary">ลาป่วย</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 4) {
                            newRow +=
                                '<span class="text-primary">ลาป่วยจากงาน</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 5) {
                            newRow +=
                                '<span class="text-primary">ลาพักร้อน</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 6) {
                            newRow +=
                                '<span class="text-primary">ขาดงาน</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 7) {
                            newRow +=
                                '<span class="text-primary">มาสาย</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 8) {
                            newRow +=
                                '<span class="text-primary">อื่น ๆ</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else {
                            newRow +=
                                '<span class="text-danger">ไม่พบประเภทการลาและเหตุผลการลา</span>';
                        }
                        newRow += '</td>' +

                            // 9
                            '<td>' + (row['l_leave_start_date'] ? row[
                                'l_leave_start_date'] : '') + '<br>' +
                            ' ' + (startTime ? startTime : '') +
                            '</td>' +

                            // 10
                            '<td>' + (row['l_leave_end_date'] ? row['l_leave_end_date'] :
                                '') + '<br>' +
                            ' ' + (endTime ? endTime : '') +
                            '</td>';
                        // 11
                        if (row['l_file']) {
                            newRow +=
                                '<td><button id="imgBtn" class="btn btn-primary" onclick="window.open(\'../upload/' +
                                row['l_file'] +
                                '\', \'_blank\')"><i class="fa-solid fa-file"></i></button></td>';
                        } else {
                            newRow +=
                                '<td><button id="imgNoBtn" class="btn btn-primary" disabled><i class="fa-solid fa-file-excel"></i></button></td>';
                        }
                        newRow +=
                            // 12
                            '<td>' + leaveStatus + '</td>' +

                            // 13
                            '<td>' + approveStatus + '</td>' +

                            // 14
                            '<td>' + (row['l_approve_datetime'] !== null ? row[
                                'l_approve_datetime'] : '') + '</td>' +

                            // 15
                            '<td>' + (row['l_reason'] ? row['l_reason'] : '') + '</td>' +

                            // 16
                            '<td>' + (row['l_approve_name'] ? row['l_approve_name'] : '') +
                            '</td>' +

                            // 17
                            '<td>' + approveStatus2 + '</td>' +

                            // 18
                            '<td>' + (row['l_approve_datetime2'] !== null ? row[
                                'l_approve_datetime2'] : '') + '</td>' +

                            // 19
                            '<td>' + (row['l_reason2'] ? row['l_reason2'] : '') + '</td>' +

                            // 20
                            '<td>' + (row['l_approve_name2'] ? row['l_approve_name2'] :
                                '') + '</td>' +

                            // 21
                            '<td>' + confirmStatus + '</td>' +

                            // 22
                            '<td>';
                        if (row['l_approve_status'] == 1 || row['l_approve_status'] == 3) {

                            newRow +=
                                // '<button type="button" class="btn btn-primary leaveChk" data-bs-toggle="modal" data-bs-target="#leaveModal" disabled>ตรวจสอบ</button>';
                                '<button type="button" class="btn btn-primary leaveChk" data-bs-toggle="modal" data-bs-target="#leaveModal" >ตรวจสอบ</button>';
                        } else {
                            newRow +=
                                '<button type="button" class="btn btn-primary leaveChk" data-bs-toggle="modal" data-bs-target="#leaveModal">ตรวจสอบ</button>';
                        }

                        newRow += '</td></tr>';

                        $("tbody").append(newRow);
                    });
                    $(".leaveChk").click(function() {
                        var rowData = $(this).closest("tr").find("td");

                        // console.log($(rowData[0]).text());
                        // console.log($(rowData[1]).text());
                        // console.log($(rowData[2]).text());
                        // console.log($(rowData[3]).text());
                        // console.log($(rowData[4]).text());
                        // console.log($(rowData[5]).text());
                        // console.log($(rowData[6]).text());
                        // console.log($(rowData[7]).text());
                        // console.log($(rowData[8]).text());
                        // console.log($(rowData[9]).text());
                        // console.log($(rowData[10]).text());
                        // console.log($(rowData[11]).text());
                        // console.log($(rowData[12]).text());

                        // Populate modal content
                        $('#leaveModal .modal-body').html(
                            '<table class="table table-bordered">' +
                            '<tr>' +
                            '<th>รหัสพนักงาน</th>' +
                            '<td>' + $(rowData[5]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>ชื่อ - นามสกุล</th>' +
                            '<td>' + $(rowData[1]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>แผนก</th>' +
                            '<td>' + $(rowData[2]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>วันที่ยื่นใบลา</th>' +
                            '<td>' + $(rowData[7]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>ประเภทการลา</th>' +
                            '<td>' + $(rowData[0]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>เหตุผลการลา</th>' +
                            '<td>' + $(rowData[3]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>วันเวลาที่ลา</th>' +
                            '<td>' + $(rowData[9]).text() + ' ถึง ' + $(rowData[10])
                            .text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>สถานะใบลา</th>' +
                            '<td>' + $(rowData[12]).html() + '</td>' +
                            '</tr>' +
                            '</table>'
                        );

                        $('#leaveModal').modal('show');
                        $('.modal-footer .btn-success').off('click').on('click',
                            function() {
                                var leaveType = $(rowData[0]).text(); // ประเภทการลา
                                var empName = $(rowData[1]).text(); // ชื่อพนักงาน
                                var depart = $(rowData[2]).text(); // แผนก
                                var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
                                var userCode = $(rowData[5]).text(); // รหัสพนักงาน
                                var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
                                var leaveStartDate = $(rowData[9])
                                    .text(); // วันเวลาที่ลาเริ่มต้น
                                var leaveEndDate = $(rowData[10])
                                    .text(); // วันเวลาที่ลาสิ้นสุด
                                var leaveStatus = $(rowData[12]).text(); // สถานะใบลา

                                var checkFirm = '1'; // ผ่าน
                                var userName = '<?php echo $userName; ?>';

                                $.ajax({
                                    url: 'a_ajax_upd_status.php',
                                    method: 'POST',
                                    data: {
                                        leaveType: leaveType,
                                        empName: empName,
                                        depart: depart,
                                        leaveReason: leaveReason,
                                        userCode: userCode,
                                        createDate: createDate,
                                        leaveStartDate: leaveStartDate,
                                        leaveEndDate: leaveEndDate,
                                        checkFirm: checkFirm,
                                        userName: userName,
                                        empName: empName,
                                        leaveStatus: leaveStatus
                                        // name: name,
                                        // createDate: createDate,
                                        // userCode: userCode,
                                        // userName: userName,
                                        // leaveType: leaveType,
                                        // leaveReason: leaveReason,
                                        // leaveStartDate: leaveStartDate,
                                        // leaveEndDate: leaveEndDate,
                                        // depart: depart,
                                        // checkFirm: checkFirm
                                    },
                                    success: function(response) {
                                        $('#leaveModal').modal('hide');
                                        Swal.fire({
                                            title: 'สำเร็จ!',
                                            text: 'ตรวจสอบผ่านสำเร็จ',
                                            icon: 'success',
                                            confirmButtonText: 'ตกลง'
                                        }).then(() => {
                                            location
                                                .reload(); // Reload the page after user clicks confirm
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(error);
                                    }
                                });
                            });
                        $('.modal-footer .btn-danger').off('click').on('click', function() {
                            var leaveType = $(rowData[0]).text(); // ประเภทการลา
                            var empName = $(rowData[1]).text(); // ชื่อพนักงาน
                            var depart = $(rowData[2]).text(); // แผนก
                            var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
                            var userCode = $(rowData[5]).text(); // รหัสพนักงาน
                            var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
                            var leaveStartDate = $(rowData[9])
                                .text(); // วันเวลาที่ลาเริ่มต้น
                            var leaveEndDate = $(rowData[10])
                                .text(); // วันเวลาที่ลาสิ้นสุด
                            var leaveStatus = $(rowData[12]).text(); // สถานะใบลา

                            var checkFirm = '2'; // ไม่ผ่าน
                            var userName = '<?php echo $userName; ?>';

                            console.log(leaveType)
                            console.log(name)
                            console.log(depart)
                            console.log(leaveReason)
                            console.log(userCode)
                            console.log(createDate)
                            console.log(leaveStartDate)
                            console.log(leaveEndDate)
                            console.log(checkFirm)
                            console.log(userName)

                            $.ajax({
                                url: 'a_ajax_upd_status.php',
                                method: 'POST',
                                data: {
                                    leaveType: leaveType,
                                    empName: empName,
                                    depart: depart,
                                    leaveReason: leaveReason,
                                    userCode: userCode,
                                    createDate: createDate,
                                    leaveStartDate: leaveStartDate,
                                    leaveEndDate: leaveEndDate,
                                    checkFirm: checkFirm,
                                    userName: userName,
                                    empName: empName,
                                    leaveStatus: leaveStatus
                                },
                                success: function(response) {
                                    $('#leaveModal').modal('hide');
                                    Swal.fire({
                                        title: 'สำเร็จ!',
                                        text: 'ตรวจสอบไม่ผ่านสำเร็จ',
                                        icon: 'success',
                                        confirmButtonText: 'ตกลง'
                                    }).then(() => {
                                        location
                                            .reload(); // Reload the page after user clicks confirm
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        });
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    $("#nameSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $("#leaveSearch").on("keyup", function() {
        var value2 = $(this).val().toLowerCase();
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value2) > -1);
        });
    });

    $("#codeSearch").on("keyup", function() {
        var value3 = $(this).val().toLowerCase(); // ค่าที่กรอกในช่องค้นหา
        var page = "<?php echo $currentPage; ?>"; // หน้าปัจจุบันที่แสดงอยู่
        var selectedMonth = "<?php echo $selectedMonth; ?>"; // เดือนที่เลือก
        var selectedYear = "<?php echo $selectedYear; ?>"; // ปีที่เลือก

        var searchCode = value3; // ค่าของ codeSearch ที่กรอก

        $.ajax({
            url: "a_ajax_get_data_usercode.php", // ชื่อไฟล์ PHP ที่ใช้แสดงข้อมูล
            type: "GET",
            data: {
                page: page,
                month: selectedMonth,
                year: selectedYear,
                codeSearch: searchCode
            },
            success: function(response) {
                // แทนที่เนื้อหาตารางด้วยข้อมูลใหม่ที่ได้จาก response
                $("tbody").html(response);

                // เปิด modal และกำหนดเนื้อหา
                $(".leaveChk").click(function() {
                    var rowData = $(this).closest("tr").find("td");
                    var modalContent = '<table class="table table-bordered">' +
                        '<tr><th>รหัสพนักงาน</th><td>' + $(rowData[5]).text() +
                        '</td></tr>' +
                        '<tr><th>ชื่อ - นามสกุล</th><td>' + $(rowData[1]).text() +
                        '</td></tr>' +
                        '<tr><th>แผนก</th><td>' + $(rowData[2]).text() + '</td></tr>' +
                        '<tr><th>วันที่ยื่นใบลา</th><td>' + $(rowData[7]).text() +
                        '</td></tr>' +
                        '<tr><th>ประเภทการลา</th><td>' + $(rowData[0]).text() +
                        '</td></tr>' +
                        '<tr><th>เหตุผลการลา</th><td>' + $(rowData[3]).text() +
                        '</td></tr>' +
                        '<tr><th>วันเวลาที่ลา</th><td>' + $(rowData[9]).text() + ' ถึง ' +
                        $(rowData[10]).text() + '</td></tr>' +
                        '<tr><th>สถานะใบลา</th><td>' + $(rowData[12]).html() +
                        '</td></tr>' +
                        '</table>';

                    $('#leaveModal .modal-body').html(modalContent);

                    // ปรับปรุงปุ่มใน modal สำหรับ "สำเร็จ"
                    $('.modal-footer .btn-success').off('click').on('click', function() {
                        var modalData = {
                            createDate: $(rowData[7]).text(),
                            userCode: $(rowData[5]).text(),
                            userName: '<?php echo $userName; ?>',
                            leaveType: $(rowData[0]).text(),
                            leaveReason: $(rowData[3]).text(),
                            leaveStartDate: $(rowData[9]).text(),
                            leaveEndDate: $(rowData[10]).text(),
                            depart: $(rowData[2]).text(),
                            checkFirm: '1', // ผ่าน
                            empName: $(rowData[1]).text(),
                            leaveStatus: $(rowData[12]).text()
                        };

                        $.ajax({
                            url: 'a_ajax_upd_status.php',
                            method: 'POST',
                            data: modalData,
                            success: function(response) {
                                $('#leaveModal').modal('hide');
                                location
                                    .reload(); // รีโหลดหน้าเมื่ออัพเดตเสร็จ
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    });

                    // ปรับปรุงปุ่มใน modal สำหรับ "ไม่ผ่าน"
                    $('.modal-footer .btn-danger').off('click').on('click', function() {
                        var modalData = {
                            createDate: $(rowData[7]).text(),
                            userCode: $(rowData[5]).text(),
                            userName: '<?php echo $userName; ?>',
                            leaveType: $(rowData[0]).text(),
                            leaveReason: $(rowData[3]).text(),
                            leaveStartDate: $(rowData[9]).text(),
                            leaveEndDate: $(rowData[10]).text(),
                            depart: $(rowData[2]).text(),
                            checkFirm: '2', // ไม่ผ่าน
                            empName: $(rowData[1]).text(),
                            leaveStatus: $(rowData[12]).text()
                        };

                        $.ajax({
                            url: 'a_ajax_upd_status.php',
                            method: 'POST',
                            data: modalData,
                            success: function(response) {
                                $('#leaveModal').modal('hide');
                                location
                                    .reload(); // รีโหลดหน้าเมื่ออัพเดตเสร็จ
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    });
                });
            }
        });
    });


    // ฟังก์ชันเพื่อเปลี่ยนหน้าเมื่อกรอกหมายเลขหน้า
    // function changePage(page) {
    //     var totalPages = <?php echo $totalPages; ?>; // จำนวนหน้าทั้งหมด
    //     if (page < 1) page = 1;
    //     if (page > totalPages) page = totalPages;

    //     var selectedMonth = "<?php echo $selectedMonth; ?>"; // เดือนที่เลือก
    //     var searchCode = $("#codeSearch").val(); // ค่าค้นหาจากช่องค้นหา

    //     var newUrl = "?page=" + page + "&month=" + encodeURIComponent(selectedMonth) +
    //         "&codeSearch=" + encodeURIComponent(searchCode); // ส่งค่า codeSearch ใหม่

    //     window.location.href = newUrl; // เปลี่ยนหน้าโดยการโหลด URL ใหม่
    // }

    function changePage(page, selectedMonth, searchCode) {
        // สร้าง URL ใหม่โดยส่งค่าทั้งหมด (page, selectedMonth, และ codeSearch)
        var newUrl = "?page=" + page + "&month=" + encodeURIComponent(selectedMonth) +
            "&codeSearch=" + encodeURIComponent(searchCode);
        window.location.href = newUrl; // รีเฟรชหน้าโดยการโหลด URL ใหม่
    }
    document.getElementById('page-input').addEventListener('input', function() {
        const page = this.value;
        const month = '<?php echo urlencode($selectedMonth); ?>';
        if (page >= 1 && page <= <?php echo $totalPages; ?>) {
            window.location.href = `?page=${page}&month=${month}`;
        }
    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>