<?php
include '../includes/db_connect.php'; 

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="VNUCOFFEESHOP\css\admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- messages section starts  -->
 
<section class="messages">
   <h1 class="heading">Messages</h1>

   <table class="messages-table">
      <thead>
         <tr>
            <th>Name</th>
            <th>Number</th>
            <th>Email</th>
            <th>Message</th>
            <th>Actions</th>
         </tr>
      </thead>
      <tbody>
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            if($select_messages->rowCount() > 0){
               while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
         ?>
         <tr>
            <td><?= $fetch_messages['name']; ?></td>
            <td><?= $fetch_messages['number']; ?></td>
            <td><?= $fetch_messages['email']; ?></td>
            <td><?= $fetch_messages['message']; ?></td>
            <td>
               <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</a>
            </td>
         </tr>
         <?php
               }
            } else {
               echo '<tr><td colspan="5" class="empty">No messages available</td></tr>';
            }
         ?>
      </tbody>
   </table>
</section>


<!-- messages section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>