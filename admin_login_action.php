<?php
session_start();
include('../includes/db_connect.php'); // Kết nối với cơ sở dữ liệu

// Kiểm tra nếu có dữ liệu gửi lên từ form đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn để kiểm tra tài khoản admin
    $query = "SELECT * FROM admin WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu có tài khoản admin nào trùng với username không
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Kiểm tra mật khẩu, sử dụng password_verify nếu mật khẩu đã được mã hóa
        if (password_verify($password, $admin['Password'])) {
            // Đăng nhập thành công, lưu thông tin vào session
            $_SESSION['admin_id'] = $admin['AdminID'];

            // Chuyển hướng đến trang dashboard (admin_dashboard.php)
            header("Location: index.php");
            exit(); // Dừng việc thực thi script sau khi chuyển hướng
        } else {
            // Nếu mật khẩu sai, chuyển hướng về trang login với thông báo lỗi
            header("Location: login.php?error=Incorrect password.");
            exit();
        }
    } else {
        // Nếu không tìm thấy tài khoản admin
        header("Location: login.php?error=Admin not found.");
        exit();
    }
} else {
    // Nếu không có form POST gửi lên, chuyển hướng về trang login
    header("Location: login.php");
    exit();
}
?>
