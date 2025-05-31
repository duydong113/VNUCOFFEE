<?php

include '../includes/db_connect.php';  

session_start();

// Kiểm tra xem người dùng đã gửi form đăng nhập chưa
if (isset($_POST['submit'])) {
    // Kiểm tra nếu dữ liệu từ form đã được gửi
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Làm sạch dữ liệu đầu vào để tránh SQL Injection
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Truy vấn thông tin admin từ bảng admin với tên đăng nhập
    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE Username = ?");
    $select_admin->bind_param("s", $username); // "s" cho chuỗi
    $select_admin->execute();
    $result = $select_admin->get_result(); // Lấy kết quả của câu truy vấn

    // Kiểm tra nếu có kết quả trả về
    if ($result->num_rows > 0) {
        // Nếu tìm thấy admin, lấy thông tin admin
        $fetch_admin = $result->fetch_assoc();
        
        // Kiểm tra mật khẩu bằng cách sử dụng password_verify() (so với mật khẩu đã mã hóa)
        if (password_verify($password, $fetch_admin['Password'])) {
            // Mật khẩu đúng, lưu AdminID vào session
            $_SESSION['admin_id'] = $fetch_admin['AdminID'];
            header('location:dashboard.php');  // Chuyển hướng đến trang dashboard
        } else {
            // Nếu mật khẩu sai, hiển thị thông báo lỗi
            $message[] = 'Incorrect password!';
        }
    } else {
        // Nếu không tìm thấy admin với tên đăng nhập đó, hiển thị thông báo lỗi
        $message[] = 'Incorrect username!';
    }
}
?>


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php
// Hiển thị thông báo nếu có lỗi
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- Admin Login Form Section Starts -->
<section class="form-container">
   <form action="" method="POST">
      <h3>Login Now</h3>
      <p>Welcome to VNUISCOFFEEadmin</p>
      <!-- Tên đăng nhập -->
      <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <!-- Mật khẩu -->
      <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <!-- Nút submit -->
      <input type="submit" value="Login Now" name="submit" class="btn">
   </form>
</section>
<!-- Admin Login Form Section Ends -->

</body>
</html>
