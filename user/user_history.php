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
    <title>ประวัติการลา</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="../logo/logo.png">
    <script src="https://kit.fontawesome.com/84c1327080.js" crossorigin="anonymous"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
</head>

<body>
    <?php require 'user_navbar.php'?>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fa-solid fa-user fa-2xl"></i>
                </div>
                <div class="col-auto">
                    <h3>ประวัติการลา</h3>
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
for ($i = 0; $i <= 5; $i++) {
    $year = date('Y', strtotime("last day of -$i year"));
    echo "<option value='$year'" . ($year == $selectedYear ? " selected" : "") . ">$year</option>";
}
echo "</select>";
?>
            </div>
            <div class="col-auto">
                <!-- <?php
echo "<input type='submit' class='btn btn-primary mb-3' value='<i class='fa-solid fa-magnifying-glass'></i>'>";
?> -->
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
        <table class="mt-3 table table-hover table-bordered" style="border-top: 1px solid rgba(0, 0, 0, 0.1);"
            id="leaveTable">
            <thead>
                <tr class="text-center align-middle">
                    <th rowspan="2">ประเภทการลา </th>
                    <th colspan="12">เดือน</th>
                    <th rowspan="2">รายละเอียดการลา</th>
                </tr>
                <tr class="text-center align-middle">
                    <td>ม.ค.</td>
                    <td>ก.พ.</td>
                    <td>มี.ค</td>
                    <td>เม.ย.</td>
                    <td>พ.ค.</td>
                    <td>มิ.ย.</td>
                    <td>ก.ค.</td>
                    <td>ส.ค.</td>
                    <td>ก.ย.</td>
                    <td>ต.ค.</td>
                    <td>พ.ย.</td>
                    <td>ธ.ค.</td>
                </tr>
            </thead>
            <tbody>
                <?php
$sql = "SELECT * FROM employee WHERE Emp_usercode = '$userCode'";
$result = $conn->query($sql);

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    // ลากิจได้รับค่าจ้าง
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลากิจได้รับค่าจ้าง' . '</td>';
    for ($i = 1; $i <= 12; $i++) {
        $sql_count = "SELECT COUNT(Items_ID) AS leave_personal_count FROM leave_items WHERE Leave_ID = '1' AND YEAR(Leave_date_start) = '$selectedYear' AND MONTH(Leave_date_start) = '$i' ORDER BY ";
        $result_count = $conn->query($sql_count);
        $row_leave_personal_count = $result_count->fetch(PDO::FETCH_ASSOC);
        echo '<td>' . $row_leave_personal_count['leave_personal_count'] . '</td>';
    }
    echo '<td><button type="button" class="btn btn-primary view-button""><i class="fa-solid fa-eye"></i></button></td>';
    echo '</tr>';

    // ลากิจไม่ได้รับค่าจ้าง
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลากิจไม่ได้รับค่าจ้าง' . '</td>';
    for ($i = 1; $i <= 12; $i++) {
        $sql_count = "SELECT COUNT(Items_ID) AS leave_personal_no_count FROM leave_items WHERE Leave_ID = '2' AND YEAR(Leave_date_start) = '$selectedYear' AND MONTH(Leave_date_start) = '$i'";
        $result_count = $conn->query($sql_count);
        $row_leave_personal_no_count = $result_count->fetch(PDO::FETCH_ASSOC);
        echo '<td>' . $row_leave_personal_no_count['leave_personal_no_count'] . '</td>';
    }
    echo '<td><button type="button" class="btn btn-primary view-button""><i class="fa-solid fa-eye"></i></button></td>';
    echo '</tr>';

    // ลาป่วย
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาป่วย' . '</td>';
    for ($i = 1; $i <= 12; $i++) {
        $sql_count = "SELECT COUNT(Items_ID) AS leave_sick_count FROM leave_items WHERE Leave_ID = '3' AND YEAR(Leave_date_start) = '$selectedYear'  AND MONTH(Leave_date_start) = '$i'";
        $result_count = $conn->query($sql_count);
        $row_leave_sick_count = $result_count->fetch(PDO::FETCH_ASSOC);
        echo '<td>' . $row_leave_sick_count['leave_sick_count'] . '</td>';
    }
    echo '<td><button type="button" class="btn btn-primary view-button""><i class="fa-solid fa-eye"></i></button></td>';
    echo '</tr>';

    // ลาป่วยจากงาน
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาป่วยจากงาน' . '</td>';
    for ($i = 1; $i <= 12; $i++) {
        $sql_count = "SELECT COUNT(Items_ID) AS leave_sick_work_count FROM leave_items WHERE Leave_ID = '4' AND YEAR(Leave_date_start) = '$selectedYear' AND MONTH(Leave_date_start) = '$i'";
        $result_count = $conn->query($sql_count);
        $row_leave_sick_work_count = $result_count->fetch(PDO::FETCH_ASSOC);
        echo '<td>' . $row_leave_sick_work_count['leave_sick_work_count'] . '</td>';
    }
    echo '<td><button type="button" class="btn btn-primary view-button""><i class="fa-solid fa-eye"></i></button></td>';
    echo '</tr>';

    // ลาพักร้อน
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ลาพักร้อน' . '</td>';
    for ($i = 1; $i <= 12; $i++) {
        $sql_count = "SELECT COUNT(Items_ID) AS leave_annual_count FROM leave_items WHERE Leave_ID = '5' AND YEAR(Leave_date_start) = '$selectedYear' AND MONTH(Leave_date_start) = '$i'";
        $result_count = $conn->query($sql_count);
        $row_leave_annual_count = $result_count->fetch(PDO::FETCH_ASSOC);
        echo '<td>' . $row_leave_annual_count['leave_annual_count'] . '</td>';
    }
    echo '<td><button type="button" class="btn btn-primary view-button""><i class="fa-solid fa-eye"></i></button></td>';
    echo '</tr>';

    // ขาดงาน
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'ขาดงาน' . '</td>';
    for ($i = 1; $i <= 12; $i++) {
        $sql_count = "SELECT COUNT(Items_ID) AS absence_work_count FROM leave_items WHERE Leave_ID = '6' AND YEAR(Leave_date_start) = '$selectedYear' AND MONTH(Leave_date_start) = '$i'";
        $result_count = $conn->query($sql_count);
        $row_absence_work_count = $result_count->fetch(PDO::FETCH_ASSOC);
        echo '<td>' . $row_absence_work_count['absence_work_count'] . '</td>';
    }
    echo '<td><button type="button" class="btn btn-primary view-button""><i class="fa-solid fa-eye"></i></button></td>';
    echo '</tr>';

    // มาสาย
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'มาสาย' . '</td>';
    for ($i = 1; $i <= 12; $i++) {
        $sql_count = "SELECT COUNT(Items_ID) AS late_count FROM leave_items WHERE Leave_ID = '7' AND YEAR(Leave_date_start) = '$selectedYear' AND MONTH(Leave_date_start) = '$i'";
        $result_count = $conn->query($sql_count);
        $row_late_count = $result_count->fetch(PDO::FETCH_ASSOC);
        echo '<td>' . $row_late_count['late_count'] . '</td>';
    }
    echo '<td><button type="button" class="btn btn-primary view-button""><i class="fa-solid fa-eye"></i></button></td>';
    echo '</tr>';

    // อื่น ๆ
    echo '<tr class="text-center align-middle">';
    echo '<td>' . 'อื่น ๆ' . '</td>';
    for ($i = 1; $i <= 12; $i++) {
        $sql_count = "SELECT COUNT(Items_ID) AS other_count FROM leave_items WHERE Leave_ID = '8' AND YEAR(Leave_date_start) = '$selectedYear' AND MONTH(Leave_date_start) = '$i'";
        $result_count = $conn->query($sql_count);
        $row_other_count = $result_count->fetch(PDO::FETCH_ASSOC);
        echo '<td>' . $row_other_count['other_count'] . '</td>';
    }
    echo '<td><button type="button" class="btn btn-primary view-button""><i class="fa-solid fa-eye"></i></button></td>';
    echo '</tr>';
}
?>


            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="leaveDetailsModal" tabindex="-1" aria-labelledby="leaveDetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveDetailsModalLabel">ประวัติ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
    $('.view-button').click(function() {
        // หาข้อมูลของแถวที่ถูกคลิก
        var row = $(this).closest('tr');
        var leaveType = row.find('td:first').text();

        $.ajax({
            url: '../ajax_get_detail.php',
            method: 'POST',
            data: {
                leaveType: leaveType
            },
            success: function(response) {
                $('#leaveDetailsModal .modal-body').html(response);
                $('#leaveDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('เกิดข้อผิดพลาด: ' + error);
            }
        });
        // alert(leaveType)
    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>