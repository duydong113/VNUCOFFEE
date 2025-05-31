<?php
$servername = "localhost"; // or your server
$username = "root";        // your db username
$password = "";            // your db password
$dbname = "coffeeshopv2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Set charset to UTF-8
$conn->set_charset("utf8");
