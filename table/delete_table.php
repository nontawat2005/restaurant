<?php include 'config.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM bookings WHERE table_id = '$id'");
mysqli_query($conn, "DELETE FROM tables WHERE id = '$id'");
header("Location: admin.php"); ?>