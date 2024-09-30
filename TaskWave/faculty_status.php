<?php
session_start();

// Include PHPMailer
require 'vendor/autoload.php'; // Adjust path if needed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$student_dbname = "student_db"; // Student database name
$faculty_dbname = "faculty_db"; // Faculty database name

// Create connection for the student database
$student_conn = new mysqli($servername, $username, $password, $student_dbname);

// Create connection for the faculty database
$faculty_conn = new mysqli($servername, $username, $password, $faculty_dbname);

// Check connections
if ($student_conn->connect_error) {
    die("Student database connection failed: " . $student_conn->connect_error);
}
if ($faculty_conn->connect_error) {
    die("Faculty database connection failed: " . $faculty_conn->connect_error);
}

// Initialize message
$message = "";

// Handle subject selection
$selected_subject = isset($_POST['subject']) ? $_POST['subject'] : '';

// Hardcoded subjects
$subjects = ['ADS', 'FSD', 'DWDM', 'ES', 'CN'];

// Fetch submissions based on selected subject
$submissions = [];
if ($selected_subject) {
    $sql = "SELECT assignment_id, user_id, subject_name, file_path
            FROM submissions
            WHERE subject_name = ?";
    $stmt = $student_conn->prepare($sql);
    $stmt->bind_param('s', $selected_subject);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $submissions[] = $row;
    }
    $stmt->close();
}

// Initialize verified submissions tracking
if (!isset($_SESSION['verified_submissions'])) {
    $_SESSION['verified_submissions'] = [];
}

// Handle marking as verified and sending email
if (isset($_POST['mark_verified'])) {
    $submission_id = $_POST['submission_id'];

    // Mark as verified in session
    $_SESSION['verified_submissions'][$submission_id] = true;
    $message = "Submission marked as verified!";

    // Fetch student email
    $sql = "SELECT s.email
            FROM submissions sub
            JOIN student_signup s ON sub.user_id = s.registrationNumber
            WHERE sub.assignment_id = ?";
    $stmt = $student_conn->prepare($sql);
    $stmt->bind_param('i', $submission_id);
    $stmt->execute();
    $stmt->bind_result($student_email);
    $stmt->fetch();
    $stmt->close();

    // Fetch faculty email from session
    $faculty_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

    // Send email using PHPMailer
    if ($student_email && $faculty_email) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'naraharisettihimarupasri@gmail.com';
            $mail->Password = 'fhik ghbm nfoh jjce';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipients
            $mail->setFrom($faculty_email, 'Faculty');
            $mail->addAddress($student_email);
            $mail->addReplyTo($faculty_email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Submission Verified';
            $mail->Body    = 'Dear Student,<br><br>Your submission has been verified by the faculty. Congratulations!<br><br>Best regards,<br>Faculty';

            $mail->send();
            $message .= " Email notification sent to the student.";
        } catch (Exception $e) {
            $message .= " Failed to send email: " . $mail->ErrorInfo;
        }
    } else {
        if (!$student_email) $message .= " No email found for the student.";
        if (!$faculty_email) $message .= " No faculty email found in session.";
    }
}

// Sort submissions with verified ones at the bottom
usort($submissions, function ($a, $b) {
    $a_verified = isset($_SESSION['verified_submissions'][$a['assignment_id']]);
    $b_verified = isset($_SESSION['verified_submissions'][$b['assignment_id']]);
    return $a_verified === $b_verified ? 0 : ($a_verified ? 1 : -1);
});

// Close connections
$student_conn->close();
$faculty_conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Status</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url("./faculty_passign.jpg") no-repeat center center fixed;
            background-size: cover;
            margin-top: 70px;
            color: #1a202c;
            text-align: left;
            background-color: #e2e8f0;
        }

        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            z-index: 1000;
        }

        .main-body {
            padding: 90px 15px 15px;
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
    animation: blink-animation 1.5s infinite; /* Apply blinking animation */
}
.home-button:hover {
    background-color: #0056b3;
}
@keyframes blink-animation {
    0% { opacity: 0.5; }
    50% { opacity: 1; }
    100% { opacity: 0.5; }
}

        .verify-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .verify-button:hover {
            background-color: #0056b3;
        }

        .verified-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: not-allowed;
        }
        header {
    background-color: #007bff;
    padding: 20px 10;
    display: flex;
    align-items: center;
    justify-content: center; /* Center content horizontally */
    position: relative;
    padding-bottom:30px;
    padding-top:30px;
}

header img {
    position: absolute;
    left: 20px; /* Distance from the left edge */
    width: 50px;
    height: 40px;
}

header h3 {
    color: #ffffff;
    margin: 0;
    font-weight: bold;
}
th {
  background-color: #7c7a7a;
  color: black;
}
td {
  background-color: #CCCCCC;
  color: black;
}


    </style>
</head>
<body>
    <header class="fixed-header">
    <img src="dd.png" class="mr-2" width="50" height="50" alt="Icon"/>
        <h3 style="text-align:center;">Submission Status</h3>
    </header>
    <div class="container main-body">
        <?php if (!empty($message)) : ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="mb-4">
            <div class="form-group">
                <label for="subject">Select Subject:</label>
                <select id="subject" name="subject" class="form-control">
                    <option value="">-- Select Subject --</option>
                    <?php foreach ($subjects as $subject) : ?>
                        <option value="<?php echo htmlspecialchars($subject); ?>" <?php echo $subject === $selected_subject ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subject); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">View Submissions</button>
        </form>

        <?php if (!empty($submissions)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Subject</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($submission['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($submission['subject_name']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($submission['file_path']); ?>" target="_blank">View File</a></td>
                            <td>
                                <?php echo isset($_SESSION['verified_submissions'][$submission['assignment_id']]) ? 'Verified' : 'Pending'; ?>
                            </td>
                            <td>
                                <?php if (isset($_SESSION['verified_submissions'][$submission['assignment_id']])) : ?>
                                    <button class="btn verified-button" disabled>Verified</button>
                                <?php else : ?>
                                    <form action="" method="POST" style="display:inline;">
                                        <input type="hidden" name="submission_id" value="<?php echo htmlspecialchars($submission['assignment_id']); ?>">
                                        <button type="submit" name="mark_verified" class="btn verify-button">Mark as Verified</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No submissions found for the selected subject.</p>
        <?php endif; ?>

        <button class="home-button" onclick="window.location.href='faculty_dash.php'">Go to Dashboard</button>
    </div>
</body>
</html>
