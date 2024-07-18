<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

session_start(); // Start a new session to set the flash message
$_SESSION['flash_message'] = 'Logged out successfully';

header("Location: login.php"); // Redirect to login page
exit();
?>

