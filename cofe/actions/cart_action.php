<?php
session_start();
require_once '../includes/db_connect.php'; // Adjust path

if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = "You need to be logged in to manage your cart.";
  $_SESSION['message_type'] = "danger";
  header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../index.php?page=login'));
  exit();
}

$customer_id = $_SESSION['user_id'];

// Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

  if ($product_id && $quantity) {
    // Check stock
    $stock_stmt = $conn->prepare("SELECT StockQuantity FROM PRODUCT WHERE ProductID = ?");
    $stock_stmt->bind_param("i", $product_id);
    $stock_stmt->execute();
    $stock_result = $stock_stmt->get_result();
    $product_stock = $stock_result->fetch_assoc();
    $stock_stmt->close();

    if (!$product_stock || $quantity > $product_stock['StockQuantity']) {
      $_SESSION['message'] = "Not enough stock available for the requested quantity.";
      $_SESSION['message_type'] = "danger";
      header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../index.php?page=products'));
      exit();
    }

    // Check if item already in cart
    $stmt = $conn->prepare("SELECT Quantity FROM CART WHERE CustomerID = ? AND ProductID = ?");
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($cart_item = $result->fetch_assoc()) {
      // Update quantity
      $new_quantity = $cart_item['Quantity'] + $quantity;
      if ($new_quantity > $product_stock['StockQuantity']) {
        $_SESSION['message'] = "Cannot add more. Total quantity would exceed stock.";
        $_SESSION['message_type'] = "danger";
      } else {
        $update_stmt = $conn->prepare("UPDATE CART SET Quantity = ? WHERE CustomerID = ? AND ProductID = ?");
        $update_stmt->bind_param("iii", $new_quantity, $customer_id, $product_id);
        if ($update_stmt->execute()) {
          $_SESSION['message'] = 'Đã cập nhật số lượng sản phẩm trong giỏ hàng!';
          $_SESSION['message_type'] = "success";
          $_SESSION['show_add_to_cart_popup'] = true;
        } else {
          $_SESSION['message'] = "Error updating cart: " . $update_stmt->error;
          $_SESSION['message_type'] = "danger";
        }
        $update_stmt->close();
      }
    } else {
      // Insert new item
      $insert_stmt = $conn->prepare("INSERT INTO CART (CustomerID, ProductID, Quantity) VALUES (?, ?, ?)");
      $insert_stmt->bind_param("iii", $customer_id, $product_id, $quantity);
      if ($insert_stmt->execute()) {
        $_SESSION['message'] = 'Đã thêm sản phẩm vào giỏ hàng thành công!';
        $_SESSION['message_type'] = "success";
        $_SESSION['show_add_to_cart_popup'] = true;
      } else {
        $_SESSION['message'] = "Error adding to cart: " . $insert_stmt->error;
        $_SESSION['message_type'] = "danger";
      }
      $insert_stmt->close();
    }
    $stmt->close();
  } else {
    $_SESSION['message'] = "Invalid product or quantity.";
    $_SESSION['message_type'] = "danger";
  }
  header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../index.php?page=cart'));
  exit();
}

// Update Cart Item Quantity
if (isset($_POST['update_cart_item'])) {
  $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

  if ($product_id && $quantity) {
    // Check stock
    $stock_stmt = $conn->prepare("SELECT StockQuantity FROM PRODUCT WHERE ProductID = ?");
    $stock_stmt->bind_param("i", $product_id);
    $stock_stmt->execute();
    $stock_result = $stock_stmt->get_result();
    $product_stock = $stock_result->fetch_assoc();
    $stock_stmt->close();

    if (!$product_stock || $quantity > $product_stock['StockQuantity']) {
      $_SESSION['message'] = "Not enough stock available for the requested quantity.";
      $_SESSION['message_type'] = "danger";
    } else {
      $stmt = $conn->prepare("UPDATE CART SET Quantity = ? WHERE CustomerID = ? AND ProductID = ?");
      $stmt->bind_param("iii", $quantity, $customer_id, $product_id);
      if ($stmt->execute()) {
        $_SESSION['message'] = "Cart updated.";
        $_SESSION['message_type'] = "success";
      } else {
        $_SESSION['message'] = "Error updating cart: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
      }
      $stmt->close();
    }
  } else {
    $_SESSION['message'] = "Invalid product or quantity for update.";
    $_SESSION['message_type'] = "danger";
  }
  header("Location: ../index.php?page=cart");
  exit();
}

// Remove From Cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
  if ($product_id) {
    $stmt = $conn->prepare("DELETE FROM CART WHERE CustomerID = ? AND ProductID = ?");
    $stmt->bind_param("ii", $customer_id, $product_id);
    if ($stmt->execute()) {
      $_SESSION['message'] = "Product removed from cart.";
      $_SESSION['message_type'] = "success";
    } else {
      $_SESSION['message'] = "Error removing product: " . $stmt->error;
      $_SESSION['message_type'] = "danger";
    }
    $stmt->close();
  } else {
    $_SESSION['message'] = "Invalid product to remove.";
    $_SESSION['message_type'] = "danger";
  }
  header("Location: ../index.php?page=cart");
  exit();
}

// Clear Cart
if (isset($_POST['clear_cart'])) {
  $stmt = $conn->prepare("DELETE FROM CART WHERE CustomerID = ?");
  $stmt->bind_param("i", $customer_id);
  if ($stmt->execute()) {
    $_SESSION['message'] = "Cart cleared.";
    $_SESSION['message_type'] = "success";
  } else {
    $_SESSION['message'] = "Error clearing cart: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
  }
  $stmt->close();
  header("Location: ../index.php?page=cart");
  exit();
}

// If no action matched
$_SESSION['message'] = "Invalid cart action.";
$_SESSION['message_type'] = "danger";
header("Location: ../index.php?page=cart");
$conn->close();
exit();
