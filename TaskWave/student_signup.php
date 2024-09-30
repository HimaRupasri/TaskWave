<?php
// Start or resume session
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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and retrieve form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $registrationNumber = isset($_POST['registrationNumber']) ? $_POST['registrationNumber'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phNo']) ? $_POST['phNo'] : '';
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';
    $sem = isset($_POST['sem']) ? $_POST['sem'] : '';
    $section = isset($_POST['section']) ? $_POST['section'] : '';
    $cgpa = isset($_POST['CGPA']) ? $_POST['CGPA'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Perform basic validation
    if (empty($name) || empty($registrationNumber) || empty($email) || empty($phone) || empty($department) || empty($year) || empty($sem) || empty($section) || empty($cgpa) || empty($password)) {
        echo "All fields are required.";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if registration number or phone number already exists
    $check_query = "SELECT * FROM student_signup WHERE registrationNumber = ? OR phNo = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $registrationNumber, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['registrationNumber'] === $registrationNumber) {
                echo "<h3><center>Registration Number already exists. Please choose a different one.</center></h3>";
                exit();
            } elseif ($row['phNo'] === $phone) {
                echo "<h3><center>Phone number already exists. Please choose a different one.</center></h3>";
                exit();
            }
        }
    } else {
        // Registration number and phone number do not exist, proceed with insertion
        // Prepare INSERT statement
        $insert_query = "INSERT INTO student_signup (name, registrationNumber, email, phNo, department, year, sem, section, CGPA, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        // Bind parameters and execute the statement
        $stmt->bind_param("ssssssssss", $name, $registrationNumber, $email, $phone, $department, $year, $sem, $section, $cgpa, $hashed_password);

        // Execute the INSERT statement
        if ($stmt->execute()) {
            session_unset();
            session_destroy();
            session_start();
            // Set session variables with signup details
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['registrationNumber'] = $registrationNumber;

            // Redirect to dashboard
            header("Location: student_dash.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>
