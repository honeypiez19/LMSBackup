<?php
// Start session
session_start();

require '../connect.php'; // Include database connection file
date_default_timezone_set('Asia/Bangkok'); // เวลาไทย

$appDate = date("Y-m-d H:i:s");

// รับค่าที่ส่งมาจาก AJAX
$userCode = $_POST['userCode'];
$createDate = $_POST['createDate'];
$status = $_POST['status'];
$userName = $_POST['userName'];
$proveName = $_POST['proveName'];
$leaveType = $_POST['leaveType'];
$leaveReason = $_POST['leaveReason'];
$leaveStartDate = $_POST['leaveStartDate'];
$leaveEndDate = $_POST['leaveEndDate'];
$depart = $_POST['depart'];

if ($status == '4') {
    // เตรียมคำสั่ง SQL
    $sql = "UPDATE leave_list SET l_approve_status2 = :status, l_approve_datetime2 = :appDate, l_approve_name2 = :userName
            WHERE l_usercode = :userCode AND l_create_datetime = :createDate";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':appDate', $appDate);
    $stmt->bindParam(':userName', $userName);
    $stmt->bindParam(':userCode', $userCode);
    $stmt->bindParam(':createDate', $createDate);

    if ($stmt->execute()) {
        // แจ้งเตือนพนักงาน
        $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_usercode = :usercode");
        $stmt->bindParam(':usercode', $userCode);
        $stmt->execute();
        $sToken = $stmt->fetchColumn();
        $sURL = 'https://lms.system-samt.com/';
        $sMessage = "$proveName อนุมัติใบลา \nประเภทการลา : $leaveType\nเหตุผลการลา : $leaveReason\nวันเวลาที่ลา : $leaveStartDate ถึง $leaveEndDate\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด $sURL";

        if ($sToken) {
            $chOne = curl_init();
            curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
            curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($chOne, CURLOPT_POST, 1);
            curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . urlencode($sMessage));
            $headers = array(
                'Content-type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . $sToken,
            );
            curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($chOne);

            if (curl_error($chOne)) {
                echo 'Error:' . curl_error($chOne);
            } else {
                $result_ = json_decode($result, true);
                echo "status : " . $result_['status'] . "\n";
                echo "message : " . $result_['message'] . "\n";
            }
            curl_close($chOne);
        }

        // แจ้งเตือน HR
        // $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_level = 'admin'");
        // $stmt->execute();
        // $adminTokens = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // foreach ($adminTokens as $sToken) {
        //     $chOne = curl_init();
        //     curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        //     curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
        //     curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
        //     curl_setopt($chOne, CURLOPT_POST, 1);
        //     curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . urlencode($sMessage));
        //     $headers = array(
        //         'Content-type: application/x-www-form-urlencoded',
        //         'Authorization: Bearer ' . $sToken,
        //     );
        //     curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
        //     curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
        //     $result = curl_exec($chOne);

        //     if (curl_error($chOne)) {
        //         echo 'Error:' . curl_error($chOne);
        //     } else {
        //         $result_ = json_decode($result, true);
        //         echo "status : " . $result_['status'] . "\n";
        //         echo "message : " . $result_['message'] . "\n";
        //     }
        //     curl_close($chOne);
        // }

        echo "อัปเดตสถานะผ่านสำเร็จ";
    } else {
        echo 'อัปเดตสถานะผ่านไม่สำเร็จ';
    }
} else if ($status == '5') {
    $sql = "UPDATE leave_list SET l_approve_status2 = :status, l_approve_datetime2 = :appDate, l_approve_name2 = :userName
            WHERE l_usercode = :userCode AND l_create_datetime = :createDate";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':appDate', $appDate);
    $stmt->bindParam(':userName', $userName);
    $stmt->bindParam(':userCode', $userCode);
    $stmt->bindParam(':createDate', $createDate);

    if ($stmt->execute()) {
        // ส่งข้อความไลน์
        $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_usercode = :usercode");
        // $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_department = :depart AND e_usercode = :usercode AND e_level IN ('user')");
        // $stmt->bindParam(':depart', $depart);
        $stmt->bindParam(':usercode', $userCode);
        $stmt->execute();
        $sToken = $stmt->fetchColumn();
        $sURL = 'http://192.168.100.122/LMS/login.php';
        $sMessage = "$proveName ไม่อนุมัติใบลา \nประเภทการลา : $leaveType\nเหตุผลการลา : $leaveReason\nวันเวลาที่ลา : $leaveStartDate ถึง $leaveEndDate\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด $sURL";
        // $ssMessage = "ไม่ผ่าน";

        $chOne = curl_init();
        curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($chOne, CURLOPT_POST, 1);
        curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
        $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken . '');
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($chOne);

        // เช็คการส่งข้อความไลน์
        if (curl_error($chOne)) {
            echo 'Error:' . curl_error($chOne);
        } else {
            $result_ = json_decode($result, true);
            echo "status : " . $result_['status'];
            echo "message : " . $result_['message'];
        }
        curl_close($chOne);
        echo "อัปเดตสถานะผ่านสำเร็จ";
    } else {
        echo 'อัปเดตสถานะผ่านไม่สำเร็จ';
    }
} else {
    echo "ไม่มีสถานะนี้";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn = null;