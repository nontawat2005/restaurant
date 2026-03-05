<?php
session_start();
// แก้ไข Path ให้ตรงกับโครงสร้างของคุณ
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รับคิวร้านอาหาร | Goodfood</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
       body {
            font-family: 'Prompt', sans-serif;
            /* 1. ดึงรูป io.png มาทำพื้นหลัง และใส่ฟิลเตอร์สีดำโปร่งแสง (0.5) ทับให้ดูพรีเมียม */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/io.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* ล็อคภาพให้อยู่กับที่เวลาเลื่อนหน้าจอ */
            
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .queue-container {
            /* 2. เปลี่ยนสีพื้นหลังกล่องให้เป็นสีขาวแบบโปร่งแสง (0.85) */
            background: rgba(255, 255, 255, 0.85);
            /* 3. เพิ่มเอฟเฟกต์กระจกเบลอ (Glassmorphism) สุดฮิต */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px); /* สำหรับ Safari */
            
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
            /* เพิ่มกรอบสีขาวบางๆ ให้ดูมีมิติมากขึ้น */
            border: 1px solid rgba(255, 255, 255, 0.5); 
        }

        .queue-container h2 {
            color: #ff4d4d;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 1.8rem;
            line-height: 1.4;
        }

        .queue-container h2 span {
            font-size: 1.1rem;
            color: #718096;
            font-weight: 400;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2d3436;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #edf2f7;
            border-radius: 12px;
            box-sizing: border-box;
            font-family: 'Prompt', sans-serif;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: #ff4d4d;
            outline: none;
            background: #fffafa;
        }

        .btn-submit {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 12px;
            font-size: 1.2rem;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Prompt', sans-serif;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 75, 43, 0.3);
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            color: #a0aec0;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .btn-back:hover {
            color: #2d3436;
        }
    </style>
</head>

<body>

    <div class="queue-container">
        <h2>🔴 โต๊ะเต็มแล้ว<br><span>ลงทะเบียนรับบัตรคิวด้านล่าง</span></h2>
        <form action="save_queue.php" method="POST">
            <div class="form-group">
                <label>👤 ชื่อลูกค้า</label>
                <input type="text" name="customer_name" required placeholder="เช่น คุณสมชาย">
            </div>
            <div class="form-group">
                <label>📱 เบอร์โทรศัพท์</label>
                <input type="tel" name="phone" required placeholder="08x-xxx-xxxx">
            </div>
            <div class="form-group">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #2d3436;">📧 อีเมล (สำหรับแจ้งเตือนคิว)</label>
                <input type="email" name="email" placeholder="เช่น example@gmail.com" required style="width: 100%; padding: 15px; border: 2px solid #edf2f7; border-radius: 12px; box-sizing: border-box; font-family: 'Prompt', sans-serif; font-size: 1rem; margin-bottom: 20px;">
            </div>
            <div class="form-group">
                <label>👥 จำนวนลูกค้า (ท่าน)</label>
                <input type="number" name="people_count" min="1" required placeholder="ระบุจำนวนคน">
            </div>
            <button type="submit" class="btn-submit">ยืนยันรับคิว</button>
        </form>
        <a href="index.php" class="btn-back">← กลับหน้าแรก</a>
    </div>

</body>

</html>