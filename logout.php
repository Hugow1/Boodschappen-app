<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

if (isset ($_COOKIE['user_id'])) {
    unset($_COOKIE['user_id']);
    setcookie('user_id', null, -1, '/');
    return true;
}


// Destroy the session.
session_destroy();

// Redirect to login page
header("location: login.php");
exit;
?>
