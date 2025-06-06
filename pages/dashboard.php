<?php
session_start();
// require_once '../includes/db_connect.php'; // Adjust path - Removed

// Optional: Restrict access, e.g., only for admin users
// For now, just require login
if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = "You must be logged in to view the dashboard.";
  $_SESSION['message_type'] = "danger";
  header("Location: index.php?page=login");
  exit();
}

$total_revenue = 0;
$total_orders = 0;

// Fetch total revenue and total number of orders
$dashboard_stmt = $conn->prepare("SELECT SUM(TotalPrice) AS TotalRevenue, COUNT(OrderID) AS TotalOrders FROM `ORDER`");

if ($dashboard_stmt) {
  $dashboard_stmt->execute();
  $result = $dashboard_stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $total_revenue = $row['TotalRevenue'] ?? 0; // Use ?? 0 for case where there are no orders yet
    $total_orders = $row['TotalOrders'] ?? 0;
  }
  $dashboard_stmt->close();
}

// Fetch monthly revenue for chart
$monthly_revenue_data = [];
$monthly_revenue_stmt = $conn->prepare("SELECT DATE_FORMAT(OrderDate, 'Y-m') AS Month, SUM(TotalPrice) AS MonthlyRevenue FROM `ORDER` GROUP BY Month ORDER BY Month ASC");

if ($monthly_revenue_stmt) {
  $monthly_revenue_stmt->execute();
  $monthly_revenue_result = $monthly_revenue_stmt->get_result();
  while ($row = $monthly_revenue_result->fetch_assoc()) {
    $monthly_revenue_data[] = $row; // Store month and revenue
  }
  $monthly_revenue_stmt->close();
}

// Fetch monthly order count for chart
$monthly_order_count_data = [];
$monthly_order_count_stmt = $conn->prepare("SELECT DATE_FORMAT(OrderDate, 'Y-m') AS Month, COUNT(OrderID) AS MonthlyOrderCount FROM `ORDER` GROUP BY Month ORDER BY Month ASC");

if ($monthly_order_count_stmt) {
  $monthly_order_count_stmt->execute();
  $monthly_order_count_result = $monthly_order_count_stmt->get_result();
  while ($row = $monthly_order_count_result->fetch_assoc()) {
    $monthly_order_count_data[] = $row; // Store month and order count
  }
  $monthly_order_count_stmt->close();
}

$conn->close(); // Close connection after all data is fetched

?>

<div class="dashboard-container">
  <h1>Dashboard</h1>
  <p>Overview of your store's performance.</p>

  <div class="dashboard-metrics">
    <div class="metric-card">
      <h3>Total Revenue</h3>
      <p>$<?php echo number_format($total_revenue, 2); ?></p>
    </div>
    <div class="metric-card">
      <h3>Total Orders</h3>
      <p><?php echo $total_orders; ?></p>
    </div>
    <!-- Add more metrics here (e.g., number of users, products) -->
  </div>

  <div class="dashboard-chart">
    <h3>Monthly Revenue</h3>
    <canvas id="monthlyRevenueChart"></canvas>
  </div>

  <div class="dashboard-chart">
    <h3>Monthly Order Count</h3>
    <canvas id="monthlyOrderCountChart"></canvas>
  </div>

  <!-- Add more dashboard content here (e.g., charts, recent orders) -->

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const monthlyRevenueData = <?php echo json_encode($monthly_revenue_data); ?>;
    const monthsRevenue = monthlyRevenueData.map(item => item.Month);
    const revenues = monthlyRevenueData.map(item => item.MonthlyRevenue);

    const ctxRevenue = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(ctxRevenue, {
      type: 'bar',
      data: {
        labels: monthsRevenue,
        datasets: [{
          label: 'Monthly Revenue',
          data: revenues,
          backgroundColor: 'rgba(139, 94, 60, 0.6)',
          borderColor: 'rgba(139, 94, 60, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Revenue ($)'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Month'
            }
          }
        },
        responsive: true,
        maintainAspectRatio: false
      }
    });

    const monthlyOrderCountData = <?php echo json_encode($monthly_order_count_data); ?>;
    const monthsOrder = monthlyOrderCountData.map(item => item.Month);
    const orderCounts = monthlyOrderCountData.map(item => item.MonthlyOrderCount);

    const ctxOrders = document.getElementById('monthlyOrderCountChart').getContext('2d');
    new Chart(ctxOrders, {
      type: 'line',
      data: {
        labels: monthsOrder,
        datasets: [{
          label: 'Monthly Orders',
          data: orderCounts,
          fill: false,
          borderColor: 'rgba(54, 162, 235, 1)',
          tension: 0.1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Orders'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Month'
            }
          }
        },
        responsive: true,
        maintainAspectRatio: false
      }
    });
  });
</script>

<style>
  .dashboard-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
  }

  .dashboard-container h1,
  .dashboard-container p {
    text-align: center;
    color: #333;
  }

  .dashboard-container h1 {
    color: #8b5e3c;
    margin-bottom: 20px;
  }

  .dashboard-metrics {
    display: flex;
    justify-content: space-around;
    margin-top: 30px;
    flex-wrap: wrap;
    /* Allow wrapping on smaller screens */
    gap: 20px;
    /* Space between cards */
  }

  .metric-card {
    background-color: #faf6f2;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    flex: 1;
    /* Allow cards to grow */
    min-width: 150px;
    /* Minimum width before wrapping */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .metric-card h3 {
    color: #4a3b31;
    margin-top: 0;
    font-size: 1.2em;
  }

  .metric-card p {
    font-size: 1.8em;
    font-weight: bold;
    color: #8b5e3c;
    margin-bottom: 0;
  }

  .dashboard-chart {
    margin-top: 40px;
    padding: 20px;
    background-color: #faf6f2;
    border: 1px solid #eee;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .dashboard-chart h3 {
    text-align: center;
    color: #4a3b31;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 1.2em;
  }

  /* Style for the canvas container if needed for specific sizing */
  #monthlyRevenueChart {
    max-height: 300px;
    /* Example height */
  }

  #monthlyOrderCountChart {
    max-height: 300px;
    /* Example height, keep consistent with the first chart */
  }
</style>