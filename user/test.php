<?php
include '../connect.php';
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

    <script src="https://kit.fontawesome.com/84c1327080.js" crossorigin="anonymous"></script>
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
    <div class="container">
        <div class="mt-3 row">
            <div class="col-3 filter-card" data-status="all">
                <div class="card text-light mb-3" style="background-color: #072ac8;">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
$sql = "SELECT COUNT(Items_ID) AS total_leave FROM leave_items WHERE leave_ID = '1' AND Emp_usercode = '6608418'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_leave = $row['total_leave'];
?>
                            <?php echo $total_leave ?>
                        </h5>
                        <p class="card-text">
                            ลากิจได้รับค่าจ้าง
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="0">
                <div class="card text-light mb-3" style="background-color: #1360e2;">
                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <p class="card-text">
                            ลากิจไม่ได้รับค่าจ้าง
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="1">
                <div class="card text-light mb-3" style="background-color: #1e96fc;">
                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <p class="card-text">
                            ลาป่วย
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="2">
                <div class="card text-light mb-3" style="background-color: #60b6fb;">
                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <p class="card-text">
                            ลาป่วยจากงาน
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 row">
            <div class="col-3 filter-card" data-status="2">
                <div class="card text-light mb-3" style="background-color: #a2d6f9;">
                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <p class="card-text">
                            ลาพักร้อน
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="2">
                <div class="card text-light mb-3" style="background-color: #cfe57d;">
                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <p class="card-text">
                            ขาดงาน
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="2">
                <div class="card text-light mb-3" style="background-color: #fcf300;">
                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <p class="card-text">
                            มาสาย
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="2">
                <div class="card text-light mb-3" style="background-color: #fedd00;">
                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <p class="card-text">
                            อื่น ๆ
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="mb-3 d-flex justify-content-end">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#leaveModal">
                    ยื่นเรื่องขอลา
                </button>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveModalLabel">รายละเอียดคำขอ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveForm">
                        <div class="row">
                            <div class="col-12">
                                <label for="leaveType" class="form-label">ประเภทการลา</label>
                                <select class="form-select" id="leaveType">
                                    <option selected>เลือกประเภทการลา</option>
                                    <option value="1">ลากิจได้รับค่าจ้าง</option>
                                    <option value="2">ลากิจไม่ได้รับค่าจ้าง</option>
                                    <option value="3">ลาป่วย</option>
                                    <option value="4">ลาป่วยจากงาน</option>
                                    <option value="5">ลาพักร้อน</option>
                                    <option value="6">ขาดงาน</option>
                                    <option value="7">มาสาย</option>
                                    <option value="8">อื่น ๆ</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 row">
                            <div class="col-12">
                                <label for="leaveReason" class="form-label">เหตุผลการลา</label>
                                <textarea class="form-control" id="leaveReason" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="mt-3 row">
                            <div class="col-6">
                                <label for="startDate" class="form-label">วันที่เริ่มต้น</label>
                                <input type="text" class="form-control" id="startDate">
                                <!-- <input type="text" id="datepicker" class="form-control"> -->

                            </div>
                            <div class="col-6">
                                <label for="startTime" class="form-label">เวลาที่เริ่มต้น</label>
                                <!-- <input type="time" class="form-control" id="startTime" value="08:00" step="3600"> -->
                                <!-- <input type="text" class="form-control" id="startTime"> -->
                                <input type="text" class="form-control" id="startTime"
                                    placeholder="Select Date and Time">

                            </div>
                        </div>

                        <div class="mt-3 row">
                            <div class="col-6">
                                <label for="endDate" class="form-label">วันที่สิ้นสุด</label>
                                <input type="date" class="form-control" id="endDate"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-6">
                                <label for="endTime" class="form-label">เวลาที่สิ้นสุด</label>
                                <input type="time" class="form-control" id="endTime" value="16:40">
                            </div>
                        </div>
                        <div class="mt-3 row">
                            <div class="col-12">
                                <label for="attachment" class="form-label">ไฟล์แนบ (jpg, jpeg, png, pdf)</label>
                                <input type="file" class="form-control" id="attachment" accept="image/*,.pdf">
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-hover" style="border-top: 1px solid rgba(0, 0, 0, 0.1);" id="leaveTable">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>Leave ID</th>
                <th>Leave Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php
// สร้างคำสั่ง SQL
$sql = "SELECT * FROM leave_items WHERE Emp_usercode = '6608418'";

// ประมวลผลคำสั่ง SQL
$result = mysqli_query($conn, $sql);

// แสดงข้อมูลในตาราง
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Emp_usercode'] . "</td>";
        echo "<td>" . $row['Leave_ID'] . "</td>";
        echo "<td>" . $row['Leave_reason'] . "</td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data found</td></tr>";
}

// ปิดการเชื่อมต่อ
mysqli_close($conn);
?>
        </tbody>
    </table>
    <script>
    $(document).ready(function() {
        $('#leaveForm').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            var empUsername = '<?php echo $empUsername; ?>';
            var empUsercode = '<?php echo $empUsercode; ?>';
            var empName = '<?php echo $empName; ?>';
            var empDep = '<?php echo $empDep; ?>';
            var leaveType = $('#leaveType').val();
            var leaveReason = $('#leaveReason').val();
            var startDate = $('#startDate').val();
            var startTime = $('#startTime').val();
            var endDate = $('#endDate').val();
            var endTime = $('#endTime').val();
            // Send the AJAX request
            $.ajax({
                url: '../ajax_add_leave.php',
                type: 'POST',
                data: {
                    empUsername: empUsername,
                    empUsercode: empUsercode,
                    empName: empName,
                    empDep: empDep,
                    leaveType: leaveType,
                    leaveReason: leaveReason,
                    startDate: startDate,
                    startTime: startTime,
                    endDate: endDate,
                    endTime: endTime
                },
                success: function(response) {
                    alert('Leave request saved successfully');
                },
                error: function() {
                    alert('Error saving leave request');
                }
            });
            alert(startTime); // Debugging purposes
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
        });
    });
    flatpickr('#startTime', {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        defaultDate: "08:00",
        minuteIncrement: 1 // เลือกเวลาทุกนาที

    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>