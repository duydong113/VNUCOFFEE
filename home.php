<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>



<section class="hero">

   <div class="swiper hero-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <div class="content">
               <span>Indulge in Happiness</span>
               <h3>Salt cream coffee</h3>
               <a href="menu.html" class="btn">Menu</a>
            </div>
            <div class="image">
               <img src="images/home-img-1.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>Have a shot of</span>
               <h3>Espresso</h3>
               <a href="menu.html" class="btn">Menu</a>
            </div>
            <div class="image">
               <img src="images\espresso.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>Feel "Green" with</span>
               <h3>Matcha Latte</h3>
               <a href="menu.html" class="btn">Menu</a>
            </div>
            <div class="image">
               <img src="images\matcha.png" alt="">
            </div>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

<section class="category">

   <h1 class="title">Category</h1>

   <div class="box-container">

      <a href="category.php?category=Coffee" class="box">
      <img src="images/cat-3.png" alt="">
         <h3>Coffee</h3>
      </a>

      <a href="category.php?category=Tea" class="box">
      <img src="images/cat-3.png" alt="">
         <h3>Tea</h3>
      </a>

      <a href="category.php?category=Smoothies" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Other drinks</h3>
      </a>

      <a href="category.php?category=Desserts" class="box">
      <img src="images/cat-3.png" alt="">
         <h3>Desserts</h3>
      </a>

   </div>

</section>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/coffeehuman.svg" alt="">
      </div>

      <div class="content">
         <h3>Why you should choose VNUISCOFFEE</h3>
         <p>At VNUISCoffee, we’re more than just a cup of coffee — we’re a community. Sourced from the finest local beans and crafted with passion, every sip delivers rich flavor and authentic Vietnamese essence. Whether you're fueling your study session or catching up with friends, VNUCoffee is your perfect companion. Quality, warmth, and a taste that feels like home — that’s the VNUCoffee promise.</p>
         <a href="menu.html" class="btn">Our menu</a>
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="title">Customer's reviews</h1>

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <img src="images/pic-kd.png" alt="">
            <p>"I absolutely love VNUCoffee! The coffee is rich and smooth, and the atmosphere is so cozy — it’s the perfect spot to relax or get some work done. The staff are super friendly and always remember my favorite drink. It really feels like a second home!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Anh Khánh Duy</h3>
         </div>
         <div class="swiper-slide slide">
            <img src="images/pic-kd.png" alt="">
            <p>"I absolutely love VNUCoffee! The coffee is rich and smooth, and the atmosphere is so cozy — it’s the perfect spot to relax or get some work done. The staff are super friendly and always remember my favorite drink. It really feels like a second home!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Anh Khánh Duy</h3>
         </div><div class="swiper-slide slide">
            <img src="images/pic-kd.png" alt="">
            <p>"I absolutely love VNUCoffee! The coffee is rich and smooth, and the atmosphere is so cozy — it’s the perfect spot to relax or get some work done. The staff are super friendly and always remember my favorite drink. It really feels like a second home!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Anh Khánh Duy</h3>
         </div><div class="swiper-slide slide">
            <img src="images/pic-kd.png" alt="">
            <p>"I absolutely love VNUCoffee! The coffee is rich and smooth, and the atmosphere is so cozy — it’s the perfect spot to relax or get some work done. The staff are super friendly and always remember my favorite drink. It really feels like a second home!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Anh Khánh Duy</h3>
         </div><div class="swiper-slide slide">
            <img src="images/pic-kd.png" alt="">
            <p>"I absolutely love VNUCoffee! The coffee is rich and smooth, and the atmosphere is so cozy — it’s the perfect spot to relax or get some work done. The staff are super friendly and always remember my favorite drink. It really feels like a second home!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Anh Khánh Duy</h3>
         </div><div class="swiper-slide slide">
            <img src="images/pic-kd.png" alt="">
            <p>"I absolutely love VNUCoffee! The coffee is rich and smooth, and the atmosphere is so cozy — it’s the perfect spot to relax or get some work done. The staff are super friendly and always remember my favorite drink. It really feels like a second home!"</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Anh Khánh Duy</h3>
         </div>

        

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

<!-- reviews section ends -->



<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   grabCursor: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
      slidesPerView: 1,
      },
      700: {
      slidesPerView: 2,
      },
      1024: {
      slidesPerView: 3,
      },
   },
});

</script>
<section class="products">

   <h1 class="title">Best seller</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>$</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>

   </div>

   <div class="more-btn">
      <a href="menu.html" class="btn">view all</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".hero-slider", {
   loop:true,
   grabCursor: true,
   effect: "flip",
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
});

</script>

</body>
</html>