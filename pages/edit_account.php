<?php
// This page requires user to be logged in, which is handled by index.php router

$customer_id = $_SESSION['user_id'];

// Handle form submission for updating details
if (isset($_POST['update_account'])) {
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']) ?: NULL; // Allow phone to be NULL

  // Basic validation
  if (empty($firstname) || empty($lastname) || empty($email)) {
    $_SESSION['message'] = "First name, last name, and email are required.";
    $_SESSION['message_type'] = "danger";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "Invalid email format.";
    $_SESSION['message_type'] = "danger";
  } else {
    // Check if email already exists for another user
    $stmt = $conn->prepare("SELECT CustomerID FROM CUSTOMER WHERE Email = ? AND CustomerID != ?");
    $stmt->bind_param("si", $email, $customer_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $_SESSION['message'] = "This email is already associated with another account.";
      $_SESSION['message_type'] = "danger";
    } else {
      // Update user details
      $update_stmt = $conn->prepare("UPDATE CUSTOMER SET Firstname = ?, Lastname = ?, Email = ?, PhoneNum = ? WHERE CustomerID = ?");
      $update_stmt->bind_param("ssssi", $firstname, $lastname, $email, $phone, $customer_id);

      if ($update_stmt->execute()) {
        $_SESSION['message'] = "Account details updated successfully!";
        $_SESSION['message_type'] = "success";

        // Update session variables if necessary (e.g., if you display firstname in header)
        $_SESSION['firstname'] = $firstname;
      } else {
        $_SESSION['message'] = "Error updating account details: " . $update_stmt->error;
        $_SESSION['message_type'] = "danger";
      }
      $update_stmt->close();
    }
    $stmt->close();
  }

  // Redirect back to the edit page or account page
  header("Location: index.php?page=edit_account"); // Or index.php?page=account
  exit();
}

// Fetch current user details for pre-filling the form
$stmt = $conn->prepare("SELECT Firstname, Lastname, Email, PhoneNum FROM CUSTOMER WHERE CustomerID = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$user_details = $result->fetch_assoc();
$stmt->close();

// Check if user details were fetched
if (!$user_details) {
  $_SESSION['message'] = "Could not retrieve your account details.";
  $_SESSION['message_type'] = "danger";
  header("Location: index.php?page=account");
  exit();
}

?>

<div class="account-form-container">
  <h1>Edit Account Details</h1>
  <p>Update your account information below.</p>

  <form action="index.php?page=edit_account" method="POST">
    <div class="form-group">
      <label for="firstname">First Name:</label>
      <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user_details['Firstname']); ?>" required>
    </div>
    <div class="form-group">
      <label for="lastname">Last Name:</label>
      <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user_details['Lastname']); ?>" required>
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_details['Email']); ?>" required>
    </div>
    <div class="form-group">
      <label for="phone">Phone Number:</label>
      <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user_details['PhoneNum'] ?? ''); ?>">
    </div>

    <button type="submit" name="update_account" class="btn">Save Changes</button>
  </form>

  <p><a href="index.php?page=account">Back to My Account</a></p>
</div>

<style>
  .account-form-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .account-form-container h1 {
    text-align: center;
    margin-top: 0;
    color: #8b5e3c;
  }

  .account-form-container p {
    text-align: center;
    margin-bottom: 20px;
    color: #555;
  }

  .account-form-container .form-group {
    margin-bottom: 15px;
  }

  .account-form-container label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #4a3b31;
  }

  .account-form-container input[type="text"],
  .account-form-container input[type="email"],
  .account-form-container input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    /* Include padding and border in the element's total width and height */
  }

  .account-form-container .btn {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #a0522d;
    /* A nice coffee-like brown */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
  }

  .account-form-container .btn:hover {
    background-color: #8b5e3c;
    /* Darker brown on hover */
  }

  .account-form-container a.btn-secondary {
    display: inline-block;
    /* Change to inline-block if needed */
    text-align: center;
    margin-top: 10px;
    color: #6c757d;
    text-decoration: none;
  }

  .account-form-container a.btn-secondary:hover {
    text-decoration: underline;
  }
</style>