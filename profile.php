<?php
session_start();
include 'DBConn.php'; // 1. Connect to your database

// 2. Security Check: If not logged in, send them to sign-up
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-up.php");
    exit();
}

// 3. Fetch fresh data for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Fallback in case user data isn't found
$user_name = $user['full_name'] ?? "Guest"; 
$user_email = $user['email'] ?? "No email provided";
$user_initial = strtoupper(substr($user_name, 0, 1)); // Get first letter for avatar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Chabis Hotness®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root { 
            --chabis-red: #b22222; 
            --dark-bg: #000000;
        }
        body { font-family: 'Inter', sans-serif; background-color: #fff; }
        
        /* Navigation */
        .navbar-brand { font-weight: 900; font-size: 24px; color: var(--chabis-red) !important; letter-spacing: 1px; }
        
        /* Profile Header - Themed to match your minimalist black/white preference */
        .profile-header { background: #000; padding: 60px 0; color: #fff; border-bottom: 5px solid var(--chabis-red); }
        .profile-avatar { 
            width: 100px; 
            height: 100px; 
            background: var(--chabis-red); 
            color: #fff; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 40px; 
            font-weight: 900; 
            border-radius: 50%; 
            margin: 0 auto 20px; 
            border: 3px solid #fff;
        }

        .btn-edit { 
            border: 2px solid var(--chabis-red); 
            color: var(--chabis-red); 
            font-weight: 800; 
            text-transform: uppercase; 
            padding: 10px 30px; 
            transition: 0.3s; 
            text-decoration: none; 
            background: #fff;
        }
        .btn-edit:hover { background: var(--chabis-red); color: #fff; }
        
        .order-card { border: 1px solid #eee; padding: 20px; margin-bottom: 15px; transition: 0.3s; }
        .order-card:hover { border-color: var(--chabis-red); }
        .accent-text { color: var(--chabis-red); font-weight: 700; }
        
        footer { background-color: #000; padding: 60px 0 40px; color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg border-bottom sticky-top bg-white">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            CHABIS HOTNESS <i class="fa-solid fa-fire" style="font-size: 18px;"></i>
        </a>
        <div class="d-flex gap-3">
            <a href="shop.php" class="text-dark small fw-bold text-uppercase text-decoration-none">Shop</a>
            <a href="logout.php" class="text-danger small fw-bold text-uppercase text-decoration-none">Logout</a>
        </div>
    </div>
</nav>

<div class="profile-header text-center">
    <div class="container">
        <div class="profile-avatar"><?php echo $user_initial; ?></div>
        <h2 class="fw-black text-uppercase mb-1"><?php echo htmlspecialchars($user_name); ?></h2>
        <p class="mb-4" style="color: #ccc;"><?php echo htmlspecialchars($user_email); ?></p>
        <a href="edit-profile.php" class="btn-edit">Edit Profile</a>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h5 class="fw-bold text-uppercase mb-4">Your Recent Activity</h5>
            
            <!-- This is currently static, but ready for your Order table later -->
            <div class="order-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-0 fw-bold">No active orders yet.</p>
                    <small class="text-muted">Start your flavor journey today.</small>
                </div>
                <div class="text-end">
                    <a href="shop.php" class="btn btn-sm btn-dark text-uppercase fw-bold" style="font-size: 10px;">Browse Sauces</a>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container text-center">
        <h5 class="mb-2 uppercase" style="letter-spacing: 2px;">CHABIS HOTNESS®</h5>
        <div class="pt-3" style="border-top: 1px solid #333; max-width: 340px; margin: 0 auto;">
            <p class="mb-1" style="font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: #aaa;">Digital Architecture & Direction</p>
            <p class="mb-0">
                <a href="#" class="text-white text-decoration-none fw-bold" style="letter-spacing: 4px; font-size: 12px;">
                    SyreTech SOLUTIONS
                </a>
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>