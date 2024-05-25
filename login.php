<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" href="../logo/logo.png">

    <script src="js/jquery-3.7.1.min.js"></script>

    <style>
    body,
    html {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .container {
        flex: 1;
    }

    .login-card {
        max-width: 400px;
        padding: 2rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        width: 100%;
    }

    @media (max-width: 576px) {
        .login-card {
            padding: 1.5rem;
        }
    }

    footer {
        text-align: center;
        padding: 20px;
        background-color: #F5F5F5;
        color: #696969;
    }
    </style>
</head>

<body class="d-flex flex-column">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card login-card">
            <div class="card-body">
                <h5 class="card-title text-center">เข้าสู่ระบบ</h5>
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="usercode" class="form-label">Usercode</label>
                        <input type="text" class="form-control" id="usercode" name="usercode">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div id="message" class="mt-3"></div>
            </div>
        </div>
    </div>
    <footer>
        <label>© Shippo Asahi Moulds (Thailand) Co., Ltd. All Rights Reserved.
        </label>
    </footer>

    <!--
    <form id="loginForm">
        <input type="text" id="usercode" name="usercode" placeholder="Usercode"><br>
        <input type="password" id="password" name="password" placeholder="Password"><br>
        <button type="submit">Login</button>
    </form> -->

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
                    if (response == "admin") {
                        alert("Welcome Admin");
                        window.location.href = "admin/admin_dashboard.php";
                    } else if (response == "user") {
                        alert("Welcome User");
                        window.location.href = "user/user_dashboard.php";
                    } else {
                        alert("รหัสผู้ใช้หรือรหัสผ่านไม่ถูกต้อง");
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
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
