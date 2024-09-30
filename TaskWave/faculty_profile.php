<?php
session_start();

// Redirect to login if session variable is not set
if (!isset($_SESSION['email'])) {
    header('Location: faculty_login_form.php');
    exit();
}

// Retrieve email from session
$email = $_SESSION['email'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "faculty_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch profile details from database based on email
$sql = "SELECT Name, Email, Department FROM faculty_signup WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Initialize variables
$faculty_name = "Unknown";
$faculty_email = "N/A";
$faculty_department = "N/A";

// Check if faculty exists
if ($stmt->num_rows > 0) {
    // Bind result variables
    $stmt->bind_result($faculty_name, $faculty_email, $faculty_department);
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
    <title>Faculty Profile</title>
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
            margin-top: 150px;
            padding-top: 80px;
        }
        .profile-card {
    width: 60%;
    margin: auto;
    background-color: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    margin-top: 50px;
    padding: 20px; /* Correct padding */
    transition: box-shadow 0.3s ease, transform 0.3s ease; /* Smooth transition */
}

.profile-card:hover {
    box-shadow: 0px 0px 15px rgba(0,0,0,0.3); /* Add shadow on hover */
    transform: scale(1.05); /* Slightly enlarge the card */
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
        <img src="dd.png" class="mr-2" width="50" height="50" alt="Icon"/>
        <div class="container">
            <a class="navbar-brand" href="#">
                <h3>Faculty Profile</h3>  
            </a>
        </div>
        <span class="navbar-text ms-auto" id="nam">
            Faculty Email: <strong id="stid"><?php echo htmlspecialchars($email); ?></strong>
        </span>
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
                        <td><b>Name:</b></td>
                        <td><?php echo htmlspecialchars($faculty_name); ?></td>
                    </tr>
                    <tr>
                        <td><b>Email:</b></td>
                        <td><?php echo htmlspecialchars($faculty_email); ?></td>
                    </tr>
                    <tr>
                        <td><b>Department:</b></td>
                        <td><?php echo htmlspecialchars($faculty_department); ?></td>
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
        window.location.href = 'faculty_dash.php';
    }
</script>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
