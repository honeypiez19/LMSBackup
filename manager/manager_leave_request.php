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
    <title>ใบลาของพนักงาน</title>

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
    <?php include 'manager_navbar.php'?>

    <!-- <?php echo $subDepart; ?>
    <?php echo $subDepart2; ?>
    <?php echo $userName; ?> -->

    <nav class="navbar bg-body-tertiary" style="background-color: #072ac8; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
  border: none;">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fa-solid fa-file-signature fa-2xl"></i>
                </div>
                <div class="col-auto">
                    <h3>ใบลาของพนักงาน</h3>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <form class="mt-3 mb-3 row" method="post">
            <label for="" class="mt-2 col-auto">เลือกปี</label>
            <div class="col-auto">
                <?php
$currentYear = date('Y'); // ปีปัจจุบัน

if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];
} else {
    $selectedYear = $currentYear;
}

echo "<select class='form-select' name='year' id='selectedYear'>";
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

// $selectedMonth = date('m'); // เดือนปัจจุบัน
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
$checkSubDepart = $subDepart;
$checkSubDepart2 = $subDepart2;
$checkSubDepart3 = $subDepart3;
$checkSubDepart4 = $subDepart4;
$checkSubDepart5 = $subDepart5;

$sql = "SELECT
COUNT(li.l_list_id) AS totalLeaveItems,
    li.l_username,
    li.l_name,
    li.l_department,
    em.e_department,
    em.e_sub_department,
    em.e_sub_department2,
    em.e_sub_department3,
    em.e_sub_department4,
    em.e_sub_department5
FROM leave_list li
INNER JOIN employees em
    ON li.l_usercode = em.e_usercode
WHERE
    li.l_approve_status IN (2,3,6)
    AND li.l_level IN ('user', 'chief', 'leader','admin')
    AND li.l_leave_id NOT IN (6, 7)
    AND YEAR(li.l_leave_end_date) = :selectedYear";

if ($selectedMonth != "All") {
    $sql .= " AND Month(li.l_leave_end_date) = :selectedMonth ";
}

if ($checkSubDepart === "Office" || $checkSubDepart2 === "Management") {
    $sql .= " AND (
        em.e_department = :checkSubDepart AND li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart2
        AND em.e_sub_department = 'AC'
    )";
} else {
    $sql .= " AND (
           (em.e_department = :subDepart AND li.l_department = :subDepart)
            OR (li.l_department = :subDepart2)
            OR (li.l_department = :subDepart3)
            OR (li.l_department = :subDepart4)
            OR (li.l_department = :subDepart5)
        )";
}

$stmt = $conn->prepare($sql);
$stmt->bindParam(':selectedYear', $selectedYear);
if ($selectedMonth != "All") {
    $stmt->bindParam(':selectedMonth', $selectedMonth, PDO::PARAM_INT);
}
if ($checkSubDepart === "Office") {
    $stmt->bindParam(':checkSubDepart', $checkSubDepart);
    $stmt->bindParam(':checkSubDepart2', $checkSubDepart2);
    // $stmt->bindParam(':checkSubDepart3', $checkSubDepart3);
    // $stmt->bindParam(':checkSubDepart4', $checkSubDepart4);
    // $stmt->bindParam(':checkSubDepart5', $checkSubDepart5);
} else {
    $stmt->bindParam(':subDepart', $subDepart);
    $stmt->bindParam(':subDepart2', $subDepart2);
    $stmt->bindParam(':subDepart3', $subDepart3);
    $stmt->bindParam(':subDepart4', $subDepart4);
    $stmt->bindParam(':subDepart5', $subDepart5);
}
// Execute and check for errors
if ($stmt->execute()) {
    $totalLeaveItems = $stmt->fetchColumn();
} else {
    // Output error information
    $errorInfo = $stmt->errorInfo();
    echo "SQL Error: " . $errorInfo[2];
    $totalLeaveItems = 0;
}

?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <!-- <i class="mt-4 fas fa-file-alt ml-2 fa-2xl"></i> -->
                                <i class="mt-4 fa-regular fa-folder-open fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            <?php echo $strAll; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- รายการลาที่รออนุมัติ -->
            <div class="col-3 filter-card" data-status="1">
                <div class="card text-bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
$checkSubDepart = $subDepart;
$checkSubDepart2 = $subDepart2;
$checkSubDepart3 = $subDepart3;
$checkSubDepart4 = $subDepart4;
$checkSubDepart5 = $subDepart5;

$sql = "SELECT
COUNT(li.l_list_id) AS totalLeaveItems,
    li.l_username,
    li.l_name,
    li.l_department,
    em.e_department,
    em.e_sub_department,
    em.e_sub_department2,
    em.e_sub_department3,
    em.e_sub_department4,
    em.e_sub_department5
FROM leave_list li
INNER JOIN employees em
    ON li.l_usercode = em.e_usercode
WHERE
    li.l_approve_status IN (2,3,6)
    AND li.l_approve_status2 = 1
    AND li.l_level IN ('user', 'chief', 'leader','admin')
    AND li.l_leave_id NOT IN (6, 7)
    AND YEAR(li.l_leave_end_date) = :selectedYear";

if ($selectedMonth != "All") {
    $sql .= " AND Month(li.l_leave_end_date) = :selectedMonth ";
}

if ($checkSubDepart === "Office" || $checkSubDepart2 === "Management") {
    $sql .= " AND (
        em.e_department = :checkSubDepart AND li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart2
        AND em.e_sub_department = 'AC'
    )";
} else {
    $sql .= " AND (
           (em.e_department = :subDepart AND li.l_department = :subDepart)
            OR (li.l_department = :subDepart2)
            OR (li.l_department = :subDepart3)
            OR (li.l_department = :subDepart4)
            OR (li.l_department = :subDepart5)
        )";
}
// $sql = "SELECT
// COUNT(li.l_list_id) AS totalLeaveItems,
// li.l_username,
// li.l_name,
// li.l_department,
//     em.e_sub_department,
//     em.e_sub_department2,
//     em.e_sub_department3,
//     em.e_sub_department4,
//     em.e_sub_department5
// FROM leave_list li
// INNER JOIN employees em
//     ON li.l_usercode = em.e_usercode
// WHERE
//     li.l_approve_status = 2
//     AND li.l_approve_status2 = 1
//     AND li.l_level IN ('user', 'chief', 'leader','admin')
//     AND li.l_leave_id NOT IN (6, 7)
//     AND YEAR(li.l_leave_end_date) = :selectedYear";
// if ($selectedMonth != "All") {
//     $sql .= " AND Month(li.l_leave_end_date) = :selectedMonth ";
// }
// $sql .= " AND (
//        (em.e_department = :subDepart AND li.l_department = :subDepart)
//         OR (li.l_department = :subDepart2)
//         OR (li.l_department = :subDepart3)
//         OR (li.l_department = :subDepart4)
//         OR (li.l_department = :subDepart5)
//     )
//     AND em.e_sub_department = 'AC'
// ";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':selectedYear', $selectedYear);
if ($selectedMonth != "All") {
    $stmt->bindParam(':selectedMonth', $selectedMonth, PDO::PARAM_INT);
}
if ($checkSubDepart === "Office") {
    $stmt->bindParam(':checkSubDepart', $checkSubDepart);
    $stmt->bindParam(':checkSubDepart2', $checkSubDepart2);
    // $stmt->bindParam(':checkSubDepart3', $checkSubDepart3);
    // $stmt->bindParam(':checkSubDepart4', $checkSubDepart4);
    // $stmt->bindParam(':checkSubDepart5', $checkSubDepart5);
} else {
    $stmt->bindParam(':subDepart', $subDepart);
    $stmt->bindParam(':subDepart2', $subDepart2);
    $stmt->bindParam(':subDepart3', $subDepart3);
    $stmt->bindParam(':subDepart4', $subDepart4);
    $stmt->bindParam(':subDepart5', $subDepart5);
}
// Execute and check for errors
if ($stmt->execute()) {
    $totalLeaveItems = $stmt->fetchColumn();
} else {
    // Output error information
    $errorInfo = $stmt->errorInfo();
    echo "SQL Error: " . $errorInfo[2];
    $totalLeaveItems = 0;
}
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <!-- <i class="mt-4 fa-solid fa-clock-rotate-left fa-2xl" style="color: #ffffff;"></i> -->
                                <i class="mt-4 fa-solid fa-hourglass-half fa-2xl" style="color: #ffffff;"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            <?php echo $strPendProve; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="4">
                <div class="card text-bg-success mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
$checkSubDepart = $subDepart;
$checkSubDepart2 = $subDepart2;
$checkSubDepart3 = $subDepart3;
$checkSubDepart4 = $subDepart4;
$checkSubDepart5 = $subDepart5;

$sql = "SELECT
COUNT(li.l_list_id) AS totalLeaveItems,
    li.l_username,
    li.l_name,
    li.l_department,
    em.e_department,
    em.e_sub_department,
    em.e_sub_department2,
    em.e_sub_department3,
    em.e_sub_department4,
    em.e_sub_department5
FROM leave_list li
INNER JOIN employees em
    ON li.l_usercode = em.e_usercode
WHERE
    li.l_approve_status IN (2,3,6)
    AND li.l_approve_status2 = 4
    AND li.l_level IN ('user', 'chief', 'leader','admin')
    AND li.l_leave_id NOT IN (6, 7)
    AND YEAR(li.l_leave_end_date) = :selectedYear";

if ($selectedMonth != "All") {
    $sql .= " AND Month(li.l_leave_end_date) = :selectedMonth ";
}

if ($checkSubDepart === "Office" || $checkSubDepart2 === "Management") {
    $sql .= " AND (
        em.e_department = :checkSubDepart AND li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart2
        AND em.e_sub_department = 'AC'
    )";
} else {
    $sql .= " AND (
           (em.e_department = :subDepart AND li.l_department = :subDepart)
            OR (li.l_department = :subDepart2)
            OR (li.l_department = :subDepart3)
            OR (li.l_department = :subDepart4)
            OR (li.l_department = :subDepart5)
        )";
}

// $sql = "SELECT
// COUNT(li.l_list_id) AS totalLeaveItems,
// li.l_username,
// li.l_name,
// li.l_department,
//     em.e_sub_department,
//     em.e_sub_department2,
//     em.e_sub_department3,
//     em.e_sub_department4,
//     em.e_sub_department5
// FROM leave_list li
// INNER JOIN employees em
//     ON li.l_usercode = em.e_usercode
// WHERE
//     li.l_approve_status = 2
//     AND li.l_approve_status2 = 4
//     AND li.l_level IN ('user', 'chief', 'leader','admin')
//     AND li.l_leave_id NOT IN (6, 7)
//       AND YEAR(li.l_leave_end_date) = :selectedYear";
// if ($selectedMonth != "All") {
//     $sql .= " AND Month(li.l_leave_end_date) = :selectedMonth ";
// }
// $sql .= " AND (
//        (em.e_department = :subDepart AND li.l_department = :subDepart)
//         OR (li.l_department = :subDepart2)
//         OR (li.l_department = :subDepart3)
//         OR (li.l_department = :subDepart4)
//         OR (li.l_department = :subDepart5)
//     )
//     AND em.e_sub_department = 'AC'
// ";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':selectedYear', $selectedYear);
if ($selectedMonth != "All") {
    $stmt->bindParam(':selectedMonth', $selectedMonth, PDO::PARAM_INT);
}
if ($checkSubDepart === "Office") {
    $stmt->bindParam(':checkSubDepart', $checkSubDepart);
    $stmt->bindParam(':checkSubDepart2', $checkSubDepart2);
    // $stmt->bindParam(':checkSubDepart3', $checkSubDepart3);
    // $stmt->bindParam(':checkSubDepart4', $checkSubDepart4);
    // $stmt->bindParam(':checkSubDepart5', $checkSubDepart5);
} else {
    $stmt->bindParam(':subDepart', $subDepart);
    $stmt->bindParam(':subDepart2', $subDepart2);
    $stmt->bindParam(':subDepart3', $subDepart3);
    $stmt->bindParam(':subDepart4', $subDepart4);
    $stmt->bindParam(':subDepart5', $subDepart5);
}
$stmt->execute();

// ดึงผลลัพธ์
$totalLeaveItems = $stmt->fetchColumn();
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <!-- <i class="mt-4 fa-solid fa-thumbs-up fa-2xl"></i> -->
                                <i class="mt-4 fa-regular fa-face-smile fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            <?php echo $strProve; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="5">
                <div class="card text-bg-danger mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
$checkSubDepart = $subDepart;
$checkSubDepart2 = $subDepart2;
$checkSubDepart3 = $subDepart3;
$checkSubDepart4 = $subDepart4;
$checkSubDepart5 = $subDepart5;

$sql = "SELECT
COUNT(li.l_list_id) AS totalLeaveItems,
    li.l_username,
    li.l_name,
    li.l_department,
    em.e_department,
    em.e_sub_department,
    em.e_sub_department2,
    em.e_sub_department3,
    em.e_sub_department4,
    em.e_sub_department5
FROM leave_list li
INNER JOIN employees em
    ON li.l_usercode = em.e_usercode
WHERE
    li.l_approve_status IN (2,3,6)
    AND li.l_approve_status2 = 5
    AND li.l_level IN ('user', 'chief', 'leader','admin')
    AND li.l_leave_id NOT IN (6, 7)
    AND YEAR(li.l_leave_end_date) = :selectedYear";

if ($selectedMonth != "All") {
    $sql .= " AND Month(li.l_leave_end_date) = :selectedMonth ";
}

if ($checkSubDepart === "Office" || $checkSubDepart2 === "Management") {
    $sql .= " AND (
        em.e_department = :checkSubDepart AND li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart2
        AND em.e_sub_department = 'AC'
    )";
} else {
    $sql .= " AND (
           (em.e_department = :subDepart AND li.l_department = :subDepart)
            OR (li.l_department = :subDepart2)
            OR (li.l_department = :subDepart3)
            OR (li.l_department = :subDepart4)
            OR (li.l_department = :subDepart5)
        )";
}

// $sql = "SELECT
// COUNT(li.l_list_id) AS totalLeaveItems,
// li.l_username,
// li.l_name,
// li.l_department,
//     em.e_sub_department,
//     em.e_sub_department2,
//     em.e_sub_department3,
//     em.e_sub_department4,
//     em.e_sub_department5
// FROM leave_list li
// INNER JOIN employees em
//     ON li.l_usercode = em.e_usercode
// WHERE
//     li.l_approve_status = 2
//     AND li.l_approve_status2 = 5
//     AND li.l_level IN ('user', 'chief', 'leader','admin')
//     AND li.l_leave_id NOT IN (6, 7)
//     AND YEAR(li.l_leave_end_date) = :selectedYear";
// if ($selectedMonth != "All") {
//     $sql .= " AND Month(li.l_leave_end_date) = :selectedMonth ";
// }
// $sql .= " AND (
//        (em.e_department = :subDepart AND li.l_department = :subDepart)
//         OR (li.l_department = :subDepart2)
//         OR (li.l_department = :subDepart3)
//         OR (li.l_department = :subDepart4)
//         OR (li.l_department = :subDepart5)
//     )
//     AND em.e_sub_department = 'AC'
// ";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':selectedYear', $selectedYear);
if ($selectedMonth != "All") {
    $stmt->bindParam(':selectedMonth', $selectedMonth, PDO::PARAM_INT);
}
if ($checkSubDepart === "Office") {
    $stmt->bindParam(':checkSubDepart', $checkSubDepart);
    $stmt->bindParam(':checkSubDepart2', $checkSubDepart2);
    // $stmt->bindParam(':checkSubDepart3', $checkSubDepart3);
    // $stmt->bindParam(':checkSubDepart4', $checkSubDepart4);
    // $stmt->bindParam(':checkSubDepart5', $checkSubDepart5);
} else {
    $stmt->bindParam(':subDepart', $subDepart);
    $stmt->bindParam(':subDepart2', $subDepart2);
    $stmt->bindParam(':subDepart3', $subDepart3);
    $stmt->bindParam(':subDepart4', $subDepart4);
    $stmt->bindParam(':subDepart5', $subDepart5);
}
$stmt->execute();

// ดึงผลลัพธ์
$totalLeaveItems = $stmt->fetchColumn();
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <!-- <i class="mt-4 fa-solid fa-thumbs-down fa-2xl"></i> -->
                                <i class="mt-4 fa-regular fa-face-frown fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            <?php echo $strNotProve; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ตารางข้อมูลการลา -->
    <div class="container-fluid">
        <div class="table-responsive">

            <table class="table table-hover" style="border-top: 1px solid rgba(0, 0, 0, 0.1);" id="leaveTable">
                <thead>
                    <tr class="text-center align-middle">
                        <th rowspan="2"><?php echo $strNo; ?></th>
                        <th rowspan="1"><?php echo $strEmpCode; ?></th>
                        <th rowspan="1"><?php echo $strEmpName; ?></th>
                        <th rowspan="2"><?php echo $strSubDate; ?></th>
                        <th rowspan="1"><?php echo $strLeaveType; ?></th>
                        <th colspan="2" class="text-center"><?php echo $strDateTime; ?></th>
                        <th rowspan="2"><?php echo $strFile; ?></th>
                        <th rowspan="2"><?php echo $strListStatus; ?></th>
                        <th rowspan="2"><?php echo $strStatus1; ?></th>
                        <th rowspan="2"><?php echo $strProveDate1; ?></th>
                        <th rowspan="2"><?php echo $strReason1; ?></th>
                        <th rowspan="2"><?php echo $strProveName1; ?></th>
                        <th rowspan="2"><?php echo $strStatus2; ?></th>
                        <th rowspan="2"><?php echo $strProveDate2; ?></th>
                        <th rowspan="2"><?php echo $strReason2; ?></th>
                        <th rowspan="2"><?php echo $strProveName2; ?></th>
                        <th rowspan="2"><?php echo $strStatusHR; ?></th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr class="text-center">
                        <th> <input type="text" class="form-control" id="codeSearch"></th>
                        <th> <input type="text" class="form-control" id="nameSearch"></th>
                        <th> <input type="text" class="form-control" id="leaveSearch"></th>
                        <th style="width: 8%;"><?php echo $strFrom; ?></th>
                        <th style="width: 8%;"><?php echo $strTo; ?></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
// $approveStatus = ($depart == 'RD') ? 4 : (($depart == 'Office') ? 4 : ($depart == '' ? NULL : 2));

$itemsPerPage = 10;

// คำนวณหน้าปัจจุบัน
if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

// $sql = "SELECT * FROM leave_list WHERE Year(l_create_datetime) = '$selectedYear'
// AND Month(l_create_datetime) = '$selectedMonth' AND l_department = 'Office'
// AND l_leave_id <> 6 AND l_leave_id <> 7 ORDER BY l_create_datetime DESC";

$checkSubDepart = $subDepart;
$checkSubDepart2 = $subDepart2;
$checkSubDepart3 = $subDepart3;
$checkSubDepart4 = $subDepart4;
$checkSubDepart5 = $subDepart5;

$sql = "SELECT
    li.*,
    em.e_department,
    em.e_sub_department,
    em.e_sub_department2,
    em.e_sub_department3,
    em.e_sub_department4,
    em.e_sub_department5
FROM leave_list li
INNER JOIN employees em
    ON li.l_usercode = em.e_usercode
WHERE
    li.l_approve_status IN (2,3,6)
    AND li.l_level IN ('user', 'chief', 'leader','admin')
    AND li.l_leave_id NOT IN (6, 7)
    AND YEAR(li.l_leave_end_date) = :selectedYear";

if ($selectedMonth != "All") {
    $sql .= " AND Month(li.l_leave_end_date) = :selectedMonth ";
}

if ($checkSubDepart === "Office" || $checkSubDepart2 === "Management") {
    $sql .= " AND (
        em.e_department = :checkSubDepart
        OR li.l_department = :checkSubDepart
        OR li.l_department = :checkSubDepart2
        AND em.e_sub_department = 'AC'
    )";
} else {
    $sql .= " AND (
           (em.e_department = :subDepart AND li.l_department = :subDepart)
            OR (li.l_department = :subDepart2)
            OR (li.l_department = :subDepart3)
            OR (li.l_department = :subDepart4)
            OR (li.l_department = :subDepart5)
        )";
}

// Prepare query for calculating total rows
$stmt = $conn->prepare($sql);
$stmt->bindParam(':selectedYear', $selectedYear);
if ($selectedMonth != "All") {
    $stmt->bindParam(':selectedMonth', $selectedMonth, PDO::PARAM_INT);
}
if ($checkSubDepart === "Office") {
    $stmt->bindParam(':checkSubDepart', $checkSubDepart);
    $stmt->bindParam(':checkSubDepart2', $checkSubDepart2);
    // $stmt->bindParam(':checkSubDepart3', $checkSubDepart3);
    // $stmt->bindParam(':checkSubDepart4', $checkSubDepart4);
    // $stmt->bindParam(':checkSubDepart5', $checkSubDepart5);
} else {
    $stmt->bindParam(':subDepart', $subDepart);
    $stmt->bindParam(':subDepart2', $subDepart2);
    $stmt->bindParam(':subDepart3', $subDepart3);
    $stmt->bindParam(':subDepart4', $subDepart4);
    $stmt->bindParam(':subDepart5', $subDepart5);
}

$stmt->execute();
$totalRows = $stmt->rowCount();

// Calculate total pages
$totalPages = ceil($totalRows / $itemsPerPage);

// Calculate offset for pagination
$offset = ($currentPage - 1) * $itemsPerPage;

// Add LIMIT and OFFSET for pagination
$sql .= " ORDER BY li.l_create_datetime DESC LIMIT :itemsPerPage OFFSET :offset";

// Prepare query with LIMIT and OFFSET
$stmt = $conn->prepare($sql);
$stmt->bindParam(':selectedYear', $selectedYear);
if ($selectedMonth != "All") {
    $stmt->bindParam(':selectedMonth', $selectedMonth, PDO::PARAM_INT);
}
if ($checkSubDepart === "Office") {
    $stmt->bindParam(':checkSubDepart', $checkSubDepart);
    $stmt->bindParam(':checkSubDepart2', $checkSubDepart2);
    // $stmt->bindParam(':checkSubDepart3', $checkSubDepart3);
    // $stmt->bindParam(':checkSubDepart4', $checkSubDepart4);
    // $stmt->bindParam(':checkSubDepart5', $checkSubDepart5);
} else {
    $stmt->bindParam(':subDepart', $subDepart);
    $stmt->bindParam(':subDepart2', $subDepart2);
    $stmt->bindParam(':subDepart3', $subDepart3);
    $stmt->bindParam(':subDepart4', $subDepart4);
    $stmt->bindParam(':subDepart5', $subDepart5);
}
$stmt->bindValue(':itemsPerPage', (int) $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
$stmt->execute();

// Row numbering
$rowNumber = $totalRows - ($currentPage - 1) * $itemsPerPage;

// Display data in table
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
            echo '<span class="text-success">' . $strStatusNormal . '</span>';
        } else {
            echo '<span class="text-danger">' . $strStatusCancel . '</span>';
        }
        echo '</td>';

        // 13
        echo '<td>';
        // รอหัวหน้าอนุมัติ
        if ($row['l_approve_status'] == 0) {
            echo '<div class="text-warning"><b>' . $strStatusProve0 . '</b></div>';
        }
        // รอผจกอนุมัติ
        elseif ($row['l_approve_status'] == 1) {
            echo '<div class="text-warning"><b>' . $strStatusProve1 . '</b></div>';
        }
        // หัวหน้าอนุมัติ
        elseif ($row['l_approve_status'] == 2) {
            echo '<div class="text-success"><b>' . $strStatusProve2 . '</b></div>';
        }
        // หัวหน้าไม่อนุมัติ
        elseif ($row['l_approve_status'] == 3) {
            echo '<div class="text-danger"><b>' . $strStatusProve3 . '</b></div>';
        }
        //  ผจก อนุมัติ
        elseif ($row['l_approve_status'] == 4) {
            echo '<div class="text-success"><b>' . $strStatusProve4 . '</b></div>';
        }
        //  ผจก ไม่อนุมัติ
        elseif ($row['l_approve_status'] == 5) {
            echo '<div class="text-danger"><b>' . $strStatusProve5 . '</b></div>';
        } elseif ($row['l_approve_status'] == 6) {
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
            echo '<div class="text-warning"><b>' . $strStatusProve0 . '</b></div>';
        }
        // รอผจกอนุมัติ
        elseif ($row['l_approve_status2'] == 1) {
            echo '<div class="text-warning"><b>' . $strStatusProve1 . '</b></div>';
        }
        // หัวหน้าอนุมัติ
        elseif ($row['l_approve_status2'] == 2) {
            echo '<div class="text-success"><b>' . $strStatusProve2 . '</b></div>';
        }
        // หัวหน้าไม่อนุมัติ
        elseif ($row['l_approve_status2'] == 3) {
            echo '<div class="text-danger"><b>' . $strStatusProve3 . '</b></div>';
        }
        //  ผจก อนุมัติ
        elseif ($row['l_approve_status2'] == 4) {
            echo '<div class="text-success"><b>' . $strStatusProve4 . '</b></div>';
        }
        //  ผจก ไม่อนุมัติ
        elseif ($row['l_approve_status2'] == 5) {
            echo '<div class="text-danger"><b>' . $strStatusProve5 . '</b></div>';
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
            echo '<span class="text-warning"><b>' . $strStatusHR0 . '</b></span>';
        } elseif ($row['l_hr_status'] == 1) {
            echo '<span class="text-success"><b>' . $strStatusHR1 . '</b></span>';
        } elseif ($row['l_hr_status'] == 2) {
            echo '<span class="text-danger"><b>' . $strStatusHR2 . '</b></span>';
        } else {
            echo $row['l_hr_status'];
        }
        echo '</td>';

        // 22 ปุ่มตรวจสอบ
        if ($row['l_approve_status'] == 4 || $row['l_approve_status'] == 5) {
            echo "<td><button type='button' class='btn btn-primary leaveChk' data-bs-toggle='modal' data-bs-target='#leaveModal' disabled>$btnCheck</button></td>";
        } else {
            echo "<td><button type='button' class='btn btn-primary leaveChk' data-bs-toggle='modal' data-bs-target='#leaveModal'>$btnCheck</button></td>";
        }

        // 23
        echo '<td>
        <button type="button" class="btn btn-primary btn-sm view-history" data-usercode="' . $row['l_usercode'] . '"><i class="fa-solid fa-clock-rotate-left"></i></button></td>';

        echo '</tr>';

        $rowNumber--;
    }

} else {
    echo '<tr><td colspan="19" style="text-align: left; color:red;">ไม่พบข้อมูล</td></tr>';
}
?>
                </tbody>
            </table>
        </div>
        <?php
echo '<div class="pagination">';
echo '<ul class="pagination">';

// กำหนดจำนวนหน้าที่จะแสดงรอบ ๆ หน้าปัจจุบัน
$range = 2;

// สร้างลิงก์ไปยังหน้าแรกหรือหน้าก่อนหน้า
if ($currentPage > 1) {
    echo '<li class="page-item"><a class="page-link" href="?page=1&month=' . urlencode($selectedMonth) . '">&laquo;</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '&month=' . urlencode($selectedMonth) . '">&lt;</a></li>';
}

// แสดงลิงก์สำหรับหน้าที่อยู่ในช่วง
for ($i = max(1, $currentPage - $range); $i <= min($totalPages, $currentPage + $range); $i++) {
    if ($i == $currentPage) {
        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
    } else {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '&month=' . urlencode($selectedMonth) . '">' . $i . '</a></li>';
    }
}

// เพิ่ม "..." ถ้าช่วงมีความห่างจากหน้าสุดท้าย
if ($currentPage + $range < $totalPages) {
    echo '<li class="page-item"><span class="page-link">...</span></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '&month=' . urlencode($selectedMonth) . '">' . $totalPages . '</a></li>';
}

// เพิ่ม "..." ถ้าช่วงมีความห่างจากหน้าแรก
if ($currentPage - $range > 1) {
    echo '<li class="page-item"><span class="page-link">...</span></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=1&month=' . urlencode($selectedMonth) . '">' . 1 . '</a></li>';
}

// สร้างลิงก์ไปยังหน้าถัดไปหรือหน้าสุดท้าย
if ($currentPage < $totalPages) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '&month=' . urlencode($selectedMonth) . '">&gt;</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '&month=' . urlencode($selectedMonth) . '">&raquo;</a></li>';
}

echo '</ul>';

// ฟอร์มสำหรับกรอกหมายเลขหน้า
echo '<form method="GET" action="" class="mb-2 d-inline-flex">';
echo '<input type="hidden" name="month" value="' . htmlspecialchars($selectedMonth) . '">';
// echo '<label for="customPage" class="me-2">ไปหน้าที่:</label>';
echo '<input type="number" name="page" id="customPage" class="form-control mb-3 mx-2" style="width: 80px;" min="1" max="' . $totalPages . '" value="' . $currentPage . '">';
echo '<button type="submit" class="mx-2 mb-3 btn btn-primary" style="width: 50px;">Go</button>';
echo '</form>';

echo '</div>';

?>
        <!-- Modal เช็คการลา -->
        <div class="modal fade" id="leaveModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title
                        01f s-5" id="staticBackdropLabel">รายละเอียดการลา</h4>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">X</button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger button-shadow"><?php echo $btnNotProve; ?></button>
                        <button type="button" class="btn btn-success button-shadow"><?php echo $btnProve; ?></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ประวัติลาของพนักงาน -->
        <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="historyModalLabel">ประวัติการลา</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- ประวัติการลาจะถูกโหลดที่นี่ -->
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

            var status = '4'; // อนุมัติ
            var userName = '<?php echo $userName; ?>';
            var proveName = '<?php echo $name; ?>';

            // alert(leaveStatus)
            $.ajax({
                url: 'm_ajax_upd_status.php',
                method: 'POST',
                data: {
                    createDate: createDate,
                    userCode: userCode,
                    status: status,
                    userName: userName,
                    proveName: proveName,
                    leaveType: leaveType,
                    leaveReason: leaveReason,
                    leaveStartDate: leaveStartDate,
                    leaveEndDate: leaveEndDate,
                    depart: depart,
                    empName: empName,
                    leaveStatus: leaveStatus
                },
                success: function(response) {
                    $('#leaveModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'อนุมัติใบลาสำเร็จ !',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('.modal-footer .btn-danger').off('click').on('click', function() {
            // ซ่อน modal หลัก
            $('#leaveModal').modal('hide');

            // เรียกใช้ SweetAlert2 หลังจากที่ modal หลักถูกซ่อนไปแล้ว
            setTimeout(function() {
                showInputDialog(); // เรียกใช้ฟังก์ชันเพื่อแสดงกล่องโต้ตอบ
            }, 300); // เพิ่ม delay เล็กน้อยเพื่อให้ modal หลักปิดสนิท
        });

        function showInputDialog() {
            Swal.fire({
                title: 'กรุณากรอกข้อมูล',
                input: 'text',
                inputLabel: 'เหตุผล',
                inputPlaceholder: 'กรอกเหตุผลการไม่อนุมัติ',
                showCancelButton: true,
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก',
                preConfirm: (inputValue) => {
                    if (!inputValue) {
                        Swal.showValidationMessage('กรุณากรอกเหตุผลการไม่อนุมัติ');
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const reasonNoProve = result.value; // รับค่าจาก input
                    console.log(reasonNoProve); // ตรวจสอบค่าที่กรอก
                    noApprove(reasonNoProve); // เรียกใช้ฟังก์ชัน noApprove
                } else {
                    $('#leaveModal').modal('show');
                }
            });
        }

        function noApprove(reasonNoProve) {

            // แสดง loading ก่อนเริ่มกระบวนการ
            Swal.fire({
                title: 'กำลังโหลด...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // แสดง icon โหลด
                }
            });

            var userCode = $(rowData[5]).text(); // รหัสพนักงาน
            var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
            var leaveType = $(rowData[0]).text(); // ประเภทการลา
            var empName = $(rowData[1]).text(); // ชื่อพนักงาน
            var depart = $(rowData[2]).text(); // แผนก
            var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
            var leaveStartDate = $(rowData[9]).text(); // วันเวลาที่ลาเริ่มต้น
            var leaveEndDate = $(rowData[10]).text(); // วันเวลาที่ลาสิ้นสุด
            var leaveStatus = $(rowData[12]).text(); // สถานะใบลา

            var status = '5'; // ไม่อนุมัติ
            var userName = '<?php echo $userName; ?>';
            var proveName = '<?php echo $name; ?>';

            var reason = reasonNoProve;

            $.ajax({
                url: 'm_ajax_upd_status.php',
                method: 'POST',
                data: {
                    createDate: createDate,
                    userCode: userCode,
                    status: status,
                    userName: userName,
                    proveName: proveName,
                    leaveType: leaveType,
                    leaveReason: leaveReason,
                    leaveStartDate: leaveStartDate,
                    leaveEndDate: leaveEndDate,
                    depart: depart,
                    leaveStatus: leaveStatus,
                    empName: empName,
                    reasonNoProve: reasonNoProve
                },
                success: function(response) {
                    $('#leaveModal').modal('hide'); // ปิด modal
                    Swal.fire({
                        title: 'ไม่อนุมัติใบลาสำเร็จ !',
                        // text: 'ทำรายการเสร็จสิ้น',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // โหลดหน้าใหม่เมื่อกดตกลง
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

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
        var depart = <?php echo json_encode($depart); ?>;
        var subDepart = <?php echo json_encode($subDepart); ?>;
        var subDepart2 = <?php echo json_encode($subDepart2); ?>;
        var subDepart3 = <?php echo json_encode($subDepart3); ?>;
        var subDepart4 = <?php echo json_encode($subDepart4); ?>;
        var subDepart5 = <?php echo json_encode($subDepart5); ?>;

        // alert(selectedYear)
        $.ajax({
            url: 'm_ajax_get_leave_data.php',
            method: 'GET',
            data: {
                status: status,
                selectedMonth: selectedMonth,
                selectedYear: selectedYear,
                depart: depart,
                subDepart: subDepart,
                subDepart2: subDepart2,
                subDepart3: subDepart3,
                subDepart4: subDepart4,
                subDepart5: subDepart5
            },
            dataType: 'json',
            success: function(data) {
                // Clear existing table rows
                $("tbody").empty();

                if (data.length === 0) {
                    $("tbody").append(
                        '<tr><td colspan="20" class="text-danger" style="text-align: left;">ไม่พบข้อมูล</td></tr>'
                    );
                } else {
                    var totalItems = data.length; // Store total count

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
                            leaveStatus =
                                '<div class="text-success"><?=$strStatusNormal?></div>';
                        } else if (row['l_leave_status'] == 1) {
                            leaveStatus =
                                '<div class="text-danger"><?=$strStatusCancel?></div>';
                        } else {
                            leaveStatus = 'ไม่พบสถานะใบลา';
                        }

                        var confirmStatus = '';
                        if (row['l_hr_status'] == 0) {
                            confirmStatus =
                                '<div class="text-warning"><b><?=$strStatusHR0?></b></div>';
                        } else if (row['l_hr_status'] == 1) {
                            confirmStatus =
                                '<div class="text-success"><b><?=$strStatusHR1?></b></div>';
                        } else if (row['l_hr_status'] == 2) {
                            confirmStatus =
                                '<div class="text-danger"><b><?=$strStatusHR2?></b></div>';
                        } else {
                            confirmStatus = row['l_hr_status'];
                        }

                        var approveStatus;
                        if (row['l_approve_status'] == 0) {
                            approveStatus =
                                '<div class="text-warning"><b><?=$strStatusProve0?></b></div>';
                        } else if (row['l_approve_status'] == 1) {
                            approveStatus =
                                '<div class="text-warning"><b><?=$strStatusProve1?></b></div>';
                        } else if (row['l_approve_status'] == 2) {
                            approveStatus =
                                '<div class="text-success"><b><?=$strStatusProve2?></b></div>';
                        } else if (row['l_approve_status'] == 3) {
                            approveStatus =
                                '<div class="text-danger"><b><?=$strStatusProve3?></b></div>';
                        } else if (row['l_approve_status'] == 4) {
                            approveStatus =
                                '<div class="text-success"><b><?=$strStatusProve4?></b></div>';
                        } else if (row['l_approve_status'] == 5) {
                            approveStatus =
                                '<div class="text-danger"><b><?=$strStatusProve5?></b></div>';
                        } else if (row['l_approve_status'] == 6) {
                            approveStatus =
                                '';
                        } else {
                            approveStatus = 'ไม่พบสถานะ';
                        }

                        var approveReason = row['l_reason'] !== null ? row['l_reason'] :
                            '';

                        var approveStatus2;
                        if (row['l_approve_status2'] == 0) {
                            approveStatus2 =
                                '<div class="text-warning"><b><?=$strStatusProve0?></b></div>';
                        } else if (row['l_approve_status2'] == 1) {
                            approveStatus2 =
                                '<div class="text-warning"><b><?=$strStatusProve1?></b></div>';
                        } else if (row['l_approve_status2'] == 2) {
                            approveStatus2 =
                                '<div class="text-success"><b><?=$strStatusProve2?></b></div>';
                        } else if (row['l_approve_status2'] == 3) {
                            approveStatus2 =
                                '<div class="text-danger"><b><?=$strStatusProve3?></b></div>';
                        } else if (row['l_approve_status2'] == 4) {
                            approveStatus2 =
                                '<div class="text-success"><b><?=$strStatusProve4?></b></div>';
                        } else if (row['l_approve_status2'] == 5) {
                            approveStatus2 =
                                '<div class="text-danger"><b><?=$strStatusProve5?></b></div>';
                        } else if (row['l_approve_status'] == 6) {
                            approveStatus2 =
                                '';
                        } else {
                            approveStatus2 = 'ไม่พบสถานะ';
                        }

                        // เวลาเริ่มต้น
                        var startTime;
                        if (row['l_leave_start_time'] == '09:00:00' && $row['l_remark'] ==
                            '08:45:00') {
                            startTime = '08:45:00';
                        } else if (row['l_leave_start_time'] == '12:00:00') {
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
                            '<td hidden>' + (row['l_leave_reason'] ? row[
                                    'l_leave_reason'] :
                                '') +
                            '</td>' +

                            // 4
                            '<td>' + (totalItems - index) + '</td>' +

                            // 5
                            '<td>' + (row['l_usercode'] ? row['l_usercode'] : '') +
                            '</td>' +

                            // 6
                            '<td>' + '<span class="text-primary">' + (row['l_name'] ?
                                row[
                                    'l_name'] : '') + '</span><br>' +
                            'แผนก : ' + (row['l_department'] ? row['l_department'] :
                                '') +
                            '</td>' +

                            // 7
                            '<td>' + (row['l_create_datetime'] ? row[
                                    'l_create_datetime'] :
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
                            '<td>' + (row['l_leave_end_date'] ? row[
                                    'l_leave_end_date'] :
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
                            '<td>' + (row['l_reason'] ? row['l_reason'] : '') +
                            '</td>' +

                            // 16
                            '<td>' + (row['l_approve_name'] ? row['l_approve_name'] :
                                '') +
                            '</td>' +

                            // 17
                            '<td>' + approveStatus2 + '</td>' +

                            // 18
                            '<td>' + (row['l_approve_datetime2'] !== null ? row[
                                'l_approve_datetime2'] : '') + '</td>' +

                            // 19
                            '<td>' + (row['l_reason2'] ? row['l_reason2'] : '') +
                            '</td>' +

                            // 20
                            '<td>' + (row['l_approve_name2'] ? row['l_approve_name2'] :
                                '') + '</td>' +

                            // 21
                            '<td>' + confirmStatus + '</td>' +

                            // 22
                            '<td>';
                        if (row['l_approve_status'] == 2 || row['l_approve_status'] ==
                            3) {
                            newRow +=
                                '<button type="button" class="btn btn-primary leaveChk" data-bs-toggle="modal" data-bs-target="#leaveModal"><?=$btnCheck?></button>';
                        } else {
                            newRow +=
                                '<button type="button" class="btn btn-primary leaveChk" data-bs-toggle="modal" data-bs-target="#leaveModal"><?=$btnCheck?></button>';
                        }
                        newRow += '</td>' +

                            // 23
                            '<td>' +
                            '<button type="button" class="btn btn-primary btn-sm view-history" data-usercode="' +
                            row['l_usercode'] + '">' +
                            '<i class="fa-solid fa-clock-rotate-left"></i></button>' +
                            '</td>' +

                            // 24
                            '<td hidden>' + (row['l_remark'] ? row['l_remark'] : '') +
                            '</td>' +
                            '</tr>';

                        $("tbody").append(newRow);
                    });
                    $(".leaveChk").click(function() {
                        var rowData = $(this).closest("tr").find("td");

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
                            '<td>' + $(rowData[8]).text() + '</td>' +
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
                                var userCode = $(rowData[5]).text(); // รหัสพนักงาน
                                var createDate = $(rowData[7])
                                    .text(); // วันที่ยื่นใบลา
                                var leaveType = $(rowData[0]).text(); // ประเภทการลา
                                var empName = $(rowData[1]).text(); // ชื่อพนักงาน
                                var depart = $(rowData[2]).text(); // แผนก
                                var leaveReason = $(rowData[3])
                                    .text(); // เหตุผลการลา
                                var leaveStartDate = $(rowData[9])
                                    .text(); // วันเวลาที่ลาเริ่มต้น
                                var leaveEndDate = $(rowData[10])
                                    .text(); // วันเวลาที่ลาสิ้นสุด
                                var leaveStatus = $(rowData[12])
                                    .text(); // สถานะใบลา


                                var status = '4'; // อนุมัติ
                                var userName = '<?php echo $userName; ?>';
                                var proveName = '<?php echo $name; ?>';

                                $.ajax({
                                    url: 'm_ajax_upd_status.php',
                                    method: 'POST',
                                    data: {
                                        createDate: createDate,
                                        userCode: userCode,
                                        status: status,
                                        userName: userName,
                                        proveName: proveName,
                                        leaveType: leaveType,
                                        leaveReason: leaveReason,
                                        leaveStartDate: leaveStartDate,
                                        leaveEndDate: leaveEndDate,
                                        depart: depart,
                                        leaveStatus: leaveStatus,
                                        empName: empName
                                    },
                                    success: function(response) {
                                        $('#leaveModal').modal('hide');
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'อนุมัติใบลาสำเร็จ !',
                                            confirmButtonText: 'ตกลง'
                                        }).then((result) => {
                                            if (result
                                                .isConfirmed) {
                                                location
                                                    .reload();
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(error);
                                    }
                                });
                            });
                        $('.modal-footer .btn-danger').off('click').on('click',
                            function() {
                                // ซ่อน modal หลัก
                                $('#leaveModal').modal('hide');

                                // เรียกใช้ SweetAlert2 หลังจากที่ modal หลักถูกซ่อนไปแล้ว
                                setTimeout(function() {
                                        showInputDialog
                                            (); // เรียกใช้ฟังก์ชันเพื่อแสดงกล่องโต้ตอบ
                                    },
                                    300
                                ); // เพิ่ม delay เล็กน้อยเพื่อให้ modal หลักปิดสนิท
                            });

                        function showInputDialog() {
                            Swal.fire({
                                title: 'กรุณากรอกข้อมูล',
                                input: 'text',
                                inputLabel: 'เหตุผล',
                                inputPlaceholder: 'กรอกเหตุผลการไม่อนุมัติ',
                                showCancelButton: true,
                                confirmButtonText: 'ตกลง',
                                cancelButtonText: 'ยกเลิก',
                                preConfirm: (inputValue) => {
                                    if (!inputValue) {
                                        Swal.showValidationMessage(
                                            'กรุณากรอกเหตุผลการไม่อนุมัติ'
                                        );
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const reasonNoProve = result
                                        .value; // รับค่าจาก input
                                    console.log(
                                        reasonNoProve); // ตรวจสอบค่าที่กรอก
                                    noApprove(
                                        reasonNoProve
                                    ); // เรียกใช้ฟังก์ชัน noApprove
                                } else {
                                    $('#leaveModal').modal('show');
                                }
                            });
                        }

                        function noApprove(reasonNoProve) {

                            // แสดง loading ก่อนเริ่มกระบวนการ
                            Swal.fire({
                                title: 'กำลังโหลด...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading(); // แสดง icon โหลด
                                }
                            });

                            var userCode = $(rowData[5]).text(); // รหัสพนักงาน
                            var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
                            var leaveType = $(rowData[0]).text(); // ประเภทการลา
                            var empName = $(rowData[1]).text(); // ชื่อพนักงาน
                            var depart = $(rowData[2]).text(); // แผนก
                            var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
                            var leaveStartDate = $(rowData[9])
                                .text(); // วันเวลาที่ลาเริ่มต้น
                            var leaveEndDate = $(rowData[10])
                                .text(); // วันเวลาที่ลาสิ้นสุด
                            var leaveStatus = $(rowData[12]).text(); // สถานะใบลา

                            var status = '5'; // ไม่อนุมัติ
                            var userName = '<?php echo $userName; ?>';
                            var proveName = '<?php echo $name; ?>';

                            var reason = reasonNoProve;

                            $.ajax({
                                url: 'm_ajax_upd_status.php',
                                method: 'POST',
                                data: {
                                    createDate: createDate,
                                    userCode: userCode,
                                    status: status,
                                    userName: userName,
                                    proveName: proveName,
                                    leaveType: leaveType,
                                    leaveReason: leaveReason,
                                    leaveStartDate: leaveStartDate,
                                    leaveEndDate: leaveEndDate,
                                    depart: depart,
                                    leaveStatus: leaveStatus,
                                    empName: empName,
                                    reasonNoProve: reasonNoProve
                                },
                                success: function(response) {
                                    $('#leaveModal').modal(
                                        'hide'); // ปิด modal
                                    Swal.fire({
                                        title: 'ไม่อนุมัติสำเร็จ !',
                                        // text: 'ทำรายการเสร็จสิ้น',
                                        icon: 'success',
                                        confirmButtonText: 'ตกลง'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location
                                                .reload(); // โหลดหน้าใหม่เมื่อกดตกลง
                                        }
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        }
                    });
                    $('.view-history').click(function() {
                        var userCode = $(this).data(
                            'usercode'); // ดึงรหัสพนักงานจาก data attribute

                        $.ajax({
                            url: 'm_ajax_get_leave_history.php', // URL ของไฟล์ PHP ที่จะจัดการข้อมูล
                            type: 'POST',
                            data: {
                                userCode: userCode
                            },
                            success: function(response) {
                                // แสดงข้อมูลประวัติการลาหรือทำสิ่งที่ต้องการหลังจากได้รับข้อมูล
                                // เช่น แสดงใน modal หรือ alert
                                $('#historyModal .modal-body').html(
                                    response);
                                $('#historyModal').modal('show');
                            },
                            error: function() {
                                alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
                            }
                        });
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    $('.view-history').click(function() {
        var userCode = $(this).data('usercode'); // ดึงรหัสพนักงานจาก data attribute

        $.ajax({
            url: 'm_ajax_get_leave_history.php', // URL ของไฟล์ PHP ที่จะจัดการข้อมูล
            type: 'POST',
            data: {
                userCode: userCode
            },
            success: function(response) {
                // แสดงข้อมูลประวัติการลาหรือทำสิ่งที่ต้องการหลังจากได้รับข้อมูล
                // เช่น แสดงใน modal หรือ alert
                $('#historyModal .modal-body').html(response);
                $('#historyModal').modal('show');
            },
            error: function() {
                alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
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
        var value3 = $(this).val().toLowerCase();
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value3) > -1);
        });
    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>