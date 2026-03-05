<?php
session_start();
include 'includes/config.php'; // ดึงไฟล์ตั้งค่าจากโฟลเดอร์ includes

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Goodfood</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --success: #27ae60;
            --danger: #e74c3c;
            --accent: #3498db;
            --warning: #f39c12;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background: #ecf0f1;
            margin: 0;
            padding: 20px;
            background-image: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('assets/admin-bg.png');
            background-size: cover;
            background-attachment: fixed;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        /* ตกแต่งปุ่มทั้งหมด */
        .btn {
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* แก้ไขปุ่มหน้าหลักให้มองเห็นชัด (ตัวหนังสือสีเข้ม พื้นหลังสว่าง) */
        .btn-home { 
            background: #f0f2f5; 
            color: #2c3e50 !important; 
            border: 1px solid #dcdde1;
        }
        
        .btn-add { background: var(--success); color: white; }
        .btn-queue { background: var(--warning); color: white; }
        .btn-logout { background: #7f8c8d; color: white; }
        
        .btn:hover { opacity: 0.8; transform: translateY(-2px); }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 15px;
            overflow: hidden;
        }

        th { background: var(--primary); color: white; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #eee; }

        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-available { background: #d4edda; color: #155724; }
        .status-busy { background: #f8d7da; color: #721c24; }

        .action-link {
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
            color: white;
            margin-right: 5px;
        }

        .bg-blue { background: var(--accent); }
        .bg-orange { background: var(--warning); }
        .bg-red { background: var(--danger); }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1 style="margin:0; color: var(--primary);">⚙️ ระบบจัดการร้าน</h1>
        <div style="display: flex; gap: 10px;">
            <a href="index.php" class="btn btn-home">🏠 หน้าหลักลูกค้า</a>
            <a href="add_table.php" class="btn btn-add">+ เพิ่มโต๊ะ</a>
            <a href="admin_queue.php" class="btn btn-queue">📋 คิวหน้าร้าน</a>
            <a href="logout.php" class="btn btn-logout" onclick="return confirm('ต้องการออกจากระบบ?');">🚪 ออก</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>เลขโต๊ะ</th>
                <th>สถานะ</th>
                <th>ผู้จอง / เบอร์</th>
                <th>คน</th>
                <th>เวลา</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT t.*, b.customer_name, b.customer_phone, b.people_count, b.booking_time 
                    FROM tables t 
                    LEFT JOIN bookings b ON t.id = b.table_id AND t.status = 'busy'
                    ORDER BY t.table_number ASC";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $is_busy = ($row['status'] == 'busy');
            ?>
                <tr>
                    <td><b>โต๊ะ <?php echo htmlspecialchars($row['table_number']); ?></b></td>
                    <td>
                        <span class="status-badge <?php echo $is_busy ? 'status-busy' : 'status-available'; ?>">
                            <?php echo $is_busy ? 'ไม่ว่าง' : 'ว่าง'; ?>
                        </span>
                    </td>
                    <td>
                        <?php if($is_busy): ?>
                            <b><?php echo htmlspecialchars($row['customer_name']); ?></b><br>
                            <small>📞 <?php echo htmlspecialchars($row['customer_phone']); ?></small>
                        <?php else: ?> - <?php endif; ?>
                    </td>
                    <td><?php echo $is_busy ? $row['people_count'].' ท่าน' : '-'; ?></td>
                    <td><?php echo $is_busy ? date('H:i น.', strtotime($row['booking_time'])) : '-'; ?></td>
                    <td>
                        <?php if ($is_busy): ?>
                            <a href="clear_table.php?id=<?php echo $row['id']; ?>" class="action-link bg-blue" onclick="return confirm('คืนโต๊ะ?')">✅ คืนโต๊ะ</a>
                        <?php else: ?>
                            <a href="update_table_status.php?id=<?php echo $row['id']; ?>&status=busy" class="action-link bg-orange">🚫 ปิดโต๊ะ</a>
                        <?php endif; ?>
                        <a href="delete_table.php?id=<?php echo $row['id']; ?>" class="action-link bg-red" onclick="return confirm('ลบโต๊ะ?')">🗑️ ลบ</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>