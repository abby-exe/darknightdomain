<?php
session_start(); // Start the session

// Destroy all session data
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to login page or any other page
header("Location: index.html"); // Change this to the desired page
exit();
?>
