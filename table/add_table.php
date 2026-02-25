<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head><title>เพิ่มโต๊ะอาหาร</title></head>
<body>
    <h2>เพิ่มโต๊ะอาหารใหม่</h2>
    <form method="POST">
        ชื่อ/เลขโต๊ะ: <input type="text" name="table_number" required placeholder="เช่น A5, VIP1">
        <button type="submit" name="add">เพิ่มโต๊ะ</button>
    </form>

    <?php
    if(isset($_POST['add'])){
        $t_num = $_POST['table_number'];
        $sql = "INSERT INTO tables (table_number, status) VALUES ('$t_num', 'available')";
        if(mysqli_query($conn, $sql)){
            echo "<script>alert('เพิ่มโต๊ะ $t_num สำเร็จ!'); window.location='admin.php';</script>";
        }
    }
    ?>
    <br><a href="admin.php">กลับหน้าจัดการ</a>
</body>
</html>