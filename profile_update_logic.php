<?php
session_start();
include 'DBConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 1. Update basic info
    $sql = "UPDATE users SET full_name='$full_name', email='$email' WHERE id='$user_id'";
    mysqli_query($conn, $sql);
    
    // 2. Update session name in case it changed
    $_SESSION['user_name'] = $full_name;

    // 3. Update password only if a new one was typed
    if (!empty($password)) {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $pass_sql = "UPDATE users SET password='$hashed_pass' WHERE id='$user_id'";
        mysqli_query($conn, $pass_sql);
    }

    echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
}
?>