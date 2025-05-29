<?php
// Already protected by index.php router
$customer_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT Firstname, Lastname, Email, Username, PhoneNum FROM CUSTOMER WHERE CustomerID = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
  echo "<h1>Error</h1><p>Could not retrieve user details.</p>";
  // Potentially log out user if data is inconsistent
  // session_destroy(); header("Location: index.php?page=login"); exit();
} else {
?>
  <div class="account-details-container">
    <h1>My Account</h1>
    <p>Welcome, <?php echo htmlspecialchars($user['Firstname']); ?>!</p>

    <h3>Account Details</h3>
    <div class="account-info">
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user['Firstname'] . ' ' . $user['Lastname']); ?></p>
      <p><strong>Username:</strong> <?php echo htmlspecialchars($user['Username']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['PhoneNum'] ?: 'Not provided'); ?></p>
    </div>

    <div class="account-actions">
      <p><a href="index.php?page=edit_account" class="btn btn-secondary">Edit Account Details</a></p>
      <p><a href="index.php?page=change_password" class="btn btn-secondary">Change Password</a></p>
    </div>

    <h3>My Orders</h3>
    <?php
    $order_stmt = $conn->prepare("SELECT OrderID, OrderDate, TotalPrice, Status FROM `ORDER` WHERE CustomerID = ? ORDER BY OrderDate DESC");
    $order_stmt->bind_param("i", $customer_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();

    if ($order_result->num_rows > 0) {
      echo "<table class='order-table'>";
      echo "<thead><tr><th>Order ID</th><th>Date</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>";
      echo "<tbody>";
      while ($order = $order_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>#{$order['OrderID']}</td>";
        echo "<td>" . date("M d, Y H:i", strtotime($order['OrderDate'])) . "</td>"; // Display time as well
        echo "<td>$" . number_format($order['TotalPrice'], 2) . "</td>";
        echo "<td>" . htmlspecialchars($order['Status']) . "</td>";
        echo "<td>";

        // Check if order can be cancelled
        $order_timestamp = strtotime($order['OrderDate']);
        $current_timestamp = time();
        $time_difference_seconds = $current_timestamp - $order_timestamp;
        $one_hour_in_seconds = 60 * 60;

        if ($order['Status'] === 'Pending' && $time_difference_seconds <= $one_hour_in_seconds) {
          echo '<form action="' . BASE_URL . 'actions/cancel_order_action.php" method="POST" style="display:inline-block;">';
          echo '<input type="hidden" name="order_id" value="' . $order['OrderID'] . '">';
          echo '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to cancel this order?\');">Cancel</button>';
          echo '</form>';
        } else {
          echo "-"; // Or a disabled button, or view details link
        }

        echo "</td>";
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
    } else {
      echo "<p>You have not placed any orders yet.</p>";
    }
    $order_stmt->close();
    ?>

  <?php } ?>
  </div>

  <style>
    .account-details-container {
      max-width: 700px;
      /* Slightly wider than forms */
      margin: 50px auto;
      padding: 30px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .account-details-container h1,
    .account-details-container h3 {
      text-align: center;
      color: #8b5e3c;
      margin-bottom: 15px;
    }

    .account-details-container h1 {
      margin-top: 0;
    }

    .account-details-container p {
      text-align: center;
      color: #555;
      margin-bottom: 20px;
    }

    .account-info,
    .account-actions,
    .order-list {
      margin-bottom: 30px;
      padding: 15px;
      border: 1px solid #eee;
      border-radius: 5px;
      background-color: #f9f9f9;
    }

    .account-info p strong {
      color: #4a3b31;
    }

    .account-actions {
      display: flex;
      justify-content: center;
      gap: 15px;
      /* Space between buttons */
    }

    .account-actions .btn {
      /* Style buttons similar to other pages or adjust as needed */
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      font-size: 16px;
    }

    .account-actions .btn-secondary {
      background-color: #6c757d;
      color: white;
      transition: background-color 0.3s ease;
    }

    .account-actions .btn-secondary:hover {
      background-color: #5a6268;
    }

    .order-list {
      list-style: none;
      padding: 0;
    }

    .order-list li {
      background-color: #fff;
      border-bottom: 1px solid #eee;
      padding: 10px;
      margin-bottom: 5px;
      border-radius: 4px;
    }

    .order-list li:last-child {
      border-bottom: none;
      margin-bottom: 0;
    }

    .order-list li strong {
      color: #4a3b31;
    }
  </style>