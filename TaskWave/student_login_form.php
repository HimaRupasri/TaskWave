<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
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
        <h3 class="text-center" style="color: blue">Student Login</h3>
        <br>
        <br>

        <form id="loginForm" action="student_login.php" method="POST" onsubmit="return fun()">
            <div class="form-group">
                <label for="registrationNumber">Registration Number:</label>
                <input type="text" class="form-control" id="registrationNumber" name="registrationNumber" required>
                <small class="text-danger">
                    <?php 
                        echo isset($_SESSION['registrationNumber_error']) ? $_SESSION['registrationNumber_error'] : ''; 
                    ?>
                </small>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="text-danger">
                    <?php 
                        echo isset($_SESSION['password_error']) ? $_SESSION['password_error'] : ''; 
                    ?>
                </small>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="student_signup_form.php">Sign Up</a> | <a href="forgot_student_password.html">Forgot Password?</a>
        </div>
    </div>

    <button class="home-button" onclick="redirectToHome()">Back</button>

    <script>
        function redirectToHome() {
            window.location.href = "home.html";
        }

        function fun() {
            var number = document.getElementById('registrationNumber').value;
            var password = document.getElementById('password').value;
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
            var errorMessages = [];

            if (number == null || number === "" || number.length !== 10) {
                errorMessages.push("Please enter your registration number correctly.");
            }

            if (/[a-z]/.test(number)) {
                alert("Registration number should not contain lowercase letters.");
            }

            if (!passwordRegex.test(password)) {
                errorMessages.push(
                    "Password must be at least 6 characters long and include:",
                    "- At least one lowercase letter",
                    "- At least one uppercase letter",
                    "- At least one digit",
                    "- At least one special character"
                );
            }

            if (errorMessages.length > 0) {
                alert(errorMessages.join('\n'));
                return false;
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Clear error messages after displaying them
unset($_SESSION['registrationNumber_error']);
unset($_SESSION['password_error']);
?>
