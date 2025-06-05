<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">VNUCOFFEE Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="orders.php">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">Users</a>
                </li>
                <!-- Thêm liên kết đến trang quản lý admin -->
                <li class="nav-item">
                    <a class="nav-link" href="admin_management.php">Admins</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (để modal, collapse, v.v. hoạt động nếu cần) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Add custom CSS for styling -->
<style>
    /* Navbar custom styles */
    .navbar {
        background-color: rgb(187, 90, 26) !important;
        padding: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Thêm bóng cho navbar */
    }
    
    .navbar-brand {
        font-size: 1.8rem;
        color: #fff; /* Màu chữ cho brand */
        font-weight: bold;
    }

    .navbar-nav .nav-link {
        color: #ecf0f1 !important; /* Màu chữ cho các liên kết */
        padding: 0.8rem 1rem;
        font-size: 1rem;
    }

    .navbar-nav .nav-link:hover {
        background-color: rgb(121, 51, 4); /* Màu nền khi hover */
        color: #fff !important; /* Màu chữ khi hover */
    }

    .navbar-toggler-icon {
        background-color: #fff; /* Màu icon cho navbar-toggler */
    }

    .navbar-collapse {
        text-align: right; /* Canh phải các mục trong navbar */
    }

    /* Modal custom styles */
    .modal-content {
        border-radius: 10px; /* Bo tròn các góc của modal */
    }

    /* Đảm bảo navbar không bị che mất bởi content khi dùng fixed-top */
    body {
        padding-top: 70px; /* Dự phòng cho navbar cố định */
    }

    .modal-header {
        background-color: #f8f9fa;
    }

    .modal-footer {
        justify-content: center;
    }
</style>
