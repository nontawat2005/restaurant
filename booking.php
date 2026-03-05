<?php
include 'includes/config.php';

// รับค่า ID โต๊ะจาก URL
$table_id = isset($_GET['id']) ? $_GET['id'] : '';
$table_number = '';

// ดึงหมายเลขโต๊ะมาแสดง
if ($table_id) {
    $sql = "SELECT table_number FROM tables WHERE id = '$table_id'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $table_number = $row['table_number'];
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองโต๊ะอาหาร | Goodfood</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&family=Sarabun:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --main-font: 'Prompt', sans-serif;
            --btn-color: linear-gradient(135deg, #32f0b6 0%, #0bbd8e 100%);
            --btn-hover: linear-gradient(135deg, #4ef5c4 0%, #11d19f 100%);
        }

        body {
            font-family: var(--main-font);
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/io.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* --------------------------------------
           สไตล์ของการ์ดฟอร์มจอง
           -------------------------------------- */
        .booking-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
            box-sizing: border-box;
            margin: 20px;
        }

        .booking-card h2 {
            text-align: center;
            margin-top: 0;
            color: #2d3436;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .table-badge {
            display: inline-block;
            background: var(--btn-color);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 1.2rem;
            margin-left: 10px;
            box-shadow: 0 4px 10px rgba(11, 189, 142, 0.3);
        }

        /* --------------------------------------
           สไตล์ของช่องกรอกข้อมูล
           -------------------------------------- */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #636e72;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #dfe6e9;
            border-radius: 12px;
            font-family: var(--main-font);
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background: #fdfdfd;
        }

        .form-control:focus {
            outline: none;
            border-color: #0bbd8e;
            box-shadow: 0 0 0 3px rgba(11, 189, 142, 0.1);
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        /* --------------------------------------
           สไตล์ของปุ่มกดยืนยันและปุ่มกลับ
           -------------------------------------- */
        .btn-submit {
            width: 100%;
            background: var(--btn-color);
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.1rem;
            border-radius: 12px;
            cursor: pointer;
            font-family: var(--main-font);
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(11, 189, 142, 0.3);
        }

        .btn-submit:hover {
            background: var(--btn-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(11, 189, 142, 0.4);
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #b2bec3;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .btn-back:hover {
            color: #2d3436;
        }
    </style>
</head>

<body>

    <div class="booking-card">
        <h2>จองโต๊ะหมายเลข <span class="table-badge"><?php echo htmlspecialchars($table_number); ?></span></h2>

        <form action="save_booking.php" method="POST">
            <input type="hidden" name="table_id" value="<?php echo htmlspecialchars($table_id); ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="customer_name">ชื่อผู้จอง</label>
                    <input type="text" id="customer_name" name="customer_name" class="form-control" required placeholder="กรอกชื่อ-นามสกุล">
                </div>
                <div class="form-group">
                    <label for="people_count">จำนวนคน (ท่าน)</label>
                    <input type="number" id="people_count" name="people_count" class="form-control" required min="1" placeholder="ระบุจำนวนคน">
                </div>
            </div>

            <div class="form-group">
                <label for="phone">เบอร์โทรศัพท์</label>
                <input type="tel" id="phone" name="phone" class="form-control" required placeholder="08X-XXX-XXXX">
            </div>

            <div class="form-group">
                <label for="email">อีเมล (ถ้ามี)</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="example@email.com">
            </div>

            <div class="form-group">
                <label for="booking_time">วันเวลาที่ต้องการจอง</label>
                <input type="datetime-local" id="booking_time" name="booking_time" class="form-control" required>
            </div>

            <button type="submit" class="btn-submit">ยืนยันการจอง</button>
            <a href="index.php" class="btn-back">← กลับไปหน้าเลือกโต๊ะ</a>
        </form>
    </div>

</body>
</html>