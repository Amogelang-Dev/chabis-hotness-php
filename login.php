<?php
session_start();
include 'DBConn.php'; // Connect to ClothingStore_db[cite: 2, 7]

if (isset($_POST['login_btn'])) {
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 1. Check if the user exists in tblUser
    $userQuery = "SELECT * FROM tblUser WHERE (username = '$identifier' OR email = '$identifier') AND password = '$password'";
    $userResult = $conn->query($userQuery);

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        
        // 2. Check Verification Status[cite: 7, 8]
        if ($user['status'] === 'Approved') {
            $_SESSION['user_id'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'customer';
            header("Location: index.php"); // Redirect to store home
            exit();
        } else {
            echo "<script>alert('Account pending approval.'); window.location='authenticate.php';</script>";
        }
    } else {
        // 3. If not found in users, check tblAdmin[cite: 7, 8]
        $adminQuery = "SELECT * FROM tblAdmin WHERE adminName = '$identifier' AND adminPassword = '$password'";
        $adminResult = $conn->query($adminQuery);

        if ($adminResult->num_rows > 0) {
            $admin = $adminResult->fetch_assoc();
            $_SESSION['admin_id'] = $admin['adminID'];
            $_SESSION['role'] = 'admin';
            header("Location: admin.php"); // Redirect to dashboard[cite: 2]
            exit();
        } else {
            echo "<script>alert('Invalid email or password.'); window.location='authenticate.php';</script>";
        }
    }
}
?>