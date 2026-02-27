<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

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
    <title>เพิ่มโต๊ะใหม่</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding-top: 50px; background: #f4f7f6; }
        .box { background: white; padding: 30px; display: inline-block; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input { padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 200px; }
        button { padding: 10px 20px; background: #2ecc71; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h2>➕ เพิ่มโต๊ะใหม่</h2>
        <form method="POST">
            <input type="text" name="table_no" placeholder="ระบุเลขโต๊ะ (เช่น A6)" required>
            <button type="submit" name="save">บันทึก</button>
        </form>
        <br><a href="admin.php" style="color: #888; text-decoration: none;">ยกเลิก</a>
    </div>
</body>
</html>