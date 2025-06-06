<?php
session_start();
require_once '../includes/db_connect.php'; // Adjust path

if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = "You must be logged in to place an order.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php?page=login");
  exit();
}

if (isset($_POST['place_order'])) {
  $customer_id = $_SESSION['user_id'];
  $total_price = filter_input(INPUT_POST, 'total_price', FILTER_VALIDATE_FLOAT);
  $payment_method = $_POST['payment_method'] ?? 'cod';

  // Get address and contact details from POST
  $province = trim($_POST['province'] ?? null);
  $district = trim($_POST['district'] ?? null);
  $ward = trim($_POST['ward'] ?? null);
  $detail_address = trim($_POST['shipping_address'] ?? null);
  $phone_num = trim($_POST['phone_num'] ?? null);

  // Get order timestamp from POST
  $order_timestamp = filter_input(INPUT_POST, 'order_timestamp', FILTER_VALIDATE_INT);

  // Basic validation for required fields
  if (empty($province) || empty($district) || empty($ward) || empty($detail_address) || empty($phone_num) || $total_price === false || $total_price <= 0 || !$order_timestamp) {
    $_SESSION['message'] = "Please fill in all required address and contact fields correctly and ensure timestamp is sent.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php?page=checkout");
    exit();
  }

  // Start transaction
  $conn->begin_transaction();

  try {
    // --- Start: Address Save Logic ---
    $address_id = null;

    // Check if address already exists for this customer
    $checkStmt = $conn->prepare("SELECT AddressID FROM address WHERE CustomerID = ? LIMIT 1");
    if (!$checkStmt) {
      throw new Exception("Database error preparing address check: " . $conn->error);
    }
    $checkStmt->bind_param("i", $customer_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $checkStmt->close();

    if ($result->num_rows > 0) {
      $address_id = $result->fetch_assoc()['AddressID'];
    } else {
      // Insert new address
      $address_stmt = $conn->prepare("INSERT INTO address (CustomerID, Province, District, Ward, Detail) VALUES (?, ?, ?, ?, ?)");
      if (!$address_stmt) {
        throw new Exception("Database error preparing address insert: " . $conn->error);
      }
      $address_stmt->bind_param("issss", $customer_id, $province, $district, $ward, $detail_address);
      if (!$address_stmt->execute()) {
        throw new Exception("Failed to save address: " . $address_stmt->error);
      }
      $address_id = $conn->insert_id;
      $address_stmt->close();
    }

    // 1. Verify Cart Items and Calculate Total
    $cart_items_stmt = $conn->prepare(
      "SELECT c.ProductID, c.Quantity, p.Price, p.StockQuantity
       FROM CART c
       JOIN PRODUCT p ON c.ProductID = p.ProductID
       WHERE c.CustomerID = ?"
    );
    $cart_items_stmt->bind_param("i", $customer_id);
    $cart_items_stmt->execute();
    $cart_result = $cart_items_stmt->get_result();

    if ($cart_result->num_rows == 0) {
      throw new Exception("Your cart is empty.");
    }

    $cart_items = [];
    $calculated_total = 0;
    while ($item = $cart_result->fetch_assoc()) {
      if ($item['Quantity'] > $item['StockQuantity']) {
        throw new Exception("Product " . $item['ProductID'] . " is out of stock for the quantity requested.");
      }
      $cart_items[] = $item;
      $calculated_total += $item['Price'] * $item['Quantity'];
    }
    $cart_items_stmt->close();

    // Verify total price (simple check)
    if (abs($calculated_total - $total_price) > 0.01) { // Allow for small floating point discrepancies
      throw new Exception("Order total mismatch. Please try again.");
    }

    // 2. Create Order
    $order_stmt = $conn->prepare(
      "INSERT INTO `ORDER` (CustomerID, TotalPrice, PhoneNum, Status, AddressID, OrderDate)
             VALUES (?, ?, ?, ?, ?, FROM_UNIXTIME(?))"
    );
    $status = 'pending'; // Define status variable
    $order_stmt->bind_param("idssii", $customer_id, $total_price, $phone_num, $status, $address_id, $order_timestamp);

    if (!$order_stmt->execute()) {
      throw new Exception("Failed to create order: " . $order_stmt->error);
    }

    $order_id = $conn->insert_id; // Get the ID of the newly inserted order

    if (!$order_id) {
      throw new Exception("Failed to create order.");
    }

    // 3. Add Order Details and Update Stock
    $order_detail_stmt = $conn->prepare(
      "INSERT INTO ORDERDETAIL (OrderID, ProductID, Quantity, UnitPrice)
             VALUES (?, ?, ?, ?)"
    );
    $update_stock_stmt = $conn->prepare(
      "UPDATE PRODUCT SET StockQuantity = StockQuantity - ? WHERE ProductID = ?"
    );

    foreach ($cart_items as $item) {
      // Add to order details
      $order_detail_stmt->bind_param("iiid", $order_id, $item['ProductID'], $item['Quantity'], $item['Price']);
      if (!$order_detail_stmt->execute()) {
        throw new Exception("Failed to add order detail for product ID: " . $item['ProductID']);
      }

      // Update stock
      $update_stock_stmt->bind_param("ii", $item['Quantity'], $item['ProductID']);
      if (!$update_stock_stmt->execute()) {
        throw new Exception("Failed to update stock for product ID: " . $item['ProductID']);
      }
    }
    $order_detail_stmt->close();
    $update_stock_stmt->close();

    // 4. Clear Cart for the user
    $clear_cart_stmt = $conn->prepare("DELETE FROM CART WHERE CustomerID = ?");
    $clear_cart_stmt->bind_param("i", $customer_id);
    $clear_cart_stmt->execute();
    $clear_cart_stmt->close();

    // If all good, commit transaction
    $conn->commit();

    $_SESSION['order_id'] = $order_id; // For confirmation page

    // Save address details to session for pre-filling checkout form next time
    $_SESSION['last_used_address'] = [
      'Province' => $province,
      'District' => $district,
      'Ward' => $ward,
      'Detail' => $detail_address,
      'AddressID' => $address_id
    ];

    // Handle payment method
    if ($payment_method === 'vnpay') {
      // Redirect to VNPay payment
      require_once '../vnpay_php/config.php';
      require_once '../includes/currency_utils.php';

      // Convert USD to VND for VNPay
      $vnd_amount = usd_to_vnd($total_price);

      // Create form data for VNPay
      $formData = array(
        'order_id' => $order_id,
        'amount' => $vnd_amount,
        'bankCode' => '' // Let user choose bank on VNPay page
      );

      // Build form and submit automatically
      echo '<form id="vnpayForm" action="../vnpay_php/vnpay_create_payment.php" method="POST">';
      foreach ($formData as $key => $value) {
        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
      }
      echo '</form>';
      echo '<script>document.getElementById("vnpayForm").submit();</script>';
      exit();
    } else {
      // For COD payment, redirect to order confirmation
      $_SESSION['message'] = "Order placed successfully! Your Order ID is #{$order_id}.";
      $_SESSION['message_type'] = "success";
      header("Location: ../index.php?page=order_confirmation");
      exit();
    }
  } catch (Exception $e) {
    $conn->rollback(); // Rollback on error
    $_SESSION['message'] = "Order placement failed: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    error_log("Order placement failed: " . $e->getMessage() . " | Customer ID: " . $customer_id); // Log for admin
    header("Location: ../index.php?page=checkout");
    exit();
  }
} else {
  $_SESSION['message'] = "Invalid request.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php?page=cart");
  exit();
}
$conn->close();
