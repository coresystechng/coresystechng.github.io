<?php
include 'connect.php';

// Create blank variables
$first_name = $last_name = $email = $phone = $subject = $message = "";

// When the submit button is pressed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for SQL injection
    $first_name = mysqli_real_escape_string($connect, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($connect, $_POST['last_name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $subject = mysqli_real_escape_string($connect, $_POST['subject']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);

    // Write Save Query
    $save_query = 
    "INSERT INTO `contact_tb` (`first_name`, `last_name`, `email`, `phone`, `subject`, `message`) VALUES ('$first_name', '$last_name', '$email', '$phone', '$subject', '$message')";

    // Send Query to server
    $send_query = mysqli_query($connect, $save_query);

    if($send_query) {
      header('Location: success.html');
    } else {
      echo 'error in sending contact details. Please try again';
    }

    // $to = "coresystechng@gmail.com";
    // $subject = "New Contact Form Submission: " . htmlspecialchars($_POST['subject']);

    // $message = "You have received a new message from your website contact form.\n\n";
    // $message .= "First Name: " . htmlspecialchars($_POST['first_name']) . "\n";
    // $message .= "Last Name: " . htmlspecialchars($_POST['last_name']) . "\n";
    // $message .= "Email: " . htmlspecialchars($_POST['email']) . "\n";
    // $message .= "Phone: " . htmlspecialchars($_POST['phone']) . "\n";
    // $message .= "Subject: " . htmlspecialchars($_POST['subject']) . "\n";
    // $message .= "Message:\n" . htmlspecialchars($_POST['message']) . "\n";

    // $headers = "From: " . htmlspecialchars($_POST['email']) . "\r\n" .
    //             "Reply-To: " . htmlspecialchars($_POST['email']) . "\r\n";

    // if (mail($to, $subject, $message, $headers)) {
    //     echo "Message sent successfully!";
    // } else {
    //     echo "Failed to send message. Please try again.";
    // }
}
?>
