<?php
include 'DBConn.php'; // Access to the $conn variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escaping inputs for basic security
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypting password

    // Check if user already exists
    $checkUser = "SELECT * FROM tblUser WHERE email = '$email'";
    $result = $conn->query($checkUser);

    if ($result->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        // Logic for inserting new user would go here in signup_logic.php
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join the Heat | Chabi's Hotness®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { 
            --chabis-red: #b22222; 
            --chabis-gold: #daa520;
        }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #fff; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
        }
        
        .auth-card { 
            max-width: 450px; 
            margin: auto; 
            padding: 40px; 
            border: 1px solid #eee; 
            border-top: 5px solid var(--chabis-red);
        }
        .auth-card a{
            text-decoration: none;
            color: var(--chabis-red);
        }
        
        .navbar-brand { 
            font-weight: 900; 
            font-size: 24px; 
            color: var(--chabis-red) !important; 
            letter-spacing: 1px; 
            text-transform: uppercase; 
            display: block; 
            text-align: center; 
            margin-bottom: 10px; 
            text-decoration: none; 
        }

        .brand-sub {
            display: block;
            text-align: center;
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 30px;
        }
        
        .form-label { 
            font-weight: 700; 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 1px; 
        }
        
        .form-control { 
            border-radius: 0; 
            border: 1px solid #ddd; 
            padding: 12px; 
        }

        .form-control:focus {
            border-color: var(--chabis-red);
            box-shadow: none;
        }
        
        .btn-auth { 
            background: var(--chabis-red); 
            color: #fff; 
            border: none; 
            padding: 15px; 
            width: 100%; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            transition: 0.3s;
        }

        .btn-auth:hover {
            background: #000;
        }

        .auth-footer { 
            font-size: 13px; 
            text-align: center; 
            margin-top: 20px; 
        }
        
        .auth-footer a { 
            color: var(--chabis-red); 
            font-weight: 700; 
            text-decoration: none; 
        }

        .auth-footer a:hover {
            color: var(--chabis-gold);
        }

    </style>
</head>
<body>

<div class="container">
    <div class="auth-card">
        <a class="navbar-brand" href="index.php">Chabi's Hotness®</a>
        <span class="brand-sub">Exotic Pepper Sauces</span>

        <h5 class="text-center fw-black text-uppercase mb-4">Create Account</h5>
        
        <form action="signup_logic.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="e.g. Name Surname" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" required id="terms">
                    <label class="form-check-label small" for="terms">
                        I agree to the <a href="privacy.php">Terms & Privacy Policy</a>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-auth">Join the Heat</button>
        </form>

        <div class="auth-footer">
            Already a member? <a href="authenticate.php">Sign In</a>
        </div>
    </div>
</div>

</body>
</html>