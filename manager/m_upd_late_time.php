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

    $proveDate = date('Y-m-d H:i:s');

    if ($action === 'approve') {
        $status = 4;
        $message = "$proveName อนุมัติการมาสายของ";
    } elseif ($action === 'deny') {
        $status = 5;
        $message = "$proveName ไม่อนุมัติการมาสายของ";
    } elseif ($action === 'comfirm') {
        $status = 4;
        $message = "$comfirmName ยืนยันมาสาย";
    } else {
        echo 'เกิดข้อผิดพลาดในการร้องขอ';
        exit;
    }

    $sql = "UPDATE leave_list SET l_approve_status2 = :status,
            l_approve_datetime2 = :proveDate,
            l_approve_name2 = :userName
            WHERE l_usercode = :userCode AND l_create_datetime = :createDateTime";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':proveDate', $proveDate, PDO::PARAM_STR);
    $stmt->bindValue(':userCode', $userCode, PDO::PARAM_STR);
    $stmt->bindValue(':createDateTime', $createDateTime, PDO::PARAM_STR);
    $stmt->bindValue(':userName', $userName, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $sURL = 'https://lms.system-samt.com/';

        $sMessage = "$message $name\nวันที่มาสาย : $lateDate\nเวลาที่มาสาย : $lateStart ถึง $lateEnd\nสถานะรายการ : $leaveStatus\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด: $sURL";

        // แจ้งเตือน K. พรสุข
        $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_level = 'manager' AND e_sub_department = 'All'");
        $stmt->bindValue(':workplace', $workplace, PDO::PARAM_STR);
        $stmt->execute();
        $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($managers) {
            foreach ($managers as $manager) {
                $sToken = $manager['e_token'];
                $chOne = curl_init();
                curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
                curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($chOne, CURLOPT_POST, 1);
                curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
                $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken);
                curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($chOne);

                if (curl_error($chOne)) {
                    echo 'Error:' . curl_error($chOne);
                }
                curl_close($chOne);
            }
        } else {
            echo "No tokens found for manager";
        }
        // แจ้งเตือน พนง.
        $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_usercode = :userCode");
        $stmt->bindValue(':userCode', $userCode, PDO::PARAM_STR);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            $sToken = $employee['e_token'];
            $sMessage = "$message $name\nวันที่มาสาย : $lateDate\nเวลาที่มาสาย : $lateStart ถึง $lateEnd\nสถานะรายการ : $leaveStatus\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด: $sURL";
            $chOne = curl_init();
            curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
            curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($chOne, CURLOPT_POST, 1);
            curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
            $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken);
            curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($chOne);

            if (curl_error($chOne)) {
                echo 'Error:' . curl_error($chOne);
            }
            curl_close($chOne);
        } else {
            echo "ไม่พบ Token ของพนักงาน";
        }

        // แจ้งเตือน Admin
        $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_level = 'admin'");
        $stmt->execute();
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($admins) {
            foreach ($admins as $admin) {
                $sToken = $admin['e_token'];
                $chOne = curl_init();
                curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
                curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($chOne, CURLOPT_POST, 1);
                curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
                $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken);
                curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($chOne);

                if (curl_error($chOne)) {
                    echo 'Error:' . curl_error($chOne);
                }
                curl_close($chOne);
            }
        } else {
            echo "ไม่พบ Token ของแอดมิน";
        }
    } else {
        echo 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
    }
} else {
    echo 'เกิดข้อผิดพลาดในการร้องขอ';
}