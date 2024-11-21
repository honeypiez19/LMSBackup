<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

require '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userCode = $_POST['userCode'];
    $userName = $_POST['userName'];
    $name = $_POST['name'];
    $telPhone = $_POST['telPhone'];
    $depart = $_POST['depart'];
    $level = $_POST['level'];
    $workplace = $_POST['workplace'];

    $leaveType = $_POST['leaveType'];
    $leaveReason = $_POST['leaveReason'];

    // ตรวจสอบประเภทการลา
    $leaveTypes = [
        1 => 'ลากิจได้รับค่าจ้าง',
        2 => 'ลากิจไม่ได้รับค่าจ้าง',
        3 => 'ลาป่วย',
        4 => 'ลาป่วยจากงาน',
        5 => 'ลาพักร้อน',
        8 => 'อื่น ๆ',
    ];
    $leaveName = $leaveTypes[$leaveType] ?? 'ไม่พบประเภทการลา';

    // วันที่ + เวลาเริ่มต้นที่ลา
    $leaveDateStart = date('Y-m-d', strtotime($_POST['startDate']));
    $leaveTimeStart = $_POST['startTime'];

    // วันที่ + เวลาสิ้นสุดที่ลา
    $leaveDateEnd = date('Y-m-d', strtotime($_POST['endDate']));
    $leaveTimeEnd = $_POST['endTime'];

    // 08:45
    if($leaveTimeStart == '08:45'){
        $leaveTimeStartLine = '08:45';
        $leaveTimeStart = '09:00';
        $remark = '08:45:00';
    } 
    // 09:45
    else if($leaveTimeStart == '09:45'){
        $leaveTimeStartLine = '09:45';
        $leaveTimeStart = '10:00';
        $remark = '09:45:00';
    }  
    // 10:45
    else if($leaveTimeStart == '10:45'){
        $leaveTimeStartLine = '10:45';
        $leaveTimeStart = '11:00';
        $remark = '10:45:00';
    } 
    // 11:45
    else if($leaveTimeStart == '12:00'){
        $leaveTimeStartLine = '11:45';
    } 
    // 12:45
    else if($leaveTimeStart == '13:00'){
        $leaveTimeStartLine = '12:45';
    } 
    // 13:10
    else if($leaveTimeStart == '13:10'){
        $leaveTimeStartLine = '13:10';
        $leaveTimeStart = '13:30';
        $remark = '13:10:00';
    } 
    // 13:40
    else if($leaveTimeStart == '13:40'){
        $leaveTimeStartLine = '13:40';
        $leaveTimeStart = '14:00';
        $remark = '13:40:00';
    } 
    // 14:10
    else if($leaveTimeStart == '14:10'){
        $leaveTimeStartLine = '14:10';
        $leaveTimeStart = '14:30';
        $remark = '14:10:00';
    } 
    // 14:40
    else if($leaveTimeStart == '14:40'){
        $leaveTimeStartLine = '14:40';
        $leaveTimeStart = '15:00';
        $remark = '14:40:00';
    } 
    // 15:10
    else if($leaveTimeStart == '15:10'){
        $leaveTimeStartLine = '15:10';
        $leaveTimeStart = '15:30';
        $remark = '15:10:00';
    } 
    // 15:40
    else if($leaveTimeStart == '15:40'){
        $leaveTimeStartLine = '15:40';
        $leaveTimeStart = '16:00';
        $remark = '15:40:00';
    } 
    // 16:10
    else if($leaveTimeStart == '16:10'){
        $leaveTimeStartLine = '16:10';
        $leaveTimeStart = '16:30';
        $remark = '16:10:00';
    } 
    // 16:40
    else if($leaveTimeStart == '17:00'){
        $leaveTimeStartLine = '16:40';
    } 
    else{
        $leaveTimeStartLine = $leaveTimeStart;
    }

    // 08:45
    if($leaveTimeEnd == '08:45'){
        $leaveTimeEndLine = '08:45';
        $leaveTimeEnd = '09:00';
        $remark = '08:45:00';
    } 
    // 09:45
    else if($leaveTimeEnd == '09:45'){
        $leaveTimeEndLine = '09:45';
        $leaveTimeEnd = '10:00';
        $remark = '09:45:00';
    }  
    // 10:45
    else if($leaveTimeEnd == '10:45'){
        $leaveTimeEndLine = '10:45';
        $leaveTimeEnd = '11:00';
        $remark = '10:45:00';
    } 
    // 11:45
    else if($leaveTimeEnd == '12:00'){
        $leaveTimeEndLine = '11:45';
    } 
    // 12:45
    else if($leaveTimeEnd == '13:00'){
        $leaveTimeEndLine = '12:45';
    } 
    // 13:10
    else if($leaveTimeEnd == '13:10'){
        $leaveTimeEndLine = '13:10';
        $leaveTimeEnd = '13:30';
        $remark = '13:10:00';
    } 
    // 13:40
    else if($leaveTimeEnd == '13:40'){
        $leaveTimeEndLine = '13:40';
        $leaveTimeEnd = '14:00';
        $remark = '13:40:00';
    } 
    // 14:10
    else if($leaveTimeEnd == '14:10'){
        $leaveTimeEndLine = '14:10';
        $leaveTimeEnd = '14:30';
        $remark = '14:10:00';
    } 
    // 14:40
    else if($leaveTimeEnd == '14:40'){
        $leaveTimeEndLine = '14:40';
        $leaveTimeEnd = '15:00';
        $remark = '14:40:00';
    } 
    // 15:10
    else if($leaveTimeEnd == '15:10'){
        $leaveTimeEndLine = '15:10';
        $leaveTimeEnd = '15:30';
        $remark = '15:10:00';
    } 
    // 15:40
    else if($leaveTimeEnd == '15:40'){
        $leaveTimeEndLine = '15:40';
        $leaveTimeEnd = '16:00';
        $remark = '15:40:00';
    } 
    // 16:10
    else if($leaveTimeEnd == '16:10'){
        $leaveTimeEndLine = '16:10';
        $leaveTimeEnd = '16:30';
        $remark = '16:10:00';
    } 
    // 16:40
    else if($leaveTimeEnd == '17:00'){
        $leaveTimeEndLine = '16:40';
    } 
    else{
        $leaveTimeEndLine = $leaveTimeEnd;
    }

    // if($leaveTimeEnd == '12:00'){
    //     $leaveTimeEndLine = '11:45';
    // } else if($leaveTimeEnd == '13:00'){
    //     $leaveTimeEndLine = '12:45';
    // } else if($leaveTimeEnd == '17:00'){
    //     $leaveTimeEndLine = '16:40';
    // }else{
    //     $leaveTimeEndLine = $leaveTimeEnd;
    // }
    
    // วันที่สร้างใบลา
    $formattedDate = $_POST['formattedDate'];
    // $formattedDate = date('Y-m-d', strtotime($_POST['formattedDate']));
  
    // สถานะใบลา
    $leaveStatus = 0;
    $leaveStatusName = ($leaveStatus == 0) ? 'ปกติ' : 'ยกเลิก';

    $comfirmStatus = 0;

    $subDepart = $_POST['subDepart'];
    $subDepart2 = $_POST['subDepart2'];
    $subDepart3 = $_POST['subDepart3'];
    $subDepart4 = $_POST['subDepart4'];
    $subDepart5 = $_POST['subDepart5'];

    $filename = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['file']['name'];
        $location = "../upload/" . $filename;
        $imageFileType = strtolower(pathinfo($location, PATHINFO_EXTENSION));

        $valid_extensions = ["jpg", "jpeg", "png"];
        if (in_array($imageFileType, $valid_extensions)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                $response = $location;
            }
        }
    }

    if($subDepart == ''){
        $proveStatus = 6;
        $proveStatus2 = 1;
    } else {
        $proveStatus = 0;
        $proveStatus2 = 1;
    }
        $stmt = $conn->prepare("INSERT INTO leave_list (l_usercode, l_username, l_name, l_department, l_phone, l_leave_id, l_leave_reason,
        l_leave_start_date, l_leave_start_time, l_leave_end_date, l_leave_end_time, l_create_datetime, l_file, l_leave_status, 
        l_hr_status, l_approve_status, l_level, l_approve_status2, l_workplace, l_remark)
        VALUES (:userCode, :userName, :name, :depart, :telPhone, :leaveType, :leaveReason, :leaveDateStart, :leaveTimeStart,
        :leaveDateEnd, :leaveTimeEnd, :formattedDate, :filename, :leaveStatus, :comfirmStatus, :proveStatus, :level, :proveStatus2, :workplace, :remark)");
    
        $stmt->bindParam(':userCode', $userCode);
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':depart', $depart);
        $stmt->bindParam(':telPhone', $telPhone);
        $stmt->bindParam(':leaveType', $leaveType);
        $stmt->bindParam(':leaveReason', $leaveReason);
        $stmt->bindParam(':leaveDateStart', $leaveDateStart);
        $stmt->bindParam(':leaveTimeStart', $leaveTimeStart);
        $stmt->bindParam(':leaveDateEnd', $leaveDateEnd);
        $stmt->bindParam(':leaveTimeEnd', $leaveTimeEnd);
        $stmt->bindParam(':formattedDate', $formattedDate);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':leaveStatus', $leaveStatus);
        $stmt->bindParam(':comfirmStatus', $comfirmStatus);
        $stmt->bindParam(':proveStatus', $proveStatus);
        $stmt->bindParam(':proveStatus2', $proveStatus2);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':workplace', $workplace);
        $stmt->bindParam(':remark', $remark);

    if ($stmt->execute()) {
        $sURL = 'https://lms.system-samt.com/';
        $sMessage = "มีใบลาของ $name \nประเภทการลา : $leaveName\nเหตุผลการลา : $leaveReason\nวันเวลาที่ลา : $leaveDateStart $leaveTimeStartLine ถึง $leaveDateEnd $leaveTimeEndLine\nสถานะใบลา : $leaveStatusName\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด : $sURL";
        // $sMessage = $depart;

        if ($depart == 'RD') {
            // แจ้งไลน์โฮซัง
            $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'leader' AND e_sub_department =  'RD'");
            // $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE e_department = 'Management' AND e_workplace = :workplace AND e_level = 'manager' AND e_sub_department = :depart");
            // $stmt = $conn->prepare("SELECT e_username, e_token FROM employees WHERE e_level = 'manager' AND e_workplace = 'Bang Phli' AND e_sub_department = 'RD'");
            $stmt->bindParam(':workplace', $workplace);
            // $stmt->bindParam(':depart', $depart);

        } else if ($depart == 'Office') {
            // บัญชี
            if ($subDepart == 'AC') {
                // แจ้งเตือนพี่แวว
                // $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'chief' AND e_sub_department = :subDepart");
                $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'chief' AND e_sub_department = 'AC'");
                $stmt->bindParam(':workplace', $workplace);
                // $stmt->bindParam(':subDepart', $subDepart);
            }
            // เซลล์
            else if ($subDepart == 'Sales') {
                // แจ้งเตือนพี่เจี๊ยบหรือพี่อ้อม
                // $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE e_workplace = :workplace AND e_level = 'chief' AND e_sub_department = :subDepart");
                $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE e_workplace = :workplace AND e_level = 'chief' AND e_sub_department = 'Sales'");
                $stmt->bindParam(':workplace', $workplace);
                // $stmt->bindParam(':subDepart', $subDepart);
            }
            // สโตร์
            else if ($subDepart == 'Store') {
                // แจ้งเตือนพี่เก๋
                $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'leader' AND e_sub_department = 'Store'");
                $stmt->bindParam(':workplace', $workplace);
                // $stmt->bindParam(':subDepart', $subDepart);
            }
            // HR
            else if ($subDepart == 'All') {
                $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'manager' AND e_sub_department = 'Office'");
                $stmt->bindParam(':workplace', $workplace);
                // $stmt->bindParam(':subDepart', $subDepart);
            }
            // พี่เต๋ / พี่น้อย / พี่ไว
            else if ($subDepart == '') {
                $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'manager' AND e_sub_department = 'Office'");
                $stmt->bindParam(':workplace', $workplace);
                // $stmt->bindParam(':subDepart', $subDepart);

            }
        } else if ($depart == 'CAD1') {
            if($subDepart == 'Modeling'){
                 $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'leader' AND e_sub_department = 'Modeling'");
                $stmt->bindParam(':workplace', $workplace);
            }
            else if($subDepart == 'Design'){
                $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'leader' AND e_sub_department = 'Design'");
                $stmt->bindParam(':workplace', $workplace); 
            }
            else {
                $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'leader' AND e_sub_department = 'Design'");
                $stmt->bindParam(':workplace', $workplace); 
            }
        } else if ($depart == 'CAD2') {
            $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'leader' AND e_sub_department = 'CAD2'");
            $stmt->bindParam(':workplace', $workplace);

        } else if ($depart == 'CAM') {
            $stmt = $conn->prepare("SELECT e_token, e_username FROM employees WHERE  e_workplace = :workplace AND e_level = 'leader' AND e_sub_department = 'CAM'");
            $stmt->bindParam(':workplace', $workplace);

        } else {
            echo "ไม่พบเงื่อนไข";
        }

        $stmt->execute();
        $tokens = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($tokens) {
            foreach ($tokens as $sToken) {
                $chOne = curl_init();
                curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
                curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($chOne, CURLOPT_POST, 1);
                curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
                $headers = [
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Bearer ' . $sToken,
                ];
                curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($chOne);

                if (curl_error($chOne)) {
                    echo 'Error:' . curl_error($chOne);
                } else {
                    $result_ = json_decode($result, true);
                    echo "status : " . $result_['status'];
                    echo "message : " . $result_['message'];
                }

                curl_close($chOne);
            }
        } else {
            echo "No tokens found for chief or manager";
        }

        // แจ้งเตือนไลน์ HR
        // $stmt = $conn->prepare("SELECT e_token FROM employees WHERE e_level = 'admin'");
        // $stmt->execute();
        // $admins = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // $aMessage = "มีใบลาของ $name \nประเภทการลา : $leaveName\nเหตุผลการลา : $leaveReason\nวันเวลาที่ลา : $leaveDateStart $leaveTimeStart ถึง $leaveDateEnd $leaveTimeEnd\nสถานะใบลา : $leaveStatusName\nกรุณาเข้าสู่ระบบเพื่อดูรายละเอียด : $sURL";
        // if ($admins) {
        //     foreach ($admins as $sToken) {
        //         $chOne = curl_init();
        //         curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        //         curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
        //         curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
        //         curl_setopt($chOne, CURLOPT_POST, 1);
        //         curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $aMessage);
        //         $headers = [
        //             'Content-type: application/x-www-form-urlencoded',
        //             'Authorization: Bearer ' . $sToken,
        //         ];
        //         curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
        //         curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
        //         $result = curl_exec($chOne);

        //         if (curl_error($chOne)) {
        //             echo 'Error:' . curl_error($chOne);
        //         } else {
        //             $result_ = json_decode($result, true);
        //             echo "status : " . $result_['status'];
        //             echo "message : " . $result_['message'];
        //         }

        //         curl_close($chOne);
        //     }
        // } else {
        //     echo "No tokens found for admin";
        // }
    } else {
        echo "Error: " . $stmt->errorInfo()[2] . "<br>";
    }

    $conn = null;
}