<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="../logo/logo.png">

    <script src="js/jquery-3.7.1.min.js"></script>
</head>

<body>
    <form id="loginForm">
        <input type="text" id="usercode" name="usercode" placeholder="Usercode"><br>
        <input type="password" id="password" name="password" placeholder="Password"><br>
        <button type="submit">Login</button>
    </form>

    <div id="message"></div>

    <script>
    $(document).ready(function() {
        $("#loginForm").submit(function(e) {
            e.preventDefault(); // หยุดการ submit form ปกติ
            var userCode = $("#usercode").val(); // รับค่า usercode จากฟอร์ม
            var passWord = $("#password").val(); // รับค่า password จากฟอร์ม
            // ทำการส่งข้อมูลด้วย AJAX
            $.ajax({
                type: "POST",
                url: "ajax_login.php", // ไฟล์ PHP ที่ใช้ในการตรวจสอบ login
                data: {
                    userCode: userCode,
                    passWord: passWord
                },
                success: function(response) {
                    // แสดงข้อความตามสถานะที่ได้รับ
                    if (response == "admin") {
                        alert("Welcome Admin");
                        window.location.href = "admin/admin_dashboard.php";
                    } else if (response == "user") {
                        alert("Welcome User");
                        window.location.href = "user/user_dashboard.php";
                    } else {
                        alert("Invalid Usercode or Password");
                    }
                },
                error: function(xhr, status, error) {
                    alert(
                        "An error occurred while processing your request. Please try again later."
                    );
                }
            });
        });
    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>
