<?php
// No session_start() here, it's in header.php
require_once 'includes/db_connect.php'; // Ensure DB connection is available for header cart count

// Include header
include 'includes/header.php';

// Determine page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Page content
switch ($page) {
  case 'home':
    include 'pages/home.php';
    break;
  case 'products':
    include 'pages/products.php';
    break;
  case 'product_detail':
    include 'pages/product_detail.php';
    break;
  case 'cart':
    include 'pages/cart.php';
    break;
  case 'checkout':
    include 'pages/checkout.php';
    break;
  case 'order_confirmation':
    include 'pages/order_confirmation.php';
    break;
  case 'login':
    include 'pages/login.php';
    break;
  case 'register':
    include 'pages/register.php';
    break;
  case 'about':
    include 'pages/about.php';
    break;
  case 'account':
    // Protect this page
    if (!isset($_SESSION['user_id'])) {
      $_SESSION['message'] = "You must be logged in to view this page.";
      $_SESSION['message_type'] = "danger";
      header("Location: index.php?page=login");
      exit();
    }
    include 'pages/account.php';
    break;
  case 'edit_account':
    include 'pages/edit_account.php';
    break;
  case 'change_password':
    include 'pages/change_password.php';
    break;
  case 'logout':
    include 'pages/logout.php';
    break;
  case 'dashboard':
    // Optional: Add admin check here
    include 'pages/dashboard.php';
    break;
  default:
    include 'pages/home.php'; // Or a 404 page
    break;
}

// Close main tag before footer
echo '</main>';

// Include footer
include 'includes/footer.php';

// Close DB connection if it was opened
if (isset($conn)) {
  $conn->close();
}

?>

<!-- Add to Cart Success Modal -->
<div id="addToCartModal" class="modal">
  <div class="modal-content">
    <span class="close-button">&times;</span>
    <h2>Thành công!</h2>
    <p id="modalMessage"></p>
    <div class="modal-actions">
      <a href="index.php?page=cart" class="btn">Xem giỏ hàng</a>
      <a href="index.php?page=products" class="btn btn-secondary">Tiếp tục mua sắm</a>
    </div>
  </div>
</div>

</body>

</html>

<!-- Order Confirmation Modal -->
<div id="orderConfirmationModal" class="modal">
  <div class="modal-content">
    <span class="close-button">&times;</span>
    <h2>Confirm Your Order</h2>
    <div id="order-details-summary">
      <!-- Order details will be inserted here by JavaScript -->
    </div>
    <div class="modal-actions">
      <button id="confirm-place-order" class="btn">Confirm and Place Order</button>
      <button id="cancel-place-order" class="btn btn-secondary">Cancel</button>
    </div>
  </div>
</div>