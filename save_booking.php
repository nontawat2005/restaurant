<?php
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากหน้าฟอร์มที่คุณเพิ่งส่งมา
    $table_id = $_POST['table_id'];
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $customer_email = $_POST['customer_email'];
    $people_count = $_POST['people_count'];
    $booking_time = $_POST['booking_time'];

    // 1. อัปเดตสถานะโต๊ะให้เป็น 'busy' (ไม่ว่าง)
    mysqli_query($conn, "UPDATE tables SET status = 'busy' WHERE id = '$table_id'");

    // 2. บันทึกข้อมูลการจองลงตาราง bookings (รวบรวมให้บันทึกในคำสั่งเดียวครบทุกช่อง)
    $sql_book = "INSERT INTO bookings (table_id, customer_name, customer_phone, customer_email, people_count, booking_time) 
                 VALUES ('$table_id', '$customer_name', '$customer_phone', '$customer_email', '$people_count', '$booking_time')";
    
    // 3. ตรวจสอบว่าบันทึกสำเร็จหรือไม่
    if (mysqli_query($conn, $sql_book)) {
        echo "<script>
                alert('🎉 จองโต๊ะสำเร็จเรียบร้อยแล้ว!'); 
                window.location.href='index.php';
              </script>";
    } else {
        // ถ้าระบบฐานข้อมูลพัง มันจะแจ้งเตือน Error สีดำๆ ให้เราเห็นตรงนี้ครับ
        echo "เกิดข้อผิดพลาดในการบันทึก: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit();
}
?>