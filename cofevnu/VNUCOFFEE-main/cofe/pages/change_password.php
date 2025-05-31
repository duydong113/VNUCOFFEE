<?php
// This page requires user to be logged in, which is handled by index.php router

$customer_id = $_SESSION['user_id'];

// TODO: Handle form submission for changing password

?>

<div class="account-form-container">
  <h1>Change Password</h1>
  <p>Update your password below.</p>

  <form action="index.php?page=change_password" method="POST">
    <div class="form-group">
      <label for="current_password">Current Password:</label>
      <input type="password" id="current_password" name="current_password" required>
    </div>
    <div class="form-group">
      <label for="new_password">New Password:</label>
      <input type="password" id="new_password" name="new_password" required>
    </div>
    <div class="form-group">
      <label for="confirm_password">Confirm New Password:</label>
      <input type="password" id="confirm_password" name="confirm_password" required>
    </div>

    <button type="submit" name="change_password_btn" class="btn">Change Password</button>
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
  }

  .account-form-container .btn {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #a0522d;
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
  }

  .account-form-container a.btn-secondary {
    display: inline-block;
    text-align: center;
    margin-top: 10px;
    color: #6c757d;
    text-decoration: none;
  }

  .account-form-container a.btn-secondary:hover {
    text-decoration: underline;
  }
</style>