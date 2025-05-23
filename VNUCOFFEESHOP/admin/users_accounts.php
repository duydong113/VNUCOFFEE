<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->execute([$delete_id]);
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    $delete_order->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$delete_id]);
    header('location:users_accounts.php');
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
    <h1 class="heading">Users Accounts</h1>

    <table class="accounts-table">
        <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Password</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $select_account = $conn->prepare("SELECT * FROM `users`");
        $select_account->execute();
        if ($select_account->rowCount() > 0) {
            while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?= $fetch_accounts['id']; ?></td>
                    <td><?= $fetch_accounts['name']; ?></td>
                    <td><?= $fetch_accounts['email']; ?></td>
                    <td><?= $fetch_accounts['number']; ?></td> <!-- Changed from phone to number -->
                    <td><?= $fetch_accounts['password']; ?></td>
                    <td>
                        <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn"
                           onclick="return confirm('Delete this account?');">Delete</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo '<tr><td colspan="6" class="empty">No accounts available</td></tr>';
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
