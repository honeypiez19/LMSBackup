<?php
// Start session
session_start();

include '../connect.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลพนักงาน</title>

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
    <?php require 'admin_navbar.php'?>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fa-solid fa-user fa-2xl"></i>
                </div>
                <div class="col-auto">
                    <h3>ข้อมูลพนักงาน</h3>
                </div>
            </div>
        </div>
    </nav>

    <div class="mt-3 container">
        <div class="row">
            <div class="col-4">
                <label for="userCodeLabel" class="form-label">รหัสพนักงาน</label>
                <input type="text" class="form-control" id="codeSearch">
                <datalist id="codeList">
                    <?php
$sql = "SELECT DISTINCT Emp_usercode FROM employee";
$result = $conn->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row['Emp_usercode'] . '">';
}
?>
                </datalist>
            </div>
            <div class="col-4">
                <label for="nameLabel" class="form-label">ชื่อพนักงาน</label>
                <input type="text" class="form-control" id="nameSearch">
                <datalist id="nameList">
                    <?php
$sql = "SELECT DISTINCT Emp_name FROM employee";
$result = $conn->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row['Emp_name'] . '">';
}
?>
                </datalist>
            </div>
            <div class="col-4">
                <label for="depLabel" class="form-label">แผนก</label>
                <input type="text" class="form-control" id="depSearch">
                <datalist id="depList">
                    <?php
$sql = "SELECT DISTINCT Emp_department FROM employee";
$result = $conn->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row['Emp_department'] . '">';
}
?>
                </datalist>
            </div>
        </div>
    </div>

    <!-- ตารางข้อมูลพนักงานทั้งหมด -->
    <div class="mt-3 container-fluid">
        <table class="table table-hover table-bordered table-sm " style="border-top: 1px solid rgba(0, 0, 0, 0.1);"
            id="empTable">
            <thead>
                <tr class="text-center align-middle">
                    <th rowspan="2">ลำดับ</th>
                    <th rowspan="2">รหัสพนักงาน</th>
                    <th rowspan="2" style="width: 10%;">ชื่อ - นามสกุล</th>
                    <th rowspan="2">แผนก</th>
                    <th rowspan="2">อายุงาน</th>
                    <th rowspan="2">ระดับ</th>
                    <th rowspan="2" style="width: 10%;">อีเมล</th>
                    <th rowspan="2">เบอร์โทรศัพท์</th>
                    <th rowspan="1" colspan="6" class="table-secondary">ประเภทการลาและจำนวนวันที่ได้รับ</th>
                    <th rowspan="2">ชื่อผู้ใช้</th>
                    <th rowspan="2">รหัสผ่าน</th>
                    <th rowspan="2" style="width: 10%;"></th>
                </tr>
                <tr class="text-center align-middle">
                    <th colspan="1" style="background-color: #ff99c8; width: 5%;">ลากิจได้รับค่าจ้าง</th>
                    <th colspan="1" style="background-color: #fcf6bd; width: 5%;">ลากิจไม่ได้รับค่าจ้าง</th>
                    <th colspan="1" style="background-color: #d0f4de; width: 5%;">ลาป่วย</th>
                    <th colspan="1" style="background-color: #a9def9; width: 5%;">ลาป่วยจากงาน</th>
                    <th colspan="1" style="background-color: #e4c1f9; width: 5%;">ลาพักร้อน</th>
                    <th colspan="1" rowspan="2">อื่น ๆ (ระบุ)</th>
                </tr>
            </thead>
            <tbody>
                <?php
$itemsPerPage = 10;

// คำนวณหน้าปัจจุบัน
if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

$sql = "SELECT * FROM employee WHERE Emp_status <> 0 ORDER BY Add_datetime DESC";
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
        echo '<td>' . $row['Emp_usercode'] . '</td>';
        echo '<td>' . $row['Emp_name'] . '</td>';
        echo '<td>' . $row['Emp_department'] . '</td>';

        // echo '<td>';
        // if ($row['Emp_department'] == 1) {
        //     echo 'RD';
        // } elseif ($row['Emp_department'] == 2) {
        //     echo 'HR';
        // } elseif ($row['Emp_department'] == 3) {
        //     echo 'Sales';
        // } elseif ($row['Emp_department'] == 4) {
        //     echo 'Purchase';
        // } elseif ($row['Emp_department'] == 5) {
        //     echo 'Store';
        // } elseif ($row['Emp_department'] == 6) {
        //     echo 'CAD1';
        // } elseif ($row['Emp_department'] == 7) {
        //     echo 'CAD2';
        // } elseif ($row['Emp_department'] == 8) {
        //     echo 'CAM';
        // } elseif ($row['Emp_department'] == 9) {
        //     echo 'Production';
        // } elseif ($row['Emp_department'] == 10) {
        //     echo 'QC';
        // } elseif ($row['Emp_department'] == 11) {
        //     echo 'Account';
        // } elseif ($row['Emp_department'] == 12) {
        //     echo 'Machine';
        // } elseif ($row['Emp_department'] == 13) {
        //     echo 'Finishing';
        // } else {
        //     echo 'Unknown';
        // }
        // echo '</td>';

        echo '<td>' . $row['Emp_yearexp'] . '</td>';
        echo '<td>' . $row['Emp_level'] . '</td>';

        // echo '<td>';
        // if ($row['Emp_level'] == 1) {
        //     echo 'Staff';
        // } elseif ($row['Emp_level'] == 2) {
        //     echo 'Chief';
        // } elseif ($row['Emp_level'] == 3) {
        //     echo 'Manager';
        // } elseif ($row['Emp_level'] == 4) {
        //     echo 'Admin';
        // } else {
        //     echo 'Unknown';
        // }
        // echo '</td>';

        echo '<td>' . $row['Emp_email'] . '</td>';
        // echo '<td>' . $row['Emp_id_line'] . '</td>';
        echo '<td>' . $row['Emp_phone'] . '</td>';
        echo '<td>' . $row['Leave_personal'] . '</td>';
        echo '<td>' . $row['Leave_personal_no'] . '</td>';
        echo '<td>' . $row['Leave_sick'] . '</td>';
        echo '<td>' . $row['Leave_sick_work'] . '</td>';
        echo '<td>' . $row['Leave_annual'] . '</td>';
        echo '<td>' . $row['Other'] . '</td>';

        echo '<td>' . $row['Emp_username'] . '</td>';
        echo '<td>' . $row['Emp_password'] . '</td>';
        echo '<td>';
        echo '<button type="button" class="btn btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#empModal" data-usercode="' . $row['Emp_usercode'] . '">แก้ไข</button>';
        echo '<button type="button" class="mx-2 btn btn-danger delete-btn" data-usercode="' . $row['Emp_usercode'] . '"><i class="fa-solid fa-trash"> ลบ</i></button>';
        echo '</td>';
        echo '</tr>';
        $rowNumber--;
    }
}
?>
            </tbody>
        </table>
        <!-- Modal แก้ไข -->
        <div class="modal fade" id="empModal" tabindex="-1" aria-labelledby="empModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="empModalLabel">แก้ไขข้อมูลพนักงาน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="empModalBody">
                        <!-- ที่นี่จะแสดงฟอร์มหรือข้อมูลของพนักงานที่ต้องการแก้ไข -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-primary" id="saveChangesBtn">บันทึก</button>
                    </div>
                </div>
            </div>
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

        </div>
        <div class="mt-3 container-fluid">
            <!-- ปุ่มเพิ่มพนักงาน -->
            <div class="row d-flex justify-content-end">
                <div class="col-1">
                    <button class="btn" id="addEmp" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i
                            class="fa-solid fa-user-plus fa-2xl" style="color: #ffffff;"></i></button>
                </div>
            </div>
            <!-- Modal เพิ่มพนักงาน -->
            <div class="modal fade" id="addEmployeeModal" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="addEmployeeModalLabel">เพิ่มข้อมูลพนักงาน</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addEmpForm">
                                <div class="row">
                                    <h5>ข้อมูลการเข้าระบบ</h5>
                                    <div class="col-6">
                                        <label for="codeLabel"><span style="color: red;">* </span>รหัสพนักงาน</label>
                                        <input class="form-control" type="text" id="add_usercode" name="add_usercode">
                                    </div>
                                    <div class="col-6">
                                        <label for="usernameLabel">ชื่อผู้ใช้</label>
                                        <input class="form-control" type="text" id="add_username" name="add_username">
                                    </div>
                                </div>
                                <div class="mt-3 row">
                                    <div class="col-6">
                                        <label for="passwordLabel">รหัสผ่าน</label>
                                        <input class="form-control" type="text" id="add_password" name="add_password"
                                            pattern="[0-9]{1,}" title="กรุณากรอกรหัสผ่านเป็นตัวเลขเท่านั้น">
                                    </div>
                                </div>
                                <div class="mt-3 row">
                                    <h5>ข้อมูลพนักงาน</h5>
                                    <div class="col-3">
                                        <label for="nameLabel">ชื่อ - นามสกุล</label>
                                        <input class="form-control" type="text" id="add_name" name="add_name">
                                    </div>
                                    <div class="col-3">
                                        <label for="levelLabel">แผนก</label>
                                        <!-- <input class="form-control" list="departmentList" id="add_department"
                                        name="add_department" type="text"> -->
                                        <!-- <datalist id="departmentList">
                                        <?php
// สร้างคำสั่ง SQL เพื่อดึงข้อมูล
$sql = "SELECT * FROM employee_department";
$stmt = $conn->prepare($sql);
$stmt->execute();

// ดึงผลลัพธ์และแสดงใน datalist
echo '<datalist id="departmentList">';
while ($row = $stmt->fetch()) {
    echo '<option value="' . $row['Department_key'] . '">';
}
?>
                                    </datalist> -->
                                        <select class="form-control" id="add_department" style="border-radius: 20px;">
                                            <option value="select" selected>กรุณาเลือกแผนก</option>
                                            <?php
$department_sql = "SELECT * FROM employee_department";
$department_result = $conn->query($department_sql);
if ($department_result->rowCount() > 0) {
    while ($department_row = $department_result->fetch()) {
        echo '<option value="' . $department_row['Department_ID'] . '">' . $department_row['Department_key'] . '</option>';
    }
}
?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="yearexpLabel">อายุงาน</label>
                                        <!-- <input class="form-control" type="text" id="add_yearexp" name="add_yearexp"> -->
                                        <input class="form-control" type="text" id="add_yearexp" name="add_yearexp"
                                            onchange="calculateLeaveDays()">
                                    </div>
                                    <div class="col-3">
                                        <label for="levelLabel">ระดับ</label>
                                        <select class="form-control" id="add_level" style="border-radius: 20px;">
                                            <option value="select" selected>กรุณาเลือกระดับ</option>
                                            <?php
$level_sql = "SELECT * FROM employee_level";
$level_result = $conn->query($level_sql);
if ($level_result->rowCount() > 0) {
    while ($level_row = $level_result->fetch()) {
        echo '<option value="' . $level_row['Level_ID'] . '">' . $level_row['Level_category_key'] . '</option>';
    }
}
?>
                                        </select>

                                    </div>
                                </div>
                                <div class="mt-3 row">
                                    <div class="col-3">
                                        <label for="emailLabel">อีเมล</label>
                                        <input class="form-control" type="text" id="add_email" name="add_email">
                                    </div>
                                    <!-- <div class="col-6">
                                    <label for="depatLabel">ไลน์</label>
                                    <input class="form-control" type="text" id="add_id_line" name="add_id_line">
                                </div> -->
                                    <div class="col-3">
                                        <label for="phoneLabel">เบอร์โทรศัพท์</label>
                                        <input class="form-control" type="text" id="add_phone" name="add_phone">
                                    </div>
                                </div>
                                <div class="mt-3 row">
                                    <h5>จำนวนวันลาที่ได้รับ</h5>
                                    <div class="col-3">
                                        <label for="personalLabel">ลากิจได้รับค่าจ้าง</label>
                                        <input class="form-control" type="text" id="add_personal" name="add_personal">
                                    </div>
                                    <div class="col-3">
                                        <label for="personalNoLabel">ลากิจไม่ได้รับค่าจ้าง</label>
                                        <input class="form-control" type="text" id="add_personal_no"
                                            name="add_personal_no">
                                    </div>
                                    <div class="col-3">
                                        <label for="sickLabel">ลาป่วย</label>
                                        <input class="form-control" type="text" id="add_sick" name="add_sick">
                                    </div>
                                    <div class="col-3">
                                        <label for="sickWorkLabel">ลาป่วยจากงาน</label>
                                        <input class="form-control" type="text" id="add_sick_work" name="add_sick_work">
                                    </div>
                                </div>
                                <div class="mt-3 row">
                                    <div class="col-3">
                                        <label for="annualLabel">ลาพักร้อน</label>
                                        <input class="form-control" type="text" id="add_annual">
                                    </div>
                                    <div class="col-3">
                                        <label for="otherLabel">อื่น ๆ</label>
                                        <input class="form-control" type="text" id="add_other">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger mx-2" id="cancelBtn">ยกเลิก</button>
                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        $(document).ready(function() {
            $('#addEmpForm').on('submit', function(e) {
                var addUsername = '<?php echo $userName; ?>';
                var add_department = $("#add_department").val();
                var add_level = $("#add_level").val();
                var add_annual = $("#add_annual").val();
                var add_other = $("#add_other").val();

                alert(add_department)
                alert(add_level)

                if (add_department !== 'select' && add_level !== 'select') {
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: '../ajax_add_employee.php',
                        data: $('#addEmpForm').serialize() + '&addUsername=' + addUsername +
                            '&add_department=' + add_department +
                            '&add_level=' + add_level +
                            '&add_annual=' + add_annual +
                            '&add_other=' + add_other,
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูล');
                        }
                    });
                } else {
                    alert('กรุณาเลือกแผนกและระดับตำแหน่ง');
                    e.preventDefault(); // หยุดการส่งข้อมูล
                }
            });
            $('.edit-btn').click(function() {
                var usercode = $(this).data('usercode');


                $.ajax({
                    url: '../ajax_get_employee_data.php',
                    method: 'POST',
                    data: {
                        usercode: usercode
                    },
                    success: function(response) {
                        $('#empModalBody').html(response);
                        $('#empModal').modal('show');
                    }
                });
            });
            $('.delete-btn').click(function() {
                var usercode = $(this).data('usercode');
                if (confirm('คุณต้องการลบข้อมูลพนักงานนี้ใช่หรือไม่?')) {
                    $.ajax({
                        type: 'POST',
                        url: '../ajax_delete_employee.php',
                        data: {
                            usercode: usercode
                        },
                        success: function(response) {
                            alert('ลบข้อมูลพนักงานสำเร็จ');
                            location.reload(); // Reload the page after successful deletion
                        },
                        error: function(xhr, status, error) {
                            alert('เกิดข้อผิดพลาดในการลบข้อมูล');
                            console.error(xhr.responseText);
                        }
                    });
                    alert(usercode)
                }
            });
            $("#saveChangesBtn").click(function() {
                var updUsername = '<?php echo $userName; ?>';
                var usercode = $("#edit_usercode").val();
                var name = $("#edit_name").val();
                var department = $("#edit_department").val();
                var yearexp = $("#edit_yearexp").val();
                var level = $("#edit_level").val();
                var email = $("#edit_email").val();
                var id_line = $("#edit_id_line").val();
                var phone = $("#edit_phone").val();
                var username = $("#edit_username").val();
                var password = $("#edit_password").val();

                var personal = $("#edit_personal").val();
                var personalNo = $("#edit_personal_no").val();
                var sick = $("#edit_sick").val();
                var sickWork = $("#edit_sick_work").val();
                var annual = $("#edit_annual").val();
                var other = $("#edit_other").val();

                $.ajax({
                    url: "../ajax_upd_employee.php",
                    type: "POST",
                    data: {
                        usercode: usercode,
                        name: name,
                        department: department,
                        yearexp: yearexp,
                        level: level,
                        email: email,
                        id_line: id_line,
                        phone: phone,
                        username: username,
                        password: password,
                        updUsername: updUsername,
                        personal: personal,
                        personalNo: personalNo,
                        sick: sick,
                        sickWork: sickWork,
                        annual: annual,
                        other: other
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "แก้ไขข้อมูลสำเร็จ",
                            text: "",
                            icon: "success",
                            confirmButtonText: "ตกลง",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                });
                // alert(empUsername);
            });
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
            $("#depSearch").on("keyup", function() {
                var value3 = $(this).val().toLowerCase();
                $("tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value3) > -1);
                });
            });
        });

        // กรอกอายุงานให้จำนวนวันลาขึ้นเอง
        function calculateLeaveDays() {
            var yearsOfExperience = parseInt(document.getElementById("add_yearexp").value);
            var personal = document.getElementById("add_personal");
            var personalNo = document.getElementById("add_personal_no");
            var sick = document.getElementById("add_sick");
            var sickWork = document.getElementById("add_sick_work");
            var annual = document.getElementById("add_annual");
            var other = document.getElementById("add_other");

            if (yearsOfExperience < 1) {
                // ไม่ถึงปี
                personal.value = "0"; // จำนวนวันลากิจได้รับค่าจ้าง
                personalNo.value = "365"; // จำนวนวันลากิจไม่ได้รับค่าจ้าง
                sick.value = "30"; // จำนวนวันลาป่วย
                sickWork.value = "365"; // จำนวนวันลาป่วยจากงาน
                annual.value = "0"; // จำนวนวันลาพักร้อน
                other.value = "365"; // จำนวนวันลาพักร้อน
            } else if (yearsOfExperience >= 1 && yearsOfExperience < 2) {
                // ถ้าอายุงานอยู่ในช่วง 1-2 ปี
                personal.value = "5"; // จำนวนวันลากิจได้รับค่าจ้าง
                annual.value = "6"; // จำนวนวันลาพักร้อน
            } else {
                // ถ้าไม่อยู่ในช่วง 1-2 ปี ให้ล้างค่าทิ้ง
                personal.value = "";
                annual.value = "";
            }
        }
        </script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/bootstrap.bundle.js"></script>
        <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>