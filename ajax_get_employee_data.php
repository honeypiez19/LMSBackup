<?php
// Start session
session_start();

require 'connect.php'; // Include database connection file

// Check if usercode is set
if (isset($_POST['usercode'])) {
    $usercode = $_POST['usercode'];

    // Query to fetch employee data based on usercode
    $sql = "SELECT * FROM employee WHERE Emp_usercode = :usercode";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usercode', $usercode);
    $stmt->execute();

    // Check if data is found
    if ($stmt->rowCount() > 0) {
        // Fetch employee data
        $row = $stmt->fetch();

        // Output HTML content for modal body with employee information
        echo '<input class="form-control" type="hidden" id="edit_usercode" value="' . $row['Emp_usercode'] . '">';
        echo '<div class="row">
        <h5>ข้อมูลการเข้าระบบ</h5>
        <div class="col-6">
            <label for="codeLabel">รหัสพนักงาน</label>
            <input  class="form-control" type="text" id="edit_usercode" value="' . $row['Emp_usercode'] . '" disabled>
        </div>
        <div class="col-6">
            <label for="usernameLabel">ชื่อผู้ใช้</label>
            <input  class="form-control" type="text" id="edit_username" value="' . $row['Emp_username'] . '">
        </div>
        </div>';

        echo '<div class="mt-3 row">
        <div class="col-6">
        <label for="passwordLabel">รหัสผ่าน</label>
        <input  class="form-control" type="text" id="edit_password" value="' . $row['Emp_password'] . '">
        </div>
        </div>';

        echo '<div class="mt-3 row">';
        echo '<h5>ข้อมูลพนักงาน</h5>';
        echo ' <div class="col-3">
        <label for="nameLabel">ชื่อ - นามสกุล</label>
        <input  class="form-control" type="text" id="edit_name" value="' . $row['Emp_name'] . '">
        </div>';

        echo '<div class="col-3">';
        echo '<label for="levelLabel">แผนก</label>';
        $department_sql = "SELECT * FROM employee_department";
        $department_result = $conn->query($department_sql);
        if ($department_result->rowCount() > 0) {
            echo '<select class="form-control" id="edit_department" style="border-radius: 50px;">';
            while ($department_row = $department_result->fetch()) {
                echo '<option value="' . $department_row['Department_ID'] . '"';
                if ($row['Emp_department'] == $department_row['Department_ID']) {
                    echo ' selected';
                }
                echo '>' . $department_row['Department_key'] . '</option>';
            }
            echo '</select>';
        } else {
            echo 'No levels found';
        }
        echo '</div>';

        echo '<div class="col-3">
        <label for="yearexpLabel">อายุงาน</label>
        <input  class="form-control" type="text" id="edit_yearexp" value="' . $row['Emp_yearexp'] . '">
        </div>';

        echo '<div class="col-3">';
        echo '<label for="levelLabel">ระดับ</label>';
        $level_sql = "SELECT * FROM employee_level";
        $level_result = $conn->query($level_sql);
        if ($level_result->rowCount() > 0) {
            echo '<select class="form-control" id="edit_level" style="border-radius: 50px;">';
            while ($level_row = $level_result->fetch()) {
                echo '<option value="' . $level_row['Level_ID'] . '"';
                if ($row['Emp_level'] == $level_row['Level_ID']) {
                    echo 'selected';
                }
                echo '>' . $level_row['Level_category_key'] . '</option>';
            }
            echo '</select>';
        } else {
            echo 'No levels found';
        }
        echo '</div>';

        echo '<div class="mt-3 row">
        <div class="col-3">
        <label for="emailLabel">อีเมล</label>
        <input  class="form-control" type="text" id="edit_email" value="' . $row['Emp_email'] . '">
        </div>

        <div class="col-3">
        <label for="phoneLabel">เบอร์โทรศัพท์</label>
        <input  class="form-control" type="text" id="edit_phone" value="' . $row['Emp_phone'] . '">
        </div>
        </div>';

        echo '<div class="mt-3 row">
        <h5>จำนวนวันลาที่ได้รับ</h5>
        <div class="col-3">
        <label for="personalLabel">ลากิจได้รับค่าจ้าง</label>
        <input  class="form-control" type="text" id="edit_personal" value="' . $row['Leave_personal'] . '">
        </div>

        <div class="col-3">
        <label for="personalNoLabel">ลากิจไม่ได้รับค่าจ้าง</label>
        <input  class="form-control" type="text" id="edit_personal_no" value="' . $row['Leave_personal_no'] . '">
        </div>

        <div class="col-3">
        <label for="sickLabel">ลาป่วย</label>
        <input  class="form-control" type="text" id="edit_sick" value="' . $row['Leave_sick'] . '">
        </div>

        <div class="col-3">
        <label for="sickWorkLabel">ลาป่วยจากงาน</label>
        <input  class="form-control" type="text" id="edit_sick_work" value="' . $row['Leave_sick_work'] . '">
        </div>

        </div>';

        echo '<div class="mt-3 row">
        <div class="col-3">
        <label for="annualLabel">ลาพักร้อน</label>
        <input  class="form-control" type="text" id="edit_annual" value="' . $row['Leave_annual'] . '">
        </div>

        <div class="col-3">
        <label for="otherLabel">อื่น ๆ</label>
        <input  class="form-control" type="text" id="edit_other" value="' . $row['Other'] . '">
        </div>
        </div>';

    }
}