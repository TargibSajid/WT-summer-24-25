
<?php
 
include "config.php";
 
$success= $error = "";
if($_SERVER["REQUEST_METHOD"]=="POST")    
{
$username=$_POST["username"];
$password=$_POST["password"];
$email=$_POST["email"];
if (empty($username)|| empty($password) || empty($email))
{
 
    $error= "Fill the form";
 
}
 
else
{
 // $hash_pass= password_hash($password, PASSWORD_DEFAULT) ;
  $sql= "INSERT INTO register (username,password,email) VALUES ('$username','$password', '$email')";
 
  if($con -> query($sql)===TRUE)
  {
    $success= "Registration Successfull and you can do the login";
  }
 
  else{
   
    $error = "error". $con->error;
 
  }
 
 
}
 
 
}
 
 
?>



















<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right,rgb(122, 121, 124),rgb(250, 251, 253));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }

        .signup-container h2 {
            margin-bottom: 25px;
            color: #333;
        }

        .signup-container input {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }

        .signup-container input:focus {
            border-color: #2575fc;
            box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
        }

        .signup-container button {
            width: 100%;
            padding: 12px;
            background-color: #2575fc;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
            transition: 0.3s;
        }

        .signup-container button:hover {
            background-color: #6a11cb;
        }

        .signup-container .login-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .signup-container .login-link a {
            color:rgb(229, 232, 236);
            text-decoration: none;
        }

        .signup-container .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create Account</h2>
        <form action="#" method="POST">
            <input type="text" placeholder="username" name="username" required>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" placeholder="Password" name="password" required>
            <input type="password" placeholder="Confirm Password" name="confirm_password" required>
            <button type="submit">Sign Up</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="signin.html">Sign In</a>
        </div>
    </div>
</body>
</html>
