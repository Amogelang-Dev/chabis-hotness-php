<?php
session_start();
include 'DBConn.php'; // Ensure your database connection file is named correctly

if (isset($_POST['login_btn'])) {
    // 1. Get and clean the input
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']);
    $password = $_POST['password'];

    // 2. Search for the user by email OR username
// Only check the email column
$query = "SELECT * FROM users WHERE email='$identifier' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        // 3. Verify the hashed password
        if (password_verify($password, $user_data['password'])) {
            
            // 4. Set Session variables to "log them in"
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['user_name'] = $user_data['full_name'];
            $_SESSION['user_email'] = $user_data['email'];

            // 5. Redirect to the profile page we just made
            header("Location: index.php");
            exit();
            
        } else {
            // Password didn't match
            echo "<script>alert('Invalid password. Please try again.'); window.location='authenticate.php';</script>";
        }
    } else {
        // User not found
        echo "<script>alert('No account found with that email/username.'); window.location='authenticate.php';</script>";
    }
} else {
    // If someone tries to access this file directly without clicking the button
    header("Location: authenticate.php");
    exit();
}
?>