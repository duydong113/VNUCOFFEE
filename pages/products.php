<?php
// Get filter parameters from GET request
$filter_name = isset($_GET['name']) ? $_GET['name'] : '';
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$filter_min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$filter_max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;

// Ensure prices are not negative
if ($filter_min_price !== null && $filter_min_price < 0) {
  $filter_min_price = 0;
}
if ($filter_max_price !== null && $filter_max_price < 0) {
  $filter_max_price = 0;
}

// Build the base SQL query
$sql = "SELECT ProductID, Name, Price, ImageURL, Description FROM PRODUCT";
$conditions = [];
$params = [];
$param_types = '';

// Add conditions based on filters
if (!empty($filter_name)) {
  $conditions[] = "Name LIKE ?";
  $params[] = "%" . $filter_name . "%";
  $param_types .= 's';
}

// Assuming CategoryID exists in PRODUCT table and maps to category options (1, 2, 3, 4)
if (!empty($filter_category)) {
  $conditions[] = "CategoryID = ?";
  $params[] = (int)$filter_category;
  $param_types .= 'i';
}

if ($filter_min_price !== null) {
  $conditions[] = "Price >= ?";
  $params[] = $filter_min_price;
  $param_types .= 'd';
}

if ($filter_max_price !== null) {
  $conditions[] = "Price <= ?";
  $params[] = $filter_max_price;
  $param_types .= 'd';
}

// Combine conditions if any exist
if (!empty($conditions)) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Add ordering
$sql .= " ORDER BY Name ASC";

// Prepare and execute the query
$stmt = $conn->prepare($sql);

if ($stmt === false) {
  // Handle error - could log this or display a user-friendly message
  echo "<p>Error preparing query: " . $conn->error . "</p>";
} else {
  // Bind parameters if they exist
  if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
  }

  $stmt->execute();
  $result = $stmt->get_result();

  // Keep the rest of the product listing loop

?>
  <h1>Our Coffee Selection</h1>
  <!-- Add search and sort forms here later -->
  <div style="display: flex; gap: 40px; align-items: flex-start;">
    <aside style="width: 250px; background: #faf6f2; border-radius: 10px; padding: 24px 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); min-height: 400px;">
      <h3 style="margin-top:0; color:#8b5e3c;">Filter Products</h3>
      <form method="GET" action="index.php">
        <input type="hidden" name="page" value="products">
        <div style="margin-bottom: 18px;">
          <label for="filter-name" style="font-weight:500;">Name</label>
          <input type="text" id="filter-name" name="name" value="<?php echo htmlspecialchars($filter_name); ?>" style="width:100%;padding:6px 10px;border-radius:5px;border:1px solid #ccc;">
        </div>
        <div style="margin-bottom: 18px;">
          <label for="filter-category" style="font-weight:500;">Category</label>
          <select id="filter-category" name="category" style="width:100%;padding:6px 10px;border-radius:5px;border:1px solid #ccc;">
            <option value="">All</option>
            <option value="1" <?php if ($filter_category === '1') echo 'selected'; ?>>Coffee</option>
            <option value="2" <?php if ($filter_category === '2') echo 'selected'; ?>>Ground</option>
            <option value="3" <?php if ($filter_category === '3') echo 'selected'; ?>>Pods</option>
            <option value="4" <?php if ($filter_category === '4') echo 'selected'; ?>>Accessories</option>
          </select>
        </div>
        <div style="margin-bottom: 18px;">
          <label style="font-weight:500;">Price ($)</label><br>
          <input type="number" name="min_price" placeholder="Min" value="<?php echo htmlspecialchars($filter_min_price); ?>" style="width:45%;padding:6px 10px;border-radius:5px;border:1px solid #ccc;" min="0"> -
          <input type="number" name="max_price" placeholder="Max" value="<?php echo htmlspecialchars($filter_max_price); ?>" style="width:45%;padding:6px 10px;border-radius:5px;border:1px solid #ccc;" min="0">
        </div>
        <button type="submit" style="background:#8b5e3c;color:#fff;padding:8px 20px;border:none;border-radius:5px;font-weight:600;">Filter</button>
      </form>
    </aside>
    <div style="flex:1;">
      <div class="product-grid">
        <?php

        if ($result && $result->num_rows > 0) {
          while ($product = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<a href="index.php?page=product_detail&id=' . $product['ProductID'] . '">';
            echo '<div class="product-image">';
            echo '<img src="' . htmlspecialchars($product['ImageURL']) . '" alt="' . htmlspecialchars($product['Name']) . '">';
            echo '</div>';
            echo '<h3>' . htmlspecialchars($product['Name']) . '</h3>';
            echo '</a>';
            echo '<p class="description">' . htmlspecialchars(substr($product['Description'], 0, 70)) . '...</p>'; // Short description
            echo '<p class="price">$' . number_format($product['Price'], 2) . '</p>';
            echo '<form action="actions/cart_action.php" method="POST">';
            echo '<input type="hidden" name="product_id" value="' . $product['ProductID'] . '">';
            echo '<input type="hidden" name="quantity" value="1">'; // Default quantity
            echo '<button type="submit" name="add_to_cart" class="btn">Add to Cart</button>';
            echo '</form>';
            echo '</div>';
          }
        } else {
          echo "<p>No products found matching your filter criteria.</p>";
        }

        $stmt->close(); // Close the prepared statement
        ?>
      </div>
    </div>
  </div>

  <style>
    .product-card a {
      text-decoration: none !important;
    }
  </style>
<?php } // Close the else block from the prepared statement check 
?>