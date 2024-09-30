<?php
session_start();

// Include your database connection script
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$registrationNumber = $password = "";
$registrationNumber_error = $password_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate registration number
    if (empty(trim($_POST['registrationNumber']))) {
        $registrationNumber_error = "Please enter your registration number.";
    } else {
        $registrationNumber = trim($_POST['registrationNumber']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_error = "Please enter your password.";
    } else {
        $password = trim($_POST['password']);
    }

    // If there are no input errors, proceed to verify credentials
    if (empty($registrationNumber_error) && empty($password_error)) {
        // Create a prepared statement
        $stmt = $conn->prepare("SELECT * FROM student_signup WHERE registrationNumber = ?");
        $stmt->bind_param("s", $registrationNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['regNumber'] = $user['registrationNumber'];
                $_SESSION['name'] = $user['name']; // Optionally store other user details in session
                // Redirect to dashboard or another page
                header("Location: student_dash.php"); // Replace with your desired redirect page
                exit();
            } else {
                $password_error = "Invalid password.";
            }
        } else {
            $registrationNumber_error = "No user found with that registration number.";
        }

        $stmt->close();
    }


    // Store error messages in session variables
    $_SESSION['registrationNumber_error'] = $registrationNumber_error;
    $_SESSION['password_error'] = $password_error;
    header("Location: student_login_form.php");
    exit();
}

// Close connection
$conn->close();
?>
