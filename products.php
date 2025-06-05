<?php
include('../includes/db_connect.php');

// 1. Xử lý khi form trong modal được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
    // Lấy dữ liệu từ form modal
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock_quantity = $_POST['stock_quantity'];
    $image_url = $_POST['image_url'];

    // Chèn vào database
    $stmt = $conn->prepare("INSERT INTO product (Name, Price, CategoryID, StockQuantity, ImageURL) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdiss", $name, $price, $category_id, $stock_quantity, $image_url);

    if ($stmt->execute()) {
        // Redirect kèm thông báo added=1
        header("Location: products.php?added=1");
        exit;
    } else {
        $error_message = "Lỗi khi thêm sản phẩm: " . $stmt->error;
    }
    $stmt->close();
}

// 2. Lấy tất cả sản phẩm để hiển thị
$result = $conn->query("SELECT * FROM product");
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include('../admin/header.php'); ?>

    <div class="container mt-5">
        <h2>Manage Products</h2>

        <!-- 2.1. Nếu có thông báo added=1, hiển thị alert-success -->
        <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Thành công!</strong> Sản phẩm đã được thêm.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Nút mở modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
            Add Product
        </button>

        <!-- Nếu có lỗi khi thêm sản phẩm, hiển thị alert-danger -->
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Bảng danh sách sản phẩm -->
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Stock Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['ProductID']; ?></td>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo number_format($row['Price'], 0); ?></td>
                        <td><?php echo $row['CategoryID']; ?></td>
                        <td><?php echo $row['StockQuantity']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['ProductID']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_product.php?id=<?php echo $row['ProductID']; ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal “Add Product” -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="products.php" method="POST">
                    <input type="hidden" name="action" value="add_product">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form fields giống edit nhưng giá trị để trống -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">-- Chọn Category --</option>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (để modal hoạt động) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
