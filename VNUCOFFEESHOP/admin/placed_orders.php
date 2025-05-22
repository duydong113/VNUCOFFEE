<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>
<!-- placed orders section starts -->
<section class="placed-orders">

   <h1 class="heading">Placed Orders</h1>

   <div class="orders-table">
      <table>
         <thead>
            <tr>
               <th>User ID</th>
               <th>Placed On</th>
               <th>Name</th>
               <th>Total Products</th>
               <th>Total Price</th>
               <th>Actions</th>
               <th>View Detail</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $select_orders = $conn->prepare("SELECT * FROM `orders`");
               $select_orders->execute();
               if($select_orders->rowCount() > 0){
                  while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
               <td><?= $fetch_orders['user_id']; ?></td>
               <td><?= $fetch_orders['placed_on']; ?></td>
               <td><?= $fetch_orders['name']; ?></td>
               <td><?= $fetch_orders['total_products']; ?></td>
               <td><?= $fetch_orders['total_price']; ?> VND</td>
               <td>
                  <div class="actions">
                     <form action="" method="POST" style="display: inline;">
                        <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                        <select name="payment_status" class="drop-down">
                           <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                           <option value="pending">pending</option>
                           <option value="completed">completed</option>
                        </select>
                        <input type="submit" value="Update" class="btn" name="update_payment">
                     </form>
                     <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
                  </div>
               </td>
               <td>
                  <button class="view-detail-btn" onclick="toggleDetail(<?= $fetch_orders['id']; ?>)">View Detail</button>
               </td>
            </tr>

            <!-- Hidden Detailed Information Row (Initially Hidden) -->
            <tr id="detail-row-<?= $fetch_orders['id']; ?>" class="detail-row">
               <td colspan="7">
                  <div class="order-details">
                     <p><strong>User ID:</strong> <?= $fetch_orders['user_id']; ?></p>
                     <p><strong>Placed On:</strong> <?= $fetch_orders['placed_on']; ?></p>
                     <p><strong>Name:</strong> <?= $fetch_orders['name']; ?></p>
                     <p><strong>Email:</strong> <?= $fetch_orders['email']; ?></p>
                     <p><strong>Number:</strong> <?= $fetch_orders['number']; ?></p>
                     <p><strong>Address:</strong> <?= $fetch_orders['address']; ?></p>
                     <p><strong>Total Products:</strong> <?= $fetch_orders['total_products']; ?></p>
                     <p><strong>Total Price:</strong> <?= $fetch_orders['total_price']; ?> VND</p>
                     <p><strong>Payment Method:</strong> <?= $fetch_orders['method']; ?></p>
                  </div>
               </td>
            </tr>
            <?php
                  }
               }else{
                  echo '<tr><td colspan="7">No orders placed yet!</td></tr>';
               }
            ?>
         </tbody>
      </table>
   </div>
<script> 
function toggleDetail(orderId) {
   const detailRow = document.getElementById('detail-row-' + orderId);
   // Toggle the visibility of the detail row
   if (detailRow.style.display === "table-row") {
      detailRow.style.display = "none";
   } else {
      detailRow.style.display = "table-row";
   }
}
</script>
</section>

<!-- placed orders section ends -->










<!-- custom js file link  -->
<script src="../js/admin_script.js"></>

</body>
</html>