<?php
session_start();

// Kiểm tra nếu người dùng đã đăng nhập và có quyền xóa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Chuyển hướng về trang login nếu chưa đăng nhập
    exit();
}

include('../includes/db_connect.php'); // Kết nối với cơ sở dữ liệu

// Kiểm tra xem có ID admin cần xóa không
if (isset($_GET['id'])) {
    $adminId = $_GET['id'];

    // Truy vấn xóa admin
    $query = "DELETE FROM admin WHERE AdminID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $adminId);

    if ($stmt->execute()) {
        header("Location: admin_management.php?success=" . urlencode("Admin account deleted successfully."));
    } else {
        header("Location: admin_management.php?error=" . urlencode("Error: " . $stmt->error));
    }
} else { header("Location: admin_management.php?error=" . urlencode("No admin ID provided."));
}
?>
