<?php

include '../includes/db_connect.php'; 

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="VNUCOFFEESHOP/css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- Admins Accounts Section Starts -->
<section class="accounts">

   <h1 class="heading">Admins Account</h1>

   <!-- Register New Admin Section -->
   <a href="register_admin.php" class="register-btn">Register New Admin</a>

   <!-- Admin Accounts Table -->
   <div class="box-container">
      <table class="accounts-table">
         <thead>
            <tr>
               <th>Admin ID</th>
               <th>Username</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_account = $conn->prepare("SELECT * FROM `admin`");
            $select_account->execute();
            if($select_account->rowCount() > 0){
               while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
            ?>
            <tr>
               <td><?= $fetch_accounts['id']; ?></td>
               <td><?= $fetch_accounts['name']; ?></td>
         <td>
   <div class="actions-container">
      <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn-custom" onclick="return confirm('Delete this account?');">Delete</a>
      <?php
         if($fetch_accounts['id'] == $admin_id){
            echo '<a href="update_profile.php" class="update-btn-custom">Update</a>';
         }
      ?>
   </div>
</td>


            </tr>
            <?php
               }
            } else {
               echo '<tr><td colspan="3" class="empty">No accounts available</td></tr>';
            }
            ?>
         </tbody>
      </table>
   </div>

</section>
<!-- Admins Accounts Section Ends -->

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
