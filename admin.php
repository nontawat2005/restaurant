<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';
?>
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Admin Control</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 40px;
            background-color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            margin: 2px;
            display: inline-block;
        }

        .btn-clear {
            background-color: #3498db;
            color: white;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>

<body>
    <h1>⚙️ แผงควบคุม Admin</h1>
    <a href="index.php">⬅️ กลับหน้าหลัก</a> | <a href="add_table.php">➕ เพิ่มโต๊ะใหม่</a>

    <table>
        <tr>
            <th>เลขโต๊ะ</th>
            <th>สถานะ</th>
            <th>ข้อมูลการจอง</th>
            <th>การจัดการ</th>
        </tr>
        <?php
        $sql = "SELECT t.*, b.booking_time, b.customer_name 
                FROM tables t 
                LEFT JOIN bookings b ON t.id = b.table_id AND t.status = 'busy'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>โต๊ะ " . $row['table_number'] . "</td>";
            echo "<td>" . ($row['status'] == 'available' ? '🟢 ว่าง' : '🔴 ไม่ว่าง') . "</td>";
            echo "<td>" . ($row['booking_time'] ? $row['customer_name'] . " (" . date('d/m/Y H:i', strtotime($row['booking_time'])) . ")" : "-") . "</td>";
            echo "<td>";

            if ($row['status'] == 'busy') {
                echo "<a href='clear_table.php?id=" . $row['id'] . "' class='btn btn-clear' onclick='return confirm(\"คืนโต๊ะให้ว่างใช่หรือไม่?\")'>คืนโต๊ะ</a>";
            }

            echo "<a href='delete_table.php?id=" . $row['id'] . "' class='btn btn-delete' onclick='return confirm(\"ลบโต๊ะนี้ออกจากระบบถาวร?\")'>ลบโต๊ะ</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>