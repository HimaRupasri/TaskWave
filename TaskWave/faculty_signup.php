<?php
// Start or resume session
session_start();

// Include your database connection script
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "faculty_db"; // Replace with your database name

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
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $phone = isset($_POST['phNo']) ? $_POST['phNo'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Perform basic validation
    if (empty($name) || empty($email) || empty($department) || empty($phone) || empty($password)) {
        echo "All fields are required.";
        exit();
    }

    // Check if email or phone already exists
    $check_query = "SELECT * FROM faculty_signup WHERE Email = ? OR Phone_Number = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email or Phone already exists
        while ($row = $result->fetch_assoc()) {
            if ($row['Email'] === $email) {
                echo "<h3><center>Email already exists. Please choose a different one.</center></h3>";
                exit();
            } elseif ($row['Phone_Number'] === $phone) {
                echo "<h3><center>Phone number already exists. Please choose a different one.</center></h3>";
                exit();
            }
        }
    }

    // Email and Phone do not exist, proceed with insertion
    // Prepare INSERT statement
    $insert_query = "INSERT INTO faculty_signup (Name, Email, Department, Phone_Number, Password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    // Bind parameters and execute the statement
    $stmt->bind_param("sssss", $name, $email, $department, $phone, $hashed_password);

    // Execute the INSERT statement
    if ($stmt->execute()) {
        echo "Registration successful!";
        $_SESSION['email'] = $email;
        // Redirect to some confirmation page or another page
        header("Location: faculty_dash.php"); // Replace with your desired redirect page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
