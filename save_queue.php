<?php
session_start();
// แก้ไข Path ให้ตรงกับโครงสร้างของคุณ
require_once 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $people_count = $_POST['people_count'];
    // รับค่าอีเมลจากฟอร์ม
    $email = $_POST['email']; 
    $status = 'waiting'; 

    // เพิ่ม email ลงในคำสั่ง SQL (รวมเป็น 5 ค่า)
    $sql = "INSERT INTO queues (customer_name, phone, people_count, email, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // ผูกค่าตัวแปร: s = string, i = integer -> ssiss (string, string, int, string, string)
    $stmt->bind_param("ssiss", $customer_name, $phone, $people_count, $email, $status);

    if ($stmt->execute()) {
        echo "<script>
                alert('🎉 บันทึกคิวของคุณเรียบร้อยแล้ว! กรุณารอรับอีเมลแจ้งเตือนนะครับ');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ เกิดข้อผิดพลาดในการบันทึกคิว: " . $conn->error . "');
                window.history.back();
              </script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: queue.php");
    exit();
}
?>