<?php
// Start session
session_start();

require '../connect.php'; // Include database connection file

// Check if usercode is set
if (isset($_POST['usercode'])) {
    $usercode = $_POST['usercode'];

    // Query to fetch employee data based on usercode
    $sql = "SELECT * FROM employees WHERE e_usercode = :usercode";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usercode', $usercode);
    $stmt->execute();

    // Check if data is found
    if ($stmt->rowCount() > 0) {
        // Fetch employee data
        $row = $stmt->fetch();

        // Output HTML content for modal body with employee information
        echo '<input class="form-control" type="hidden" id="edit_usercode" value="' . $row['e_usercode'] . '">';
        echo '<div class="row">
        <h5>ข้อมูลการเข้าระบบ</h5>
        <div class="col-6">
            <label for="codeLabel">รหัสพนักงาน</label>
            <input  class="form-control" type="text" id="edit_usercode" value="' . $row['e_usercode'] . '" disabled>
        </div>
        <div class="col-6">
            <label for="usernameLabel">ชื่อผู้ใช้</label>
            <input  class="form-control" type="text" id="edit_username" value="' . $row['e_username'] . '">
        </div>
        </div>';

        echo '<div class="mt-3 row">
        <div class="col-6">
        <label for="passwordLabel">รหัสผ่าน</label>
        <input  class="form-control" type="text" id="edit_password" value="' . $row['e_password'] . '">
        </div>
        </div>';

        echo '<div class="mt-3 row">';
        echo '<h5>ข้อมูลพนักงาน</h5>';
        echo ' <div class="col-3">
        <label for="nameLabel">ชื่อ - นามสกุล</label>
        <input  class="form-control" type="text" id="edit_name" value="' . $row['e_name'] . '">
        </div>';

// Query เพื่อดึงข้อมูลแผนกของพนักงานที่เลือก
        $employee_sql = "SELECT employees.e_department, department.d_department
    FROM employees
    INNER JOIN department ON employees.e_department = department.d_department
    WHERE employees.e_usercode = :usercode";
        $stmt_employee = $conn->prepare($employee_sql);
        $stmt_employee->bindParam(':usercode', $usercode); // Bind ค่า usercode
        $stmt_employee->execute();
        $employee_data = $stmt_employee->fetch(); // ดึงข้อมูลแผนกของพนักงาน

// Query เพื่อดึงข้อมูลแผนกทั้งหมด
        $department_sql = "SELECT d_department FROM department";
        $stmt_department = $conn->prepare($department_sql);
        $stmt_department->execute();
        $departments = $stmt_department->fetchAll(); // ดึงข้อมูลแผนกทั้งหมด

// แสดงข้อมูลแผนก
        echo '<div class="col-3">';
        echo '<label for="levelLabel">แผนก</label>';
        echo '<select class="form-control" id="edit_department" style="border-radius: 20px;">';

// Loop ผ่านแผนกทั้งหมด
        foreach ($departments as $department) {
            echo '<option value="' . $department['d_department'] . '"';

            // ตรวจสอบว่าแผนกของพนักงานตรงกับแผนกไหน
            if ($employee_data['d_department'] == $department['d_department']) {
                echo ' selected'; // เลือกแผนกที่ตรงกับข้อมูลพนักงาน
            }

            echo '>' . $department['d_department'] . '</option>';
        }

        echo '</select>';
        echo '</div>';

        echo '<div class="col-3">
        <label for="yearexpLabel">อายุงาน</label>
        <input  class="form-control" type="text" id="edit_yearexp" value="' . $row['e_yearexp'] . '">
        </div>';

        // Query เพื่อดึงข้อมูลระดับของพนักงาน
        $employee_sql = "SELECT employees.e_level, level.l_level
        FROM employees
        INNER JOIN level ON employees.e_level = level.l_level
        WHERE employees.e_usercode = :usercode";
        $stmt_employee = $conn->prepare($employee_sql);
        $stmt_employee->bindParam(':usercode', $usercode); // Bind ค่า usercode
        $stmt_employee->execute();
        $employee_data = $stmt_employee->fetch(); // ดึงข้อมูลระดับของพนักงาน

// Query เพื่อดึงข้อมูลระดับทั้งหมด
        $level_sql = "SELECT l_id, l_level FROM level";
        $stmt_level = $conn->prepare($level_sql);
        $stmt_level->execute();
        $levels = $stmt_level->fetchAll(); // ดึงข้อมูลระดับทั้งหมด

// แสดงข้อมูลระดับ
        echo '<div class="col-3">';
        echo '<label for="levelLabel">ระดับ</label>';
        echo '<select class="form-control" id="edit_level" style="border-radius: 50px;">';

// Loop ผ่านระดับทั้งหมด
        foreach ($levels as $level) {
            echo '<option value="' . $level['l_level'] . '"';

// ตรวจสอบว่าระดับของพนักงานตรงกับระดับไหน
            if ($employee_data['e_level'] == $level['l_level']) {
                echo ' selected'; // เลือกระดับที่ตรงกับข้อมูลพนักงาน
            }

            echo '>' . $level['l_level'] . '</option>';
        }

        echo '</select>';
        echo '</div>';

        echo '<div class="mt-3 row">
        <div class="col-3">
        <label for="emailLabel">อีเมล</label>
        <input  class="form-control" type="text" id="edit_email" value="' . $row['e_email'] . '">
        </div>

        <div class="col-3">
        <label for="phoneLabel">เบอร์โทรศัพท์</label>
        <input  class="form-control" type="text" id="edit_phone" value="' . $row['e_phone'] . '">
        </div>';
        // echo '<input  class="form-control" type="text" id="edit_workplace" value="' . $row['e_workplace'] . '">';

        $employee_sql = "SELECT employees.e_workplace, workplace.w_name
        FROM employees
        INNER JOIN workplace ON employees.e_workplace = workplace.w_name
        WHERE employees.e_usercode = :usercode";
        $stmt_employee = $conn->prepare($employee_sql);
        $stmt_employee->bindParam(':usercode', $usercode); // Bind ค่า usercode
        $stmt_employee->execute();
        $employee_data = $stmt_employee->fetch(); // ดึงข้อมูลสถานที่ทำงานของพนักงาน

        // Query เพื่อดึงข้อมูลสถานที่ทำงานทั้งหมด
        $workplace_sql = "SELECT w_id, w_name FROM workplace";
        $stmt_workplace = $conn->prepare($workplace_sql);
        $stmt_workplace->execute();
        $workplaces = $stmt_workplace->fetchAll(); // ดึงข้อมูลสถานที่ทำงานทั้งหมด

        // แสดงข้อมูลสถานที่ทำงาน
        echo '<div class="col-3">';
        echo '<label for="workplaceLabel">สถานที่ทำงาน</label>';
        echo '<select class="form-control" id="edit_workplace" style="border-radius: 50px;">';

        // Loop ผ่านสถานที่ทำงานทั้งหมด
        foreach ($workplaces as $workplace) {
            echo '<option value="' . $workplace['w_name'] . '"';

            // ตรวจสอบว่าสถานที่ทำงานของพนักงานตรงกับรายการไหน
            if ($employee_data['e_workplace'] == $workplace['w_name']) {
                echo ' selected'; // เลือกรายการที่ตรงกับสถานที่ทำงานของพนักงาน
            }

            echo '>' . $workplace['w_name'] . '</option>';
        }

        echo '</select>';
        echo '</div>';

        echo '</div>';

        echo '<div class="mt-3 row">
        <h5>จำนวนวันลาที่ได้รับ</h5>
        <div class="col-3">
        <label for="personalLabel">ลากิจได้รับค่าจ้าง</label>
        <input  class="form-control" type="text" id="edit_personal" value="' . $row['e_leave_personal'] . '">
        </div>

        <div class="col-3">
        <label for="personalNoLabel">ลากิจไม่ได้รับค่าจ้าง</label>
        <input  class="form-control" type="text" id="edit_personal_no" value="' . $row['e_leave_personal_no'] . '">
        </div>

        <div class="col-3">
        <label for="sickLabel">ลาป่วย</label>
        <input  class="form-control" type="text" id="edit_sick" value="' . $row['e_leave_sick'] . '">
        </div>

        <div class="col-3">
        <label for="sickWorkLabel">ลาป่วยจากงาน</label>
        <input  class="form-control" type="text" id="edit_sick_work" value="' . $row['e_leave_sick_work'] . '">
        </div>

        </div>';

        echo '<div class="mt-3 row">
        <div class="col-3">
        <label for="annualLabel">ลาพักร้อน</label>
        <input  class="form-control" type="text" id="edit_annual" value="' . $row['e_leave_annual'] . '">
        </div>

        <div class="col-3">
        <label for="otherLabel">อื่น ๆ</label>
        <input  class="form-control" type="text" id="edit_other" value="' . $row['e_other'] . '">
        </div>
        </div>';

    }
}