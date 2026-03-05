<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['admin'])) { exit(); }

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    if ($status == 'busy') {
        $sql = "UPDATE tables SET status = 'busy' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

header("Location: admin.php");
exit();
?>