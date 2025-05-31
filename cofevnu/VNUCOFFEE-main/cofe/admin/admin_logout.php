<?php

include '../includes/db_connect.php';  // Đảm bảo đường dẫn đến file kết nối đúng

session_start();  // Khởi tạo session

// Hủy tất cả session và xóa dữ liệu
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập
header('location:../admin/admin_login.php');  // Đảm bảo đường dẫn chính xác đến admin_login.php

exit;  // Đảm bảo script dừng lại tại đây và không thực hiện thêm hành động nào sau khi chuyển hướng
?>
