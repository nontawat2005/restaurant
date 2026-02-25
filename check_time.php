<?php 
include 'config.php';
$id = $_GET['id'];
$sql = "SELECT b.*, t.table_number FROM bookings b 
        JOIN tables t ON b.table_id = t.id 
        WHERE t.id = '$id' ORDER BY b.id DESC LIMIT 1";
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);
?>
<h2>สถานะโต๊ะ <?php echo $data['table_number']; ?></h2>
<p>โต๊ะนี้ถูกจองตั้งแต่วันที่: <?php echo date('d/m/Y H:i', strtotime($data['booking_time'])); ?></p>
<p>คาดว่าจะว่างหลังจากนี้ 2 ชม. (ประมาณ <?php echo date('H:i', strtotime($data['booking_time'] . ' +2 hours')); ?> น.)</p>
<a href="index.php">กลับหน้าหลัก</a>