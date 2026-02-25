<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจองโต๊ะอาหาร | Goodfood</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --busy-gradient: linear-gradient(135deg, #f85032 0%, #e73827 100%);
            --bg-color: #f0f2f5;
        }

        body { 
            font-family: 'Prompt', sans-serif; 
            background-color: var(--bg-color); 
            margin: 0; 
            padding: 40px 20px; 
            text-align: center;
        }

        h1 { color: #2c3e50; margin-bottom: 40px; font-weight: 600; }

        /* ปรับ Container ให้เป็น Grid เพื่อรองรับมือถือ */
        .table-container { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); 
            gap: 25px; 
            max-width: 1000px; 
            margin: 0 auto; 
        }

        .table-box { 
            height: 180px; 
            border-radius: 24px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            text-decoration: none; 
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .table-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .available { background: var(--primary-gradient); color: #064e3b; }
        .busy { background: var(--busy-gradient); opacity: 0.9; }

        .table-number { font-size: 2rem; font-weight: 600; }
        
        .status-badge {
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.3);
            padding: 2px 12px;
            border-radius: 15px;
            margin-top: 5px;
        }

        .time-label { 
            font-size: 0.75rem; 
            margin-top: 10px; 
            background: rgba(0,0,0,0.1); 
            padding: 4px 8px; 
            border-radius: 8px; 
        }

        .admin-link { margin-top: 50px; display: block; color: #888; text-decoration: none; font-size: 0.9rem; }
        .admin-link:hover { color: #333; }
    </style>
</head>
<body>

    <h1>🍴 โต๊ะอาหาร ร้าน Goodfood</h1>

    <div class="table-container">
        <?php
        $sql = "SELECT t.*, b.booking_time 
                FROM tables t 
                LEFT JOIN bookings b ON t.id = b.table_id AND t.status = 'busy'
                ORDER BY t.table_number ASC";
        $result = mysqli_query($conn, $sql);

        // --- ส่วนที่ปรับปรุงอยู่ใน Loop นี้ครับ ---
        while ($row = mysqli_fetch_assoc($result)) {
            $is_available = ($row['status'] == 'available');
            $class = $is_available ? 'available' : 'busy';
            $status_text = $is_available ? 'ว่าง' : 'จองแล้ว';
            $link = $is_available ? "booking.php?id=".$row['id'] : "check_time.php?id=".$row['id'];
            
            echo "<a href='$link' class='table-box $class'>";
            echo "  <div class='table-number'>" . htmlspecialchars($row['table_number']) . "</div>";
            echo "  <div class='status-badge'>$status_text</div>";
            
            if (!$is_available && !empty($row['booking_time'])) {
                echo "<div class='time-label'>⏰ " . date('H:i', strtotime($row['booking_time'])) . " น.</div>";
            }
            echo "</a>";
        }
        // --- จบส่วน Loop ---
        ?>
    </div>

    <a href="admin.php" class="admin-link">⚙️ สำหรับเจ้าหน้าที่ (Admin)</a>

</body>
</html>