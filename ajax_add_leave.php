<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userCode = $_POST['userCode'];
    $userName = $_POST['userName'];
    $name = $_POST['name'];
    $telPhone = $_POST['telPhone'];
    $depart = $_POST['depart'];

    $leaveID = $_POST['leaveType']; // ประเภทการลา
    $leaveReason = $_POST['leaveReason']; // เหตุผลการลา

    // วันที่ + เวลาเริ่มต้นที่ลา
    $leaveDateStart = $_POST['startDate'];
    $leaveDateStart = date('Y-m-d', strtotime($leaveDateStart)); // แปลงรูปแบบวันที่เป็น YYYY-MM-DD
    $leaveTimeStart = $_POST['startTime'];

    // วันที่ + เวลาสิ้นสุดที่ลา
    $leaveDateEnd = $_POST['endDate'];
    $leaveDateEnd = date('Y-m-d', strtotime($leaveDateEnd)); // แปลงรูปแบบวันที่เป็น YYYY-MM-DD
    $leaveTimeEnd = $_POST['endTime'];

    // วันที่สร้างใบลา
    $createDatetime = date('Y-m-d H:i:s');

    $leaveStatus = 0; // 0 = สร้างใบลา
    $comfirmStatus = 0; // รอตรวจสอบ
    $proveStatus = 0;

    // ตรวจสอบสิทธิ์ก่อนอัปโหลดไฟล์
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['file']['name'];
        $location = "upload/" . $filename;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);

        // ตรวจสอบ extension ของไฟล์
        $valid_extensions = array("jpg", "jpeg", "png");
        if (in_array(strtolower($imageFileType), $valid_extensions)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                $response = $location;
                // $stmt = $conn->prepare("INSERT INTO tbl_uploads (img_file) VALUES (:filename)");
                // $stmt->bindParam(':filename', $filename);
                // $stmt->execute();
                // echo $response;
            }
        }
    }

    // ใช้ parameterized query เพื่อป้องกัน SQL injection
    $stmt = $conn->prepare("INSERT INTO leave_items (Emp_usercode, Emp_username, Emp_name, Emp_department, Emp_phone, Leave_ID, Leave_reason, Leave_date_start, Leave_time_start, Leave_date_end, Leave_time_end, Create_datetime,Img_file,Leave_status,Confirm_status,Approve_status)
    VALUES (:userCode, :userName, :name, :depart, :telPhone, :leaveID, :leaveReason, :leaveDateStart, :leaveTimeStart, :leaveDateEnd, :leaveTimeEnd, :createDatetime,:filename,:leaveStatus,:comfirmStatus,:proveStatus)");
    $stmt->bindParam(':userCode', $userCode);
    $stmt->bindParam(':userName', $userName);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':depart', $depart);
    $stmt->bindParam(':telPhone', $telPhone);
    $stmt->bindParam(':leaveID', $leaveID);
    $stmt->bindParam(':leaveReason', $leaveReason);
    $stmt->bindParam(':leaveDateStart', $leaveDateStart);
    $stmt->bindParam(':leaveTimeStart', $leaveTimeStart);
    $stmt->bindParam(':leaveDateEnd', $leaveDateEnd);
    $stmt->bindParam(':leaveTimeEnd', $leaveTimeEnd);
    $stmt->bindParam(':createDatetime', $createDatetime);
    $stmt->bindParam(':filename', $filename);
    $stmt->bindParam(':leaveStatus', $leaveStatus);
    $stmt->bindParam(':comfirmStatus', $comfirmStatus);
    $stmt->bindParam(':proveStatus', $proveStatus);

    if ($stmt->execute()) {
        // ส่งข้อความไลน์
        $stmt = $conn->prepare("SELECT Emp_token FROM employee WHERE Emp_usercode = '$userCode'");
        $stmt->bindParam(':userCode', $userCode);
        $stmt->execute();
        $sToken = $stmt->fetchColumn();
        $sURL = 'http://119.59.124.39/LMS/login.php';
        $sMessage = "There is a request for leave of $name. Please login to the system to view the details: $sURL";

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
        echo "Leave request saved successfully";

    } else {
        echo "Error: " . $sql . "<br>";
    }

    $conn = null;
}
