<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Chuyển hướng về trang login nếu chưa đăng nhập
    exit();
}

include('../includes/db_connect.php'); // Kết nối với cơ sở dữ liệu

// Truy vấn lấy tất cả các admin
$query = "SELECT AdminID, Username, FullName, Email, Phone, Role, CreatedAt FROM admin";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Include Header -->
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center">Admin Management</h2>

        <!-- Display success or error message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_GET['success']; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_GET['error']; ?>
            </div>
        <?php endif; ?>

        <!-- Button to open Create Admin Modal -->
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createAdminModal">
            Create Admin Account
        </button>

        <!-- Admin List Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
    <td><?php echo $row['AdminID']; ?></td>
    <td><?php echo $row['Username']; ?></td>
    <td><?php echo $row['FullName']; ?></td>
    <td><?php echo $row['Email']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['Role']; ?></td>
    <td><?php echo $row['CreatedAt']; ?></td>
    <td>
        <button class="btn btn-warning edit-btn" 
                data-id="<?php echo $row['AdminID']; ?>"
                data-username="<?php echo $row['Username']; ?>"
                data-fullname="<?php echo $row['FullName']; ?>"
                data-email="<?php echo $row['Email']; ?>"
                data-phone="<?php echo $row['Phone']; ?>"
                data-role="<?php echo $row['Role']; ?>"
                data-bs-toggle="modal" 
                data-bs-target="#editAdminModal">Edit</button>
        <button class="btn btn-danger delete-btn" data-id="<?php echo $row['AdminID']; ?>" data-bs-toggle="modal" data-bs-target="#deleteAdminModal">Delete</button>
    </td>
</tr>

                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Create Admin Modal -->
    <div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAdminModalLabel">Create Admin Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="create_admin_action.php" method="POST">
                        <div class="form-group mb-3">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="full_name">Full Name:</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">Phone:</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="role">Role:</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="Manager">Manager</option>
                                <option value="Employee">Employee</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Modal Xác nhận Xóa -->
<div class="modal fade" id="deleteAdminModal" tabindex="-1" aria-labelledby="deleteAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAdminModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this admin account? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var adminId = this.getAttribute('data-id');
            var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            confirmDeleteBtn.setAttribute('href', 'delete_admin.php?id=' + adminId);
        });
    });
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var adminId = this.getAttribute('data-id');
        var username = this.getAttribute('data-username');
        var fullName = this.getAttribute('data-fullname');
        var email = this.getAttribute('data-email');
        var phone = this.getAttribute('data-phone');
        var role = this.getAttribute('data-role');

        // Điền thông tin vào các trường trong modal
        document.getElementById('admin_id').value = adminId;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_full_name').value = fullName;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_role').value = role;
    });
});

</script><!-- Modal Edit Admin -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminModalLabel">Edit Admin Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_admin_action.php" method="POST">
                    <input type="hidden" name="admin_id" id="admin_id">

                    <div class="form-group mb-3">
                        <label for="edit_username">Username:</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_full_name">Full Name:</label>
                        <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_email">Email:</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_phone">Phone:</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_role">Role:</label>
                        <select class="form-control" id="edit_role" name="role" required>
                            <option value="Manager">Manager</option>
                            <option value="Employee">Employee</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_password">Password:</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                        <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>
