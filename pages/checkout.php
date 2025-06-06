<?php
if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = "You must be logged in to checkout.";
  $_SESSION['message_type'] = "danger";
  header("Location: index.php?page=login");
  exit();
}

$customer_id = $_SESSION['user_id'];

// Fetch user's most recent address from DB or session
$address_details = null;

// Check if address details are in session (from a previous order)
if (isset($_SESSION['last_used_address'])) {
  $address_details = $_SESSION['last_used_address'];
} else {
  // Fetch user's most recent address from DB if not in session
  $address_stmt = $conn->prepare("SELECT Province, District, Ward, Detail FROM address WHERE CustomerID = ? ORDER BY AddressID DESC LIMIT 1");
  if ($address_stmt) {
    $address_stmt->bind_param("i", $customer_id);
    $address_stmt->execute();
    $address_result = $address_stmt->get_result();
    $address_details = $address_result->fetch_assoc();
    $address_stmt->close();
  }
}

// Fetch cart items to ensure cart is not empty and calculate total
$cart_items_stmt = $conn->prepare(
  "SELECT p.Price, c.Quantity
     FROM CART c
     JOIN PRODUCT p ON c.ProductID = p.ProductID
     WHERE c.CustomerID = ?"
);
$cart_items_stmt->bind_param("i", $customer_id);
$cart_items_stmt->execute();
$cart_result = $cart_items_stmt->get_result();

if ($cart_result->num_rows == 0) {
  $_SESSION['message'] = "Your cart is empty. Add some items before checking out.";
  $_SESSION['message_type'] = "info";
  header("Location: index.php?page=cart");
  exit();
}

$total_price = 0;
while ($item = $cart_result->fetch_assoc()) {
  $total_price += $item['Price'] * $item['Quantity'];
}
$cart_items_stmt->close();

// Fetch customer details for pre-filling form (optional)
$cust_stmt = $conn->prepare("SELECT Firstname, Lastname, Email, PhoneNum FROM CUSTOMER WHERE CustomerID = ?");
$cust_stmt->bind_param("i", $customer_id);
$cust_stmt->execute();

$cust_result = $cust_stmt->get_result();

$customer_details = $cust_result->fetch_assoc();
$cust_stmt->close();

?>
<br />
<br />
<br />
<h1>Checkout</h1>
<p>Please confirm your details and place your order.</p>

<form action="<?php echo BASE_URL; ?>actions/order_action.php" method="POST">
  <h3>Thông tin giao hàng</h3>

  <div class="form-group">
    <label for="province">Tỉnh / Thành phố:</label>
    <select id="province" name="province" required <?php echo $address_details && isset($address_details['Province']) ? 'data-prefill="' . htmlspecialchars($address_details['Province']) . '">' : '>' ?>
      <option value="">-- Chọn Tỉnh/Thành phố --</option>
      <!-- Options sẽ được điền bằng JavaScript -->
    </select>
  </div>

  <div class="form-group">
    <label for="district">Quận / Huyện:</label>
    <select id="district" name="district" required disabled <?php echo $address_details && isset($address_details['District']) ? 'data-prefill="' . htmlspecialchars($address_details['District']) . '">' : '>' ?>
      <option value="">-- Chọn Quận/Huyện --</option>
      <!-- Options sẽ được điền bằng JavaScript dựa trên Tỉnh đã chọn -->
    </select>
  </div>

  <div class="form-group">
    <label for="ward">Phường / Xã:</label>
    <select id="ward" name="ward" required disabled <?php echo $address_details && isset($address_details['Ward']) ? 'data-prefill="' . htmlspecialchars($address_details['Ward']) . '">' : '>' ?>
      <option value="">-- Chọn Phường/Xã --</option>
      <!-- Options sẽ được điền bằng JavaScript dựa trên Quận đã chọn -->
    </select>
  </div>

  <!-- Note: Populating the dropdowns requires a dataset of administrative divisions and JavaScript/AJAX implementation. -->
  <!-- Dữ liệu có thể lấy từ package vn-province-district-ward, nhưng cần xử lý để tích hợp vào môi trường PHP/JS truyền thống. -->

  <div class="form-group">
    <label for="shipping_address">Địa chỉ chi tiết (Số nhà, tên đường...):</label>
    <textarea id="shipping_address" name="shipping_address" rows="3" required><?php echo htmlspecialchars($address_details['Detail'] ?? ''); ?></textarea>
  </div>

  <p><a href="#" id="enter-new-address">Enter a new address</a></p>

  <div class="form-group">
    <label for="phone_num">Số điện thoại liên hệ (cho đơn hàng):</label>
    <input type="text" id="phone_num" name="phone_num" value="<?php echo htmlspecialchars($customer_details['PhoneNum'] ?? ''); ?>" required>
  </div>

  <h3>Order Summary</h3>
  <p><strong>Total Amount: $<?php echo number_format($total_price, 2); ?></strong></p>
  <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

  <!-- Payment section -->
  <h3>Phương thức thanh toán</h3>

  <div class="form-group">
    <label><input type="radio" name="payment_method" value="cod" checked required> Thanh toán khi nhận hàng (COD)</label><br>
    <label><input type="radio" name="payment_method" value="vnpay" required> VNPAY</label><br>
    <label><input type="radio" name="payment_method" value="paypal" required> PayPal</label><br>
    <label><input type="radio" name="payment_method" value="momo" required> Momo</label>
  </div>

  <button type="submit" name="place_order" class="btn">Đặt hàng</button>
  <input type="hidden" name="place_order" value="1">
  <input type="hidden" name="order_timestamp" id="order_timestamp">
</form>

<script src="<?php echo BASE_URL; ?>js/address.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.querySelector('form');
    const placeOrderButton = checkoutForm.querySelector('button[name="place_order"]');
    const orderConfirmationModal = document.getElementById('orderConfirmationModal');
    const orderDetailsSummary = document.getElementById('order-details-summary');
    const confirmPlaceOrderButton = document.getElementById('confirm-place-order');
    const cancelPlaceOrderButton = document.getElementById('cancel-place-order');
    const closeModalButton = orderConfirmationModal.querySelector('.close-button');

    // Prevent default form submission and show confirmation modal
    placeOrderButton.addEventListener('click', function(event) {
      event.preventDefault();

      // Gather details from the form
      const province = document.getElementById('province').options[document.getElementById('province').selectedIndex].text;
      const district = document.getElementById('district').options[document.getElementById('district').selectedIndex].text;
      const ward = document.getElementById('ward').options[document.getElementById('ward').selectedIndex].text;
      const shippingAddress = document.getElementById('shipping_address').value;
      const phoneNum = document.getElementById('phone_num').value;
      const totalAmount = document.querySelector('input[name="total_price"]').value;
      const paymentMethod = document.querySelector('input[name="payment_method"]:checked').nextSibling.textContent.trim(); // Get text next to the radio button

      // Build the summary HTML
      orderDetailsSummary.innerHTML = `
            <p><strong>Shipping Address:</strong></p>
            <p>${shippingAddress}, ${ward}, ${district}, ${province}</p>
            <p><strong>Phone Number:</strong> ${phoneNum}</p>
            <p><strong>Total Amount:</strong> $${parseFloat(totalAmount).toFixed(2)}</p>
            <p><strong>Payment Method:</strong> ${paymentMethod}</p>
        `;

      // Show the modal
      orderConfirmationModal.style.display = 'block';
    });

    // Handle modal button clicks
    confirmPlaceOrderButton.addEventListener('click', function() {
      // If confirmed, submit the original form

      // Set the current timestamp before submitting
      document.getElementById('order_timestamp').value = Math.floor(Date.now() / 1000); // Get current Unix timestamp in seconds

      // Enable disabled selects before submitting to ensure their values are sent
      document.getElementById('district').disabled = false;
      document.getElementById('ward').disabled = false;

      checkoutForm.submit();
    });

    function closeModal() {
      orderConfirmationModal.style.display = 'none';
    }

    cancelPlaceOrderButton.addEventListener('click', closeModal);
    closeModalButton.addEventListener('click', closeModal);

    // Close modal if user clicks outside of it
    window.addEventListener('click', function(event) {
      if (event.target == orderConfirmationModal) {
        closeModal();
      }
    });

    // The address population logic from address.js will still run separately
    // Ensure address.js is loaded before this script if needed
  });
</script>

</body>

</html>