<?php
session_start();
include 'DBConn.php';

// Security Gate
if (!isset($_SESSION['is_admin'])) { 
    header("Location: admin_login.php"); 
    exit(); 
}

// Fetch Real-Time Analytics
$count_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$count_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status = 'pending'"))['total'];
$sum_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders"))['total'] ?? 0;

// Fetch Recent Registrations from users table
$recent_users = mysqli_query($conn, "SELECT full_name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Control Unit | Pastimes®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap');

        :root {
            --sidebar-width: 260px;
            --accent-blue: #0678eb;
            --bg-pure: #000000;
            --card-bg: #0a0a0a;
            --border-dim: #222;
        }

        body { 
            background-color: var(--bg-pure); 
            color: #fff; 
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: #050505;
            border-right: 1px solid var(--border-dim);
            position: fixed;
            padding: 40px 20px;
        }

        .brand-logo { font-weight: 900; letter-spacing: 4px; font-size: 20px; margin-bottom: 5px; }
        .brand-sub { font-size: 9px; color: #444; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 50px; }

        .nav-link {
            color: #555;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 15px 0;
            transition: 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link i { margin-right: 15px; font-size: 14px; }
        .nav-link:hover, .nav-link.active { color: #fff; }
        .nav-link.active { border-right: 3px solid var(--accent-blue); }

        .logout-link { color: #ff3e3e !important; margin-top: 100px; }

        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 60px;
        }

        .welcome-msg { font-weight: 900; letter-spacing: 1px; font-size: 32px; }
        .status-line { font-size: 11px; color: #444; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 50px; }

        /* Metric Cards */
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-dim);
            padding: 30px;
            transition: 0.3s;
        }

        .stat-card:hover { border-color: #444; }
        .stat-label { font-size: 10px; text-transform: uppercase; color: #555; font-weight: 700; letter-spacing: 2px; margin-bottom: 15px; }
        .stat-value { font-size: 28px; font-weight: 900; }

        /* Recent Activity Table */
        .activity-section { margin-top: 60px; }
        .section-title { font-size: 12px; text-transform: uppercase; font-weight: 900; letter-spacing: 3px; margin-bottom: 30px; }
        
        .custom-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .custom-table th { font-size: 10px; color: #333; text-transform: uppercase; padding: 10px; letter-spacing: 1px; }
        .table-row { background: #080808; transition: 0.3s; }
        .table-row:hover { background: #111; }
        .table-row td { padding: 18px 15px; border-top: 1px solid var(--border-dim); border-bottom: 1px solid var(--border-dim); font-size: 13px; }
        .table-row td:first-child { border-left: 1px solid var(--border-dim); }
        .table-row td:last-child { border-right: 1px solid var(--border-dim); }

        .btn-live { border: 1px solid #fff; border-radius: 0; color: #fff; font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 8px 20px; }
    </style>
</head>
<body>

    <!-- Navigation Sidebar -->
    <div class="sidebar">
        <div class="brand-logo">PASTIMES®</div>
        <div class="brand-sub">Admin Control Unit</div>

        <nav class="mt-5">
            <a href="admin_dashboard.php" class="nav-link active"><i class="fa-solid fa-chart-line"></i> Overview</a>
            <a href="admin_products.php" class="nav-link"><i class="fa-solid fa-shirt"></i> Inventory</a>
            <a href="admin_orders.php" class="nav-link"><i class="fa-solid fa-box"></i> Orders</a>
            <a href="admin_users.php" class="nav-link"><i class="fa-solid fa-users"></i> Customers</a>
            
            <a href="logout.php" class="nav-link logout-link"><i class="fa-solid fa-power-off"></i> Terminate Session</a>
        </nav>
    </div>

    <!-- Main View -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="welcome-msg text-uppercase">Welcome back, <?php echo $_SESSION['admin_user']; ?></h1>
                <div class="status-line">System operational. Midrand HQ Status: <span style="color: #00ff88;">Online</span></div>
            </div>
            <a href="index.php" class="btn btn-live">View Live Site</a>
        </div>

        <!-- Metrics Grid -->
        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-label">Total Inventory</div>
                    <div class="stat-value"><?php echo $count_products; ?> <span style="font-size: 12px; color: #444;">Pieces</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-label">Active Orders</div>
                    <div class="stat-value"><?php echo $count_orders; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-label">Revenue (ZAR)</div>
                    <div class="stat-value">R <?php echo number_format($sum_revenue, 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Recent Registrations -->
        <div class="activity-section">
            <h3 class="section-title">Recent Customer Registrations</h3>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Identity</th>
                        <th>Electronic Mail</th>
                        <th class="text-end">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = mysqli_fetch_assoc($recent_users)): ?>
                    <tr class="table-row">
                        <td class="fw-bold text-uppercase"><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td class="text-muted"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="text-end text-muted"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>