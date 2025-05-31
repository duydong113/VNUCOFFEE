<?php
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
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel</title>
   <!-- Đường dẫn sửa lại đến admin_style.css -->
   <link rel="stylesheet" href="../css/admin_style.css"> 
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<header class="header">
   <section class="flex">
      <!-- Đường dẫn đến dashboard.php sửa lại -->
      <a href="dashboard.php" class="logo">VNUISCOFFEE<span>admin</span></a>
      <nav class="navbar">
         <!-- Các trang sẽ được liên kết với đường dẫn đúng trong thư mục admin -->
         <a href="dashboard.php">Home</a>
         <a href="products.php">Products</a>
         <a href="placed_orders.php">Orders</a>
         <!-- <a href="admin_accounts.php">Admins</a> -->
         <a href="users_accounts.php">Users</a>
         <a href="messages.php">Feedbacks</a>
      </nav>

      <div class="profile">
         <?php
            // Truy vấn thông tin admin từ bảng admin
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE AdminID = ?");
            $select_profile->execute([$admin_id]);  // Sử dụng AdminID trong session thay vì id
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <!-- Hiển thị tên admin -->
         <p><?= $fetch_profile['Username']; ?></p> <!-- Hiển thị tên đăng nhập của admin -->
         <a href="update_profile.php" class="btn">Update Profile</a>
         <div class="flex-btn">
            <!-- Đường dẫn tới các trang login và register của admin -->
            <a href="admin_login.php" class="option-btn">Login</a>
            <a href="register_admin.php" class="option-btn">Register</a>
         </div>
         <!-- Đường dẫn logout -->
         <a href="../admin/admin_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
      </div>

   </section>
</header>
</body>
</html>
