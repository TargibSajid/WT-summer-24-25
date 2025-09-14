<?php
session_start();
require 'Config.php'; // Make sure $con is your mysqli connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname    = trim($_POST["fullname"]);
    $username    = trim($_POST["username"]);
    $email       = trim($_POST["email"]);
    $password    = $_POST["password"];
    $phonenumber = trim($_POST["phone"]);

    $stmt = $con->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        echo "❌ Email already registered!";
        exit;
    }
    $stmt->close();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $con->prepare("INSERT INTO users (full_name, user_name, email, password_hash, phone_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $username, $email, $password_hash, $phonenumber);

    if ($stmt->execute()) {

        $_SESSION['username'] = $username;
        $_SESSION['email']    = $email;
        $_SESSION['user_id'] = $row['user_id'];

        setcookie("username", $username, time() + (86400*7), "/");



        header("Location: HomePage.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;

        
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
    <title>Sign Up | Ancestral</title>
    <link rel="stylesheet" href="../cascade/Signup.css? <?php echo time(); ?>"> <!-- External CSS -->

</head>
<body>
    <div class="bg-image"></div>

 <h1 style = "color: black" class = "header">NCESTRAL</h1>
 <img src="../../Logo.svg" alt="Ancestral Logo" class="Logo">


    <div class="signup-container">
        <h2>Create Your Account</h2>
        <form action="Signup.php" method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" placeholder="Name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="@example.com" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter password" required>
            </div>


            <div class="form-group">
                <label for="phone">Phone Number (Optional)</label>
                <input type="tel" id="phone" name="phone" placeholder="+880">
            </div>

            <div class="form-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="/terms" target="_blank">Terms & Conditions</a></label>
            </div>

            <button type="submit" class="submit-btn">Sign Up</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="/login">Log In</a>
        </div>
    </div>
</body>
</html>
