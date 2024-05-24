<?php
// เชื่อมต่อฐานข้อมูล
include_once 'connect.php';
date_default_timezone_set('Asia/Bangkok');

$leaveID = $_POST['leaveId'];
$createDatetime = $_POST['createDatetime'];
$usercode = $_POST['usercode'];
$name = $_POST['name'];

$canDatetime = date('Y-m-d H:i:s');

// คืนจำนวนวันลา
$sqlReturn = "UPDATE leave_items SET Leave_status = 1, Cancel_datetime = :canDatetime, Approve_status = 0 WHERE Leave_ID = :leaveID AND Create_datetime = :createDatetime";
$stmtReturn = $conn->prepare($sqlReturn);
$stmtReturn->bindParam(':leaveID', $leaveID);
$stmtReturn->bindParam(':createDatetime', $createDatetime);
$stmtReturn->bindParam(':canDatetime', $canDatetime);

if ($stmtReturn->execute()) {
    // ส่งข้อความไลน์
    $stmt = $conn->prepare("SELECT Emp_token FROM employee WHERE Emp_usercode = '$usercode'");
    $stmt->bindParam(':usercode', $usercode);
    $stmt->execute();
    $sToken = $stmt->fetchColumn();
    $sURL = 'http://119.59.124.39/LMS/login.php';
    $sMessage = "$name leave was cancelled when $canDatetime";

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
    echo "ยกเลิกใบลาสำเร็จ";
} else {
    echo "Error";

}
