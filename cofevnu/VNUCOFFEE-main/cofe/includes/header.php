<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Define BASE_URL for easy linking
define('BASE_URL', '/cofe/'); // Adjusted to match directory name
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cofe Shop</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- Add Google Fonts if using Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
  <script src="js/main.js" defer></script>
</head>

<body>
  <header>
    <div class="container">
      <div id="logo">
        <a href="index.php">VNU Coffeeshop</a>
      </div>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="index.php?page=products">Products</a></li>
          <li><a href="index.php?page=cart">Cart
              <?php
              // Basic cart count - more robust solution would be a DB query if cart is DB based for logged-in users
              if (isset($_SESSION['user_id']) && isset($conn)) { // $conn check if db_connect was included
                $count_stmt = $conn->prepare("SELECT SUM(Quantity) AS total_items FROM CART WHERE CustomerID = ?");
                if ($count_stmt) {
                  $count_stmt->bind_param("i", $_SESSION['user_id']);
                  $count_stmt->execute();
                  $count_result = $count_stmt->get_result();
                  $count_row = $count_result->fetch_assoc();
                  if ($count_row && $count_row['total_items'] > 0) {
                    echo ' (' . $count_row['total_items'] . ')';
                  }
                  $count_stmt->close();
                }
              } elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $item_count = 0;
                foreach ($_SESSION['cart'] as $item) $item_count += $item['quantity'];
                if ($item_count > 0) echo ' (' . $item_count . ')';
              }
              ?>
            </a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="index.php?page=account">My Account</a></li>
            <li><a href="index.php?page=logout">Logout</a></li>
          <?php else: ?>
            <li><a href="index.php?page=login">Login</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <div class="container">
      <?php
      // Display session messages
      if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
      }
      ?>