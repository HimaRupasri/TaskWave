<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>
    <link rel="icon" type="image/png" href="dd.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 100px;
            max-height: 100px;
        }
        .caption {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .home-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center" style="color: blue">Faculty Login</h3>
        <br><br>

        <?php
        session_start();
        // Display any error messages from session
        if (isset($_SESSION['email_error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['email_error'] . '</div>';
            unset($_SESSION['email_error']);
        }
        if (isset($_SESSION['password_error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['password_error'] . '</div>';
            unset($_SESSION['password_error']);
        }
        ?>

        <form id="loginForm" onsubmit="return validateForm()" action="faculty_login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="faculty_signup_form.php">Sign Up</a> | <a href="forgot_fac.html">Forgot Password?</a>
        </div>
    </div>

    <button class="home-button" onclick="redirectToHome()">Back</button>

    <script>
        function validateForm() {
            let valid = true;
            let errorMessages = [];
            
            let email = document.getElementById('Email').value;
            let password = document.getElementById('password').value;
            
            // Email regex to match only emails with the domain svecw.edu.in
            let emailRegex = /^[a-zA-Z0-9._%+-]+@svecw\.edu\.in$/;
            let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
    
            if (!emailRegex.test(email)) {
                errorMessages.push('Please enter a valid email address with the domain svecw.edu.in');
                valid = false;
            }
    
            if (!passwordRegex.test(password)) {
                errorMessages.push(
                    "Password must be at least 6 characters long and include:",
                    "- At least one lowercase letter",
                    "- At least one uppercase letter",
                    "- At least one digit",
                    "- At least one special character"
                );
                valid = false;
            }
    
            if (!valid) {
                alert(errorMessages.join('\n'));
            }
    
            return valid;
        }
    
        function redirectToHome() {
            window.location.href = "home.html";
        }
    </script>
    
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>

<?php
unset($_SESSION['email_error']);
unset($_SESSION['password_error']);
?>
