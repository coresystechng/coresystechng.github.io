<?php

    // Start session if not already started
    if (!isset($_SESSION['id'])) {
        session_start();
    }

    // Destroy all session data to log user out
    session_unset();
    session_destroy();

    // Start a fresh session to set logout success message
    session_start();
    $_SESSION['success'] = "You have been logged out successfully.";
    
    // Redirect to login page after logout
    header('Location: index.php');
    exit();

?>