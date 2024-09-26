<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../fullcalendar/fullcalendar.min.css" />


    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="../js/jquery-3.7.1.min.js"></script>

    <style>
    body {
        font-size: 14px;
        font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    #calendar {
        width: 100%;
        max-width: 1000px;
        /* ปรับความกว้างสูงสุดของปฏิทิน */
        margin: 0 auto;
    }

    .back-button-container {
        text-align: left;
        margin: 10px;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    .back-button:hover {
        background-color: #0056b3;
    }

    .fc-daygrid-day.fc-day-sat {
        background-color: #E6E6FA;
        color: black;
    }

    .fc-daygrid-day.fc-day-sun {
        background-color: #FFE4E1;
        color: black;
    }

    .fc-button {
        min-width: 80px !important;
        height: 40px !important;
    }

    /* Media queries สำหรับหน้าจอขนาดเล็ก */
    @media screen and (max-width: 1200px) {
        #calendar {
            width: 90%;
        }
    }

    @media screen and (max-width: 768px) {
        body {
            font-size: 12px;
            /* ปรับขนาดฟอนต์ */
        }

        .back-button {
            padding: 8px 16px;
            font-size: 12px;
        }

        #calendar {
            width: 100%;
            margin: 0 10px;
        }

        .fc-toolbar {
            flex-wrap: wrap;
        }

        .fc-toolbar .fc-left,
        .fc-toolbar .fc-right {
            flex: 100%;
            text-align: center;
        }

        .fc-toolbar .fc-center {
            margin-top: 10px;
        }
    }

    @media screen and (max-width: 480px) {
        body {
            font-size: 10px;
            /* ปรับขนาดฟอนต์ */
        }

        .back-button {
            padding: 6px 12px;
            font-size: 10px;
        }

        #calendar {
            width: 100%;
            margin: 0;
        }

        .fc-toolbar {
            flex-direction: column;
        }

        .fc-toolbar .fc-left,
        .fc-toolbar .fc-right,
        .fc-toolbar .fc-center {
            flex: 100%;
            text-align: center;
            margin-bottom: 10px;
        }
    }
    </style>
</head>

<body>
    <!-- ปุ่มย้อนกลับไปยังหน้า Dashboard พร้อมจัดให้อยู่ทางซ้าย -->
    <div class="back-button-container">
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>

    <!-- <div class="response"></div> -->
    <div id='calendar'></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            dateClick: function(info) {
                var eventTitle = prompt('Enter Event Title:');
                if (eventTitle) {
                    // AJAX request to PHP to save event
                    $.ajax({
                        url: 'a_ajax_add_holiday.php',
                        method: 'POST',
                        data: {
                            eventTitle: eventTitle,
                            h_start_date: info.dateStr,
                            h_end_date: info.dateStr,
                            h_hr_name: 'admin' // Replace with dynamic HR name if needed
                        },
                        success: function(response) {
                            if (response.trim() === 'success') {
                                calendar.addEvent({
                                    title: eventTitle,
                                    start: info.dateStr,
                                    allDay: true
                                });
                                alert('Event added successfully');
                            } else {
                                alert('Failed to add event');
                            }
                        },
                        error: function() {
                            alert('Error communicating with server.');
                        }
                    });
                }
            },
            events: function(info, successCallback, failureCallback) {
                $.ajax({
                    url: 'a_ajax_get_holiday.php',
                    method: 'GET',
                    data: {
                        start: info.startStr, // Start date of the visible range
                        end: info.endStr // End date of the visible range
                    },
                    success: function(response) {
                        var events = JSON.parse(response);
                        // Format events for FullCalendar
                        var formattedEvents = events.map(function(event) {
                            return {
                                title: event.h_name,
                                start: event.h_start_date,
                                allDay: true
                            };
                        });
                        successCallback(formattedEvents);
                    },
                    error: function() {
                        failureCallback('Error communicating with server.');
                    }
                });
            },
            eventClick: function(info) {
                // แปลงวันที่ให้ถูกต้องตาม Timezone ของระบบ
                var startDate = info.event.start.toLocaleDateString('en-CA'); // รูปแบบ YYYY-MM-DD

                alert(startDate); // แสดงวันที่เพื่อยืนยันว่าถูกต้อง

                if (confirm('Do you want to delete this event?')) {
                    $.ajax({
                        url: 'a_ajax_delete_holiday.php',
                        method: 'POST',
                        data: {
                            start: startDate // ส่งวันที่ไปยัง PHP
                        },
                        success: function(response) {
                            if (response.trim() === 'success') {
                                info.event.remove(); // ลบเหตุการณ์จากปฏิทิน
                                alert('Event deleted successfully');
                            } else {
                                alert('Failed to delete event');
                            }
                        },
                        error: function() {
                            alert('Error communicating with server.');
                        }
                    });
                }
            }

        });
        calendar.render();
    });

    function addHolidays(year) {
        const holidays = [];

        // วันสงกรานต์
        const songkranStart = new Date(year, 3, 13); // 13 เมษายน
        const songkranEnd = new Date(year, 3, 15); // 15 เมษายน
        let currentDate = songkranStart;
        while (currentDate <= songkranEnd) {
            holidays.push({
                eventTitle: 'วันสงกรานต์',
                h_start_date: formatDate(currentDate),
                h_end_date: formatDate(currentDate),
                h_hr_name: 'admin'
            });
            currentDate.setDate(currentDate.getDate() + 1); // วันถัดไป
        }

        // หาวันอาทิตย์ทั้งหมดในปี
        let date = new Date(year, 0, 1); // เริ่มต้นที่วันที่ 1 มกราคม

        // หาวันอาทิตย์แรกของปี
        while (date.getDay() !== 0) {
            date.setDate(date.getDate() + 1);
        }

        // เพิ่มวันอาทิตย์ทั้งหมดในปีนั้น
        while (date.getFullYear() === year) {
            holidays.push({
                eventTitle: 'วันหยุดวันอาทิตย์',
                h_start_date: formatDate(date),
                h_end_date: formatDate(date),
                h_hr_name: 'admin',

            });
            date.setDate(date.getDate() + 7); // ไปที่วันอาทิตย์ถัดไป
        }

        // วันแรงงาน (1 พฤษภาคม)
        holidays.push({
            eventTitle: 'วันแรงงาน',
            h_start_date: `${year}-05-01`,
            h_end_date: `${year}-05-01`,
            h_hr_name: 'admin'
        });

        // 3 มิถุนายน Queen Suthida's Birthday
        holidays.push({
            eventTitle: "วันเฉลิมพระชนมพรรษา สมเด็จพระนางเจ้าฯ พระบรมราชินี",
            h_start_date: `${year}-06-03`,
            h_end_date: `${year}-06-03`,
            h_hr_name: 'admin'
        });

        // 29 กรกฎาคม King's Birthday
        holidays.push({
            eventTitle: "วันเฉลิมพระชนมพรรษา พระบาทสมเด็จพระเจ้าอยู่หัว",
            h_start_date: `${year}-07-29`,
            h_end_date: `${year}-07-29`,
            h_hr_name: 'admin'
        });

        // 12 สิงหาคม Queen Sirikit The Queen Mother's birthday
        holidays.push({
            eventTitle: "วันเฉลิมพระชนมพรรษา สมเด็จพระนางเจ้าสิริกิติ์ฯ",
            h_start_date: `${year}-08-12`,
            h_end_date: `${year}-08-12`,
            h_hr_name: 'admin'
        });

        // 13 ตุลาคม วันคล้ายวันสวรรคตในหลวงรัชกาลที่ 9
        holidays.push({
            eventTitle: "วันคล้ายวันสวรรคต ร.9",
            h_start_date: `${year}-10-13`,
            h_end_date: `${year}-10-13`,
            h_hr_name: 'admin'
        });

        // 5 ธันวาคม วันพ่อแห่งชาติ
        holidays.push({
            eventTitle: "วันพ่อแห่งชาติ",
            h_start_date: `${year}-12-05`,
            h_end_date: `${year}-12-05`,
            h_hr_name: 'admin'
        });

        // 31 ธันวาคม วันสิ้นปี
        holidays.push({
            eventTitle: "วันสิ้นปี",
            h_start_date: `${year}-12-31`,
            h_end_date: `${year}-12-31`,
            h_hr_name: 'admin'
        });

        // ส่งข้อมูลวันหยุดไปยังเซิร์ฟเวอร์
        $.ajax({
            url: 'a_ajax_add_holiday.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                holidays: holidays
            }),
            success: function(response) {
                console.log('All holidays added successfully');
            },
            error: function() {
                alert('Error communicating with server.');
            }
        });
    }

    // ฟังก์ชันในการแปลงวันเป็นรูปแบบ YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // เดือนเริ่มต้นที่ 0
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // เพิ่มวันหยุดสำหรับปีปัจจุบัน
    const year = new Date().getFullYear();
    addHolidays(year);

    // getSundaysOfYear(year);

    // console.log(sundays);
    </script>
</body>

</html>