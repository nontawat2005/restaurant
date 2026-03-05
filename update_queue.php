<?php
session_start();
// ตรวจสอบโครงสร้างไฟล์: config.php อยู่ที่เดียวกับไฟล์นี้ (ตามรูปสกรีนช็อตล่าสุด)
include 'includes/config.php';

// โหลดไฟล์ PHPMailer จากโฟลเดอร์ PHPMailer (ตามรูปสกรีนช็อตล่าสุด)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'includes/PHPMailer/Exception.php';
require 'includes/PHPMailer/PHPMailer.php';
require 'includes/PHPMailer/SMTP.php';
if (!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit(); 
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $action = $_GET['action'];
    $new_status = '';

    if ($action == 'call') {
        $new_status = 'called'; 
        
        // ดึงข้อมูลจากตาราง queues โดยใช้คอลัมน์ 'email' (ตามรูปโครงสร้าง DB ของคุณ)
        $query = "SELECT customer_name, email FROM queues WHERE id = '$id'";
        $result = mysqli_query($conn, $query);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            // ตรวจสอบว่าช่อง email ไม่ว่าง
            if (!empty($row['email'])) {
                $customer_name = $row['customer_name'];
                $customer_email = $row['email'];
                
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'Nontawat.do@ku.th'; 
                    $mail->Password   = 'bcecdydkezzpmlqu'; // รหัสผ่านแอป 16 ตัว (พิมพ์ติดกัน)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587; // ใช้ 587 เพื่อเลี่ยงการโดนบล็อก
                    $mail->CharSet    = 'UTF-8'; 

                    // ปิดการตรวจ SSL สำหรับ localhost
                    $mail->SMTPOptions = array(
                        'ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true)
                    );

                    $mail->setFrom('Nontawat.do@ku.th', 'Goodfood Restaurant');
                    $mail->addAddress($customer_email, $customer_name); 

                    $mail->isHTML(true);
                    $mail->Subject = '🎉 ถึงคิวของคุณแล้ว! - ร้านอาหาร Goodfood';
                    $mail->Body    = "<h2>คุณ {$customer_name} ครับ</h2><p>ขณะนี้ถึงคิวของคุณแล้ว กรุณามาที่หน้าร้านได้เลยครับ!</p>";

                    $mail->send();
                } catch (Exception $e) {
                    // บันทึกความผิดพลาดลง Error Log
                    error_log("Mail Error: " . $mail->ErrorInfo);
                }
            }
        }

    } elseif ($action == 'complete') {
        $new_status = 'completed'; 
    } elseif ($action == 'cancel') {
        $new_status = 'cancelled'; 
    }

    if ($new_status != '') {
        mysqli_query($conn, "UPDATE queues SET status = '$new_status' WHERE id = '$id'");
    }
}

header("Location: admin_queue.php");
exit();