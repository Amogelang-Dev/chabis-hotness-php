<?php
session_start();
include 'DBConn.php';

// Security Gate
if (!isset($_SESSION['is_admin'])) { 
    header("Location: admin_login.php"); 
    exit(); 
}

// Fetch users from the 'users' table based on your latest screenshot
$user_query = "SELECT id, full_name, email, role, created_at FROM users ORDER BY id ASC";
$users = mysqli_query($conn, $user_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Base | Pastimes® Terminal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap');

        body { 
            background-color: #000; 
            color: #fff; 
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.5px;
        }

        .page-header {
            border-bottom: 1px solid #222;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }

        .fw-black { font-weight: 900; letter-spacing: 2px; }

        /* Professional User Table Styling */
        .user-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .user-table thead th {
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #444;
            padding: 15px;
        }

        .user-row {
            background: #080808;
            transition: 0.3s ease-in-out;
        }

        .user-row:hover {
            background: #111;
            transform: scale(1.002);
        }

        .user-row td {
            padding: 20px 15px;
            border-top: 1px solid #222;
            border-bottom: 1px solid #222;
            vertical-align: middle;
        }

        .user-row td:first-child { border-left: 1px solid #222; }
        .user-row td:last-child { border-right: 1px solid #222; }

        /* Role Badges matching your DB values */
        .role-badge {
            font-size: 9px;
            text-transform: uppercase;
            padding: 4px 10px;
            border: 1px solid #333;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .role-admin { border-color: #0678eb; color: #0678eb; }
        .role-customer { border-color: #444; color: #888; }

        .btn-overview {
            border-radius: 0;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        
        .btn-overview:hover { background: #fff; color: #000; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center page-header">
        <div>
            <h2 class="fw-black m-0 text-uppercase">Customer Base</h2>
            <p class="text-muted small m-0">Directory Audit | <?php echo date('Y-m-d'); ?></p>
        </div>
        <a href="admin_dashboard.php" class="btn btn-outline-light btn-overview px-4">Return to Overview</a>
    </div>

    <div class="table-responsive">
        <table class="user-table">
            <thead>
                <tr>
                    <th width="80">Index</th>
                    <th>Identity</th>
                    <th>Electronic Mail</th>
                    <th>System Role</th>
                    <th class="text-end">Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($users)): ?>
                <tr class="user-row">
                    <td class="text-muted fw-bold">#0<?php echo $row['id']; ?></td>
                    <td>
                        <div class="fw-bold text-uppercase" style="font-size: 13px; letter-spacing: 1px;">
                            <?php echo htmlspecialchars($row['full_name']); ?>
                        </div>
                    </td>
                    <td>
                        <div class="text-muted" style="font-size: 12px;"><?php echo htmlspecialchars($row['email']); ?></div>
                    </td>
                    <td>
                        <!-- Dynamic Role badge based on your 'role' column -->
                        <span class="role-badge <?php echo ($row['role'] == 'admin') ? 'role-admin' : 'role-customer'; ?>">
                            <?php echo strtoupper($row['role']); ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <span class="text-muted" style="font-size: 11px;">
                            <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>