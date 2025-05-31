<?php
session_start();
require_once '../includes/db_connect.php'; // Adjust path as actions is a subfolder

$action = $_GET['action'] ?? '';

if ($action == 'register' && $_SERVER['REQUEST_METHOD'] == 'POST') {
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $email = trim($_POST['email']);
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $phone = trim($_POST['phone']) ?: NULL;

  // Basic validation
  if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password)) {
    $_SESSION['message'] = "All required fields must be filled.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php?page=register");
    exit();
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "Invalid email format.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php?page=register");
    exit();
  }

  // Check if email or username already exists
  $stmt = $conn->prepare("SELECT CustomerID FROM CUSTOMER WHERE Email = ? OR Username = ?");
  $stmt->bind_param("ss", $email, $username);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    $_SESSION['message'] = "Email or Username already exists.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php?page=register");
    exit();
  }
  $stmt->close();

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert user
  $stmt = $conn->prepare("INSERT INTO CUSTOMER (Firstname, Lastname, Email, Username, Password, PhoneNum) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssss", $firstname, $lastname, $email, $username, $hashed_password, $phone);

  if ($stmt->execute()) {
    $_SESSION['message'] = "Registration successful! Please login.";
    $_SESSION['message_type'] = "success";
    header("Location: ../index.php?page=login");
  } else {
    $_SESSION['message'] = "Registration failed: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php?page=register");
  }
  $stmt->close();
} elseif ($action == 'login' && $_SERVER['REQUEST_METHOD'] == 'POST') {
  $username_or_email = trim($_POST['username']);
  $password = $_POST['password'];

  if (empty($username_or_email) || empty($password)) {
    $_SESSION['message'] = "Username/Email and Password are required.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php?page=login");
    exit();
  }

  $stmt = $conn->prepare("SELECT CustomerID, Username, Password, Firstname FROM CUSTOMER WHERE Username = ? OR Email = ?");
  $stmt->bind_param("ss", $username_or_email, $username_or_email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['Password'])) {
      $_SESSION['user_id'] = $user['CustomerID'];
      $_SESSION['username'] = $user['Username'];
      $_SESSION['firstname'] = $user['Firstname'];
      $_SESSION['message'] = "Welcome back, " . htmlspecialchars($user['Firstname']) . "!";
      $_SESSION['message_type'] = "success";
      header("Location: ../index.php?page=home"); // Or account page
    } else {
      $_SESSION['message'] = "Invalid username/email or password.";
      $_SESSION['message_type'] = "danger";
      header("Location: ../index.php?page=login");
    }
  } else {
    $_SESSION['message'] = "Invalid username/email or password.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php?page=login");
  }
  $stmt->close();
} elseif ($action == 'logout') {
  session_unset();
  session_destroy();
  session_start(); // Start a new session to store the logout message
  $_SESSION['message'] = "You have been logged out.";
  $_SESSION['message_type'] = "info";
  header("Location: ../index.php?page=home");
  exit();
} else {
  $_SESSION['message'] = "Invalid action.";
  $_SESSION['message_type'] = "danger";
  header("Location: ../index.php");
  exit();
}

$conn->close();
