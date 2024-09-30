<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

// Include the database connection
require 'db_config.php';

// Function to generate a secure password
function generateSecurePassword($length = 12) {
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $digits = '0123456789';
    $special = '!@#$%^&*()-_=+[]{}|;:,.<>?';

    // Ensure at least one character from each set
    $password = $upper[random_int(0, strlen($upper) - 1)] .
                $lower[random_int(0, strlen($lower) - 1)] .
                $digits[random_int(0, strlen($digits) - 1)] .
                $special[random_int(0, strlen($special) - 1)];

    // Fill the remaining length with random characters from all sets
    $all = $upper . $lower . $digits . $special;
    for ($i = 4; $i < $length; $i++) {
        $password .= $all[random_int(0, strlen($all) - 1)];
    }

    // Shuffle the password to ensure randomness
    return str_shuffle($password);
}

// Check if email is set in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['email'])) {
        echo "Invalid request: Email parameter missing.";
        exit;
    }

    $email = $_POST['email'];

    // Generate a new secure password
    $newPassword = generateSecurePassword(12); // You can adjust the length as needed
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    try {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'naraharisettihimarupasri@gmail.com'; // SMTP username
        $mail->Password = 'fhik ghbm nfoh jjce'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('naraharisettihimarupasri@gmail.com', 'Your Name');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your New Password';
        $mail->Body    = "Your new password is: <b>$newPassword</b>";

        // Send the email
        $mail->send();

        // Update the password in the database
        $query = "UPDATE student_signup SET password = ? WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$hashedPassword, $email]);

        echo "A new password has been sent to your email.";

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    } catch (PDOException $e) {
        echo "Database query failed: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
