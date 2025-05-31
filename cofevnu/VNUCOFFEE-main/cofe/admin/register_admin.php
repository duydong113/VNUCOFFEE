<?php
include '../includes/db_connect.php'; 

session_start();

// $admin_id = $_SESSION['admin_id'];

// if(!isset($admin_id)){
//    header('location:admin_login.php');
// };

if(isset($_POST['submit'])){

   $username = $_POST['name'];
   $username = filter_var($username, FILTER_SANITIZE_STRING);
   $password = $_POST['pass'];
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   $confirm_password = $_POST['cpass'];
   $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);

   // Kiểm tra nếu tên đăng nhập đã tồn tại trong bảng admin
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE Username = ?");
   $select_admin->execute([$username]);
   
   if($select_admin->rowCount() > 0){
      $message[] = 'Username already exists!';
   }else{
      if($password != $confirm_password){
         $message[] = 'Confirm password not matched!';
      }else{
         // Mã hóa mật khẩu trước khi lưu vào database
         $hashed_password = password_hash($password, PASSWORD_DEFAULT);

         // Thêm admin mới vào bảng admin
         $insert_admin = $conn->prepare("INSERT INTO `admin`(Username, Password) VALUES(?,?)");
         $insert_admin->execute([$username, $hashed_password]);

         $message[] = 'New admin registered!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Registration</title>
   <!-- Link to custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <!-- Font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

</head>

<body>
   <?php include '../admin/admin_header.php' ?>

   <!-- Admin Account Section -->
   <section class="form-container">
      <form action="" method="POST">
         <h3>Register new admin</h3>
         <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" maxlength="20" required placeholder="Confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Register Now" name="submit" class="btn">
      </form>
   </section>

   <!-- Accounts Section -->
   <section class="accounts">
      <div class="box-container">
         <div class="box-container-header">
            <th>Admin ID</th>
            <th>Username</th>
            <th>Actions</th>
         </div>
         <div class="box">
            <!-- Here you need to fetch and display the list of registered admins -->
            <?php
               $select_admins = $conn->prepare("SELECT * FROM `admin`");
               $select_admins->execute();
               while($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)){
            ?>
            <p><span>Admin ID:</span> <?= $fetch_admins['AdminID'] ?></p>
            <p><span>Username:</span> <?= $fetch_admins['Username'] ?></p>
            <button class="delete-btn">Delete</button>
            <button class="update-btn">Update</button>
            <?php } ?>
         </div>
      </div>
   </section>

   <!-- Register New Admin Button -->
   <button id="registerBtn" class="btn">Register New Admin</button>

   <!-- Popup Container for Register -->
   <div id="popup" class="popup-container">
      <button class="close-btn" onclick="closePopup()">X</button>
      <h3>Register New Admin</h3>
      <form action="" method="POST">
         <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box">
         <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="box">
         <input type="password" name="cpass" maxlength="20" required placeholder="Confirm your password" class="box">
         <input type="submit" value="Register" name="submit" class="btn">
      </form>
   </div>

   <script>
      // Open the register popup
      document.getElementById('registerBtn').addEventListener('click', function() {
         document.getElementById('popup').classList.add('active');
      });

      // Close the register popup
      function closePopup() {
         document.getElementById('popup').classList.remove('active');
      }
   </script>
</body>
</html>
