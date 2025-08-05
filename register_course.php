<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "coresystechng@gmail.com";
    $subject = "New Course registered by" . htmlspecialchars($_POST['first_name']);

    $message = "You have received a new message from your website's course registration form.\n\n";
    $message .= "First Name: " . htmlspecialchars($_POST['first_name']) . "\n";
    $message .= "Last Name: " . htmlspecialchars($_POST['last_name']) . "\n";
    $message .= "Email: " . htmlspecialchars($_POST['email']) . "\n";
    $message .= "Phone: " . htmlspecialchars($_POST['phone']) . "\n";
    $message .= "Course: " . htmlspecialchars($_POST['course']) . "\n";

    $headers = "From: " . htmlspecialchars($_POST['email']) . "\r\n" .
                "Reply-To: " . htmlspecialchars($_POST['email']) . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "Registered successfully!";
    } else {
        echo "Failed to register courses. Please try again.";
    }
}
?>
