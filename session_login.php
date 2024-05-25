<?php
// session_start();
if (isset($_SESSION['Emp_usercode'])) {
    $usercode = $_SESSION['Emp_usercode'];
    // คำสั่ง SQL เพื่อดึง Emp_username จากฐานข้อมูล
    $sql = "SELECT * FROM employee_session WHERE Emp_usercode ='$usercode'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $username = $row['Emp_username'];
    $name = $row['Emp_name'];

    // echo "Welcome, $username";
} else {
    // echo "Welcome, Guest";
}