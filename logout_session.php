<?php
// Check if the user is logged in
if (isset($_SESSION['Emp_usercode'])) {
    // Logout button clicked
    if (isset($_POST['logoutButton'])) {
        // Employee's usercode
        $usercode = $_SESSION['Emp_usercode'];

        // Update Employee_session table with the current datetime
        $updateQuery = "UPDATE Employee_session SET Logout_datetime = NOW() WHERE Emp_usercode = '$usercode'";
        $conn->query($updateQuery);

        // Destroy the session
        session_destroy();

        // Redirect to the login page or wherever you want
        header("Location: ../login.php");
        exit();
    }
} else {
    // Redirect to login page or handle unauthorized access
    header("Location: ../login.php");
    exit();
}