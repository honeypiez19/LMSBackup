<?php
include 'connect.php';
if (isset($_POST['leaveType'])) {
    $leaveType = $_POST['leaveType'];

    if ($leaveType == 'ลากิจได้รับค่าจ้าง') {
        $conType = str_replace("ลากิจได้รับค่าจ้าง", "1", $leaveType);
    } else if ($leaveType == 'ลากิจไม่ได้รับค่าจ้าง') {
        $conType = str_replace("ลากิจไม่ได้รับค่าจ้าง", "2", $leaveType);
    } else if ($leaveType == 'ลาป่วย') {
        $conType = str_replace("ลาป่วย", "3", $leaveType);
    } else if ($leaveType == 'ลาป่วยจากงาน') {
        $conType = str_replace("ลาป่วยจากงาน", "4", $leaveType);
    } else if ($leaveType == 'ลาพักร้อน') {
        $conType = str_replace("ลาพักร้อน", "5", $leaveType);
    } else if ($leaveType == 'ขาดงาน') {
        $conType = str_replace("ขาดงาน", "6", $leaveType);
    } else if ($leaveType == 'มาสาย') {
        $conType = str_replace("มาสาย", "7", $leaveType);
    } else if ($leaveType == 'อื่น ๆ') {
        $conType = str_replace("อื่น ๆ", "8", $leaveType);
    } else {
        echo 'ไม่มีประเภทการลา';
    }

    // ดึงข้อมูลการลาจากฐานข้อมูล
    $sql = "SELECT * FROM leave_items WHERE Leave_ID = '$conType' ORDER BY Create_datetime";
    $result = $conn->query($sql);
    $totalRows = $result->rowCount();
    $rowNumber = $totalRows; // Start with the total number of rows    // ตรวจสอบว่ามีข้อมูลการลาหรือไม่
    if ($totalRows > 0) {
        echo '<h5>' . $leaveType . '</h5>';
        echo '<table class="table table-hover">';
        echo '<thead>';
        echo '<tr class="text-center align-middle">';
        echo '<th rowspan="2">ลำดับ</th>';
        echo '<th rowspan="2">ประเภทการลา</th>';
        echo '<th rowspan="2">วันที่ยื่นใบลา</th>';
        echo '<th colspan="2">วันเวลาที่ลา</th>';
        echo '<th rowspan="2">สถานะ</th>';
        echo '<th rowspan="2">สถานะอนุมัติ</th>';
        echo '<th rowspan="2">สถานะ (เฉพาะ HR)</th>';

        echo '</tr>';

        echo '<tr class="text-center">';
        echo '<th>จาก</th>';
        echo '<th>ถึง</th>';
        echo '</tr>';

        echo '</thead>';
        echo '<tbody>';

        foreach ($result as $row) {
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

            echo '</tr>';
            $rowNumber--;
        }

        echo '</tbody>';
        echo '</table>';

    } else {
        // ถ้าไม่มีข้อมูลการลา
        echo '<div class="leave-details">';
        echo '<h4>' . $leaveType . '</h4>';
        echo '<p>ไม่มีข้อมูลการลา</p>';
        echo '</div>';
    }
}