<?php
include('../includes/db_connect.php');

// Kiểm tra xem đã có ID của đơn hàng cần lấy chưa
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Truy vấn chi tiết đơn hàng
    $result = $conn->query("SELECT * FROM `order` WHERE OrderID = $order_id");

    if ($result) {
        // Lấy dữ liệu đơn hàng
        $order = $result->fetch_assoc();
        echo json_encode($order);  // Trả về dữ liệu dưới dạng JSON
    } else {
        echo json_encode(['error' => 'Unable to fetch order details.']);
    }
}
?>
