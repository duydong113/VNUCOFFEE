<?php
include('../includes/db_connect.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); 
    exit();
}
$orders_count   = $conn->query("SELECT COUNT(*) FROM `order`")->fetch_row()[0];
$products_count = $conn->query("SELECT COUNT(*) FROM `product`")->fetch_row()[0];
$users_count    = $conn->query("SELECT COUNT(*) FROM `customer`")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link 
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
      rel="stylesheet" 
    />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- ===== CSS CHO DASHBOARD ===== -->
    <style>
        /* 1. Font chung và màu nền body */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa; /* Màu xám nhạt */
            margin: 0;
            padding: 0;
        }

        /* 2. Container chính */
        .container-dashboard {
            margin-top: 80px; /* Đảm bảo không bị che header */
            margin-bottom: 40px;
        }

        /* 3. Tiêu đề trang */
        .dashboard-title {
            font-size: 2rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 1.5rem;
        }

        /* 4. Card số liệu */
        .dashboard-card {
            border: none;
            border-radius: 0.75rem;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%; /* Đảm bảo card bằng nhau chiều cao */
        }
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        .dashboard-card .card-body {
            text-align: center;
            padding: 1.8rem;
        }
        .dashboard-card .card-title {
            font-size: 1.25rem;
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .dashboard-card .card-text {
            font-size: 2.75rem;
            font-weight: 700;
            color: #1e272e;
            margin: 0;
            line-height: 1;
        }
        .dashboard-card a {
            text-decoration: none;
        }

        /* 5. Responsive: thu nhỏ font và padding khi màn hình nhỏ */
        @media (max-width: 767.98px) {
            .dashboard-card .card-body {
                padding: 1.2rem;
            }
            .dashboard-card .card-title {
                font-size: 1.1rem;
            }
            .dashboard-card .card-text {
                font-size: 2.25rem;
            }
        }
    </style>
    <!-- ==== KẾT THÚC CSS CHO DASHBOARD ==== -->
</head>
<body>
    <!-- Include Header (navbar) -->
    <?php include('../admin/header.php'); ?>

    <div class="container container-dashboard">
        <h1 class="dashboard-title">Admin Dashboard</h1>

        <!-- Row chứa 3 card, sử dụng gutter (g-4) để tạo khoảng cách đều -->
        <div class="row g-4">
            <!-- Card for Orders -->
            <div class="col-sm-12 col-md-6 col-lg-4">
                <a href="orders.php" class="text-decoration-none">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text"><?php echo $orders_count; ?></p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card for Products -->
            <div class="col-sm-12 col-md-6 col-lg-4">
                <a href="products.php" class="text-decoration-none">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <p class="card-text"><?php echo $products_count; ?></p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card for Users -->
            <div class="col-sm-12 col-md-6 col-lg-4">
                <a href="users.php" class="text-decoration-none">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text"><?php echo $users_count; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (để modal, collapse, v.v. hoạt động nếu cần) -->
    <script 
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    ></script>
</body>
</html>
