<?php
session_start();

require '../connect.php'; // Include database connection file
date_default_timezone_set('Asia/Bangkok'); // เวลาไทย

$leaveType = $_POST['leaveType'];
$name = $_POST['name'];
$depart = $_POST['depart'];
$leaveReason = $_POST['leaveReason'];
$userCode = $_POST['userCode'];
$createDate = $_POST['createDate'];
$leaveStartDate = $_POST['leaveStartDate'];
$leaveEndDate = $_POST['leaveEndDate'];
$checkFirm = $_POST['checkFirm'];
$userName = $_POST['userName'];

$firmDate = date("Y-m-d H:i:s");
$pass = '1';
$passNo = '2';

if ($checkFirm == '1') {
    // Update leave status
    $sql = "UPDATE leave_list SET l_hr_status = :pass, l_hr_datetime = :firmDate, l_hr_name = :userName
            WHERE l_usercode = :userCode AND l_create_datetime = :createDate";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pass', $pass);
    $stmt->bindParam(':firmDate', $firmDate);
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
        $sMessage = "HR ตรวจสอบใบลาของ $name ผ่านเรียบร้อย\nประเภทการลา : $leaveType\nเหตุผลการลา : $leaveReason\nวันเวลาที่ลา : $leaveStartDate ถึง $leaveEndDate\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด $sURL";

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

        // แจ้งเตือนผู้จัดการในแผนก
        $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_department = :depart AND e_level IN ('chief','manager')");
        $stmt->bindParam(':depart', $depart);
        $stmt->execute();
        $cmTokens = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($cmTokens as $sToken) {
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

        echo "อัปเดตสถานะผ่านสำเร็จ";
    } else {
        echo 'อัปเดตสถานะผ่านไม่สำเร็จ';
    }
} else if ($checkFirm == '2') {
    $sql = "UPDATE leave_list SET l_hr_status = :passNo, l_hr_datetime = :firmDate, l_hr_name = :userName
            WHERE l_usercode = :userCode AND l_create_datetime = :createDate";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':passNo', $passNo);
    $stmt->bindParam(':firmDate', $firmDate);
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
        $sMessage = "HR ตรวจสอบใบลาของ $name ไมผ่านเรียบร้อย\nประเภทการลา : $leaveType\nเหตุผลการลา : $leaveReason\nวันเวลาที่ลา : $leaveStartDate ถึง $leaveEndDate\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด $sURL";

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

        // แจ้งเตือนผู้จัดการในแผนก
        $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_department = :depart AND e_level IN ('chief','manager')");
        $stmt->bindParam(':depart', $depart);
        $stmt->execute();
        $cmTokens = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($cmTokens as $sToken) {
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

        echo "อัปเดตสถานะผ่านสำเร็จ";
    } else {
        echo 'อัปเดตสถานะผ่านไม่สำเร็จ';
    }

} else {
    echo "ไม่พบสถานะ";
}

$conn = null;