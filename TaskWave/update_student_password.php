<?php

// Include the database connection
require 'db_config.php';

// Check if email and newPassword are set in the POST request
if (!isset($_POST['email']) || !isset($_POST['newPassword'])) {
    echo "Invalid request: Email or new password parameter missing.";
    exit;
}

$email = $_POST['email'];
$newPassword = $_POST['newPassword'];

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

try {
    // Update the password in the student_signup table
    $query = "UPDATE student_signup SET password = ?, reset_code = NULL WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$hashedPassword, $email]);

    echo "Password has been successfully updated.";

} catch (PDOException $e) {
    echo "Database query failed: " . $e->getMessage();
}
?>
