<?php
include '../includes/db_connect.php'; 

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `order` SET Status = ? WHERE OrderID = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `order` WHERE OrderID = ?");
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
   <title>Placed Orders</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../admin/admin_header.php' ?>

<!-- Placed Orders Section Starts -->
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
               $select_orders = $conn->prepare("SELECT * FROM `order`");
               $select_orders->execute();
               if($select_orders->rowCount() > 0){
                  while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
               <td><?= $fetch_orders['CustomerID']; ?></td>
               <td><?= $fetch_orders['OrderDate']; ?></td>
               <td><?= $fetch_orders['CustomerID']; ?></td>
               <td><?= $fetch_orders['TotalProducts']; ?></td>
               <td><?= $fetch_orders['TotalPrice']; ?> VND</td>
               <td>
                  <div class="actions">
                     <form action="" method="POST" style="display: inline;">
                        <input type="hidden" name="order_id" value="<?= $fetch_orders['OrderID']; ?>">
                        <select name="payment_status" class="drop-down">
                           <option value="" selected disabled><?= $fetch_orders['Status']; ?></option>
                           <option value="pending">Pending</option>
                           <option value="completed">Completed</option>
                        </select>
                        <input type="submit" value="Update" class="btn" name="update_payment">
                     </form>
                     <a href="placed_orders.php?delete=<?= $fetch_orders['OrderID']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
                  </div>
               </td>
               <td>
                  <button class="view-detail-btn" onclick="toggleDetail(<?= $fetch_orders['OrderID']; ?>)">View Detail</button>
               </td>
            </tr>

            <!-- Hidden Detailed Information Row (Initially Hidden) -->
            <tr id="detail-row-<?= $fetch_orders['OrderID']; ?>" class="detail-row">
               <td colspan="7">
                  <div class="order-details">
                     <p><strong>User ID:</strong> <?= $fetch_orders['CustomerID']; ?></p>
                     <p><strong>Placed On:</strong> <?= $fetch_orders['OrderDate']; ?></p>
                     <p><strong>Name:</strong> <?= $fetch_orders['CustomerID']; ?></p>
                     <p><strong>Email:</strong> <?= $fetch_orders['Email']; ?></p>
                     <p><strong>Number:</strong> <?= $fetch_orders['PhoneNum']; ?></p>
                     <p><strong>Address:</strong> <?= $fetch_orders['AddressID']; ?></p>
                     <p><strong>Total Products:</strong> <?= $fetch_orders['TotalProducts']; ?></p>
                     <p><strong>Total Price:</strong> <?= $fetch_orders['TotalPrice']; ?> VND</p>
                     <p><strong>Payment Method:</strong> <?= $fetch_orders['Method']; ?></p>
                  </div>
               </td>
            </tr>
            <?php
                  }
               } else {
                  echo '<tr><td colspan="7">No orders placed yet!</td></tr>';
               }
            ?>
         </tbody>
      </table>
   </div>

<script>
function toggleDetail(orderId) {
   const detailRow = document.getElementById('detail-row-' + orderId);
   if (detailRow.style.display === "table-row") {
      detailRow.style.display = "none";
   } else {
      detailRow.style.display = "table-row";
   }
}
</script>

</section>

<!-- Placed Orders Section Ends -->

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
