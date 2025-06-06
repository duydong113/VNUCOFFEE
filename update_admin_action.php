<?php
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminId = $_POST['admin_id'];
    $username = $_POST['username'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Nếu có mật khẩu mới, cập nhật mật khẩu
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE admin SET Username = ?, FullName = ?, Email = ?, Phone = ?, Role = ?, Password = ? WHERE AdminID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $username, $fullName, $email, $phone, $role, $password, $adminId);
    } else {
        $query = "UPDATE admin SET Username = ?, FullName = ?, Email = ?, Phone = ?, Role = ? WHERE AdminID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $username, $fullName, $email, $phone, $role, $adminId);
    }

    if ($stmt->execute()) {
        header("Location: admin_management.php?success=Admin updated successfully.");
    } else {
        header("Location: admin_management.php?error=Failed to update admin.");
    }
}
?>
