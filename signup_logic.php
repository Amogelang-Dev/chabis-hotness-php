<?php
// Turn off error display for the final redirect to work, 
// but keep logging enabled so we can check if it fails.
ini_set('display_errors', 0); 
error_reporting(E_ALL);

session_start();
include 'DBConn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Using null coalescing (??) to prevent the "Undefined key" warnings
    $fname     = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
    $lname     = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
    $email     = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';

    // If inputs are empty, send them back
    if (empty($email) || empty($password)) {
        header("Location: sign-up.php?error=emptyfields");
        exit();
    }

    $full_name = trim($fname . " " . $lname);

    // Check if email exists
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Email already in use!'); window.location='sign-up.php';</script>";
        exit();
    }

    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    // Insert into your table (id, full_name, email, password, role, created_at)
    $sql = "INSERT INTO users (full_name, email, password, role) 
            VALUES ('$full_name', '$email', '$hashed_pass', 'user')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['user_name'] = $full_name;
        
        // Redirecting to home
        header("Location: index.php");
        exit();
    } else {
        // If it fails, we need to see why
        ini_set('display_errors', 1);
        die("Database Error: " . mysqli_error($conn));
    }

} else {
    header("Location: sign-up.php");
    exit();
}
?>