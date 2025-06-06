<?php
// Database connection is already included in index.php
// Headers and session are already managed in index.php

// Main content wrapper
echo '<main class="container">';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<div class='alert alert-danger'>Product Not Found. Invalid product ID.</div>";
} else {
  $product_id = intval($_GET['id']);
  $stmt = $conn->prepare("SELECT p.ProductID, p.Name AS ProductName, p.Description, 
                              p.Price, p.ImageURL, p.StockQuantity, 
                              c.Name AS CategoryName 
                       FROM PRODUCT p 
                       LEFT JOIN CATEGORY c ON p.CategoryID = c.CategoryID 
                       WHERE p.ProductID = ?");
  if ($stmt) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $product = $result->fetch_assoc();
?>
      <div class="product-detail">
        <br />
        <br />
        <h1><?php echo htmlspecialchars($product['ProductName']); ?></h1>
        <div class="product-content">
          <div class="product-image">
            <img src="<?php echo htmlspecialchars($product['ImageURL']); ?>"
              alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
          </div>
          <div class="product-info">
            <p class="category"><strong>Category:</strong> <?php echo htmlspecialchars($product['CategoryName'] ?: 'N/A'); ?></p>
            <p class="price">$<?php echo number_format($product['Price'], 2); ?></p>
            <p class="stock"><strong>Availability:</strong>
              <?php echo ($product['StockQuantity'] > 0) ?
                '<span class="in-stock">' . $product['StockQuantity'] . ' in stock</span>' :
                '<span class="out-of-stock">Out of stock</span>'; ?>
            </p>
            <div class="description">
              <?php echo nl2br(htmlspecialchars($product['Description'])); ?>
            </div>

            <?php if ($product['StockQuantity'] > 0): ?>
              <form action="<?php echo BASE_URL; ?>actions/cart_action.php" method="POST" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                <div class="form-group">
                  <label for="quantity">Quantity:</label>
                  <input type="number" name="quantity" id="quantity" value="1"
                    min="1" max="<?php echo $product['StockQuantity']; ?>">
                </div>
                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
              </form>
            <?php else: ?>
              <p class="out-of-stock-message">This product is currently out of stock.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
<?php
    } else {
      echo "<div class='alert alert-danger'>Product Not Found. The product you are looking for does not exist.</div>";
    }
    $stmt->close();
  } else {
    echo "<div class='alert alert-danger'>Error: Could not fetch product details.</div>";
  }
}

echo '</main>';
?>