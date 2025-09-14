<?php
session_start();


session_unset();
session_destroy();


if (isset($_COOKIE['username'])) {
    setcookie("username", "", time() - 3600, "/"); // expired
}


header("Location: HomePage.php");
exit;
?>