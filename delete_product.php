<?php
include('../includes/db_connect.php');

// Kiểm tra xem có ID của sản phẩm cần xóa không
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Truy vấn SQL để xóa sản phẩm
    $sql = "DELETE FROM product WHERE ProductID = $product_id";

    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully!";
        header("Location: products.php"); // Chuyển hướng về trang quản lý sản phẩm
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
