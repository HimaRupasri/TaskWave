<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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

        /* Centering form fields */
        form {
            text-align: center;
        }
        form .form-group {
            text-align: left; /* Ensure labels are left-aligned within centered form */
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center" style="color:blue">Student SignUp</h3>
        <form id="signupForm" onsubmit="return validateForm()" action="student_signup.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="registrationNumber">Registration Number:</label>
                <input type="text" class="form-control" id="registrationNumber" name="registrationNumber" required>
                <small id="regNumHelp" class="form-text text-danger"></small>
                <span id="registrationError" class="text-danger"><?php echo isset($registration_error) ? $registration_error : ''; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <small id="emailHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="phNo">Phone Number:</label>
                <input type="tel" class="form-control" id="phNo" name="phNo" pattern="[0-9]{10}" required>
                <small id="phoneHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" class="form-control" id="department" name="department" required>
                <small id="deptHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <select class="form-control" id="year" name="year" required>
                    <option value="">Select Year</option>
                    <option value="1">First Year</option>
                    <option value="2">Second Year</option>
                    <option value="3">Third Year</option>
                    <option value="4">Fourth Year</option>
                </select>
                <small id="yearHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="sem">Semester:</label>
                <select class="form-control" id="sem" name="sem" required>
                    <option value="">Select Semester</option>
                    <option value="1">I</option>
                    <option value="2">II</option>
                </select>
                <small id="semHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="section">Section:</label>
                <select class="form-control" id="section" name="section" required>
                    <option value="">Select Section</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                </select>
                <small id="sectionHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="CGPA">CGPA:</label>
                <input type="text" class="form-control" id="CGPA" name="CGPA" required>
                <small id="cgpaHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small id="passwordHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                <small id="confirmPasswordHelp" class="form-text text-danger"></small>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Signup</button>
        </form>
        <div class="text-center mt-3">
            Already have an account? <a href="student_login_form.php">Login</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        function validateForm() {
    let valid = true;
    // Clear previous error messages
    document.querySelectorAll('.form-text.text-danger').forEach(el => el.textContent = '');

    const name = document.getElementById('name').value.trim();
    const registrationNumber = document.getElementById('registrationNumber').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phNo').value.trim();
    const department = document.getElementById('department').value.trim();
    const year = document.getElementById('year').value;
    const sem = document.getElementById('sem').value;
    const section = document.getElementById('section').value;
    const cgpa = document.getElementById('CGPA').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Validate registration number length
    if (registrationNumber.length !== 10) {
        document.getElementById('regNumHelp').textContent = 'Registration number must be exactly 10 characters long';
        valid = false;
    }

    // Validate registration number should not contain lowercase letters
    if (/[a-z]/.test(registrationNumber)) {
        document.getElementById('regNumHelp').textContent = 'Registration number should not contain lowercase letters.';
        valid = false;
    }

    // Validate CGPA range (assuming 0 to 10)
    const cgpaFloat = parseFloat(cgpa);
    if (isNaN(cgpaFloat) || cgpaFloat < 0 || cgpaFloat > 10) {
        document.getElementById('cgpaHelp').textContent = 'Enter a valid CGPA between 0 and 10';
        valid = false;
    }

    // Validate password strength
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{4,}$/;
    if (!passwordRegex.test(password)) {
        document.getElementById('passwordHelp').textContent = 'Password must be at least 4 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character';
        valid = false;
    }

    // Validate password match
    if (password !== confirmPassword) {
        document.getElementById('confirmPasswordHelp').textContent = 'Passwords do not match';
        valid = false;
    }

    // Validate all required fields are filled
    if (!name || !registrationNumber || !email || !phone || !department || !year || !sem || !section || !cgpa || !password || !confirmPassword) {
        if (!name) document.getElementById('nameHelp').textContent = 'Name is required';
        if (!registrationNumber) document.getElementById('regNumHelp').textContent = 'Registration number is required';
        if (!email) document.getElementById('emailHelp').textContent = 'Email is required';
        if (!phone) document.getElementById('phoneHelp').textContent = 'Phone number is required';
        if (!department) document.getElementById('deptHelp').textContent = 'Department is required';
        if (!year) document.getElementById('yearHelp').textContent = 'Year is required';
        if (!sem) document.getElementById('semHelp').textContent = 'Semester is required';
        if (!section) document.getElementById('sectionHelp').textContent = 'Section is required';
        if (!cgpa) document.getElementById('cgpaHelp').textContent = 'CGPA is required';
        if (!password) document.getElementById('passwordHelp').textContent = 'Password is required';
        if (!confirmPassword) document.getElementById('confirmPasswordHelp').textContent = 'Confirm password is required';
        valid = false;
    }

    const phpError = document.getElementById('registrationError');
    if (phpError && phpError.textContent.trim().length > 0) {
        valid = false; 
        alert(phpError.textContent); 
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
    </script>
</body>
</html>
