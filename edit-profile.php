<?php
session_start();
include 'DBConn.php'; 

// 1. Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-up.php");
    exit();
}

// 2. Fetch the latest user info from the DB
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$full_name = $user['full_name'] ?? "";
$email = $user['email'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Chabis Hotness®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { 
            --chabis-red: #b22222; 
            --dark-bg: #000000;
        }
        body { font-family: 'Inter', sans-serif; background-color: #fff; }
        
        .navbar-brand { font-weight: 900; font-size: 24px; color: var(--chabis-red) !important; letter-spacing: 1px; }
        
        .form-label { font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: #333; }
        .form-control { border-radius: 0; border: 1px solid #000; padding: 12px; font-size: 14px; }
        .form-control:focus { border-color: var(--chabis-red); box-shadow: none; }
        
        .btn-save { 
            background: #000; 
            color: #fff; 
            border: none; 
            padding: 15px; 
            width: 100%; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            transition: 0.3s;
        }
        .btn-save:hover { background: var(--chabis-red); }
        
        footer { background-color: #000; padding: 60px 0 40px; color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg border-bottom sticky-top bg-white">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            CHABIS HOTNESS <i class="fa-solid fa-fire" style="font-size: 18px;"></i>
        </a>
        <a href="profile.php" class="text-dark small fw-bold text-uppercase text-decoration-none">
            <i class="fa-solid fa-xmark me-1"></i> Cancel
        </a>
    </div>
</nav>

<div class="container my-5 py-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h2 class="fw-black text-uppercase mb-2" align="center">Account Settings</h2>
            <p class="text-muted mb-5" align="center">Update your personal information and security credentials.</p>
            
            <form action="update_logic.php" method="POST">
                <div class="mb-4">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($full_name); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">New Password</label>
                    <small class="d-block text-muted mb-2" style="font-size: 10px;">Leave blank to keep your current password.</small>
                    <input type="password" class="form-control" name="password" placeholder="••••••••">
                </div>
                
                <div class="mb-5">
                    <label class="form-label">Shipping Address</label>
                    <textarea class="form-control" name="address" rows="3" placeholder="Enter your delivery address..."></textarea>
                </div>
                
                <button type="submit" class="btn-save shadow-sm">Save Changes</button>
            </form>
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