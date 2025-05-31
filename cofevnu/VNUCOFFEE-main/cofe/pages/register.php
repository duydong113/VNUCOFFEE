<?php
if (isset($_SESSION['user_id'])) {
  header("Location: index.php?page=account"); // Redirect if already logged in
  exit();
}
?>
<style>
  .register-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.45);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .register-popup {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
    padding: 40px 32px 28px 32px;
    min-width: 340px;
    max-width: 95vw;
    position: relative;
    animation: popupIn 0.25s cubic-bezier(.4, 2, .6, 1) both;
  }

  @keyframes popupIn {
    0% {
      transform: scale(0.85) translateY(40px);
      opacity: 0;
    }

    100% {
      transform: scale(1) translateY(0);
      opacity: 1;
    }
  }

  .register-popup h1 {
    margin-top: 0;
    color: #8b5e3c;
    text-align: center;
  }

  .register-popup .form-group label {
    color: #4a3b31;
  }

  .register-popup .btn {
    width: 100%;
    margin-top: 10px;
  }

  .register-popup .close-btn {
    position: absolute;
    top: 12px;
    right: 18px;
    background: none;
    border: none;
    font-size: 1.7em;
    color: #b08a6a;
    cursor: pointer;
    transition: color 0.2s;
  }

  .register-popup .close-btn:hover {
    color: #d9534f;
  }

  .register-popup p {
    text-align: center;
    margin-bottom: 0;
  }
</style>
<div class="register-overlay">
  <div class="register-popup">
    <button class="close-btn" onclick="window.location.href='index.php'" title="Close">&times;</button>
    <h1>Register</h1>
    <form action="actions/auth_action.php?action=register" method="POST">
      <div class="form-group">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required>
      </div>
      <div class="form-group">
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number (Optional):</label>
        <input type="text" id="phone" name="phone">
      </div>
      <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have an account? <a href="index.php?page=login">Login here</a>.</p>
  </div>
</div>