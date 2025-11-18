<?php
include 'connect.php';

// Create blank variables
$first_name = $surname = $email = $phone = $subject = $message = "";

// When the submit button is pressed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for SQL injection
    $first_name = mysqli_real_escape_string($connect, $_POST['first_name']);
    $surname = mysqli_real_escape_string($connect, $_POST['surname']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $subject = mysqli_real_escape_string($connect, $_POST['subject']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);

    // Write Save Query
    $save_query = 
    "INSERT INTO `contact_tb` (`first_name`, `surname`, `email`, `phone`, `subject`, `message`) VALUES ('$first_name', '$surname', '$email', '$phone', '$subject', '$message')";

    // Send Query to server
    $send_query = mysqli_query($connect, $save_query);

    if($send_query) {
      header('Location: success.php');
      include 'send_contact_mail.php'; // Include the mail script to send the email
    } else {
      echo 'error in sending contact details. Please try again';
    }
}
?>
