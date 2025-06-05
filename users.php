<?php
include('../includes/db_connect.php');

$result = $conn->query("SELECT * FROM customer");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     <!-- Include Header -->
<?php include('../admin/header.php'); ?>

    <div class="container mt-5">
        <h2>Manage Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['CustomerID']; ?></td>
                        <td><?php echo $row['Username']; ?></td>
                        <td><?php echo $row['Firstname'] . ' ' . $row['Lastname']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['PhoneNum']; ?></td>
                        <td><?php echo $row['Status'] ?? 'Active'; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['CustomerID']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_user.php?id=<?php echo $row['CustomerID']; ?>" class="btn btn-danger">Delete</a>
                            <?php if ($row['Status'] == 'Blocked') { ?>
                                <a href="deactivate_user.php?id=<?php echo $row['CustomerID']; ?>" class="btn btn-success">Deactivate</a>
                            <?php } else { ?>
                                <a href="block_user.php?id=<?php echo $row['CustomerID']; ?>" class="btn btn-secondary">Block</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
