<?php
session_start();
include('../includes/db_connect.php'); // Kết nối với cơ sở dữ liệu

// Kiểm tra nếu có dữ liệu gửi lên từ form tạo admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Kiểm tra mật khẩu có hợp lệ không (tối thiểu 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt)
    function validate_password($password) {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            return "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            return "Password must contain at least one number.";
        }
        if (!preg_match('/[\W_]/', $password)) {
            return "Password must contain at least one special character (e.g., !@#$%^&*).";
        }
        return true;
    }

    $password_validation = validate_password($password);
    if ($password_validation !== true) {
        // Nếu mật khẩu không hợp lệ, chuyển hướng và hiển thị thông báo lỗi
        header("Location: admin_management.php?error=" . urlencode($password_validation));
        exit();
    }

    // Kiểm tra xem username, email hoặc phone đã tồn tại trong cơ sở dữ liệu chưa
    $query = "SELECT * FROM admin WHERE Username = ? OR Email = ? OR Phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu trùng username, email hoặc phone, chuyển hướng và hiển thị thông báo lỗi
        header("Location: admin_management.php?error=" . urlencode("Username, Email, or Phone is already taken."));
        exit();
    }

    // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Truy vấn để thêm admin mới vào cơ sở dữ liệu
    $query = "INSERT INTO admin (Username, FullName, Email, Phone, Role, Password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $username, $full_name, $email, $phone, $role, $hashed_password);

    if ($stmt->execute()) {
        // Nếu tạo tài khoản thành công, chuyển hướng và hiển thị thông báo thành công
        header("Location: admin_management.php?success=" . urlencode("Admin account created successfully!"));
        exit();
    } else {
        // Nếu có lỗi khi thêm admin, chuyển hướng và hiển thị thông báo lỗi
        header("Location: admin_management.php?error=" . urlencode("Error: " . $stmt->error));
        exit();
    }
}
?>
