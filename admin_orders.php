<?php
session_start();
include 'DBConn.php';

// Security Gate
if (!isset($_SESSION['is_admin'])) { 
    header("Location: admin_login.php"); 
    exit(); 
}

// Optimized Query: Joins orders with order_items to count total pieces per order
$order_query = "SELECT o.id, o.total_amount, o.status, o.order_date, o.user_id,
                (SELECT SUM(quantity) FROM order_items WHERE order_id = o.id) as total_items
                FROM orders o 
                ORDER BY o.order_date DESC";

$orders = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Logs | Pastimes® Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap');

        body { background-color: #000; color: #fff; font-family: 'Inter', sans-serif; letter-spacing: -0.5px; }
        .page-header { border-bottom: 1px solid #222; padding-bottom: 20px; margin-bottom: 40px; }
        .fw-black { font-weight: 900; letter-spacing: 2px; }

        .order-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .order-table thead th { text-transform: uppercase; font-size: 10px; font-weight: 700; letter-spacing: 2px; color: #444; padding: 15px; }

        .order-row { background: #080808; transition: 0.3s; }
        .order-row:hover { background: #111; transform: scale(1.005); }
        .order-row td { padding: 20px 15px; border-top: 1px solid #222; border-bottom: 1px solid #222; vertical-align: middle; }
        .order-row td:first-child { border-left: 1px solid #222; }
        .order-row td:last-child { border-right: 1px solid #222; }

        .status-badge { font-size: 9px; text-transform: uppercase; padding: 4px 10px; border: 1px solid #333; letter-spacing: 1px; font-weight: 700; }
        .status-pending { color: #ffcc00; border-color: #ffcc00; }
        .status-completed { color: #00ff88; border-color: #00ff88; }

        .empty-state { border: 1px dashed #222; padding: 80px 0; text-align: center; color: #444; text-transform: uppercase; letter-spacing: 2px; font-size: 11px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center page-header">
        <div>
            <h2 class="fw-black m-0">ORDER LOGS</h2>
            <p class="text-muted small m-0">System Time: <?php echo date('Y-m-d H:i'); ?> | Midrand HQ</p>
        </div>
        <a href="admin_dashboard.php" class="btn btn-outline-light btn-sm px-4" style="border-radius:0; font-size:10px; font-weight:700;">BACK TO DASHBOARD</a>
    </div>

    <?php if ($orders && mysqli_num_rows($orders) > 0): ?>
        <div class="table-responsive">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Timestamp</th>
                        <th>Volume</th>
                        <th>Valuation</th>
                        <th>Current Status</th>
                        <th class="text-end">Management</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($orders)): ?>
                    <tr class="order-row">
                        <td class="fw-black" style="color: #0678eb;">#PT-0<?php echo $row['id']; ?></td>
                        <td>
                            <div style="font-size: 12px;"><?php echo date('d M Y', strtotime($row['order_date'])); ?></div>
                            <div class="text-muted" style="font-size: 10px;"><?php echo date('H:i', strtotime($row['order_date'])); ?></div>
                        </td>
                        <td>
                            <span style="font-size: 13px;"><?php echo $row['total_items'] ?? 0; ?> Pieces</span>
                        </td>
                        <td class="fw-bold">R <?php echo number_format($row['total_amount'], 2); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                <?php echo strtoupper($row['status']); ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="view_order.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-light btn-sm" style="font-size: 9px; border-radius: 0; font-weight: 700;">MANIFEST</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-box-open mb-3 d-block" style="font-size: 24px;"></i>
            No active transactions found in clothingstore_db.
        </div>
    <?php endif; ?>
</div>

</body>
</html>