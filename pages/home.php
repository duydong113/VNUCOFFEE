<?php
// Get more products for slider
$slider_query = "SELECT ProductID, Name, Price, ImageURL 
                 FROM PRODUCT 
                 ORDER BY RAND() 
                 LIMIT 12";  // Increased limit to show more products
$slider_result = $conn->query($slider_query);

?>

<div class="hero-section">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <h1>Welcome to Cofe Shop</h1>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />



    <p>Discover our premium coffee selection</p>
  </div>
</div>
<!-- New Image Gallery Section -->
<div class="image-gallery-section">
  <div class="container">
    <div class="gallery-content">
      <div class="main-image-area">

      </div>
      <div class="small-images-gallery">
        <!-- Placeholder for small images -->
        <a href="#"><img src="images/bg.png" alt="Small Coffee 1"></a>
        <a href="#"><img src="images/bgg.png" alt="Small Coffee 2"></a>
        <a href="#"><img src="images/hero-bg.png" alt="Small Coffee 3"></a>
      </div>
    </div>
  </div>
</div>
<div class="main-content">

  <!-- Your existing sidebar content -->
</div>

<div class="product-slider-section">
  <div style="text-align: left; margin-bottom: 20px;background-color:#fff4e6">
    <h2 style="color: #4a3b31; font-size: 2em; font-weight: 600;">Featured Products</h2>

  </div>

  <div class="product-slider">
    <button class="slider-arrow prev-arrow" id="prevBtn">&lt;</button>
    <div class="slider-container">
      <?php while ($product = $slider_result->fetch_assoc()): ?>
        <div class="slider-item">
          <img src="<?php echo htmlspecialchars($product['ImageURL']); ?>"
            alt="<?php echo htmlspecialchars($product['Name']); ?>">
          <h3><?php echo htmlspecialchars($product['Name']); ?></h3>
          <p class="price"><?php echo number_format($product['Price'], 0, ',', '.'); ?>đ</p>
          <a href="index.php?page=product_detail&id=<?php echo $product['ProductID']; ?>"
            class="btn btn-secondary">More details</a>
        </div>
      <?php endwhile; ?>
    </div>
    <button class="slider-arrow next-arrow" id="nextBtn">&gt;</button>
  </div>
</div>



<div class="signature-section" style="display: flex; align-items: center; justify-content: center; background: #fff; margin: 40px 0; padding: 40px 0;">
  <div style="flex: 1; max-width: 500px; padding: 40px;">
    <h2 style="font-size: 2.5em; color: #333; font-weight: bold; margin-bottom: 10px;">SIGNATURE</h2>
    <h3 style="font-size: 2em; color: #222; margin-bottom: 20px;">By The Cofe Shop</h3>
    <p style="font-size: 1.2em; color: #444; margin-bottom: 30px;">
      Where the coffee shop is a place of meeting, a place of food with many flavors, and a place of inspiration.
    </p>
    <button style="background: #c00; color: #fff; border: none; padding: 15px 40px; border-radius: 8px; font-size: 1.1em; font-weight: bold; cursor: pointer;">Find out more</button>
  </div>
  <div style="flex: 1; display: flex; align-items: center; justify-content: flex-end; height: 100%;">
    <img src="images/signature-coffeehouse.jpg" alt="SIGNATURE By The Coffee House" style="width: 100%; max-width: 600px; height: auto; border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); object-fit: cover;">
  </div>
</div>





<style>
  dy {
    font-family: "Poppins", sans-serif;
  }

  .hero-section {
    position: relative;
    background: url("images/hero-bg.png") no-repeat center;
    background-size: cover;
    color: #fff;
    text-align: center;
    padding: 100px 20px;
    margin-bottom: 40px;
  }

  .hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1;
  }

  .hero-content {
    position: relative;
    z-index: 2;
  }

  .hero-section h1 {
    font-size: 3em;
    margin-bottom: 20px;
    color: #fff;
  }

  /* Main Content Area */
  main {
    padding: 20px 0;
  }

  h1,
  h2,
  h3 {
    color: #4a3b31;
    margin-bottom: 15px;
  }

  /* Main Content Layout */
  .main-content {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 30px;
  }

  /* Products */
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }

  .product-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .product-card img {
    max-width: 100%;
    height: 200px;
    object-fit: cover;
    margin-bottom: 10px;
    border-radius: 3px;
  }

  .product-card h3 {
    font-size: 1.2em;
    margin-bottom: 5px;
  }

  .product-card .price {
    font-weight: bold;
    color: #8b5e3c;
    /* Lighter brown */
    margin-bottom: 10px;
  }

  /* Product Slider */
  .product-slider-section {
    margin: 40px 0;
    position: relative;
  }

  .product-slider {
    position: relative;
    padding: 0 40px;
    overflow: hidden;
  }

  .slider-container {
    display: flex;
    gap: 20px;
    scroll-behavior: smooth;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x mandatory;
    padding: 10px 0;
  }

  .slider-item {
    min-width: 220px;
    flex: 0 0 auto;
    scroll-snap-align: start;
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
  }

  .slider-item:hover {
    transform: translateY(-5px);
  }

  .slider-item img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 10px;
  }

  .slider-item h3 {
    font-size: 1.1em;
    margin: 10px 0;
    color: #4a3b31;
  }

  .slider-item .price {
    color: #8b5e3c;
    font-weight: bold;
    margin-bottom: 15px;
  }

  .slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background: #4a3b31;
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 20px;
    transition: all 0.3s ease;
    z-index: 2;
  }

  .slider-arrow:hover {
    background: #6a4a2f;
    transform: translateY(-50%) scale(1.1);
  }

  .prev-arrow {
    left: 0;
  }

  .next-arrow {
    right: 0;
  }

  .slider-arrow:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.5;
  }

  /* Product Detail Page */
  .product-detail {
    padding: 20px 0;
  }

  .product-content {
    display: flex;
    gap: 40px;
    margin-top: 20px;
  }

  .product-image {
    flex: 1;
    max-width: 500px;
  }

  .product-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .product-info {
    flex: 1;
  }

  .product-info .category {
    color: #666;
    margin-bottom: 15px;
  }

  .product-info .price {
    font-size: 2em;
    color: #8b5e3c;
    font-weight: bold;
    margin-bottom: 20px;
  }

  .product-info .stock {
    margin-bottom: 20px;
  }

  .product-info .in-stock {
    color: #28a745;
  }

  .product-info .out-of-stock {
    color: #dc3545;
  }

  .product-info .description {
    line-height: 1.8;
    margin-bottom: 30px;
    color: #444;
  }

  .add-to-cart-form {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-top: 20px;
  }

  .add-to-cart-form .form-group {
    margin: 0;
  }

  .add-to-cart-form input[type="number"] {
    width: 80px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  .out-of-stock-message {
    color: #dc3545;
    font-weight: bold;
    margin-top: 20px;
  }

  /* Buttons */
  .btn {
    display: inline-block;
    background: #8b5e3c;
    color: #fff;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .btn:hover {
    background: #6a4a2f;
  }

  .btn-secondary {
    background: #c8a07d;
  }

  .btn-secondary:hover {
    background: #b08a6a;
  }

  .btn-danger {
    background: #d9534f;
  }

  .btn-danger:hover {
    background: #c9302c;
  }

  /* Forms */
  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
    color: #4a3b31;
  }

  .form-group input[type="text"],
  .form-group input[type="email"],
  .form-group input[type="password"],
  .form-group input[type="number"],
  .form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  /* Cart Table */
  .cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .cart-table th,
  .cart-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
  }

  .cart-table th {
    background-color: #f2f2f2;
    color: #4a3b31;
  }

  .cart-table img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    margin-right: 10px;
    vertical-align: middle;
  }

  .cart-table input[type="number"] {
    width: 60px;
    padding: 5px;
  }

  .cart-total {
    text-align: right;
    font-size: 1.2em;
    margin-bottom: 20px;
  }

  .cart-total strong {
    color: #4a3b31;
  }

  /* Alerts/Messages */
  .alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
  }

  .alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
  }

  .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
  }

  .alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
  }

  /* Footer */
  footer {
    background: #2d2d2d;
    color: #fff;
    padding: 40px 20px;
  }

  .footer-content {
    display: flex;
    justify-content: space-between;
    margin-bottom: 40px;
  }

  .footer-section {
    flex: 1;
    margin-right: 30px;
  }

  .footer-section:last-child {
    margin-right: 0;
  }

  .footer-section h3 {
    color: #fff;
    font-size: 1.1em;
    margin-bottom: 20px;
    font-weight: 500;
  }

  .footer-section ul {
    list-style: none;
    padding: 0;
  }

  .footer-section ul li {
    margin-bottom: 12px;
  }

  .footer-section ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 0.9em;
    opacity: 0.8;
    transition: opacity 0.3s ease;
  }

  .footer-section ul li a:hover {
    opacity: 1;
  }

  .contact-info {
    margin-top: 20px;
  }

  .contact-info h3 {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .contact-info h3:before {
    content: "";
    display: inline-block;
    width: 24px;
    height: 24px;
    background-image: url("../images/location-icon.png");
    background-size: contain;
  }

  .contact-info p {
    line-height: 1.6;
    font-size: 0.9em;
    opacity: 0.8;
  }

  .footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 20px;
  }

  .footer-bottom p {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85em;
    margin-bottom: 8px;
    line-height: 1.5;
  }

  /* Phone number style */
  [href^="tel"] {
    font-size: 1.2em;
    font-weight: 500;
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  [href^="tel"]:before {
    content: "";
    display: inline-block;
    width: 24px;
    height: 24px;
    background-image: url("../images/phone-icon.png");
    background-size: contain;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .product-content {
      flex-direction: column;
    }

    .product-image {
      max-width: 100%;
    }
  }
</style>