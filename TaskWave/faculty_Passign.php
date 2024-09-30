<?php
// Include database configuration file
include 'db_config.php';

$response = array('success' => false, 'message' => 'Unknown error');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $section = $_POST['section']; // Retrieve section value

    // Handle file upload
    $filePath = NULL; // Default to NULL if no file is uploaded
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['assignment_file']['tmp_name'];
        $fileName = $_FILES['assignment_file']['name'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Define allowed file extensions and directory to save
        $allowedExts = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'); // Adjust as needed
        $uploadFileDir = './uploads/';
        $filePath = $uploadFileDir . $fileName;

        if (in_array($fileExtension, $allowedExts)) {
            if (move_uploaded_file($fileTmpPath, $filePath)) {
                // File uploaded successfully
            } else {
                $filePath = NULL; // Set to NULL on file move error
            }
        } else {
            $filePath = NULL; // Set to NULL on invalid file extension
        }
    } else {
        // No file uploaded
        $filePath = NULL;
    }

    // Insert data into the database
    $sql = "INSERT INTO assignments (subject, description, due_date, file_path, section) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssss', $subject, $description, $due_date, $filePath, $section); // Include section in bind_param
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Your task is submitted.';
        } else {
            $response['message'] = "Error executing statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Error preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    $response['message'] = 'Invalid request method.';
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
