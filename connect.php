<?php
// $servername = "localhost";
$servername = "27.254.134.24";
$username = "system_LMS";
$password = "samt1234";
$db = "system_LMS";
// $db = "LMS2";

$port = "system_LMS";
// Create connection
// $conn = new mysqli($servername, $username, $password, $db, $port);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$db",
        $username,
        $password
    );
    // เซ็ตค่าการเชื่อมต่อฐานข้อมูลให้เป็น UTF-8
    $conn->exec("SET NAMES utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}