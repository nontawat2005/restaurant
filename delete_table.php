<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    // ลบการจองก่อนเพื่อป้องกัน Error (Foreign Key)
    mysqli_query($conn, "DELETE FROM bookings WHERE table_id = '$id'");
    // ลบโต๊ะออกจากฐานข้อมูล
    mysqli_query($conn, "DELETE FROM tables WHERE id = '$id'");
    
    header("Location: admin.php");
    exit();
}
?>