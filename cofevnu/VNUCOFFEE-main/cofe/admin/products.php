<?php
include '../includes/db_connect.php'; 

session_start();

$admin_id = $_SESSION['admin_id'];

// if(!isset($admin_id)){
//    header('location:admin_login.php');
// };

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `product` WHERE Name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Product name already exists!';
   }else{
      if($image_size > 2000000){
         $message[] = 'Image size is too large';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = $conn->prepare("INSERT INTO `product`(Name, CategoryID, Price, Image) VALUES(?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image]);

         $message[] = 'New product added!';
      }

   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `product` WHERE ProductID = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['Image']);
   $delete_product = $conn->prepare("DELETE FROM `product` WHERE ProductID = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE ProductID = ?");
   $delete_cart->execute([$delete_id]);
   header('location:products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../admin/admin_header.php' ?>

<!-- Search and Add Product Section -->
<section class="search-add-products">
   <h1 class="heading">Products</h1>
   
   <!-- Search Bar -->
   <div class="search-bar">
      <input type="text" placeholder="Search products..." id="productSearch" class="box">
   </div>

   <!-- Add Product Button -->
   <button class="btn" id="openAddProductForm">Add Product</button>

   <!-- Sort Button with Dropdown -->
   <div class="sort-dropdown">
      <button class="btn" id="openSortMenu">Sort</button>
      <div class="dropdown-content" id="sortMenu">
         <a href="javascript:void(0);" id="sortByPriceAsc">Price: Low to High</a>
         <a href="javascript:void(0);" id="sortByPriceDesc">Price: High to Low</a>
         <a href="javascript:void(0);" id="sortByName">Alphabetically</a>
      </div>
   </div>
</section>

<!-- Add Product Popup Form -->
<div class="popup" id="addProductPopup">
   <form action="" method="POST" enctype="multipart/form-data" class="popup-form">
      <h3>Add Product</h3>
      <input type="text" required placeholder="Enter product name" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Select category</option>
         <option value="1">Coffee</option>
         <option value="2">Tea</option>
         <option value="3">Smoothie</option>
         <option value="4">Juice</option>
         <option value="5">Desserts</option>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <input type="submit" value="Add Product" name="add_product" class="btn">
      <button type="button" class="btn-close" id="closeAddProductForm">Close</button>
   </form>
</div>
<!-- Show Products Table -->
<section class="show-products">

   <div class="products-table">
      <table>
         <thead>
            <tr>
               <th>Image</th> <!-- Add an image column -->
               <th>Product Name</th>
               <th>Category</th>
               <th>Price</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody id="productTableBody">
            <?php
               $show_products = $conn->prepare("SELECT * FROM `product`");
               $show_products->execute();
               if($show_products->rowCount() > 0){
                  while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
            ?>
            <tr>
               <td><img src="../uploaded_img/<?= $fetch_products['Image']; ?>" alt="Product Image" class="product-image"></td> <!-- Display product image -->
               <td><?= $fetch_products['Name']; ?></td>
               <td><?= $fetch_products['CategoryID']; ?></td>
               <td><?= $fetch_products['Price']; ?> VND</td>
               <td>
                  <a href="update_product.php?update=<?= $fetch_products['ProductID']; ?>" class="option-btn">Update</a>
                  <a href="products.php?delete=<?= $fetch_products['ProductID']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
               </td>
            </tr>
            <?php
                  }
               }else{
                  echo '<tr><td colspan="5">No products added yet.</td></tr>';
               }
            ?>
         </tbody>
      </table>
   </div>

</section>

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>

<script>
   // Open Add Product Popup
   document.getElementById('openAddProductForm').addEventListener('click', function() {
      document.getElementById('addProductPopup').style.display = 'flex';
   });

   // Close Add Product Popup
   document.getElementById('closeAddProductForm').addEventListener('click', function() {
      document.getElementById('addProductPopup').style.display = 'none';
   });

   // Product Search Functionality
   document.getElementById('productSearch').addEventListener('input', function() {
   let filter = this.value.toUpperCase();
   let rows = document.querySelectorAll('#productTableBody tr');

   rows.forEach(row => {
      let name = row.querySelector('td:nth-child(2)').textContent;  // Change to column 2
      if(name.toUpperCase().includes(filter)){
         row.style.display = '';
      } else {
         row.style.display = 'none';
      }
   });
});
// Sorting functionality
document.getElementById('openSortMenu').addEventListener('click', function() {
   document.getElementById('sortMenu').style.display = 'block';
});

document.getElementById('sortByPriceAsc').addEventListener('click', function() {
   sortProducts('price', 'asc');
});

document.getElementById('sortByPriceDesc').addEventListener('click', function() {
   sortProducts('price', 'desc');
});

document.getElementById('sortByName').addEventListener('click', function() {
   sortProducts('name', 'asc');
});

function sortProducts(criteria, order) {
   let rows = Array.from(document.querySelectorAll('#productTableBody tr'));
   
   rows.sort(function(a, b) {
      let cellA = a.querySelector(`td:nth-child(${criteria === 'name' ? 2 : 4})`).textContent;
      let cellB = b.querySelector(`td:nth-child(${criteria === 'name' ? 2 : 4})`).textContent;
      
      if (criteria === 'price') {
         cellA = parseFloat(cellA.replace(' VND', '').trim());
         cellB = parseFloat(cellB.replace(' VND', '').trim());
      }
      
      if (order === 'asc') {
         return cellA < cellB ? -1 : 1;
      } else {
         return cellA > cellB ? -1 : 1;
      }
   });
   
   let tbody = document.getElementById('productTableBody');
   tbody.innerHTML = '';
   rows.forEach(row => tbody.appendChild(row));
   
   // Close dropdown after sorting
   document.getElementById('sortMenu').style.display = 'none';
}


</script>

</body>
</html>
