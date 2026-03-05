<?php
session_start();
include 'includes/config.php';
// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    // 1. ลบรายการจองออกจากตาราง bookings
    mysqli_query($conn, "DELETE FROM bookings WHERE table_id = '$id'");
    // 2. ปรับสถานะในตาราง tables ให้เป็น available
    mysqli_query($conn, "UPDATE tables SET status = 'available' WHERE id = '$id'");
    
    header("Location: admin.php"); // ทำเสร็จแล้วกลับหน้า Admin
    exit();
}
?>