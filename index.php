<?php
include 'includes/config.php';
date_default_timezone_set('Asia/Bangkok');

// 1. คำนวณเวลาเส้นตายที่ 1 ชั่วโมง (60 นาที)
$time_threshold = date('Y-m-d H:i:s', strtotime('-60 minutes'));

// 2. ค้นหาการจองที่เกิน 1 ชั่วโมง
$check_expired = "SELECT table_id FROM bookings WHERE booking_time <= '$time_threshold'";
$expired_result = mysqli_query($conn, $check_expired);

if (mysqli_num_rows($expired_result) > 0) {
    while ($expired_row = mysqli_fetch_assoc($expired_result)) {
        $t_id = $expired_row['table_id'];
        // ปรับสถานะโต๊ะกลับเป็นว่าง และลบข้อมูลการจอง
        mysqli_query($conn, "UPDATE tables SET status = 'available' WHERE id = '$t_id'");
        mysqli_query($conn, "DELETE FROM bookings WHERE table_id = '$t_id'");
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจองโต๊ะอาหาร | Goodfood</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            /* กำหนดโทนสีและฟอนต์ */
            --main-font: 'Prompt', sans-serif;
            --thai-font: 'Sarabun', sans-serif;
            --accent-color: #ff9f43; /* สีส้มสำหรับปุ่ม admin */
        }

        body {
            font-family: var(--main-font);
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/io.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #ffffff;
            margin: 0;
            padding-bottom: 50px;
        }

        h1 {
            text-align: center;
            margin-top: 100px;
            margin-bottom: 40px;
            color: #ffffff;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
            font-weight: 600;
        }

        /* --------------------------------------
           สไตล์ของกล่องโต๊ะ
           -------------------------------------- */
        .table-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
            gap: 25px;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px 50px 20px;
        }

        .table-box {
            height: 180px; 
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none; 
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        }

        .table-box:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        /* โต๊ะว่าง: สีเขียวอมฟ้า (Cyan/Mint) สดใสแบบในรูป */
        .available {
            background: linear-gradient(135deg, #32f0b6 0%, #0bbd8e 100%);
        }

        /* โต๊ะจองแล้ว: สีส้มอมชมพูไล่ไปเหลือง (Sunset) สดใสแบบในรูป */
        .busy {
            background: linear-gradient(135deg, #ff6a5f 0%, #ffc73c 100%);
        }

        .table-number {
            font-size: 2.5rem;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .status-badge {
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.25);
            padding: 5px 18px;
            border-radius: 20px;
            margin-top: 10px;
            font-family: var(--thai-font);
            font-weight: bold;
        }

        .time-label {
            font-size: 0.85rem;
            margin-top: 12px;
            background: rgba(0, 0, 0, 0.2);
            color: white;
            padding: 6px 15px;
            border-radius: 12px;
            font-family: var(--main-font);
        }

        /* --------------------------------------
           สไตล์ของนาฬิกาและปุ่มแอดมิน (มุมขวาบน)
           -------------------------------------- */
        .header-controls {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .clock-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 25px;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.5);
            text-align: right;
            backdrop-filter: blur(8px);
        }

        #realtime-date {
            font-size: 13px;
            color: #636e72;
            display: block;
        }

        #realtime-clock {
            font-size: 20px;
            color: #2d3436;
            font-weight: 600;
        }

        .admin-btn {
            background-color: var(--accent-color);
            color: #ffffff;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 8px 18px;
            border-radius: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 600;
        }

        .admin-btn:hover {
            background-color: #ffffff;
            color: #2d3436;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="header-controls">
        <div class="clock-container">
            <span id="realtime-date">วันที่ 00/00/00</span>
            <span id="realtime-clock">00:00:00</span>
        </div>
        <a href="admin.php" class="admin-btn">⚙️ สำหรับเจ้าหน้าที่ (Admin)</a>
    </div>

    <script>
        function showDateTime() {
            const clock = document.getElementById('realtime-clock');
            const dateSpan = document.getElementById('realtime-date');

            if (clock && dateSpan) {
                const now = new Date();

                const timeString = now.toLocaleTimeString('th-TH', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
                });
                clock.innerText = timeString + " น.";

                const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
                const dateString = now.toLocaleDateString('th-TH', dateOptions);
                dateSpan.innerText = dateString;
            }
        }
        setInterval(showDateTime, 1000);
        showDateTime();
    </script>

    <h1>🍴 โต๊ะอาหาร ร้าน Goodfood</h1>

    <div class="table-container">
        <?php
        $sql = "SELECT t.*, b.booking_time, b.people_count 
                FROM tables t 
                LEFT JOIN bookings b ON t.id = b.table_id AND t.status = 'busy'
                ORDER BY t.table_number ASC";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $is_available = ($row['status'] == 'available');
            $class = $is_available ? 'available' : 'busy';
            $status_text = $is_available ? 'ว่าง' : 'จองแล้ว';
            $link = $is_available ? "booking.php?id=" . $row['id'] : "check_time.php?id=" . $row['id'];

            echo "<a href='$link' class='table-box $class'>";
            echo "  <div class='table-number'>" . htmlspecialchars($row['table_number']) . "</div>";
            echo "  <div class='status-badge'>$status_text</div>";

            if (!$is_available && !empty($row['booking_time'])) {
                $people = !empty($row['people_count']) ? $row['people_count'] : '-';
                echo "<div class='time-label'>⏰ " . date('H:i', strtotime($row['booking_time'])) . " น. | 👥 " . $people . " ท่าน</div>";
            }
            echo "</a>";
        }
        ?>
    </div>

</body>
</html>