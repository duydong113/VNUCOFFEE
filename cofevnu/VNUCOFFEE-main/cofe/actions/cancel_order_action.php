<?php
session_start();
require_once '../includes/db_connect.php'; // Adjust path

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = "You must be logged in to cancel an order.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php?page=login");
  exit();
}

$customer_id = $_SESSION['user_id'];

// Check if OrderID is provided
if (!isset($_POST['order_id'])) {
  $_SESSION['message'] = "Invalid request. Order ID not provided.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php?page=account"); // Redirect to account page or order history
  exit();
}

$order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);

if (!$order_id) {
  $_SESSION['message'] = "Invalid Order ID.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php?page=account"); // Redirect to account page or order history
  exit();
}

// Start transaction for cancellation
$conn->begin_transaction();

try {
  // 1. Fetch order details and check conditions for cancellation
  $order_stmt = $conn->prepare("SELECT CustomerID, OrderDate, Status FROM `ORDER` WHERE OrderID = ? FOR UPDATE"); // FOR UPDATE to lock the row
  if (!$order_stmt) {
    throw new Exception("Database error preparing order fetch: " . $conn->error);
  }
  $order_stmt->bind_param("i", $order_id);
  $order_stmt->execute();
  $order_result = $order_stmt->get_result();

  if ($order_result->num_rows === 0) {
    throw new Exception("Order not found.");
  }

  $order = $order_result->fetch_assoc();
  $order_stmt->close();

  // Check if the logged-in user owns the order
  if ($order['CustomerID'] !== $customer_id) {
    throw new Exception("You do not have permission to cancel this order.");
  }

  // Check if the order status allows cancellation
  if ($order['Status'] !== 'Pending') {
    throw new Exception("Order cannot be cancelled. Current status: " . htmlspecialchars($order['Status']));
  }

  // Check if the order is within the 1-hour cancellation window
  $order_timestamp = strtotime($order['OrderDate']);
  $current_timestamp = time();
  $time_difference_seconds = $current_timestamp - $order_timestamp;
  $one_hour_in_seconds = 60 * 60; // 1 hour

  if ($time_difference_seconds > $one_hour_in_seconds) {
    throw new Exception("Order cannot be cancelled. More than 1 hour has passed since placing the order.");
  }

  // 2. Get order items to update stock
  $order_items_stmt = $conn->prepare("SELECT ProductID, Quantity FROM ORDERDETAIL WHERE OrderID = ?");
  if (!$order_items_stmt) {
    throw new Exception("Database error preparing order items fetch: " . $conn->error);
  }
  $order_items_stmt->bind_param("i", $order_id);
  $order_items_stmt->execute();
  $order_items_result = $order_items_stmt->get_result();

  $update_stock_stmt = $conn->prepare("UPDATE PRODUCT SET StockQuantity = StockQuantity + ? WHERE ProductID = ?");
  if (!$update_stock_stmt) {
    throw new Exception("Database error preparing stock update: " . $conn->error);
  }

  while ($item = $order_items_result->fetch_assoc()) {
    // Add quantity back to stock
    $update_stock_stmt->bind_param("ii", $item['Quantity'], $item['ProductID']);
    if (!$update_stock_stmt->execute()) {
      throw new Exception("Failed to update stock for product ID: " . $item['ProductID']);
    }
  }
  $order_items_stmt->close();
  $update_stock_stmt->close();

  // 3. Update order status to Cancelled
  $cancel_stmt = $conn->prepare("UPDATE `ORDER` SET Status = 'Cancelled' WHERE OrderID = ?");
  if (!$cancel_stmt) {
    throw new Exception("Database error preparing cancellation update: " . $conn->error);
  }
  $cancel_stmt->bind_param("i", $order_id);
  if (!$cancel_stmt->execute()) {
    throw new Exception("Failed to update order status to Cancelled: " . $cancel_stmt->error);
  }
  $cancel_stmt->close();

  // If all good, commit transaction
  $conn->commit();

  $_SESSION['message'] = "Order #{$order_id} has been successfully cancelled.";
  $_SESSION['message_type'] = "success";
  header("Location: ../index.php?page=account"); // Redirect to account page or order history
  exit();
} catch (Exception $e) {
  $conn->rollback(); // Rollback on error
  $_SESSION['message'] = "Order cancellation failed: " . $e->getMessage();
  $_SESSION['message_type'] = "danger";
  error_log("Order cancellation failed: " . $e->getMessage() . " | Customer ID: " . $customer_id . " | Order ID: " . ($order_id ?? 'N/A')); // Log for admin
  header("Location: ../index.php?page=account"); // Redirect to account page or order history
  exit();
}

$conn->close();
