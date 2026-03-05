<?php
session_start();
session_unset(); // ล้างค่าตัวแปร session ทั้งหมด
session_destroy(); // ทำลาย session

// เด้งกลับไปหน้าเข้าสู่ระบบ
header("Location: login.php");
exit();
?>