<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php?page=login");
  exit();
}

// If user confirms logout
if (isset($_POST['confirm_logout'])) {
  // Clear all session variables
  $_SESSION = array();

  // Destroy the session
  session_destroy();

  // Redirect to home page
  header("Location: index.php");
  exit();
}
?>

<div class="logout-container">
  <h2>Logout Confirmation</h2>
  <p>Are you sure you want to logout?</p>

  <form method="POST" action="">
    <div class="button-group">
      <button type="submit" name="confirm_logout" class="btn btn-danger">Yes, Logout</button>
      <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>

<style>
  .logout-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    text-align: center;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .button-group {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    justify-content: center;
  }

  .btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-size: 16px;
  }

  .btn-danger {
    background-color: #dc3545;
    color: white;
  }

  .btn-secondary {
    background-color: #6c757d;
    color: white;
  }

  .btn:hover {
    opacity: 0.9;
  }
</style>