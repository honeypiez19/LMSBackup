<?php
// Include the database connection file
include 'connect.php';

$status = $_GET['status'];

// Prepare a SQL query to select leave data based on the status
if ($status == 'all') {
    $sql = "SELECT * FROM leave_items ORDER BY Create_datetime DESC";
} else if ($status == 0) {
    $sql = "SELECT * FROM leave_items WHERE Confirm_status = 0 ORDER BY Create_datetime DESC";
} else if ($status == 1) {
    $sql = "SELECT * FROM leave_items WHERE Confirm_status = 1 ORDER BY Create_datetime DESC";
} else if ($status == 2) {
    $sql = "SELECT * FROM leave_items WHERE Confirm_status = 2 ORDER BY Create_datetime DESC";
} else {

}

$stmt = $conn->prepare($sql);
if ($status != 0) {
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
}
$stmt->execute();

// Fetch the results as an associative array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Send the results as JSON
echo json_encode($results);