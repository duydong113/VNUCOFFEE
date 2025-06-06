<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'vnpay_php/config.php';

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
  if (substr($key, 0, 4) == "vnp_") {
    $inputData[$key] = $value;
  }
}

unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
  if ($i == 1) {
    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
  } else {
    $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
    $i = 1;
  }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Get order ID from VNPay response
$order_id = $inputData['vnp_TxnRef'];
$vnp_ResponseCode = $inputData['vnp_ResponseCode'];
$vnp_TransactionStatus = $inputData['vnp_TransactionStatus'];

// Start transaction
$conn->begin_transaction();

try {
  if ($secureHash == $vnp_SecureHash) {
    if ($vnp_ResponseCode == '00' && $vnp_TransactionStatus == '00') {
      // Payment successful
      $update_stmt = $conn->prepare("UPDATE `ORDER` SET Status = 'Paid' WHERE OrderID = ?");
      $update_stmt->bind_param("i", $order_id);
      $update_stmt->execute();
      $update_stmt->close();

      $_SESSION['message'] = "Thanh toán thành công! Đơn hàng của bạn đã được xác nhận.";
      $_SESSION['message_type'] = "success";
    } else {
      // Payment failed
      $update_stmt = $conn->prepare("UPDATE `ORDER` SET Status = 'Payment Failed' WHERE OrderID = ?");
      $update_stmt->bind_param("i", $order_id);
      $update_stmt->execute();
      $update_stmt->close();

      $_SESSION['message'] = "Thanh toán thất bại. Vui lòng thử lại hoặc chọn phương thức thanh toán khác.";
      $_SESSION['message_type'] = "danger";
    }
  } else {
    $_SESSION['message'] = "Dữ liệu không hợp lệ.";
    $_SESSION['message_type'] = "danger";
  }

  $conn->commit();
} catch (Exception $e) {
  $conn->rollback();
  $_SESSION['message'] = "Có lỗi xảy ra: " . $e->getMessage();
  $_SESSION['message_type'] = "danger";
}

$conn->close();

// Redirect to order confirmation page
header("Location: index.php?page=order_confirmation");
exit();
