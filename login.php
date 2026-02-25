<?php
session_start();
if(isset($_POST['login'])) {
    if($_POST['user'] == "admin" && $_POST['pass'] == "1234") { // ตั้งรหัส
        $_SESSION['admin'] = true;
        header("Location: admin.php");
    } else { echo "รหัสผิด!"; }
}
?>
<form method="post">
    User: <input type="text" name="user"><br>
    Pass: <input type="password" name="pass"><br>
    <button type="submit" name="login">เข้าสู่ระบบ</button>
</form>