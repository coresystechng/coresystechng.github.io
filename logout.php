<?php

    // always start session
    session_start();

    // destroy session
    session_unset();
    session_destroy();

    // fresh session to set flash message
    session_start();
    $_SESSION['success'] = "You have been logged out successfully.";

    header('Location: index.php');
    exit();

?>
