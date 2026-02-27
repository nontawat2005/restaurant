<?php
session_start();
include 'config.php';

// เช็คสิทธิ์ Admin
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
    <title>Admin Dashboard - Restaurant Systems</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --success: #27ae60;
            --danger: #e74c3c;
            --info: #3498db;
            --light: #f8f9fa;
        }
        body { font-family: 'Sarabun', sans-serif; background-color: #ecf0f1; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        h1 { margin: 0; color: var(--primary); font-size: 24px; }
        
        .nav-btns .btn { padding: 10px 18px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; transition: 0.3s; margin-left: 5px; }
        .btn-add { background: var(--success); color: white; }
        .btn-home { background: var(--info); color: white; }
        .btn:hover { opacity: 0.85; transform: translateY(-1px); }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; overflow: hidden; border-radius: 8px; }
        th { background-color: var(--primary); color: white; padding: 15px; text-align: left; font-size: 14px; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 14px; color: #444; }
        tr:hover { background-color: #fcfcfc; }

        .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-available { background: #d4edda; color: #155724; }
        .status-busy { background: #f8d7da; color: #721c24; }

        .action-btns .btn-sm { padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 12px; color: white; margin-right: 5px; }
        .btn-clear { background: var(--info); }
        .btn-delete { background: var(--danger); }
        
        .phone-link { color: var(--primary); text-decoration: none; font-weight: bold; }
        .phone-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>⚙️ แผงควบคุม Admin</h1>
        <div class="nav-btns">
            <a href="add_table.php" class="btn btn-add">+ เพิ่มโต๊ะ</a>
            <a href="index.php" class="btn btn-home">🏠 หน้าหลัก</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>เลขโต๊ะ</th>
                <th>สถานะ</th>
                <th>ชื่อผู้จอง</th>
                <th>เบอร์โทรศัพท์</th> <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // SQL สำหรับดึงข้อมูลโต๊ะพร้อมชื่อและเบอร์โทรผู้จอง
            $sql = "SELECT t.*, b.customer_name, b.customer_phone 
                    FROM tables t 
                    LEFT JOIN bookings b ON t.id = b.table_id AND t.status = 'busy'
                    ORDER BY t.table_number ASC";
            $result = mysqli_query($conn, $sql);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $status_class = ($row['status'] == 'available') ? 'status-available' : 'status-busy';
                $status_text = ($row['status'] == 'available') ? 'ว่าง' : 'ไม่ว่าง';
            ?>
                <tr>
                    <td><strong>โต๊ะ <?php echo $row['table_number']; ?></strong></td>
                    <td><span class="status-pill <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                    <td><?php echo $row['customer_name'] ? $row['customer_name'] : '-'; ?></td>
                    <td>
                        <?php if($row['customer_phone']): ?>
                            <a href="tel:<?php echo $row['customer_phone']; ?>" class="phone-link">
                                📞 <?php echo $row['customer_phone']; ?>
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="action-btns">
                        <?php if ($row['status'] == 'busy'): ?>
                            <a href="clear_table.php?id=<?php echo $row['id']; ?>" 
                               class="btn-sm btn-clear" 
                               onclick="return confirm('คืนโต๊ะนี้ให้ว่างใช่หรือไม่?')">คืนโต๊ะ</a>
                        <?php endif; ?>
                        
                        <a href="delete_table.php?id=<?php echo $row['id']; ?>" 
                           class="btn-sm btn-delete" 
                           onclick="return confirm('ลบโต๊ะถาวร?')">ลบโต๊ะ</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>