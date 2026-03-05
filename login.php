<?php
// ห้ามมีช่องว่างหรือการเว้นบรรทัดก่อนแท็ก <?php นี้นะครับ
session_start(); 
include 'includes/config.php';

// ตรวจสอบว่ามีการกดปุ่ม "login" หรือยัง
if (isset($_POST['login'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    // เช็คค่าที่รับมา (คุณเปลี่ยนเป็น admin / 123456)
    if ($username === 'admin' && $password === '123456') {
        
        // สร้าง Session ชื่อ admin ให้ตรงกับที่หน้า admin.php ต้องการ
        $_SESSION['admin'] = true; 
        
        // ย้ายไปหน้า admin.php
        header("Location: admin.php");
        exit();
    } else {
        // ถ้ารหัสผิดให้แจ้งเตือน
        echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบแอดมิน</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f2f5; margin: 0; }
        .login-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 300px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #34495e; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        button:hover { background: #2c3e50; }body {
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
    </style>
</head>
<body>

<div class="login-card">
    <h2>Admin Login</h2>
   <form method="POST" action="login.php">
        <input type="text" name="user" placeholder="Username" required>
        <input type="password" name="pass" placeholder="Password" required>
        <button type="submit" name="login">เข้าสู่ระบบ</button>
    </form>
</div>

</body>
</html>