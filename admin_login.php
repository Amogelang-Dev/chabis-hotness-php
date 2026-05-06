<?php
session_start();
include 'DBConn.php'; 

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the input from the form
    $email = mysqli_real_escape_string($conn, $_POST['username']); 
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    /**
     * UPDATES MADE BASED ON YOUR DATABASE SCHEMA:
     * 1. Table name is 'users' (not 'tblUser').
     * 2. Columns used are 'email', 'password', and 'role'.
     */
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND role = 'admin'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);
        
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_user'] = $user_data['email'];
        $_SESSION['admin_name'] = $user_data['full_name']; // Storing full_name for the dashboard
        
        header("Location: admin_dashboard.php"); 
        exit();
    } else {
        $error_message = "ACCESS DENIED: UNAUTHORIZED CREDENTIALS";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | Chabi's Hotness®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { 
            --chabis-red: #b22222; 
            --chabis-gold: #daa520;
        }

        body { 
            background-color: #0a0a0a; 
            color: #ffffff; 
            font-family: 'Inter', sans-serif; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border: 1px solid #222;
            background-color: #000;
            border-top: 4px solid var(--chabis-red);
        }

        .brand-logo {
            font-weight: 900;
            font-size: 26px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 5px;
            text-align: center;
            color: var(--chabis-red);
        }

        .admin-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--chabis-gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 35px;
            text-align: center;
            display: block;
        }

        .form-control {
            background-color: #111;
            border: 1px solid #333;
            color: #fff;
            border-radius: 0;
            padding: 12px 15px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            background-color: #151515;
            border-color: var(--chabis-red);
            color: #fff;
            box-shadow: none;
        }

        .btn-login {
            background-color: var(--chabis-red);
            color: #fff;
            border: none;
            border-radius: 0;
            padding: 14px;
            width: 100%;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 2px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: var(--chabis-gold);
            color: #000;
        }

        .error-box {
            background-color: rgba(178, 34, 34, 0.1);
            color: #ff4d4d;
            border: 1px solid var(--chabis-red);
            padding: 12px;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
            text-transform: uppercase;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #555;
            text-decoration: none;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .back-link:hover { color: var(--chabis-gold); }
    </style>
</head>
<body>

<div class="login-container">
    <div class="brand-logo">CHABI'S HOTNESS®</div>
    <span class="admin-label">Administrative Portal</span>

    <?php if(!empty($error_message)): ?>
        <div class="error-box"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-1">
            <label class="small text-muted text-uppercase fw-bold mb-2" style="font-size: 10px; letter-spacing: 1px;">Admin Email</label>
            <input type="text" name="username" class="form-control" placeholder="admin@chabishotness.co.za" required>
        </div>
        <div class="mb-4">
            <label class="small text-muted text-uppercase fw-bold mb-2" style="font-size: 10px; letter-spacing: 1px;">Secure Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-login">Authorize Access</button>
    </form>

    <a href="index.php" class="back-link">&larr; Return to Storefront</a>
</div>

</body>
</html>