<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Signup</title>
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
        <h3 class="text-center" style="color: blue">Faculty SignUp</h3>
        <form id="signupForm" onsubmit="return validateForm()" action="faculty_signup.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <small id="emailHelp" class="form-text text-danger"></small>
                <span id="emailerror" class="text-danger"><?php echo isset($emailerror) ? $emailerror : ''; ?></span>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" class="form-control" id="department" name="department" required>
            </div>
            <div class="form-group">
                <label for="phNo">Phone Number:</label>
                <input type="tel" class="form-control" id="phNo" name="phNo" pattern="[0-9]{10}" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small id="passwordHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">SignUp</button>
        </form>
        <div class="text-center mt-3">
            Already have an account? <a href="faculty_login.html">Login</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        function validateForm() {
            const email = document.getElementById('email').value;
            const emailHelp = document.getElementById('emailHelp');
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordHelp = document.getElementById('passwordHelp');
            const emailPattern = /^[a-zA-Z0-9._%+-]+@svecw\.edu\.in$/;
    
            // Clear previous error messages
            emailHelp.textContent = '';
            passwordHelp.textContent = '';
    
            let valid = true;
    
            // Validate email
            if (!emailPattern.test(email)) {
                emailHelp.textContent = 'Invalid email address. Please use your svecw.edu.in email.';
                valid = false;
            }
    
            // Validate password strength
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{4,}$/;
            if (!passwordPattern.test(password)) {
                passwordHelp.textContent = 'Password must be at least 4 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.';
                valid = false;
            } else if (password !== confirmPassword) {
                passwordHelp.textContent = 'Passwords do not match.';
                valid = false;
            }
    
            return valid;
        }
    </script>
    
    <button class="home-button" onclick="redirectToHome()">Back</button>

    <script>
        // JavaScript function to redirect to home.html
        function redirectToHome() {
            window.location.href = "home.html";
        }

        const phpError = document.getElementById('emailerror');
    if (phpError && phpError.textContent.trim().length > 0) {
        valid = false; 
        alert(phpError.textContent); 
    }

    </script>
</body>
</html>