<?php
// Start session
// session_start();

require 'connect.php';
// date_default_timezone_set('Asia/Bangkok'); // เวลาไทย

// $updDate = date("Y-m-d H:i:s");

// $usercode = $_POST['usercode'];

// $sql = "UPDATE employee SET Emp_status = 0 WHERE Emp_usercode = $usercode";

// if ($conn->query($sql) === true) {
//     echo "ลบข้อมูลพนักงานเรียบร้อยแล้ว";
// } else {
//     echo "เกิดข้อผิดพลาดในการลบข้อมูล: ";
// }

$usercode = $_POST["usercode"];
$sql = "UPDATE employee SET Emp_status = 0 WHERE Emp_usercode = '$usercode'";
$result = $conn->query($sql);
echo "Success";
