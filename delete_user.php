<?php
// Include the database connection
include('../includes/db_connect.php');

// Check if the 'CustomerID' is set in the POST request
if (isset($_POST['CustomerID'])) {
    // Get the CustomerID from the POST request
    $customerID = $_POST['CustomerID'];

    // Prepare the SQL query to delete the user from the database
    $stmt = $conn->prepare("DELETE FROM customer WHERE CustomerID = ?");
    $stmt->bind_param("i", $customerID);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to the manage_users page with a success message
        header("Location: users.php?status=success");
    } else {
        // If there was an error, redirect back to the manage_users page with an error message
        header("Location: users.php?status=error");
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If no CustomerID was found, redirect back to the manage_users page with an error message
    header("Location: users.php?status=error");
}

// Close the database connection
$conn->close();
?>
