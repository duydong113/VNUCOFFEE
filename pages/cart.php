<h1>Your Shopping Cart</h1>
<?php
if (!isset($_SESSION['user_id'])) {
  echo "<p class='alert alert-info'>You need to <a href='index.php?page=login'>login</a> to manage your cart and checkout.</p>";
  // Optional: Implement session-based cart for guests here if desired
} else {
  $customer_id = $_SESSION['user_id'];
  $cart_items_stmt = $conn->prepare(
    "SELECT c.ProductID, p.Name, p.Price, c.Quantity, p.ImageURL
         FROM CART c
         JOIN PRODUCT p ON c.ProductID = p.ProductID
         WHERE c.CustomerID = ?"
  );
  $cart_items_stmt->bind_param("i", $customer_id);
  $cart_items_stmt->execute();
  $cart_result = $cart_items_stmt->get_result();

  if ($cart_result->num_rows > 0) {
?>
    <table class="cart-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total_price = 0;
        while ($item = $cart_result->fetch_assoc()) {
          $subtotal = $item['Price'] * $item['Quantity'];
          $total_price += $subtotal;
        ?>
          <tr>
            <td>
              <a href="index.php?page=product_detail&id=<?php echo $item['ProductID']; ?>" class="cart-product-link">
                <?php echo htmlspecialchars($item['Name']); ?>
              </a>
            </td>
            <td>$<?php echo number_format($item['Price'], 2); ?></td>
            <td>
              <form action="<?php echo BASE_URL; ?>actions/cart_action.php" method="POST" style="display:inline-block;">
                <input type="hidden" name="product_id" value="<?php echo $item['ProductID']; ?>">
                <input type="number" name="quantity" value="<?php echo $item['Quantity']; ?>" min="1" style="width: 60px;">
                <button type="submit" name="update_cart_item" class="btn btn-secondary" style="padding:5px 8px; font-size:0.8em;">Update</button>
              </form>
            </td>
            <td>$<?php echo number_format($subtotal, 2); ?></td>
            <td>
              <form action="<?php echo BASE_URL; ?>actions/cart_action.php" method="POST" style="display:inline-block;">
                <input type="hidden" name="product_id" value="<?php echo $item['ProductID']; ?>">
                <button type="submit" name="remove_from_cart" class="btn btn-danger" style="padding:5px 8px; font-size:0.8em;">Remove</button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="cart-total">
      <strong>Total: $<?php echo number_format($total_price, 2); ?></strong>
    </div>
    <div style="text-align: right;">
      <form action="<?php echo BASE_URL; ?>actions/cart_action.php" method="POST" style="display:inline-block; margin-right:10px;">
        <button type="submit" name="clear_cart" class="btn btn-danger">Clear Cart</button>
      </form>
      <a href="index.php?page=checkout" class="btn">Proceed to Checkout</a>
    </div>
<?php
  } else {
    echo "<p class='alert alert-info'>Your cart is empty. <a href='index.php?page=products'>Start shopping!</a></p>";
  }
  $cart_items_stmt->close();
}
?>
<style>
  .cart-product-link {
    text-decoration: none !important;
    color: inherit;
  }

  .cart-product-link:hover {
    text-decoration: underline;
    color: #8b5e3c;
  }
</style>