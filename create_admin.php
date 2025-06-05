<?php
include('../includes/db_connect.php'); // Kết nối với cơ sở dữ liệu

// Tạo thông tin tài khoản admin
$username = 'admin'; 
$password = 'admin123';

// Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Thực hiện câu lệnh SQL để thêm tài khoản admin vào bảng admin
$query = "INSERT INTO admin (Username, Password) VALUES (?, ?)";
$stmt = $conn->prepare($query);

// Bind tham số và thực thi câu lệnh
$stmt->bind_param("ss", $username, $hashed_password);
$stmt->execute();

// Kiểm tra nếu câu lệnh thành công
if ($stmt->affected_rows > 0) {
    echo "Admin account created successfully!";
} else {
    echo "Error creating admin account: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
