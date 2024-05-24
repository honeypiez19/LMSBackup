<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convert HTML to PDF</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }
    </style>
</head>

<body>
    <!-- ตารางข้อมูลพนักงาน -->
    <div class="mt-3 container-fluid">
        <button onclick="capture()">Capture</button>
        <table class="table table-hover table-bordered" id="leaveEmpTable">
            <tr>
                <th rowspan="2">ลำดับ</th>
                <th colspan="2">ข้อมูลส่วนตัว</th>
                <th rowspan="2">แผนก</th>
            </tr>
            <tr>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
            </tr>
            <tr>
                <td>1</td>
                <td rowspan="2">John</td>
                <td>Doe</td>
                <td>Marketing</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane</td>
                <td>Smith</td>
                <td>Sales</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane</td>
                <td>Smith</td>
                <td>Sales</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane</td>
                <td>Smith</td>
                <td>Sales</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane</td>
                <td>Smith</td>
                <td>Sales</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane</td>
                <td>Smith</td>
                <td>Sales</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane</td>
                <td>Smith</td>
                <td>Sales</td>
            </tr>
        </table>

    </div>

    <script>
    async function capture() {
        const {
            jsPDF
        } = window.jspdf;
        const element = document.querySelector("#leaveEmpTable");

        const canvas = await html2canvas(element, {
            scale: 2, // Increase the scale for better quality
            useCORS: true, // Use CORS to allow cross-origin images
            scrollX: 0, // Prevent scrolling issues
            scrollY: 0
        });

        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();

        // Calculate the image dimensions to fit the PDF page size
        const imgProps = pdf.getImageProperties(imgData);
        const imgWidth = imgProps.width;
        const imgHeight = imgProps.height;

        const ratio = Math.min(pdfWidth / imgWidth, pdfHeight / imgHeight);
        const width = imgWidth * ratio;
        const height = imgHeight * ratio;

        const x = (pdfWidth - width) / 5;
        const y = (pdfHeight - height) / 5;

        pdf.addImage(imgData, 'PNG', x, y, width, height);
        pdf.save("capture.pdf");
    }
    </script>
</body>

</html>
