<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "coresystechng@gmail.com";
    $subject = "New Contact Form Submission: " . htmlspecialchars($_POST['subject']);

    $message = "You have received a new message from your website contact form.\n\n";
    $message .= "First Name: " . htmlspecialchars($_POST['first_name']) . "\n";
    $message .= "Last Name: " . htmlspecialchars($_POST['last_name']) . "\n";
    $message .= "Email: " . htmlspecialchars($_POST['email']) . "\n";
    $message .= "Phone: " . htmlspecialchars($_POST['phone']) . "\n";
    $message .= "Subject: " . htmlspecialchars($_POST['subject']) . "\n";
    $message .= "Message:\n" . htmlspecialchars($_POST['message']) . "\n";

    $headers = "From: " . htmlspecialchars($_POST['email']) . "\r\n" .
                "Reply-To: " . htmlspecialchars($_POST['email']) . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message. Please try again.";
    }
}
?>
