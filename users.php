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
                            <a href="#" data-id="<?php echo $row['CustomerID']; ?>" class="btn btn-warning">Edit</a>
                             <form action="delete_user.php" method="POST" style="display:inline;" id="deleteForm<?php echo $row['CustomerID']; ?>">
    <input type="hidden" name="CustomerID" value="<?php echo $row['CustomerID']; ?>">
    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['CustomerID']; ?>)">Delete</button>
</form>
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
    <!-- Modal Structure -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_user.php" method="POST" id="editUserForm">
                    <input type="hidden" name="CustomerID" id="editCustomerID">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" name="Username" required>
                    </div>
                    <div class="mb-3">
                        <label for="editFirstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstname" name="Firstname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastname" name="Lastname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhoneNum" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="editPhoneNum" name="PhoneNum" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    // Open Modal and Populate User Data
    document.querySelectorAll('.btn-warning').forEach(button => {
        button.addEventListener('click', function(e) {
            const customerID = this.getAttribute('data-id');
            fetch(`get_user_data.php?id=${customerID}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the form with user data
                    document.getElementById('editCustomerID').value = data.CustomerID;
                    document.getElementById('editUsername').value = data.Username;
                    document.getElementById('editFirstname').value = data.Firstname;
                    document.getElementById('editLastname').value = data.Lastname;
                    document.getElementById('editEmail').value = data.Email;
                    document.getElementById('editPhoneNum').value = data.PhoneNum;
                    
                    // Show the modal
                    var myModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    myModal.show();
                })
                .catch(err => console.error(err));
        });
    });
     function confirmDelete(customerID) {
        // Hiển thị hộp thoại xác nhận
        if (confirm('Are you sure you want to delete this user?')) {
            // Nếu người dùng xác nhận, gửi form
            document.getElementById('deleteForm' + customerID).submit();
        }
    }
</script>

</body>
</html>
