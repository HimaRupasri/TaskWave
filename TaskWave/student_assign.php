<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$faculty_dbname = "faculty_db"; // Replace with your faculty database name
$student_dbname = "student_db"; // Replace with your student database name

// Create connections for both databases
$faculty_conn = new mysqli($servername, $username, $password, $faculty_dbname);
$student_conn = new mysqli($servername, $username, $password, $student_dbname);

// Check connections
if ($faculty_conn->connect_error) {
    die("Faculty database connection failed: " . $faculty_conn->connect_error);
}
if ($student_conn->connect_error) {
    die("Student database connection failed: " . $student_conn->connect_error);
}

// Initialize message
$message = "";
$show_message = true; // Flag to control whether to display the message

// Check if the user is logged in
if (!isset($_SESSION['regNumber'])) {
    die('User not logged in');
}

$user_id = $_SESSION['regNumber'];

// Fetch student section from the student_signup table
$sql = "SELECT section FROM student_signup WHERE registrationNumber = ?";
$stmt = $student_conn->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$stmt->bind_result($student_section);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assignment_id = $_POST['assignment_id'];
    $submission_file = $_FILES['submission_file'];

    if ($submission_file['error'] == UPLOAD_ERR_OK) {
        $file_path = 'uploads/' . basename($submission_file['name']);
        if (move_uploaded_file($submission_file['tmp_name'], $file_path)) {
            // Fetch subject name from the assignments table
            $sql = "SELECT subject FROM assignments WHERE id = ?";
            $stmt = $faculty_conn->prepare($sql);
            $stmt->bind_param('i', $assignment_id);
            $stmt->execute();
            $stmt->bind_result($subject);
            $stmt->fetch();
            $stmt->close();

            // Insert submission with the subject name
            $sql = "INSERT INTO submissions (user_id, assignment_id, file_path, subject_name) VALUES (?, ?, ?, ?)";
            if ($stmt = $student_conn->prepare($sql)) {
                $stmt->bind_param('siss', $user_id, $assignment_id, $file_path, $subject);
                if ($stmt->execute()) {
                    $show_message = false;
                } else {
                    $message = "Error submitting file: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Error preparing statement: " . $student_conn->error;
            }
        } else {
            $message = "Error uploading file.";
        }
    } else {
        $message = "No file uploaded or file upload error.";
    }
}

// Fetch assignments for the logged-in user from faculty_db where section matches
$sql = "SELECT * FROM assignments WHERE section = ?";
$stmt = $faculty_conn->prepare($sql);
$stmt->bind_param('s', $student_section);
$stmt->execute();
$assignments_result = $stmt->get_result();
$stmt->close();

// Fetch submissions for the logged-in user from student_db
$sql = "SELECT * FROM submissions WHERE user_id = ?";
$submissions_stmt = $student_conn->prepare($sql);
$submissions_stmt->bind_param('s', $user_id);
$submissions_stmt->execute();
$submissions_result = $submissions_stmt->get_result();

$submitted_files = [];
while ($submission = $submissions_result->fetch_assoc()) {
    $submitted_files[$submission['assignment_id']] = [
        'file_path' => $submission['file_path'],
        'subject' => $submission['subject_name']
    ];
}

$submissions_stmt->close();

// Get the current date
$current_date = date('Y-m-d');

// Prepare arrays to sort assignments
$completed_assignments = [];
$not_completed_assignments = [];

// Sort assignments based on status
while ($assignment = $assignments_result->fetch_assoc()) {
    $isSubmitted = isset($submitted_files[$assignment['id']]);
    $isOverdue = $current_date > $assignment['due_date'];

    if ($isSubmitted) {
        $completed_assignments[] = $assignment;
    } elseif ($isOverdue) {
        $assignment['status'] = 'You did not complete';
        $not_completed_assignments[] = $assignment;
    } else {
        $not_completed_assignments[] = $assignment;
    }
}

// Merge arrays to display assignments with completed ones at the bottom
$sorted_assignments = array_merge($not_completed_assignments, $completed_assignments);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
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

        header {
            background-color: #007bff;
            padding: 10px 0;
        }

        header .navbar-brand {
            color: #ffffff;
            font-weight: bold;
            flex: 1; /* This allows the text to be centered */
            text-align: center; /* Center the text */
            display: flex;
            align-items: center; /* Center icon and text vertically */
            justify-content: center; /* Aligns items vertically in the center */
        }

        header .navbar-text {
            color: #ffffff;
        }

        header .navbar-brand img {
            margin-right: 30px; /* Space between the image and the text */
        }

        header .navbar-nav {
            flex: 1; /* This allows the text to be centered */
            justify-content: center; /* Center the text */
        }

        .main-body {
            padding: 150px 15px 15px;
            margin-left: 350px;
            margin-top: -80px;
        }

        .assignment-container {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            position: relative;
        }

        .assignment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .assignment-header h5 {
            margin: 0;
        }

        .upload-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-button:hover {
            background-color: #0056b3;
        }

        .assignment-info {
            margin-bottom: 10px;
        }

        .assignment-submit {
            text-align: center;
            margin-top: 20px;
        }

        .submit-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 7px;
        }

        .submit-button:hover {
            background-color: #218838;
        }

        .tick-button {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: yellow;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .tick-button.completed {
            background-color: #28a745;
        }

        .tick-button.not-completed {
            background-color: #dc3545;
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
            transition: background-color 0.3s ease-in-out;
        }

        .home-button:hover {
            background-color: #0056b3; /* Slightly darker blue on hover */
        }

        /* Media query for small screens */
        @media (max-width: 768px) {
            .card {
                flex: 1 1 calc(50% - 20px); /* Two cards per row on small screens */
            }

            .main-content {
                gap: 20px; /* Reduce space between cards on smaller screens */
            }
        }

        /* Media query for extra small screens */
        @media (max-width: 480px) {
            .card {
                flex: 1 1 100%; /* Full-width cards on very small screens */
            }
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
    </style>
</head>
<body>
<header class="navbar navbar-expand-md navbar-dark fixed-top">
        <div class="container">
        <img src="dd.png" alt="Icon" style="height: 50px; margin-right: 10px; margin-left: -150px;">
            <a class="navbar-brand" href="#">
                <h3>Assignments</h3>
            </a>
        </div>
    </header>
    <div class="container main-body">
        <?php if ($show_message && !empty($message)) : ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($sorted_assignments as $assignment) : ?>
                    <div class="assignment-container mb-3 p-3 border rounded">
                        <div class="assignment-header d-flex justify-content-between align-items-center">
                            <h5><?php echo htmlspecialchars($assignment['subject']); ?></h5>
                        </div>
                        <div class="assignment-info mt-2">
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($assignment['description']); ?></p>
                            <p><strong>Due Date:</strong> <?php echo htmlspecialchars($assignment['due_date']); ?></p>
                            <?php if (isset($assignment['status'])) : ?>
                                <p><strong>Status:</strong> <?php echo htmlspecialchars($assignment['status']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php
                        $isSubmitted = isset($submitted_files[$assignment['id']]);
                        $isOverdue = $current_date > $assignment['due_date'];
                        ?>
                        <div class="assignment-submit mt-3 text-center">
                            <?php if ($isSubmitted) : ?>
                                <p>Assignment already submitted</p>
                                <button class="tick-button completed">Completed</button>
                            <?php elseif ($isOverdue) : ?>
                                <p>Assignment is overdue and not submitted</p>
                                <button class="tick-button not-completed">Not Completed</button>
                            <?php else : ?>
                                <form action="student_assign.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="assignment_id" value="<?php echo htmlspecialchars($assignment['id']); ?>">
                                    <input type="file" name="submission_file" required>
                                    <button type="submit" class="submit-button mt-2">Submit</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <button class="btn btn-primary home-button" onclick="redirectToDashboard()">Back to Dashboard</button>

<script>
    function redirectToDashboard() {
        window.location.href = 'student_dash.php';
    }
</script>
</body>
</html>
<?php
$faculty_conn->close();
$student_conn->close();
?>
