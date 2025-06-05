<?php
include('../includes/db_connect.php');

// Kiểm tra xem người dùng đã gửi form hay chưa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra dữ liệu gửi từ form
    if (isset($_POST['name'], $_POST['price'], $_POST['category_id'], $_POST['stock_quantity'], $_POST['image_url'])) {
        // Lấy dữ liệu từ form
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        $stock_quantity = $_POST['stock_quantity'];
        $image_url = $_POST['image_url'];

        // Truy vấn SQL để thêm sản phẩm vào cơ sở dữ liệu
        $sql = "INSERT INTO product (Name, Price, CategoryID, StockQuantity, ImageURL)
                VALUES ('$name', '$price', '$category_id', '$stock_quantity', '$image_url')";

        // Thực thi truy vấn và kiểm tra kết quả
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Product added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Dữ liệu form không hợp lệ.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     <!-- Include Header -->
<?php include('../admin/header.php'); ?>


    <div class="container mt-5">
        <h2>Add New Product</h2>
        <form action="add_product.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <!-- Các lựa chọn danh mục có thể được thêm từ cơ sở dữ liệu -->
                    <option value="1">Whole Bean</option>
                    <option value="2">Ground Coffee</option>
                    <option value="3">Coffee Pods</option>
                    <option value="4">Accessories</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="image_url" name="image_url" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
