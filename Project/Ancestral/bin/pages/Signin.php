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
        <form action="#" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <a href="#">Forgot Password?</a>
        <a href="Signup.php">Don't have an account? Sign Up</a>
    </div>

</body>
</html>
