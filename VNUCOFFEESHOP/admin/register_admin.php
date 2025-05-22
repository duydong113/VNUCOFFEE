<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

// if(!isset($admin_id)){
//    header('location:admin_login.php');
// };

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   if($select_admin->rowCount() > 0){
      $message[] = 'username already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'new admin registered!';
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
   <link rel="stylesheet" href="VNUCOFFEESHOP/css/admin_style.css">
   <!-- Font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
</head>

<body>
   <?php include '../components/admin_header.php' ?>

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
            <p><span>Admin ID:</span> 51</p>
            <p><span>Username:</span> toilaadmin</p>
            <button class="delete-btn">Delete</button>
            <button class="update-btn">Update</button>
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
