<?php
session_start();
// 1. ตรวจสอบให้แน่ใจว่าเรียกจากโฟลเดอร์ includes ที่จัดไว้
include 'includes/config.php'; 

if (!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit(); 
}

if (isset($_POST['save'])) {
    $table_no = mysqli_real_escape_string($conn, $_POST['table_no']);
    // เพิ่มโต๊ะใหม่ลง Database
    mysqli_query($conn, "INSERT INTO tables (table_number, status) VALUES ('$table_no', 'available')");
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มโต๊ะใหม่ | Goodfood</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Sarabun', sans-serif; 
            text-align: center; 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* เรียกรูปพื้นหลังจากโฟลเดอร์ assets */
            background-image: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)), url('assets/admin-bg.png');
            background-size: cover;
            background-position: center;
        }
        .box { 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            width: 100%;
            max-width: 350px;
        }
        h2 { color: #2c3e50; margin-bottom: 25px; }
        input { 
            padding: 12px; 
            border: 2px solid #eee; 
            border-radius: 10px; 
            width: 100%; 
            box-sizing: border-box;
            font-size: 16px;
            margin-bottom: 20px;
            text-align: center;
        }
        input:focus { border-color: #3498db; outline: none; }
        button { 
            padding: 12px; 
            background: #27ae60; 
            color: white; 
            border: none; 
            border-radius: 10px; 
            cursor: pointer; 
            width: 100%;
            font-weight: 600;
            font-size: 16px;
            transition: 0.3s;
        }
        button:hover { background: #219150; transform: translateY(-2px); }
        .back-link { 
            display: block; 
            margin-top: 20px; 
            color: #7f8c8d; 
            text-decoration: none; 
            font-size: 14px; 
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="box">
        <h2>➕ เพิ่มโต๊ะใหม่</h2>
        <form method="POST">
            <input type="text" name="table_no" placeholder="ระบุเลขโต๊ะ (เช่น A6)" required autofocus>
            <button type="submit" name="save">บันทึกข้อมูลโต๊ะ</button>
        </form>
        <a href="admin.php" class="back-link">⬅️ ยกเลิกและกลับหน้าจัดการ</a>
    </div>
</body>
</html>