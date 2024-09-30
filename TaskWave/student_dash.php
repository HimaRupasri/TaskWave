<?php
session_start();

if (!isset($_SESSION['regNumber'])) {
    header('Location: student_login_form.php');
    exit();
}

$reg_no = $_SESSION['regNumber'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="icon" type="image/png" href="dd.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url("./faculty_passign.jpg") no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            overflow-x: hidden; /* Prevents horizontal scroll on small screens */
            padding: 0;
            margin: 0;
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

        .main-content {
            margin-top: 100px; /* Adjust top margin to center cards */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap; /* Ensures that the cards wrap to new lines if there's no space */
            gap: 30px; /* Space between cards */
            padding: 20px; /* Padding around the card container */
            min-height: calc(100vh - 160px); /* Ensure the content fills at least the available vertical space */
        }

        .card {
    cursor: pointer;
    flex: 1 1 calc(50% - 20px); /* Adjust for responsive layout */
    max-width: 300px; /* Limit maximum card width */
    background-color: #ffffff; /* Card background color */
    border: 1px solid #dddddd; /* Border color */
    border-radius: 20px; /* Rounded corners */
    width: 250px;
    height: 250px;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, outline 0.3s ease-in-out;
    margin: 10px auto; /* Center cards horizontally */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); /* Shadow effect */
    outline: 2px solid transparent; /* Transparent outline for hover effect */
    position: relative; /* Position for z-index usage */
    z-index: 1; /* Ensure card is on top */
    padding: 10px; /* Padding inside card */
}

.card:hover {
    transform: scale(1.05); /* Slightly enlarge card */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
    outline: 2px solid #007bff; /* Blue outline on hover */
    background-color: #479cf7; /* Change background color on hover */
}

.card .card-body {
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.card .card-title {
    color: #007bff; /* Default text color */
    font-weight: bold;
    margin-top: 25px;
    transition: color 0.3s ease-in-out;
    font-size: 1.2em; /* Larger font size for readability */
}

.card:hover .card-title {
    color: #ffffff; /* Change text color on hover */
}

.card .card-body i {
    color: #007bff; /* Default icon color */
    transition: color 0.3s ease-in-out; /* Smooth color transition */
}

.card:hover .card-body i {
    color: #ffffff; /* Change icon color on hover */
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
                <h3>Student Dashboard</h3>
            </a>
            <span class="navbar-text ms-auto" id="nam">
                Student ID: <strong id="stid"><?php echo htmlspecialchars($reg_no); ?></strong>
            </span>
        </div>
    </header>
    <main class="container main-content">
        <!-- Responsive card layout -->
        <div class="row w-100">
            <!-- First Row -->
            <div class="col-md-6 d-flex flex-column align-items-center mb-4">
                <div class="card w-75 mb-4" onclick="window.location.href='student_profile.php';">
                    <div class="card-body text-center">
                        <h5 class="card-title d-flex align-items-center justify-content-center">
                            <i class="bi bi-person" style="font-size: 2rem;"></i>
                        </h5>
                        <h5 class="card-title">Profile</h5>
                    </div>
                </div>
                <div class="card w-75 mb-4" onclick="window.location.href='student_submissions.php';">
                    <div class="card-body text-center">
                        <h5 class="card-title d-flex align-items-center justify-content-center">
                            <i class="bi bi-send" style="font-size: 2rem;"></i>
                        </h5>
                        <h5 class="card-title">Submission</h5>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="col-md-6 d-flex flex-column align-items-center mb-4">
                <div class="card w-75 mb-4" onclick="window.location.href='student_assign.php';">
                    <div class="card-body text-center">
                        <h5 class="card-title d-flex align-items-center justify-content-center">
                            <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                        </h5>
                        <h5 class="card-title">Assignments</h5>
                    </div>
                </div>
                <div class="card w-75 mb-4" onclick="window.location.href='student_timetable.html';">
                    <div class="card-body text-center">
                        <h5 class="card-title d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar" style="font-size: 2rem;"></i>
                        </h5>
                        <h5 class="card-title">Timetable</h5>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <button class="btn btn-primary home-button" onclick="redirectToDashboard()">Back to Login</button>

<script>
    function redirectToDashboard() {
        window.location.href = 'student_login_form.php';
    }
</script>

</body>
</html>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
