<?php
session_start();

// Redirect to login if session variable is not set
if (!isset($_SESSION['regNumber'])) {
    header('Location: student_login_form.php');
    exit();
}

// Retrieve registration number from session
$reg_no = $_SESSION['regNumber'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch profile details from database based on registration number
$sql = "SELECT registrationNumber, name, email, department, year, CGPA FROM student_signup WHERE registrationNumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $reg_no);
$stmt->execute();
$stmt->store_result();

// Initialize variables
$student_number = "N/A";
$student_name = "Unknown";
$student_email = "N/A";
$student_department = "N/A";
$student_year = "N/A";
$student_cgpa = "N/A";

// Check if student exists
if ($stmt->num_rows > 0) {
    // Bind result variables
    $stmt->bind_result($student_number, $student_name, $student_email, $student_department, $student_year, $student_cgpa);
    $stmt->fetch();
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="icon" type="image/png" href="dd.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            background: url("./faculty_passign.jpg") no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        header {
            background-color: #007bff;
            padding: 10px 0; /* Adjust padding to make sure there's space for the icon and text */
        }
        header .navbar-brand {
            color: #ffffff;
            font-weight: bold;
            flex: 1; /* This allows the text to be centered */
            text-align: center; /* Center the text */
            display: flex;
            align-items: center; /* Center icon and text vertically */
            justify-content: center;
        }
        header .navbar-text {
            color: #ffffff;
        }
        .profile-section {
            margin-top: -10px;
            padding-top: 30px;
            padding-bottom:20px;
        }
        .profile-card {
    width: 60%;
    margin: auto;
    background-color: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    margin-top: 20px;
    padding: 20px; /* Correct padding */
    transition: box-shadow 0.3s ease, transform 0.3s ease; /* Smooth transition */
}

.profile-card:hover {
    box-shadow: 0px 0px 15px rgba(0,0,0,0.3); /* Add shadow on hover */
    transform: scale(1.02); /* Slightly enlarge the card */
}

.profile-card .card-body {
    text-align: center;
}

.profile-info {
    text-align: left;
    font-size: 16px;
}

.profile-info b {
    font-weight: bold;
    color: #007bff;
}

        .table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }
        .table td, .table th {
            padding: 8px;
            border: none;
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
.profile-image {
    max-width: 150px; /* Adjust image size */
    border-radius: 50%; /* Make the image circular */
}

/* Blinking animation */
@keyframes blink-animation {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
        .profile-image {
            max-width: 150px; /* Adjust image size */
            border-radius: 50%; /* Make the image circular */
        }
    </style>
</head>
<body>
<header class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Student Profile</a>
        <span class="navbar-text ml-auto">
            Student ID: <strong><?php echo htmlspecialchars($reg_no); ?></strong>
        </span>
    </div>
</header>

<main class="container mt-5 profile-section">
    <div class="profile-card">
        <div class="card-body">
            <h4 class="profile-card-title">Profile Details</h4>
            <br>
            <img src="profile.png" alt="Profile Picture" class="profile-image"><br><br>
            <div class="profile-info">
                <table class="table">
                    <tr>
                        <td><b>Registration Number:</b></td>
                        <td><?php echo htmlspecialchars($student_number); ?></td>
                    </tr>
                    <tr>
                        <td><b>Name:</b></td>
                        <td><?php echo htmlspecialchars($student_name); ?></td>
                    </tr>
                    <tr>
                        <td><b>Email:</b></td>
                        <td><?php echo htmlspecialchars($student_email); ?></td>
                    </tr>
                    <tr>
                        <td><b>Department:</b></td>
                        <td><?php echo htmlspecialchars($student_department); ?></td>
                    </tr>
                    <tr>
                        <td><b>Year:</b></td>
                        <td><?php echo htmlspecialchars($student_year); ?></td>
                    </tr>
                    <tr>
                        <td><b>CGPA:</b></td>
                        <td><?php echo htmlspecialchars($student_cgpa); ?></td>
                    </tr>
                    <!-- Add more profile details here -->
                </table>
            </div>
        </div>
    </div>
</main>

<button class="btn btn-primary home-button" onclick="redirectToDashboard()">Back to Dashboard</button>

<script>
    function redirectToDashboard() {
        window.location.href = 'student_dash.php';
    }
</script>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
