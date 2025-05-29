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
    // --- Start: Address Save Logic (from user's suggestion) ---
    $address_id = null;

    // Check if address already exists for this customer
    $checkStmt = $conn->prepare("SELECT AddressID FROM address WHERE CustomerID = ? LIMIT 1");
    if (!$checkStmt) {
      throw new Exception("Database error preparing address check: " . $conn->error);
    }
    $checkStmt->bind_param("i", $customer_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
      // Update existing address
      $row = $result->fetch_assoc();
      $address_id = $row['AddressID'];

      $updateStmt = $conn->prepare("UPDATE address SET Province = ?, District = ?, Ward = ?, Detail = ? WHERE AddressID = ?");
      if (!$updateStmt) {
        throw new Exception("Database error preparing address update: " . $conn->error);
      }
      $updateStmt->bind_param("ssssi", $province, $district, $ward, $detail_address, $address_id);
      if (!$updateStmt->execute()) {
        throw new Exception("Database error updating address: " . $updateStmt->error);
      }
      $updateStmt->close();
    } else {
      // Insert new address
      $insertStmt = $conn->prepare("INSERT INTO address (CustomerID, Province, District, Ward, Detail) VALUES (?, ?, ?, ?, ?)");
      if (!$insertStmt) {
        throw new Exception("Database error preparing address insert: " . $conn->error);
      }
      $insertStmt->bind_param("issss", $customer_id, $province, $district, $ward, $detail_address);
      if (!$insertStmt->execute()) {
        throw new Exception("Database error inserting address: " . $insertStmt->error);
      }
      $address_id = $insertStmt->insert_id; // Get new AddressID
      $insertStmt->close();
    }
    $checkStmt->close();

    // Ensure we have a valid AddressID
    if (!$address_id) {
      throw new Exception("Could not determine valid AddressID.");
    }
    // --- End: Address Save Logic ---

    // 1. Get cart items
    $cart_items_stmt = $conn->prepare(
      "SELECT c.ProductID, p.Price AS UnitPrice, c.Quantity, p.StockQuantity
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
      $calculated_total += $item['UnitPrice'] * $item['Quantity'];
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
    $status = 'Pending'; // Define status variable
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
      $order_detail_stmt->bind_param("iiid", $order_id, $item['ProductID'], $item['Quantity'], $item['UnitPrice']);
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
      'AddressID' => $address_id // Store AddressID for potential future use (e.g., selecting from saved addresses)
    ];

    $_SESSION['message'] = "Order placed successfully! Your Order ID is #{$order_id}.";
    $_SESSION['message_type'] = "success";
    header("Location: ../index.php?page=order_confirmation");
    exit();
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
