<?php
session_start();
include 'includes/config.php';

// ตรวจสอบว่าได้ล็อกอินเป็นแอดมินหรือยัง
if (!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit(); 
}

// ดึงข้อมูลคิวที่สถานะเป็น 'รอคิว' หรือ 'เรียกแล้ว' เรียงตามเวลาที่จองก่อน-หลัง
$sql = "SELECT * FROM queues WHERE status IN ('waiting', 'called') ORDER BY created_at ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคิว | Admin Goodfood</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 15px; }
        h2 { color: #2c3e50; margin: 0; }
        .btn-back { background: #95a5a6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; transition: 0.3s; }
        .btn-back:hover { background: #7f8c8d; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #2c3e50; color: white; }
        tr:hover { background-color: #f9f9f9; }
        
        /* ป้ายสถานะ */
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 0.9rem; color: white; font-weight: 600; }
        .badge.waiting { background-color: #f39c12; }
        .badge.called { background-color: #3498db; }
        
        /* ปุ่มจัดการ */
        .btn-action { padding: 8px 12px; border-radius: 6px; text-decoration: none; color: white; font-size: 0.9rem; margin-right: 5px; display: inline-block; transition: 0.3s; }
        .btn-call { background-color: #3498db; }
        .btn-call:hover { background-color: #2980b9; }
        .btn-success { background-color: #2ecc71; }
        .btn-success:hover { background-color: #27ae60; }
        .btn-danger { background-color: #e74c3c; }
        .btn-danger:hover { background-color: #c0392b; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>📋 ระบบจัดการคิวหน้าร้าน</h2>
        <div>
            <a href="admin.php" class="btn-back">⬅️ กลับหน้าจัดการโต๊ะ</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>คิวที่</th>
                <th>ชื่อลูกค้า</th>
                <th>เบอร์โทรศัพท์</th>
                <th>จำนวน (ท่าน)</th>
                <th>เวลาที่จอง</th>
                <th>สถานะ</th>
                <th>จัดการคิว</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) { 
                    // แปลงสถานะเป็นภาษาไทยและกำหนดสี
                    $status_class = $row['status'];
                    $status_text = ($row['status'] == 'waiting') ? 'กำลังรอคิว' : 'เรียกคิวแล้ว';
            ?>
            <tr>
                <td><strong>#<?= $row['id'] ?></strong></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= $row['people_count'] ?></td>
                <td><?= date('H:i น.', strtotime($row['created_at'])) ?></td>
                <td><span class="badge <?= $status_class ?>"><?= $status_text ?></span></td>
                <td>
                    <?php if($row['status'] == 'waiting'): ?>
                        <a href="update_queue.php?id=<?= $row['id'] ?>&action=call" class="btn-action btn-call">📢 เรียกคิว</a>
                    <?php endif; ?>
                    
                    <a href="update_queue.php?id=<?= $row['id'] ?>&action=complete" class="btn-action btn-success">✅ เข้าโต๊ะแล้ว</a>
                    <a href="update_queue.php?id=<?= $row['id'] ?>&action=cancel" class="btn-action btn-danger" onclick="return confirm('แน่ใจหรือไม่ว่าต้องการยกเลิกคิวนี้?');">❌ ยกเลิก</a>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='7' style='text-align: center; padding: 30px; color: #7f8c8d;'>🎉 ตอนนี้ไม่มีลูกค้ารอคิวครับ</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>