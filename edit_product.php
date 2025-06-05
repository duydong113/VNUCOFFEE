<?php
include('../includes/db_connect.php');
// Kiểm tra xem đã có id của sản phẩm cần sửa hay chưa
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $result = $conn->query("SELECT * FROM product WHERE ProductID = $product_id");
    $product = $result->fetch_assoc();

    // Kiểm tra xem người dùng đã gửi form chưa
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Lấy dữ liệu từ form
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        $stock_quantity = $_POST['stock_quantity'];
        $image_url = $_POST['image_url'];

        // Truy vấn SQL để cập nhật sản phẩm
        $sql = "UPDATE product SET 
                Name = '$name', 
                Price = '$price', 
                CategoryID = '$category_id', 
                StockQuantity = '$stock_quantity', 
                ImageURL = '$image_url' 
                WHERE ProductID = $product_id";

        if ($conn->query($sql) === TRUE) {
            echo "Product updated successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     <!-- Include Header -->
<?php include('../admin/header.php'); ?>


    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form action="edit_product.php?id=<?php echo $product['ProductID']; ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['Name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['Price']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="1" <?php echo ($product['CategoryID'] == 1) ? 'selected' : ''; ?>>Whole Bean</option>
                    <option value="2" <?php echo ($product['CategoryID'] == 2) ? 'selected' : ''; ?>>Ground Coffee</option>
                    <option value="3" <?php echo ($product['CategoryID'] == 3) ? 'selected' : ''; ?>>Coffee Pods</option>
                    <option value="4" <?php echo ($product['CategoryID'] == 4) ? 'selected' : ''; ?>>Accessories</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?php echo $product['StockQuantity']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo $product['ImageURL']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</body>
</html>
