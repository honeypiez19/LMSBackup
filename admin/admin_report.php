<?php
session_start();

require '../connect.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงาน</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="../logo/logo.png">
    <script src="https://kit.fontawesome.com/84c1327080.js" crossorigin="anonymous"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tcpdf/6.4.0/tcpdf.min.js"></script>


</head>

<body>
    <?php require 'admin_navbar.php'?>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fa-regular fa-file fa-2xl"></i>
                </div>
                <div class="col-auto">
                    <h3>รายงานของพนักงาน</h3>
                </div>
            </div>
        </div>
    </nav>

    <div class="mt-3 container">
        <div class="row">
            <div class="col-4">
                <label for="" class="form-label">รหัสพนักงาน</label>
                <input type="text" class="form-control" id="codeSearch" list="codeList">
                <datalist id="codeList">
                    <option value="All">All</option>
                    <?php
$sql = "SELECT DISTINCT * FROM employee";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo '<option value="' . $row['Emp_usercode'] . '">' . $row['Emp_name'] . '</option>';
}
?>
                </datalist>
            </div>
        </div>
        <div id="leaveReport" class="mt-3"></div>
        <input id="downloadPdfButton" type="submit">Download PDF</input>

    </div>
    <script>
    $(document).ready(function() {
        $('#codeSearch').on('input', function() {
            var emp_code = $(this).val();
            if (emp_code !== '') {
                $.ajax({
                    url: '../ajax_get_leavereport.php',
                    type: 'POST',
                    data: {
                        emp_code: emp_code
                    },
                    success: function(data) {
                        $('#leaveReport').html(data);
                    }
                });
            } else {
                $.ajax({
                    url: '../ajax_get_leavereport.php',
                    type: 'POST',
                    data: {
                        emp_code: ''
                    },
                    success: function(data) {
                        $('#leaveReport').html(data);
                    }
                });
            }
        });
        $('#downloadPdfButton').click('input', function() {
            var emp_code = $('#codeSearch').val();
            window.location.href = "testPDF2.php?emp_code=" + emp_code;
        });

    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>