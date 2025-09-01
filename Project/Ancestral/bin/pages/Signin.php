<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Ancestral</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f7f7f7 url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABg3Am0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAAd0SU1FB+YCAg0mHsyNwy0AAADkSURBVGje7dpBCYAwDERR9n//nVnhZhYEYZp3SpbbmHCsoItgCQX1x6GgDuRIG8sB3kwyYJnD3Yh7PSDPB+CoCY0T3cH+9jXYdVwEzVccw7SkhY8XgIxKSEvF4CMSEhLxeAjEhIS8XgIxISEvF4CMSEhLxeAjEhIS8XgIxISEvF4CMSEhLxeAjEhIS8XgIxISEvF4CMSEhLxeAjEhIS8XgIxISEvF4CMSEhLxeAjEhIS8XgIxISEvF4CMSEhLxeAjEhIS8XgIxISVv8A3Jqv5tOLO2UAAAAASUVORK5CYII=') repeat;
        }

        .signin-container {
            background-color: #fff;
            padding: 40px 30px;
            width: 350px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .signin-container h2 {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        .signin-container input[type="email"],
        .signin-container input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }

        .signin-container input[type="email"]:focus,
        .signin-container input[type="password"]:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 5px rgba(108, 99, 255, 0.5);
        }

        .signin-container button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #6c63ff;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .signin-container button:hover {
            background-color: #5750d2;
        }

        .signin-container a {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #666;
            text-decoration: none;
        }

        .signin-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

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
