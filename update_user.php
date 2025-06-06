<?php
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customerID = $_POST['CustomerID'];
    $username = $_POST['Username'];
    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $email = $_POST['Email'];
    $phoneNum = $_POST['PhoneNum'];
    $password = $_POST['Password']; // Get the password field value

    // Check if the password field is filled
    if (!empty($password)) {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE customer SET Username = ?, Firstname = ?, Lastname = ?, Email = ?, PhoneNum = ?, Password = ? WHERE CustomerID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $username, $firstname, $lastname, $email, $phoneNum, $hashedPassword, $customerID);
    } else {
        // If password is not provided, don't change it
        $query = "UPDATE customer SET Username = ?, Firstname = ?, Lastname = ?, Email = ?, PhoneNum = ? WHERE CustomerID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $username, $firstname, $lastname, $email, $phoneNum, $customerID);
    }

    if ($stmt->execute()) {
        header("Location: users.php"); // Redirect back to the users management page
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
