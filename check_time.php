<?php
include 'config.php';
date_default_timezone_set('Asia/Bangkok');

$table_id = mysqli_real_escape_string($conn, $_GET['id']);

// ดึงข้อมูลการจองปัจจุบันของโต๊ะนี้
$sql = "SELECT b.*, t.table_number 
        FROM bookings b 
        JOIN tables t ON b.table_id = t.id 
        WHERE b.table_id = '$table_id' 
        ORDER BY b.booking_time DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$booking = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตรวจสอบเวลาโต๊ะว่าง</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; text-align: center; padding: 50px; background: #f0f2f5; }
        .info-card { background: white; padding: 30px; border-radius: 20px; display: inline-block; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .time-highlight { color: #e74c3c; font-weight: bold; font-size: 1.2rem; }
        .btn-back { display: block; margin-top: 20px; text-decoration: none; color: #888; }
    </style>
</head>
<body>

<div class="info-card">
    <?php if ($booking): 
        $booked_time = strtotime($booking['booking_time']);
        $free_time_after = date('H:i', strtotime('+60 minutes', $booked_time)); // ว่างหลังจอง 1 ชม.
    ?>
        <h2>โต๊ะ <?php echo $booking['table_number']; ?> ไม่ว่างในขณะนี้</h2>
        <p>มีคิวจองเวลา: <span class="time-highlight"><?php echo date('H:i', $booked_time); ?> น.</span></p>
        <hr>
        <p>💡 โต๊ะนี้จะกลับมาว่างอีกครั้งเวลาประมาณ: <br>
           <span class="time-highlight" style="color: #2ecc71; font-size: 1.5rem;"><?php echo $free_time_after; ?> น.</span>
        </p>
        <p style="font-size: 0.8rem; color: #999;">*โต๊ะจะถูกเคลียร์อัตโนมัติหากเลยเวลาจอง 1 ชั่วโมง</p>
    <?php else: ?>
        <p>ไม่พบข้อมูลการจอง หรือโต๊ะว่างแล้ว</p>
    <?php endif; ?>
    
    <a href="index.php" class="btn-back">← กลับไปเลือกโต๊ะอื่น</a>
</div>

</body>
</html>