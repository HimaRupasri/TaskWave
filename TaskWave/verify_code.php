<?php
// Include the database connection
require 'db_config.php';

// Check if email and code are set in the GET request
if (!isset($_GET['email']) || !isset($_GET['code'])) {
    echo "Invalid request: Email or code parameter missing.";
    exit;
}

$email = $_GET['email'];
$code = $_GET['code'];

// Verify the reset code
$query = "SELECT reset_code FROM student_signup WHERE email = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$email]);
$storedCode = $stmt->fetchColumn();

if ($code === $storedCode) {
    // Code is valid, redirect to the reset password page
    header('Location: reset_student_password.php?email=' . urlencode($email));
    exit;
} else {
    echo "Invalid reset code.";
}
?>
