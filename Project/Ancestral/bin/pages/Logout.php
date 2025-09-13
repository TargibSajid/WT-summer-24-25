<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Optional: Destroy the cookie too
if (isset($_COOKIE['username'])) {
    setcookie("username", "", time() - 3600, "/"); // expired
}

// Redirect back to HomePage
header("Location: HomePage.php");
exit;
?>