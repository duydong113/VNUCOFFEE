<?php

include '../includes/db_connect.php';  

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE AdminID = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../admin/admin_header.php' ?>

<!-- Admin Dashboard Section Starts -->
<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Welcome back!</h3>
         <!-- Hiển thị tên admin -->
         <p><?= $fetch_profile['Username']; ?></p> <!-- Đổi từ 'name' thành 'Username' -->
         <a href="update_profile.php" class="btn">Update profile</a>
      </div>

      <div class="box">
         <?php
            // Tính tổng tiền các đơn hàng đã hoàn thành
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `order` WHERE Status = ?"); // Chú ý thay 'payment_status' thành 'Status' nếu cần
            $select_completes->execute(['delivered']); // Giả sử 'delivered' là trạng thái hoàn tất
            while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
               $total_completes += $fetch_completes['TotalPrice']; // Tính tổng giá trị của các đơn hàng
            }
         ?>
         <h3><?= $total_completes; ?><span> VNĐ</span></h3>
         <p>Total completed orders</p>
         <a href="placed_orders.php" class="btn">See orders</a>
      </div>

      <div class="box">
         <?php
            // Đếm tổng số đơn hàng
            $select_orders = $conn->prepare("SELECT * FROM `order`");
            $select_orders->execute();
            $numbers_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $numbers_of_orders; ?></h3>
         <p>Total orders</p>
         <a href="placed_orders.php" class="btn">See orders</a>
      </div>

      <div class="box">
         <?php
            // Đếm tổng số sản phẩm
            $select_products = $conn->prepare("SELECT * FROM `product`");
            $select_products->execute();
            $numbers_of_products = $select_products->rowCount();
         ?>
         <h3><?= $numbers_of_products; ?></h3>
         <p>Products added</p>
         <a href="products.php" class="btn">See products</a>
      </div>

      <div class="box">
         <?php
            // Đếm tổng số người dùng (từ bảng customer)
            $select_users = $conn->prepare("SELECT * FROM `customer`");
            $select_users->execute();
            $numbers_of_users = $select_users->rowCount();
         ?>
         <h3><?= $numbers_of_users; ?></h3>
         <p>Users accounts</p>
         <a href="users_accounts.php" class="btn">See users</a>
      </div>

      <div class="box">
         <?php
            // Đếm tổng số feedback/messages
            $select_messages = $conn->prepare("SELECT * FROM `feedback`"); // Thay 'messages' thành 'feedback' nếu cần
            $select_messages->execute();
            $numbers_of_messages = $select_messages->rowCount();
         ?>
         <h3><?= $numbers_of_messages; ?></h3>
         <p>Feedbacks</p>
         <a href="messages.php" class="btn">See messages</a>
      </div>

   </div>

</section>
<!-- Admin Dashboard Section Ends -->

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
