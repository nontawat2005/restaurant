<?php include 'config.php'; ?>
<h2>จองโต๊ะหมายเลข: <?php
                    $table_id = $_GET['id'];
                    $sql = "SELECT table_number FROM tables WHERE id = $table_id";
                    $res = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($res);
                    echo $row['table_number'];
                    ?></h2>

<form action="save_booking.php" method="POST">
    <label>เบอร์โทรศัพท์:</label>
    <input type="text" name="phone" required class="form-control">

    <label>จำนวนคน:</label>
    <input type="number" name="people_count" min="1" required class="form-control">

    <input type="datetime-local" name="booking_time" required>
    <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">
    ชื่อผู้จอง: <input type="text" name="customer_name" required><br><br>
    อีเมล: <input type="email" name="customer_email" required><br><br>
    เบอร์โทรศัพท์: <input type="text" name="customer_phone" required><br><br>
    วันเวลาที่ต้องการจอง: <input type="datetime-local" name="booking_time" required><br><br>
    <button type="submit">ยืนยันการจอง</button>
</form>