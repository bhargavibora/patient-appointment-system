<?php
// Start the session before any output
session_start();

// Reset session variables
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

// Import database connection
include("connection.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fname = $_SESSION['personal']['fname'];
    $lname = $_SESSION['personal']['lname'];
    $name = $fname . " " . $lname;
    $address = $_SESSION['personal']['address'];
    $nic = $_SESSION['personal']['nic'];
    $dob = $_SESSION['personal']['dob'];
    $email = $_POST['newemail'];
    $tele = $_POST['tele'];
    $newpassword = $_POST['newpassword'];
    $cpassword = $_POST['cpassword'];

    // Validate passwords
    if ($newpassword == $cpassword) {
        // Check if the email already exists
        $sqlmain = "SELECT * FROM webuser WHERE email = ?";
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        } else {
            // Insert user data into the database
            $database->query("INSERT INTO patient(pemail, pname, ppassword, paddress, pnic, pdob, ptel) VALUES ('$email', '$name', '$newpassword', '$address', '$nic', '$dob', '$tele')");
            $database->query("INSERT INTO webuser VALUES ('$email', 'p')");

            // Set session variables
            $_SESSION["user"] = $email;
            $_SESSION["usertype"] = "p";
            $_SESSION["username"] = $fname;

            // Redirect to the patient dashboard
            header('Location: patient/index.php');
            exit(); // Exit after redirect
        }
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>';
    }
} else {
    $error = '<label for="promter" class="form-label"></label>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your head content here -->
</head>
<body>
    <!-- Your HTML content here -->
</body>
</html>
