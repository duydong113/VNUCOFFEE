<h1>Order Confirmation</h1>
<?php
if (isset($_SESSION['order_id'])) {
  $order_id = $_SESSION['order_id'];
  // You could fetch order details again here if needed for a more detailed confirmation
  echo "<p class='alert alert-success'>Thank you for your order! Your Order ID is: <strong>#" . htmlspecialchars($order_id) . "</strong></p>";
  echo "<p>We have received your order and will begin processing it shortly. You will receive an email confirmation soon (feature to be implemented).</p>";
  echo "<p><a href='index.php?page=account' class='btn'>View My Orders</a> <a href='index.php?page=products' class='btn btn-secondary'>Continue Shopping</a></p>";

  unset($_SESSION['order_id']); // Clear it after displaying
} else {
  // If someone lands here directly without placing an order
  echo "<p class='alert alert-info'>No order to confirm. <a href='index.php?page=products'>Start shopping</a> or <a href='index.php?page=account'>view your past orders</a>.</p>";
}
?>