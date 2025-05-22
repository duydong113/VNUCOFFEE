<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
  header('location:home.php');
};

// Check if payment was successful
if (isset($_GET['payment_intent']) && isset($_GET['payment_intent_client_secret'])) {
  $payment_intent_id = $_GET['payment_intent'];

  // Process the order if payment was successful
  if (isset($_SESSION['order_details'])) {
    $order_details = $_SESSION['order_details'];

    $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
    $insert_order->execute([
      $user_id,
      $order_details['name'],
      $order_details['number'],
      $order_details['email'],
      'stripe',
      $order_details['address'],
      $order_details['total_products'],
      $order_details['total_price']
    ]);

    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$user_id]);

    // Clear the session
    unset($_SESSION['order_details']);
    unset($_SESSION['payment_intent_id']);

    $message[] = 'Payment successful! Your order has been placed.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Success</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php include 'components/user_header.php'; ?>

  <div class="heading">
    <h3>Payment Success</h3>
    <p><a href="home.php">home</a> <span> / payment success</span></p>
  </div>

  <section class="checkout">
    <div class="success-message">
      <h1>Thank you for your order!</h1>
      <p>Your payment was successful and your order has been placed.</p>
      <a href="orders.php" class="btn">View Orders</a>
    </div>
  </section>

  <?php include 'components/footer.php'; ?>
</body>

</html>