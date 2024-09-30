<?php
session_start();

// Include your database connection script
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "faculty_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$email = $password = "";
$email_error = $password_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate registration number
    if (empty(trim($_POST['email']))) {
        $email_error = "Please enter your email.";
    } else {
        $email = trim($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_error = "Please enter your password.";
    } else {
        $password = trim($_POST['password']);
    }

    // If there are no input errors, proceed to verify credentials
    if (empty($email_error) && empty($password_error)) {
        // Create a prepared statement
        $stmt = $conn->prepare("SELECT * FROM faculty_signup WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['Password'])) {
                // Password is correct, set session variables
                $_SESSION['email'] = $user['Email'];
                $_SESSION['name'] = $user['Name']; // Optionally store other user details in session
                // Redirect to dashboard or another page
                header("Location: faculty_dash.php"); // Replace with your desired redirect page
                exit();
            } else {
                $password_error = "Invalid password.";
            }
        } else {
            $email_error = "No user found with that email address. Please try to signup.";
        }

        $stmt->close();
    }

    // Store error messages in session variables
    if (!empty($email_error)) {
        $_SESSION['email_error'] = $email_error;
    }
    if (!empty($password_error)) {
        $_SESSION['password_error'] = $password_error;
    }

    // Redirect to login form
    header("Location: faculty_login_form.php");
    exit();
}

// Close connection
$conn->close();
?>
