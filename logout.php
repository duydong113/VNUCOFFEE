<?php
session_start();

// Xóa tất cả dữ liệu session
session_unset();
session_destroy();

// Chuyển hướng về trang login sau khi logout
header("Location: admin_login.php");
exit();
?>
