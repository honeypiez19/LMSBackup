<?php
require 'connect.php';

if (isset($_POST['emp_code'])) {
    $emp_code = $_POST['emp_code'];
    if ($emp_code === 'All') {
        $sql = "SELECT * FROM employee";
    } else {
        $sql = "SELECT * FROM employee WHERE Emp_usercode = '$emp_code'";
    }
    $result = mysqli_query($conn, $sql);
    $rowNumber = 1;

    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr class="text-center align-middle">
        <th>ลำดับ</th>
        <th>ชื่อ - สกุล</th>
        <th>ประเภทของการลา</th>
        <th>จำนวนวันที่ได้</th>
        <th>จำนวนวันที่ใช้ไป</th>
        <th>จำนวนวันคงเหลือ</th>
        </tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            // แสดงข้อมูลของพนักงาน
            echo '<tr class="text-center align-middle">';
            echo '<td rowspan="5">' . $rowNumber . '</td>';
            echo '<td rowspan="5">' . $row['Emp_name'] . '</td>';

            // ลากิจได้รับค่าจ้าง
            $leaveSql = "SELECT count(Leave_ID) as Leave_personal_count FROM leave_items WHERE Emp_usercode = '" . $row['Emp_usercode'] . "' AND Leave_ID = 1";
            $leaveResult = mysqli_query($conn, $leaveSql);
            $leavePersonalRow = mysqli_fetch_assoc($leaveResult);
            echo '<td>ลากิจได้รับค่าจ้าง</td>';
            echo '<td>' . $row['Leave_personal'] . '</td>';
            echo '<td>' . $leavePersonalRow['Leave_personal_count'] . '</td>';
            echo '<td>' . ($row['Leave_personal'] - $leavePersonalRow['Leave_personal_count']) . '</td>';

            echo '</tr>';

            // ลากิจไม่ได้รับค่าจ้าง
            $leaveSql = "SELECT count(Leave_ID) as Leave_personal_no_count FROM leave_items WHERE Emp_usercode = '" . $row['Emp_usercode'] . "' AND Leave_ID = 2";
            $leaveResult = mysqli_query($conn, $leaveSql);
            $leavePersonalNoRow = mysqli_fetch_assoc($leaveResult);
            echo '<tr class="text-center align-middle">';
            echo '<td>ลากิจไม่ได้ค่าจ้าง</td>';
            echo '<td>' . $row['Leave_personal_no'] . '</td>';
            echo '<td>' . $leavePersonalNoRow['Leave_personal_no_count'] . '</td>';
            echo '<td>' . ($row['Leave_personal_no'] - $leavePersonalNoRow['Leave_personal_no_count']) . '</td>';
            echo '</tr>';

            // ลาป่วย
            $leaveSql = "SELECT count(Leave_ID) as Leave_sitck_count FROM leave_items WHERE Emp_usercode = '" . $row['Emp_usercode'] . "' AND Leave_ID = 3";
            $leaveResult = mysqli_query($conn, $leaveSql);
            $leaveSickRow = mysqli_fetch_assoc($leaveResult);
            echo '<tr class="text-center align-middle">';
            echo '<td>ลาป่วย</td>';
            echo '<td>' . $row['Leave_sick'] . '</td>';
            echo '<td>' . $leaveSickRow['Leave_sitck_count'] . '</td>';
            echo '<td>' . ($row['Leave_sick'] - $leaveSickRow['Leave_sitck_count']) . '</td>';
            echo '</tr>';

            // ลาป่วยจากงาน
            $leaveSql = "SELECT count(Leave_ID) as Leave_sick_work_count FROM leave_items WHERE Emp_usercode = '" . $row['Emp_usercode'] . "' AND Leave_ID = 4";
            $leaveResult = mysqli_query($conn, $leaveSql);
            $leaveSickWorkRow = mysqli_fetch_assoc($leaveResult);
            echo '<tr class="text-center align-middle">';
            echo '<td>ลาป่วยจากงาน</td>';
            echo '<td>' . $row['Leave_sick'] . '</td>';
            echo '<td>' . $leaveSickWorkRow['Leave_sick_work_count'] . '</td>';
            echo '<td>' . ($row['Leave_sick'] - $leaveSickWorkRow['Leave_sick_work_count']) . '</td>';
            echo '</tr>';

            // ลาพักร้อน
            $leaveSql = "SELECT count(Leave_ID) as Leave_annual_count FROM leave_items WHERE Emp_usercode = '" . $row['Emp_usercode'] . "' AND Leave_ID = 5";
            $leaveResult = mysqli_query($conn, $leaveSql);
            $leaveAnnualRow = mysqli_fetch_assoc($leaveResult);
            echo '<tr class="text-center align-middle">';
            echo '<td>ลาพักร้อน</td>';
            echo '<td>' . $row['Leave_annual'] . '</td>';
            echo '<td>' . $leaveAnnualRow['Leave_annual_count'] . '</td>';
            echo '<td>' . ($row['Leave_annual'] - $leaveAnnualRow['Leave_annual_count']) . '</td>';
            echo '</tr>';

            $rowNumber++;
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'No data found.';
    }
}
