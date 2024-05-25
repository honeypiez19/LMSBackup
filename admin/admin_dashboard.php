<?php
session_start();

require '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด</title>

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
    // $monthName = date('F', strtotime("first day of -$i month"));
    echo "<option value='$month'" . ($month == $selectedMonth ? " selected" : "") . ">$month</option>";
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
$sql = "SELECT COUNT(Items_ID) AS totalLeaveItems FROM leave_items WHERE Month(Create_datetime) = '$selectedMonth' AND Leave_status = '1'";
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
$sql = "SELECT COUNT(Items_ID) AS totalLeaveItems FROM leave_items WHERE Confirm_status = 0 AND Month(Create_datetime) = '$selectedMonth'  AND Leave_status = '1'";
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
$sql = "SELECT COUNT(Items_ID) AS totalLeaveItems FROM leave_items WHERE Confirm_status = 1 AND Month(Create_datetime) = '$selectedMonth'  AND Leave_status = '1'";
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
$sql = "SELECT COUNT(Items_ID) AS totalLeaveItems FROM leave_items WHERE Confirm_status = 2 AND Month(Create_datetime) = '$selectedMonth'  AND Leave_status = '1'";
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
                    <th rowspan="2">รหัสพนักงาน</th>
                    <th rowspan="1">ชื่อ - นามสกุล</th>
                    <th rowspan="1">รายการลา</th>
                    <th rowspan="2">วันที่ยื่นใบลา</th>
                    <th colspan="2" class="text-center">วันเวลาที่ลา</th>
                    <th rowspan="2">ไฟล์แนบ</th>
                    <th rowspan="2">สถานะใบลา</th>
                    <th rowspan="2">สถานะอนุมัติ</th>
                    <th rowspan="2">วันเวลาอนุมัติ</th>
                    <th rowspan="2">ระดับหัวหน้า</th>
                    <th rowspan="2">ระดับผู้จัดการขึ้นไป</th>
                    <th rowspan="2">เหตุผลอนุมัติ</th>
                    <th rowspan="2">สถานะ (เฉพาะ HR)</th>
                    <th rowspan="2"></th>
                </tr>
                <tr class="text-center">
                    <th> <input type="text" class="form-control" id="nameSearch"></th>
                    <th> <input type="text" class="form-control" id="leaveSearch"></th>
                    <th style="width: 8%;">จาก</th>
                    <th style="width: 8%;">ถึง</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
$itemsPerPage = 10;

// คำนวณหน้าปัจจุบัน
if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

$sql = "SELECT * FROM leave_items WHERE Month(Create_datetime) = '$selectedMonth'  ORDER BY Create_datetime DESC ";
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
        echo '<td>' . $rowNumber . '</td>';
        echo '<td>' . $row['Emp_usercode'] . '</td>';
        echo '<td>' . '<span class="text-primary">' . $row['Emp_name'] . '</span>' . '<br>' . 'แผนก : ' . $row['Emp_department'] . '</td>';
        // echo '<td>' . '<span class="text-primary">' . $row['Emp_name'] . '</span>' . '<br>' . 'แผนก : ' . $row['Emp_department'] . '</td>';
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

        echo '</td>';
        if (!empty($row['Img_file'])) {
            echo '<td><button id="imgBtn" class="btn btn-primary" onclick="window.open(\'../upload/' . $row['Img_file'] . '\', \'_blank\')"><i class="fa-solid fa-file"></i></button></td>';
        } else {
            echo '<td><button id="imgNoBtn" class="btn btn-primary" disabled><i class="fa-solid fa-file-excel"></i></button></td>';
        }
        echo '</td>';

        echo '<td>';
        if ($row['Leave_status'] == 1) {
            echo '<span class="text-danger">ยกเลิกใบลา</span>';
        } else {
        }
        echo '</td>';

        echo '<td>';
        if ($row['Approve_status'] == 0) {
            echo '<div class="text-warning"><b>รออนุมัติ</b></div>';
        } elseif ($row['Approve_status'] == 1) {
            echo '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
        } elseif ($row['Approve_status'] == 2) {
            echo '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
        } else {
            echo $row['Approve_status'];
        }
        echo '</td>';

        echo '<td>' . $row['Approve_datetime'] . '</td>';
        echo '<td>' . $row['Approve_name'] . '</td>';
        echo '<td>' . $row['Approve_name2'] . '</td>';
        echo '<td>' . $row['Approve_reason'] . '</td>';

        echo '<td >';
        if ($row['Confirm_status'] == 0) {
            echo '<div class="text-warning"><b>รอตรวจสอบ</b></div>';
        } elseif ($row['Confirm_status'] == 1) {
            echo '<div class="text-success"><b>ผ่าน</b></div>';
        } elseif ($row['Confirm_status'] == 2) {
            echo '<div class="text-danger"><b>ไม่ผ่าน</b></div>';
        } else {
            echo $row['Confirm_status'];
        }
        echo '</td>';

        echo "<td><button type='button' class='btn btn-primary leaveChk' data-bs-toggle='modal' data-bs-target='#leaveModal'>ตรวจสอบ</button></td>";
        echo '</tr>';
        $rowNumber--;
    }

} else {
    echo '<tr><td colspan="15" style="text-align: left; color:red;">ไม่พบข้อมูล</td></tr>';
}
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
    $(".filter-card").click(function() {
        var status = $(this).data("status");
        $.ajax({
            url: '../ajax_get_leavedata.php',
            method: 'GET',
            data: {
                status: status
            },
            dataType: 'json',
            success: function(data) {
                // Clear existing table rows
                $("tbody").empty();

                // Populate the table with the new data
                $.each(data, function(index, row) {
                    var leaveType = '';
                    if (row['Leave_ID'] == 1) {
                        leaveType = 'ลากิจได้รับค่าจ้าง';
                    } else if (row['Leave_ID'] == 2) {
                        leaveType = 'ลากิจไม่ได้รับค่าจ้าง';
                    } else if (row['Leave_ID'] == 3) {
                        leaveType = 'ลาป่วย';
                    } else if (row['Leave_ID'] == 4) {
                        leaveType = 'ลาป่วยจากงาน';
                    } else if (row['Leave_ID'] == 5) {
                        leaveType = 'ลาพักร้อน';
                    } else if (row['Leave_ID'] == 6) {
                        leaveType = 'ขาดงาน';
                    } else if (row['Leave_ID'] == 7) {
                        leaveType = 'มาสาย';
                    } else if (row['Leave_ID'] == 8) {
                        leaveType = 'อื่น ๆ';
                    } else {
                        leaveType = row['Leave_ID'];
                    }

                    var confirmStatus = '';
                    if (row['Confirm_status'] == 0) {
                        confirmStatus =
                            '<div class="text-warning"><b>รอตรวจสอบ</b></div>';
                    } else if (row['Confirm_status'] == 1) {
                        confirmStatus =
                            '<div class="text-success"><b>ผ่าน</b></div>';
                    } else if (row['Confirm_status'] == 2) {
                        confirmStatus =
                            '<div class="text-danger"><b>ไม่ผ่าน</b></div>';
                    } else {
                        confirmStatus = row['Confirm_status'];
                    }

                    var approveStatus = '';
                    if (row['Approve_status'] == 0) {
                        approveStatus =
                            '<div class="text-warning"><b>รออนุมัติ</b></div>';
                    } else if (row['Approve_status'] == 1) {
                        approveStatus =
                            '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
                    } else if (row['Approve_status'] == 2) {
                        approveStatus =
                            '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
                    } else {
                        approveStatus = row['Approve_status'];
                    }

                    var approveReason = row['Approve_reason'] !== null ? row[
                        'Approve_reason'] : '';

                    var newRow = '<tr class="align-middle">' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + (row['Emp_usercode'] ? row['Emp_usercode'] : '') +
                        '</td>' +
                        '<td>' + '<span class="text-primary">' + (row['Emp_name'] ? row[
                            'Emp_name'] : '') + '</span>' + '<br>' +
                        'แผนก : ' + (row['Emp_department'] ? row['Emp_department'] :
                            '') +
                        '</td>' +
                        '<td>';
                    if (row['Leave_ID'] == 1) {
                        newRow +=
                            '<span class="text-primary">ลากิจได้รับค่าจ้าง</span><br>เหตุผล : ' +
                            (row['Leave_reason'] ? row['Leave_reason'] : '');
                    } else if (row['Leave_ID'] == 2) {
                        newRow +=
                            '<span class="text-primary">ลากิจไม่ได้รับค่าจ้าง</span><br>เหตุผล : ' +
                            (row['Leave_reason'] ? row['Leave_reason'] : '');
                    } else if (row['Leave_ID'] == 3) {
                        newRow +=
                            '<span class="text-primary">ลาป่วย</span><br>เหตุผล : ' +
                            (row['Leave_reason'] ? row['Leave_reason'] : '');
                    } else {
                        newRow += row['Leave_ID'] ? row['Leave_ID'] : '';
                    }
                    newRow += '</td>' +
                        '<td>' + (row['Leave_date_start'] ? row['Leave_date_start'] :
                            '') +
                        '<br>' + (row['Leave_time_start'] ? row['Leave_time_start'] :
                            '') +
                        '</td>' +
                        '<td>' + (row['Leave_date_end'] ? row['Leave_date_end'] : '') +
                        '<br>' + (row['Leave_time_end'] ? row['Leave_time_end'] : '') +
                        '</td>' +
                        '<td>' + (row['Create_datetime'] ? row['Create_datetime'] :
                            '') +
                        '</td>';
                    if (row['Img_file']) {
                        newRow +=
                            '<td><button id="imgBtn" class="btn btn-primary" onclick="window.open(\'../upload/' +
                            row['Img_file'] +
                            '\', \'_blank\')"><i class="fa-solid fa-file"></i></button></td>';
                    } else {
                        newRow +=
                            '<td><button id="imgNoBtn" class="btn btn-primary" disabled><i class="fa-solid fa-file-excel"></i></button></td>';
                    }
                    newRow +=
                        '<td>' + approveStatus + '</td>' +
                        '<td>' + (row['Approve_datetime'] !== null ? row[
                            'Approve_datetime'] : '') + '</td>' +
                        '<td>' + (row['Approve_name'] ? row['Approve_name'] : '') +
                        '</td>' +
                        '<td>' + (row['Approve_name2'] ? row['Approve_name2'] : '') +
                        '</td>' +
                        '<td>' + approveReason + '</td>' +
                        '<td>' + confirmStatus + '</td>' +
                        '<td>' +
                        '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#leaveModal">ตรวจสอบ</button>' +
                        '</td>' +
                        '</tr>';

                    $("tbody").append(newRow);
                });
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
    $(document).on('click', '.leaveChk', function() {
        var rowData = $(this).closest('tr').find('td');
        var confirmStatus = $(rowData[14]).text(); // สถานะ HR
        var approveStatus = $(rowData[9]).text(); // สถานะอนุมัติหัวหน้า ผจก

        var confirmChk = '';
        var approveChk = '';

        if (confirmStatus === 'รอตรวจสอบ') {
            confirmChk = '<span class="text-warning">รอตรวจสอบ</span>';
        } else if (confirmStatus === 'ตรวจสอบแล้ว') {
            confirmChk =
                confirmChk = '<span class="text-success">ตรวจสอบแล้ว</span>';
        } else if (confirmStatus === 'ตรวจสอบไม่ผ่าน') {
            confirmChk =
                confirmChk = '<span class="text-danger">ตรวจสอบไม่ผ่าน</span>';
        } else {
            confirmChk = confirmStatus;
        }
        if (approveStatus === 'รออนุมัติ') {
            approveChk = '<span class="text-warning">รออนุมัติ</span>';
        } else if (approveStatus === 'อนุมัติ') {
            approveChk =
                approveChk = '<span class="text-success">อนุมัติ</span>';
        } else if (approveStatus === 'ไม่อนุมัติ') {
            approveChk =
                approveChk = '<span class="text-danger">ไม่อนุมัติ</span>';
        } else {
            approveChk = approveStatus;
        }

        // นำข้อมูลจากแถวไปแสดงใน Modal
        $('#leaveModal .modal-body').html(
            '<p><strong>รหัสพนักงาน : </strong>' + $(rowData[1]).html() + '</p>' +
            '<p><strong>ชื่อ - นามสกุล : </strong>' + $(rowData[2]).html() + '</p>' +
            '<p><strong>รายการลา : </strong>' + $(rowData[3]).html() + '</p>' +
            '<p><strong>วันที่สร้างใบลา : </strong>' + $(rowData[4]).text() + '</p>' +
            '<p><strong>วันเวลาที่ลา : </strong>' + $(rowData[5]).text() + ' ถึง ' + $(rowData[6]).text() +
            '</p>' +
            '<p><strong>สถานะใบลา : </strong><span style="color: red;">' + $(rowData[8]).text() +
            '</span></p>' +
            '<p><strong>สถานะ (เฉพาะ HR) : </strong> ' + confirmChk + '</p>'
        );
        // เมื่อคลิกปุ่ม "ผ่าน"
        $('.modal-footer .btn-success').on('click', function() {
            var userCode = $(rowData[1]).text(); // รหัสพนักงาน
            var createDate = $(rowData[4]).text(); // วันที่สร้างใบลา
            var checkFirm = '1'; // ผ่าน

            var userName = '<?php echo $userName; ?>';
            $.ajax({
                url: '../ajax_upd_status.php',
                method: 'POST',
                data: {
                    createDate: createDate,
                    userCode: userCode,
                    checkFirm: checkFirm,
                    userName: userName
                },
                success: function(response) {
                    $('#leaveModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
            alert(checkFirm)
        });
        // ปุ่มไม่ผ่าน
        $('.modal-footer .btn-danger').on('click', function() {
            var userCode = $(rowData[1]).text(); // รหัสพนักงาน
            var createDate = $(rowData[6]).text(); // วันที่สร้างใบลา
            var checkFirm = '2'; // ไม่ผ่าน

            // var empUsername = '<?php echo $userName; ?>';

            $.ajax({
                url: '../ajax_upd_status.php',
                method: 'POST',
                data: {
                    createDate: createDate,
                    userCode: userCode,
                    checkFirm: checkFirm,
                    userName: userName
                },
                success: function(response) {
                    $('#leaveModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>