<?php
require_once '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['userName'];
    $proveName = $_POST['proveName'];
    $createDateTime = $_POST['createDateTime'];
    $depart = $_POST['depart'];
    $lateDate = $_POST['lateDate'];
    $lateStart = $_POST['lateStart'];
    $lateEnd = $_POST['lateEnd'];
    $userCode = $_POST['userCode'];
    $name = $_POST['name'];
    $leaveStatus = $_POST['leaveStatus'];
    $action = $_POST['action'];
    $comfirmName = $_POST['comfirmName'];
    $level = $_POST['level'];

    $proveDate = date('Y-m-d H:i:s');

    if ($action === 'approve') {
        $status = 2;
        $message = "$proveName อนุมัติการมาสายของ";
    } elseif ($action === 'deny') {
        $status = 3;
        $message = "$proveName ไม่อนุมัติการมาสายของ";
    } elseif ($action === 'comfirm') {
        $status = 2;
        $message = "$comfirmName ยืนยันมาสาย";
    } else {
        echo 'เกิดข้อผิดพลาดในการร้องขอ';
        exit;
    }

    $sql = "UPDATE leave_list SET l_approve_status = :status,
    l_approve_datetime = :proveDate,
    l_approve_name = :userName
    WHERE l_usercode = :userCode AND l_create_datetime = :createDateTime";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':proveDate', $proveDate, PDO::PARAM_STR);
    $stmt->bindValue(':userCode', $userCode, PDO::PARAM_STR);
    $stmt->bindValue(':createDateTime', $createDateTime, PDO::PARAM_STR);
    $stmt->bindValue(':userName', $userName, PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($action === 'comfirm') {
            // ส่งการแจ้งเตือนถึงผู้จัดการ
            $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_sub_department = 'Office' AND e_level = 'manager'");
            $stmt->execute();
            $managerResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $managerToken = $managerResult['e_token'];

            $sURL = 'https://lms.system-samt.com/';
            $sMessage = "$message \nวันที่มาสาย : $lateDate\nเวลาที่มาสาย : $lateStart ถึง $lateEnd\nสถานะรายการ : $leaveStatus\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด: $sURL";

            // แจ้งเตือนผู้จัดการ
            $chOne = curl_init();
            curl_setopt_array($chOne, [
                CURLOPT_URL => "https://notify-api.line.me/api/notify",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => http_build_query(["message" => $sMessage]),
                CURLOPT_HTTPHEADER => ['Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $managerToken],
                CURLOPT_RETURNTRANSFER => 1,
            ]);
            $managerResult = curl_exec($chOne);
            if (curl_error($chOne)) {
                echo 'Error:' . curl_error($chOne);
            }
            curl_close($chOne);

            // แจ้งเตือนไลน์พนักงาน
            $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_usercode = :userCode");
            $stmt->bindParam(':userCode', $userCode, PDO::PARAM_STR);
            $stmt->execute();
            $employeeResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $employeeToken = $employeeResult['e_token'];

            $chTwo = curl_init();
            curl_setopt_array($chTwo, [
                CURLOPT_URL => "https://notify-api.line.me/api/notify",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => http_build_query(["message" => $sMessage]),
                CURLOPT_HTTPHEADER => ['Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $employeeToken],
                CURLOPT_RETURNTRANSFER => 1,
            ]);
            $employeeResult = curl_exec($chTwo);
            if (curl_error($chTwo)) {
                echo 'Error:' . curl_error($chTwo);
            }
            curl_close($chTwo);

            echo "อัปเดตสถานะสำเร็จ";
        } else {
            // แจ้งเตือนไลน์ผู้จัดการ
            $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_department = :depart AND e_level IN ('manager')");
            $stmt->bindParam(':depart', $depart, PDO::PARAM_STR);
            $stmt->execute();
            $managerResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $managerToken = $managerResult['e_token'];

            $sURL = 'https://lms.system-samt.com/';
            $sMessage = "$message $name \nวันที่มาสาย : $lateDate\nเวลาที่มาสาย : $lateStart ถึง $lateEnd\nสถานะรายการ : $leaveStatus\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด: $sURL";

            $chOne = curl_init();
            curl_setopt_array($chOne, [
                CURLOPT_URL => "https://notify-api.line.me/api/notify",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => http_build_query(["message" => $sMessage]),
                CURLOPT_HTTPHEADER => ['Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $managerToken],
                CURLOPT_RETURNTRANSFER => 1,
            ]);
            $managerResult = curl_exec($chOne);
            if (curl_error($chOne)) {
                echo 'Error:' . curl_error($chOne);
            }
            curl_close($chOne);

        }

    } else {
        echo 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
    }
} else {
    echo 'เกิดข้อผิดพลาดในการร้องขอ';
}