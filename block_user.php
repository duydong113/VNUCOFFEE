<?php
include('../includes/db_connect.php');

// Kiểm tra nếu có ID người dùng
if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    // Cập nhật trạng thái người dùng thành "Blocked"
    $query = "UPDATE customer SET Status = 'Blocked' WHERE CustomerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        // Redirect lại trang quản lý sau khi block
        header('Location:users.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "User ID is required.";
}
?>
