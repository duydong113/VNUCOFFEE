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
   <title>VNUIS Coffee</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   <style>
      :root {
         --white: #fff;
         --black: #333;
         --yellow:rgb(105, 33, 0); /* Màu nâu nhạt */
         --light-color: #999;
         --border: 0.1rem solid rgba(0,0,0,0.1);
      }

      .header{
         position: sticky;
         top:0; left:0; right:0;
         z-index: 1000;
         background-color: var(--white);
         border-bottom: var(--border);
      }

      .header .flex{
         display: flex;
         align-items: center;
         justify-content: space-between;
         position: relative;
      }

      .header .flex .logo{
         font-size: 2.5rem;
         color:var(--black);
         font-weight: bold;
         text-transform: uppercase;
      }

      .header .flex .navbar a{
         font-size: 2rem;
         color:var(--black);
         margin:0 1rem;
         display: inline-block;
         transition: all 0.3s ease;
      }

      .header .flex .navbar a:hover{
         color: var(--yellow);
         transform: scale(1.1);
      }

      .header .flex .icons > *{
         margin-left: 1.5rem;
         font-size: 2.5rem;
         color:var(--black);
         cursor: pointer;
         transition: all 0.3s ease;
      }

      .header .flex .icons > *:hover{
         color: var(--yellow);
         transform: scale(1.2);
      }

      .header .flex .icons span{
         font-size: 2rem;
      }

      #menu-btn{
         display: none;
      }

      .header .flex .profile{
         background-color: var(--white);
         border:var(--border);
         padding:1.5rem;
         text-align: center;
         position: absolute;
         top:125%; right:2rem;
         width: 30rem;
         display: none;
         animation: fadeIn .2s linear;
      }

      .header .flex .profile.active{
         display: inline-block;
      }

      @keyframes fadeIn {
         0%{
            transform: translateY(1rem);
         }
      }

      .header .flex .profile .name{
         font-size: 2rem;
         color:var(--black);
         margin-bottom: .5rem;
      }

      .header .flex .profile .account{
         margin-top: 1.5rem;
         font-size: 2rem;
         color:var(--light-color);
      }

      .header .flex .profile .account a{
         color:var(--black);
      }

      .header .flex .profile .account a:hover{
         color:var(--yellow); 
      }
   </style>
</head>
<body>

<header class="header">
   <section class="flex">

      <a href="home.php" class="logo">VNUIS Coffee</a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="about.php">About</a>
         <a href="menu.php">Menu</a>
         <a href="orders.php">Orders</a>
         <a href="contact.php">Contact</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         </div>
         <p class="account">
            <a href="login.php">Login</a> or
            <a href="register.php">Register</a>
         </p> 
         <?php
            }else{
         ?>
            <p class="name">Please login first!</p>
            <a href="login.php" class="btn">Login</a>
         <?php
          }
         ?>
      </div>

   </section>
</header>

</body>
</html>