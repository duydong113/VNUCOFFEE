<?php
include('../includes/db_connect.php');

if (isset($_GET['id'])) {
    $customerID = $_GET['id'];
    $query = "SELECT * FROM customer WHERE CustomerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    echo json_encode(['error' => 'User not found']);
}
?>
