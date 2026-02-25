<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table_id = $_POST['table_id'];
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $customer_email = $_POST['customer_email'];
    $phone = $_POST['phone'];
    $people_count = $_POST['people_count'];
    $booking_time = $_POST['booking_time'];

    $sql_book = "INSERT INTO bookings (table_id, customer_name, phone, people_count, booking_time) 
             VALUES ('$table_id', '$customer_name', '$phone', '$people_count', '$booking_time')";
    // ตรวจสอบชื่อจากหน้าฟอร์ม booking.php (ถ้าหน้าฟอร์มใช้ชื่ออื่น ให้เปลี่ยนตรงนี้)
    $booking_time = isset($_POST['booking_time']) ? $_POST['booking_time'] : date('Y-m-d H:i:s');

    // 1. อัปเดตสถานะโต๊ะ
    mysqli_query($conn, "UPDATE tables SET status = 'busy' WHERE id = '$table_id'");

    // 2. สร้างคำสั่ง SQL สำหรับบันทึกการจอง
    $sql_book = "INSERT INTO bookings (table_id, customer_name, customer_phone, customer_email, booking_time) 
                 VALUES ('$table_id', '$customer_name', '$customer_phone', '$customer_email', '$booking_time')";

    // 3. รันคำสั่ง
    if (mysqli_query($conn, $sql_book)) {
        echo "<script>alert('จองสำเร็จ!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
