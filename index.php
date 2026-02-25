<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจองโต๊ะอาหาร</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; text-align: center; background-color: #f8f9fa; padding: 20px; }
        .table-container { display: flex; justify-content: center; gap: 25px; flex-wrap: wrap; padding: 40px; }
        .table-box { 
            width: 160px; height: 160px; border-radius: 20px; 
            display: flex; flex-direction: column; align-items: center; justify-content: center; 
            color: white; text-decoration: none; transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .available { background: linear-gradient(145deg, #2ecc71, #27ae60); }
        .busy { background: linear-gradient(145deg, #e74c3c, #c0392b); cursor: not-allowed; }
        .table-number { font-size: 1.5rem; font-weight: bold; }
        .time-label { font-size: 0.75rem; background: rgba(0,0,0,0.2); padding: 4px 8px; border-radius: 10px; margin-top: 8px; }
    </style>
</head>
<body>
    <h1>🍴 โต๊ะอาหาร ร้านGoodfood </h1>
    <div class="table-container">
        <?php
        $sql = "SELECT t.*, b.booking_time 
                FROM tables t 
                LEFT JOIN bookings b ON t.id = b.table_id AND t.status = 'busy'
                ORDER BY t.table_number ASC";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $is_available = ($row['status'] == 'available');
            $class = $is_available ? 'available' : 'busy';
           $link = $is_available ? "booking.php?id=".$row['id'] : "check_time.php?id=".$row['id'];
            
            echo "<a href='$link' class='table-box $class'>";
            echo "<div class='table-number'>โต๊ะ " . htmlspecialchars($row['table_number']) . "</div>";
            if (!$is_available) {
                echo "<div>ไม่ว่าง</div>";
                if (!empty($row['booking_time'])) {
                    echo "<div class='time-label'>" . date('d/m/Y H:i', strtotime($row['booking_time'])) . " น.</div>";
                }
            } else { echo "<div>ว่าง</div>"; }
            echo "</a>";
        }
        ?>
    </div>
    <p><a href="admin.php" style="color: #888; text-decoration: none;">⚙️ สำหรับเจ้าหน้าที่ (Admin)</a></p>
</body>
</html>