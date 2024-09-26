<?php
function removeCharacterAndFollowingTextFromFile($filename, $lineNumber, $characterToRemove)
{
    // อ่านเนื้อหาของไฟล์
    $fileContent = file($filename, FILE_IGNORE_NEW_LINES);

    // ตรวจสอบว่าบรรทัดที่ 4 มีอยู่ในไฟล์
    if (isset($fileContent[$lineNumber - 1])) {
        // ลบตัวอักษรและข้อความที่ติดมาหลังจากนั้น
        $fileContent[$lineNumber - 1] = preg_replace("/$characterToRemove.*/", '', $fileContent[$lineNumber - 1]);

        // เขียนเนื้อหากลับไปยังไฟล์
        file_put_contents($filename, implode(PHP_EOL, $fileContent));
    } else {
        echo "ไม่พบบรรทัดที่ $lineNumber ในไฟล์";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีไฟล์ถูกอัปโหลด
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // กำหนดตัวแปรสำหรับการอัปโหลดไฟล์
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);

        // ตรวจสอบและสร้างไดเรกทอรีถ้าไม่มีอยู่
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // ย้ายไฟล์ที่อัปโหลดไปยังตำแหน่งที่กำหนด
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            echo "ไฟล์ถูกอัปโหลดสำเร็จ: " . $uploadFile . "<br>";

            // ลบตัวอักษร 'S' และข้อความที่ติดมาจากบรรทัดที่ 4
            removeCharacterAndFollowingTextFromFile($uploadFile, 4, 'S');
            echo "ตัวอักษร 'S' และข้อความที่ติดมาถูกลบจากบรรทัดที่ 4 ในไฟล์ " . basename($_FILES['file']['name']);

            // ส่งไฟล์กลับไปยังผู้ใช้เพื่อดาวน์โหลด
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($uploadFile) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($uploadFile));
            readfile($uploadFile);
            exit;
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }
    } else {
        echo "กรุณาเลือกไฟล์เพื่ออัปโหลด";
    }
}