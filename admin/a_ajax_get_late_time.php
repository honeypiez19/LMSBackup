<?php
// เชื่อมต่อฐานข้อมูล
include '../connect.php'; // เปลี่ยนเส้นทางไปยังไฟล์เชื่อมต่อฐานข้อมูลของคุณ

if (isset($_GET['userCode'])) {
    $userCode = $_GET['userCode'];
    // คำสั่ง SQL เพื่อดึงข้อมูลของพนักงาน และไม่นับการมาสายที่ถูกยกเลิก
    $sql = "SELECT * FROM leave_list WHERE l_usercode = :userCode AND l_leave_id = 7 AND l_leave_status <> 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userCode', $userCode);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // นับจำนวนแถวทั้งหมด
    $totalRows = count($rows);

    if ($totalRows > 0) {
        echo '<span><b>' . 'รหัสพนักงาน : ' . $rows[0]['l_usercode'] . '</b></span><br>';
        echo '<span><b>' . 'ชื่อพนักงาน : ' . $rows[0]['l_name'] . '</b></span><br>';
        echo '<span><b>' . 'แผนก : ' . $rows[0]['l_department'] . '</b></span><br>';

        // สร้างตัวแปรเพื่อเก็บลำดับแถว
        $absenceCount = 0;
        $latenessCount = 0;

        echo '<table class="table">
        <thead>
        <tr class="text-center align-middle">
        <th>ลำดับ</th>
        <th>วันเวลาที่มาสาย</th>
        <th>สถานะรายการ</th>
        <th>หมายเหตุ</th>
        </tr>
        </thead>
        <tbody>';

        foreach ($rows as $index => $row) {
            if ($row['l_leave_status'] != 1) { // เช็คว่าไม่ใช่การมาสายที่ถูกยกเลิก
                echo '<tr class="text-center align-middle">';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . $row['l_leave_start_date'] . '<br>' . $row['l_leave_start_time'] . ' ถึง ' . $row['l_leave_end_time'] . '</td>';
                echo '<td>';
                if ($row['l_leave_status'] == 1) {
                    echo '<span class="text-danger">ยกเลิกมาสาย</span>';
                } else {
                    echo '<span class="text-success">ปกติ</span>';
                }
                echo '</td>';
                echo '<td>' . $row['l_remark'] . '</td>';
                echo '</tr>';

                $latenessCount++;

                if ($latenessCount % 3 == 0) {
                    $absenceCount++;
                    echo '<tr class="text-center align-middle" style="color: red;">';
                    echo '<td colspan="4"><b>ขาดงานครั้งที่ ' . $absenceCount . ' เนื่องจากมาสายครบ 3 ครั้ง</b></td>';
                    echo '</tr>';
                }
            }
        }

        echo '</tbody>
        </table>';
    } else {
        echo '<span><b>ไม่พบข้อมูลการมาสายของพนักงานที่ระบุ</b></span>';
    }

    exit; // เพิ่มคำสั่ง exit เพื่อหยุดการทำงานของไฟล์นี้หลังจากแสดงข้อมูล
}
