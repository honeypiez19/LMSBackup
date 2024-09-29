<?php
include '../connect.php';
date_default_timezone_set('Asia/Bangkok');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userCode = $_POST['userCode'];
    $userName = $_POST['userName'];
    $name = $_POST['name'];
    $department = $_POST['department'];
    $level = $_POST['level'];
    $telPhone = $_POST['telPhone'];
    $reason = $_POST['reason'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $addName = $_POST['addName'];
    $addDate = date('Y-m-d H:i:s');
    $workplace = $_POST['workplace'];
    $subDepart = $_POST['subDepart'];

    $startDate = $_POST['startDate'];
    $startDate = date('Y-m-d', strtotime($startDate));

    $endDate = $_POST['endDate'];
    $endDate = date('Y-m-d', strtotime($endDate));
    $createDatetime = date('Y-m-d H:i:s');

    $leaveType = 7;
    $leaveStatus = 0;
    $proveStatus = 0;
    $proveStatus2 = 1;
    $proveStatus3 = 6;

    // $workplace = $_POST['workplace'];
    // $subDepart = $_POST['subDepart'];
    // $subDepart2 = $_POST['subDepart2'];
    // $subDepart3 = $_POST['subDepart3'];
    // $subDepart4 = $_POST['subDepart4'];
    // $subDepart5 = $_POST['subDepart5'];
    // $levelMapping = [
    //     1 => 'user',
    //     2 => 'chief',
    //     3 => 'manager',
    //     4 => 'admin',
    // ];

    // $levelCode = $levelMapping[$level];

    if ($level == 'user') {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM leave_list WHERE l_usercode = :userCode AND l_leave_id = :leaveType");
        $stmt->bindParam(':userCode', $userCode, PDO::PARAM_STR);
        $stmt->bindParam(':leaveType', $leaveType, PDO::PARAM_INT);
        $stmt->execute();
        $lateCount = $stmt->fetchColumn();

        // เพิ่มจำนวนครั้งที่มาสาย
        $lateCount++;

        // เพิ่ม remark สำหรับหัวหน้า
        $remark = "มาสายครั้งที่ $lateCount";

        // บันทึกข้อมูลการมาสาย
        $leaveSql = "INSERT INTO leave_list (
            l_usercode, l_username, l_name, l_department, l_level, l_phone, l_leave_id,
            l_leave_reason, l_leave_start_date, l_leave_start_time, l_leave_end_date,
            l_leave_end_time, l_leave_status, l_create_datetime, l_remark,
            l_approve_status, l_approve_status2, l_hr_create_name, l_hr_create_datetime, l_workplace
        ) VALUES (
            :userCode, :userName, :name, :department, :level, :telPhone, :leaveType,
            :reason, :startDate, :startTime, :endDate, :endTime, :leaveStatus,
            :createDatetime, :remark, :proveStatus, :proveStatus2, :addName, :addDate, :workplace
        )";

        $stmt = $conn->prepare($leaveSql);
        $stmt->bindParam(':userCode', $userCode, PDO::PARAM_STR);
        $stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':department', $department, PDO::PARAM_STR);
        $stmt->bindParam(':level', $level, PDO::PARAM_STR);
        $stmt->bindParam(':telPhone', $telPhone, PDO::PARAM_STR);
        $stmt->bindParam(':leaveType', $leaveType, PDO::PARAM_INT);
        $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
        $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
        $stmt->bindParam(':leaveStatus', $leaveStatus, PDO::PARAM_INT);
        $stmt->bindParam(':createDatetime', $createDatetime, PDO::PARAM_STR);
        $stmt->bindParam(':remark', $remark, PDO::PARAM_STR);
        $stmt->bindParam(':proveStatus', $proveStatus, PDO::PARAM_INT);
        $stmt->bindParam(':proveStatus2', $proveStatus2, PDO::PARAM_INT);
        $stmt->bindParam(':addName', $addName, PDO::PARAM_STR);
        $stmt->bindParam(':addDate', $addDate, PDO::PARAM_STR);
        $stmt->bindParam(':workplace', $workplace, PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($lateCount % 3 == 0) {
                // บันทึกข้อมูลเพิ่มเติมเมื่อมาสาย 3 ครั้ง
                $remarkStopWork = "มาสายรวม $lateCount ครั้ง";
                $leaveSql = "INSERT INTO leave_list (
                    l_usercode, l_username, l_name, l_department, l_level, l_phone,
                    l_leave_id, l_leave_reason, l_leave_start_date, l_leave_start_time,
                    l_leave_end_date, l_leave_end_time, l_leave_status, l_create_datetime,
                    l_remark, l_approve_status, l_approve_status2, l_hr_create_name, l_hr_create_datetime,  l_workplace
                ) VALUES (
                    :userCode, :userName, :name, :department, :level, :telPhone, 6,
                    :reason, :startDate, :startTime, :endDate, :endTime, :leaveStatus,
                    :createDatetime, :remarkStopWork, :proveStatus, :proveStatus2, :addName, :addDate, 
                )";

                $stmt = $conn->prepare($leaveSql);
                $stmt->bindParam(':userCode', $userCode, PDO::PARAM_STR);
                $stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':department', $department, PDO::PARAM_STR);
                $stmt->bindParam(':level', $level, PDO::PARAM_STR);
                $stmt->bindParam(':telPhone', $telPhone, PDO::PARAM_STR);
                $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
                $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
                $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
                $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
                $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
                $stmt->bindParam(':leaveStatus', $leaveStatus, PDO::PARAM_INT);
                $stmt->bindParam(':createDatetime', $createDatetime, PDO::PARAM_STR);
                $stmt->bindParam(':remarkStopWork', $remarkStopWork, PDO::PARAM_STR);
                $stmt->bindParam(':proveStatus', $proveStatus, PDO::PARAM_INT);
                $stmt->bindParam(':proveStatus2', $proveStatus2, PDO::PARAM_INT);
                $stmt->bindParam(':addName', $addName, PDO::PARAM_STR);
                $stmt->bindParam(':addDate', $addDate, PDO::PARAM_STR);
                $stmt->bindParam(':workplace', $workplace, PDO::PARAM_STR);

                $stmt->execute();
            }

            // เตรียมข้อความสำหรับ LINE Notify
            $supervisorURL = 'https://lms.system-samt.com/';
            $supervisorMessage = "$name มาสาย\nวันที่มาสาย : $startDate\nเวลาที่มาสาย : $startTime ถึง $endTime\nสถานะรายการ : ปกติ\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด $supervisorURL";

            // ดึง token ของหัวหน้าและผู้จัดการ
            $stmtDept = $conn->prepare("SELECT e_token FROM employees WHERE e_sub_department = 'Store' AND e_level IN ('leader','chief', 'manager')");
            // $stmtDept->bindParam(':department', $department);
            $stmtDept->execute();
            $supervisorTokens = $stmtDept->fetchAll(PDO::FETCH_COLUMN);

            // ส่งการแจ้งเตือนทาง LINE
            foreach ($supervisorTokens as $supervisorToken) {
                $chSupervisor = curl_init();
                curl_setopt($chSupervisor, CURLOPT_URL, "https://notify-api.line.me/api/notify");
                curl_setopt($chSupervisor, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($chSupervisor, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($chSupervisor, CURLOPT_POST, 1);
                curl_setopt($chSupervisor, CURLOPT_POSTFIELDS, "message=" . urlencode($supervisorMessage));
                $headersSupervisor = [
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Bearer ' . $supervisorToken,
                ];
                curl_setopt($chSupervisor, CURLOPT_HTTPHEADER, $headersSupervisor);
                curl_setopt($chSupervisor, CURLOPT_RETURNTRANSFER, 1);
                $resultSupervisor = curl_exec($chSupervisor);

                // ตรวจสอบ error
                if (curl_error($chSupervisor)) {
                    echo 'Error:' . curl_error($chSupervisor);
                } else {
                    $resultSupervisor_ = json_decode($resultSupervisor, true);
                    echo "status : " . $resultSupervisor_['status'];
                    echo "message : " . $resultSupervisor_['message'];
                }

                curl_close($chSupervisor);
            }

            echo "บันทึกข้อมูลการมาสายเรียบร้อยแล้ว";
        } else {
            echo "Error: ไม่สามารถบันทึกข้อมูลได้";
        }
    } 
     else {
        echo 'ไม่พบสถานะ';
    }

} else {
    echo "Error: Invalid request method";
}