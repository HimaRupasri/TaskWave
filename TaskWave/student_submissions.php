<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$student_dbname = "student_db"; // Replace with your student database name

// Create connection
$student_conn = new mysqli($servername, $username, $password, $student_dbname);

// Check connection
if ($student_conn->connect_error) {
    die("Student database connection failed: " . $student_conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['regNumber'])) {
    die('User not logged in');
}

$user_id = $_SESSION['regNumber'];

// Fetch submissions for the logged-in user
$sql = "SELECT * FROM submissions WHERE user_id = ?";
$stmt = $student_conn->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$submissions = [];
while ($row = $result->fetch_assoc()) {
    $submissions[] = $row;
}

$stmt->close();
$student_conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Submissions</title>
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
            padding-right: 350px;
            margin-left: 350px;
            margin-top: 150px;
        }

        .submission-container {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .submission-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .submission-header h5 {
            margin-left: 310px;
        }

        .file-link {
            color: #007bff;
        }

        .file-link:hover {
            text-decoration: underline;
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
                <h3>Student Submissions</h3>
            </a>
        </div>
    </header>
    <div class="container main-body">
        <?php if (count($submissions) > 0) : ?>
            <?php foreach ($submissions as $submission) : ?>
                <?php 
                    $file_path = htmlspecialchars($submission['file_path']);
                    $file_mtime = file_exists($file_path) ? date("Y-m-d H:i:s", filemtime($file_path)) : "Unknown";
                ?>
                <div class="submission-container">
                    <div class="submission-header">
                        <h5><?php echo htmlspecialchars($submission['subject_name']); ?></h5>
                        
                        <small>Submitted on: <?php echo $file_mtime; ?></small>
                    </div>
                    <div>
                        <p><strong>Submitted File:</strong> <a href="<?php echo $file_path; ?>" class="file-link" download><?php echo basename($file_path); ?></a></p>
                    </div>
                    <div style="margin-left:300px;"><span class="completed-label">Completed</span></div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="alert alert-danger"><b>No submissions found.</b></div>
        <?php endif; ?>
    </div>

    <button class="home-button" onclick="redirectToDashboard()">Back to Dashboard</button>

    <script>
        function redirectToDashboard() {
            window.location.href = 'student_dash.php';
        }
    </script>
</body>
</html>
