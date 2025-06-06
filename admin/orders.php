<?php
include('../includes/db_connect.php');

$result = $conn->query("SELECT * FROM `order`");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        /* General style for the page */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;  /* Light background color for contrast */
        }

        /* Table styling */
        table {
            width: 100%;
            margin-top: 2rem;
            border-collapse: collapse;
        }

        /* Header Row Styling */
        thead {
            background-color: #343a40;
            color: white;
        }

        /* Table cell and row styling */
        table th, table td {
            text-align: center;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd; /* Light border for table */
        }

        /* Odd/even row shading */
        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        /* Hover effect for rows */
        tbody tr:hover {
            background-color: #e2e6ea;
            cursor: pointer;
        }

        /* Styling for buttons */
        .btn-info, .btn-danger {
            text-transform: uppercase;
            font-weight: 500;
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
        }

        /* Info button (View) */
        .btn-info {
            background-color: #17a2b8;
            border: none;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        /* Cancel button */
        .btn-danger {
            background-color: #dc3545;
            border: none;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Heading Styling */
        h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #343a40;
        }

        /* Adding padding to the container */
        .container {
            padding: 2rem;
        }

        /* Responsive table design */
        @media (max-width: 767px) {
            table th, table td {
                font-size: 0.9rem; /* Smaller font size on mobile */
                padding: 10px;
            }
        }
    </style>

</head>
<body>
    <!-- Include Header -->
    <?php include('../admin/header.php'); ?>

    <div class="container mt-5">
        <h2>Manage Orders</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['OrderID']; ?></td>
                        <td><?php echo $row['CustomerID']; ?></td>
                        <td><?php echo $row['OrderDate']; ?></td>
                        <td><?php echo number_format($row['TotalPrice'], 2); ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td>
                            <!-- Button to open the modal and display order details -->
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewOrderModal" data-orderid="<?php echo $row['OrderID']; ?>">View</button>
                            <a href="cancel_order.php?id=<?php echo $row['OrderID']; ?>" class="btn btn-danger">Cancel</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal to show order details -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetails">
                    <!-- Content will be loaded via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js for modal to work -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript to load order details dynamically -->
    <script>
        // Listen to when the View button is clicked and populate the modal with order details
        document.querySelectorAll('.btn-info').forEach(button => {
            button.addEventListener('click', function () {
                let orderId = this.getAttribute('data-orderid');
                
                // Make an AJAX request to fetch order details
                fetch(`get_order_details.php?id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Fill in the modal with the order details
                        document.getElementById('orderDetails').innerHTML = `
                            <p><strong>Order ID:</strong> ${data.OrderID}</p>
                            <p><strong>Customer ID:</strong> ${data.CustomerID}</p>
                            <p><strong>Order Date:</strong> ${data.OrderDate}</p>
                            <p><strong>Total Price:</strong> ${data.TotalPrice}</p>
                            <p><strong>Status:</strong> ${data.Status}</p>
                            <p><strong>Shipping Address:</strong> ${data.ShippingAddress}</p>
                            <p><strong>Payment Method:</strong> ${data.PaymentMethod}</p>
                            <!-- Add other details as needed -->
                        `;
                    })
                    .catch(error => console.error('Error fetching order details:', error));
            });
        });
    </script>
</body>
</html>
