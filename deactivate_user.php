<?php
include('../includes/db_connect.php');
if (isset($_GET['id'])) {
    $userID = $_GET['id'];
    $query = "UPDATE customer SET Status = 'Active' WHERE CustomerID = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error in SQL query preparation: " . $conn->error);
    }

    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        header('Location: users.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

} else {
    echo "User ID is required.";
}
?>
