<?php
include '../includes/db_connect.php'; 

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Xóa dữ liệu người dùng từ bảng Customer (thay vì bảng users)
    $delete_customer = $conn->prepare("DELETE FROM `Customer` WHERE CustomerID = ?");
    $delete_customer->execute([$delete_id]);

    // Xóa các đơn hàng liên quan từ bảng orders
    $delete_order = $conn->prepare("DELETE FROM `order` WHERE CustomerID = ?");
    $delete_order->execute([$delete_id]);

    // Xóa các sản phẩm trong giỏ hàng từ bảng cart
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE CustomerID = ?");
    $delete_cart->execute([$delete_id]);

    header('location:users_accounts.php');  // Trở lại trang quản lý tài khoản người dùng
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Accounts</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- User accounts section starts -->
<section class="accounts">
    <h1 class="heading">Customer Accounts</h1>

    <table class="accounts-table">
        <thead>
        <tr>
            <th>Customer ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Lấy tất cả khách hàng từ bảng Customer
        $select_account = $conn->prepare("SELECT * FROM `Customer`");
        $select_account->execute();
        
        if ($select_account->rowCount() > 0) {
            while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?= $fetch_accounts['CustomerID']; ?></td>
                    <td><?= $fetch_accounts['Username']; ?></td>
                    <td><?= $fetch_accounts['Email']; ?></td>
                    <td><?= $fetch_accounts['PhoneNum']; ?></td> <!-- Hiển thị số điện thoại -->
                    <td>
                        <!-- Nút xóa người dùng -->
                        <a href="users_accounts.php?delete=<?= $fetch_accounts['CustomerID']; ?>" class="delete-btn"
                           onclick="return confirm('Delete this account?');">Delete</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo '<tr><td colspan="5" class="empty">No accounts available</td></tr>';
        }
        ?>
        </tbody>
    </table>
</section>

<!-- User accounts section ends -->

<!-- Custom JS file link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
