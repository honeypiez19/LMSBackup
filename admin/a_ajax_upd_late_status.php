<?php
include '../connect.php';
date_default_timezone_set('Asia/Bangkok');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usercode = $_POST['usercode'];
    $createDatetime = $_POST['createDatetime'];
    $userName = $_POST['userName'];
    $lateDate = $_POST['lateDate'];
    $lateStart = $_POST['lateStart'];
    $lateEnd = $_POST['lateEnd'];
    $depart = $_POST['depart'];
    $name = $_POST['name'];
    $leaveStatus = $_POST['leaveStatus'];
    $leaveType = $_POST['leaveType']; // Ensure this is provided
    $leaveReason = $_POST['leaveReason']; // Ensure this is provided
    $action = $_POST['action'];

    $currentDateTime = date('Y-m-d H:i:s');

    // Prepare SQL statement based on action
    if ($action === 'cancel') {
        $updateSql = "UPDATE leave_list SET l_leave_status = 1, l_hr_status = 0, l_hr_cancel_datetime = :currentDateTime, l_hr_cancel_name = :userName WHERE l_usercode = :usercode AND l_create_datetime = :createDatetime";
    } elseif ($action === 'approve') {
        $updateSql = "UPDATE leave_list SET l_hr_status = 1, l_hr_name = :userName, l_hr_datetime = :currentDateTime WHERE l_usercode = :usercode AND l_create_datetime = :createDatetime";
    } elseif ($action === 'deny') {
        $updateSql = "UPDATE leave_list SET l_hr_status = 2, l_hr_name = :userName, l_hr_datetime = :currentDateTime WHERE l_usercode = :usercode AND l_create_datetime = :createDatetime";
    } else {
        echo "Invalid action.";
        exit;
    }

    $stmt = $conn->prepare($updateSql);
    $stmt->bindParam(':usercode', $usercode, PDO::PARAM_STR);
    $stmt->bindParam(':createDatetime', $createDatetime, PDO::PARAM_STR);
    $stmt->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
    $stmt->bindParam(':userName', $userName, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Fetch user token
        $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_usercode = :usercode");
        $stmt->bindParam(':usercode', $usercode, PDO::PARAM_STR);
        $stmt->execute();
        $sToken = $stmt->fetchColumn();

        $sURL = 'https://lms.system-samt.com/';

        // Prepare message for employee
        $sMessage = "";
        if ($action === 'cancel') {
            $sMessage = "ยกเลิกการมาสายของ $name\nวันที่มาสาย : $lateDate\nเวลาที่มาสาย : $lateStart ถึง $lateEnd\nสถานะรายการ : $leaveStatus\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด: $sURL";
        } elseif ($action === 'approve') {
            $sMessage = "ตรวจสอบมาสายของ $name ผ่านเรียบร้อย\nวันที่มาสาย : $lateDate\nเวลาที่มาสาย : $lateStart ถึง $lateEnd\nสถานะรายการ : $leaveStatus\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด: $sURL";
        } elseif ($action === 'deny') {
            $sMessage = "ตรวจสอบมาสายของ $name ไม่ผ่านเรียบร้อย\nวันที่มาสาย : $lateDate\nเวลาที่มาสาย : $lateStart ถึง $lateEnd\nสถานะรายการ : $leaveStatus\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด: $sURL";
        }

        if ($sToken) {
            // Sending notification to employee
            $chOne = curl_init();
            curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
            curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($chOne, CURLOPT_POST, 1);
            curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . urlencode($sMessage));
            $headers = ['Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken];
            curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($chOne);

            if (curl_error($chOne)) {
                echo 'Error:' . curl_error($chOne);
            } else {
                $result_ = json_decode($result, true);
                echo "Employee Notification - status: " . $result_['status'] . ", message: " . $result_['message'] . "\n";
            }

            curl_close($chOne);
        }

        echo "Successfully updated leave status.";
    } else {
        echo "Failed to update leave status.";
    }
}