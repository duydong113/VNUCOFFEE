<?php
require_once 'vendor/autoload.php';
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
  header('location:home.php');
};

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('YOUR_STRIPE_SECRET_KEY');

if (isset($_POST['stripe_payment'])) {
  try {
    // Get the total amount from the session
    $total_price = $_POST['total_price'];

    // Create a PaymentIntent with the order amount and currency
    $payment_intent = \Stripe\PaymentIntent::create([
      'amount' => $total_price * 100, // Convert to cents
      'currency' => 'usd',
      'automatic_payment_methods' => [
        'enabled' => true,
      ],
    ]);

    // Store the payment intent ID in session for later use
    $_SESSION['payment_intent_id'] = $payment_intent->id;

    // Return the client secret to the frontend
    echo json_encode(['clientSecret' => $payment_intent->client_secret]);
    exit;
  } catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
  }
}

if (isset($_POST['confirm_payment'])) {
  try {
    $payment_intent_id = $_POST['payment_intent_id'];

    // Retrieve the payment intent
    $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

    if ($payment_intent->status === 'succeeded') {
      // Payment was successful, process the order
      $name = $_POST['name'];
      $number = $_POST['number'];
      $email = $_POST['email'];
      $address = $_POST['address'];
      $total_products = $_POST['total_products'];
      $total_price = $_POST['total_price'];

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, 'stripe', $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      echo json_encode(['success' => true]);
      exit;
    }
  } catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stripe Payment</title>
  <script src="https://js.stripe.com/v3/"></script>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php include 'components/user_header.php'; ?>

  <div class="heading">
    <h3>Complete Payment</h3>
    <p><a href="home.php">home</a> <span> / payment</span></p>
  </div>

  <section class="checkout">
    <form id="payment-form">
      <div id="payment-element"></div>
      <button id="submit" class="btn">Pay Now</button>
      <div id="error-message"></div>
    </form>
  </section>

  <script>
    const stripe = Stripe('YOUR_STRIPE_PUBLISHABLE_KEY');
    const elements = stripe.elements();
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit');

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      submitButton.disabled = true;

      try {
        const response = await fetch('stripe_payment.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'stripe_payment=1&total_price=<?php echo $_GET['amount']; ?>'
        });

        const data = await response.json();

        if (data.error) {
          throw new Error(data.error);
        }

        const {
          error
        } = await stripe.confirmPayment({
          elements,
          clientSecret: data.clientSecret,
          confirmParams: {
            return_url: window.location.origin + '/payment_success.php',
          }
        });

        if (error) {
          throw error;
        }
      } catch (error) {
        const messageDiv = document.getElementById('error-message');
        messageDiv.textContent = error.message;
      }

      submitButton.disabled = false;
    });
  </script>

  <?php include 'components/footer.php'; ?>
</body>

</html>