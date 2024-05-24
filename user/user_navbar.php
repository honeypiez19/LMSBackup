<?php
include '../connect.php';
if (isset($_SESSION['Emp_usercode'])) {
    $userCode = $_SESSION['Emp_usercode'];
    // $sql = "SELECT * FROM employee_session WHERE Emp_usercode ='$userCode'";
    $sql = "SELECT * FROM employee_session
        JOIN employee ON employee_session.Emp_usercode = employee.Emp_usercode
        WHERE employee_session.Emp_usercode ='$userCode'";
    $result = $conn->query($sql);
    $userName = "";
    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $userName = $row['Emp_username'];
        $name = $row['Emp_name'];
        $telPhone = $row['Emp_phone'];
        $depart = $row['Emp_department'];
    }
} else {
    $userName = "";
    $name = "";
    $telPhone = "";
    $depart = "";
}
// เมื่อมีการกดปุ่ม "ออกจากระบบ"
if (isset($_POST['logoutButton'])) {
    // บันทึกเวลา logout
    $userCode = $_SESSION['Emp_usercode'];
    $logoutTime = date('Y-m-d H:i:s'); // เวลาปัจจุบัน
    $sql = "UPDATE employee_session SET Logout_datetime = '$logoutTime' WHERE Emp_usercode ='$userCode'";
    $conn->query($sql);

    // ทำการลบ session ทั้งหมด
    session_unset();

    // ทำลาย session
    session_destroy();

    // กลับไปที่หน้า login.php
    header("Location: ../login.php");
    exit;
}
?>

<nav class="navbar navbar-expand-lg" style="background-color: #072ac8; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
  border: none;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="user_dashboard.php" style="color: white;">หน้าหลัก</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="user_leave.php" style="color: white;">การลา</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false" style="color: white;">
                        การลา
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="user_leave.php">สถิติการลา</a></li>
                        <li><a class="dropdown-item" href="user_history.php">ประวัติการลา</a></li>
                    </ul>
                </li>
            </ul>
            <form method="post">
                <ul class="nav justify-content-end">
                    <?php if (!empty($userName)): ?>
                    <li class="nav-item">
                        <label class="mt-2 mx-2" style="color: white;"><?php echo $userName; ?></label>
                    </li>
                    <?php endif;?>
                    <li class="nav-item">
                        <button type="submit" name="logoutButton" class="form-control btn btn-dark">ออกจากระบบ</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</nav>
<!-- <script src="../js/popper.min.js"></script> -->
<script src="../js/bootstrap.min.js"></script>
<script src="../js/bootstrap.bundle.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>