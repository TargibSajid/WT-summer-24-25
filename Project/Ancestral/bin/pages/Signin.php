<?php

session_start();
require 'Config.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $con->prepare("SELECT user_id, user_name, email, password_hash 
                           FROM users 
                           WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['username'] = $row['user_name'];
            $_SESSION['email'] = $row['email'];

            setcookie("username", $row['user_name'], time() + (86400 * 7), "/");

            header("Location: HomePage.php");
            exit;
        } else {
            echo "<p style='color:red;'>❌ Wrong password!</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ No account found with that email!</p>";
    }

    $stmt->close();
}
$con->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Ancestral</title>
<link rel="stylesheet" href="../cascade/Signin.css? <?php echo time(); ?>">
</head>
<body>
    <div class="bg-image"></div>
     <h1 style = "color: black" class = "header">NCESTRAL</h1>
 <img src="../../Logo.svg" alt="Ancestral Logo" class="Logo">

    <div class="signin-container">
        <h2>Sign In</h2>
        <form action="Signin.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <a href="#">Forgot Password?</a>
        <a href="Signup.php">Don't have an account? Sign Up</a>
    </div>

</body>
</html>
