<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['is_admin'])) { 
    header("Location: admin_login.php"); 
    exit(); 
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Fetch General Order Info
$order_res = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id");
$order = mysqli_fetch_assoc($order_res);

if (!$order) {
    die("Manifest not found in system.");
}

// 2. Fetch specific items and link to the products table for names/images
$items_query = "SELECT oi.*, p.product_name, p.image_path 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = $order_id";
$items = mysqli_query($conn, $items_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manifest #PT-0<?php echo $order_id; ?> | Pastimes®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap');
        body { background: #000; color: #fff; font-family: 'Inter', sans-serif; }
        .manifest-container { max-width: 800px; margin: 50px auto; border: 1px solid #222; background: #080808; padding: 40px; }
        .header-line { border-bottom: 1px solid #222; padding-bottom: 20px; margin-bottom: 30px; }
        .label { font-size: 10px; text-transform: uppercase; color: #555; font-weight: 700; letter-spacing: 1px; }
        .value { font-size: 14px; font-weight: 400; margin-bottom: 20px; }
        .item-row { border-bottom: 1px solid #111; padding: 15px 0; }
        .item-img { width: 50px; height: 60px; object-fit: cover; border: 1px solid #222; margin-right: 15px; }
        .total-section { background: #111; padding: 20px; margin-top: 30px; border-left: 4px solid #0678eb; }
        .btn-print { background: transparent; border: 1px solid #444; color: #fff; font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 10px 20px; transition: 0.3s; }
        .btn-print:hover { background: #fff; color: #000; }
    </style>
</head>
<body>

<div class="container">
    <div class="manifest-container">
        <div class="d-flex justify-content-between align-items-start header-line">
            <div>
                <h2 class="fw-black m-0" style="letter-spacing: 2px;">SHIPPING MANIFEST</h2>
                <p class="text-muted small">Reference: #PT-0<?php echo $order['id']; ?></p>
            </div>
            <button onclick="window.print()" class="btn-print">Print Document</button>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="label">Status</div>
                <div class="value text-uppercase" style="color: #ffcc00; font-weight: 700;">
                    ● <?php echo $order['status']; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="label">Order Date</div>
                <div class="value"><?php echo date('d F Y - H:i', strtotime($order['order_date'])); ?></div>
            </div>
        </div>

        <div class="mt-4">
            <div class="label mb-3">Itemized Contents</div>
            <?php while($item = mysqli_fetch_assoc($items)): ?>
            <div class="d-flex align-items-center item-row">
                <img src="<?php echo $item['image_path']; ?>" class="item-img">
                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size: 13px; text-transform: uppercase;">
                        <?php echo htmlspecialchars($item['product_name']); ?>
                    </div>
                    <div class="text-muted small">SKU: 00<?php echo $item['product_id']; ?></div>
                </div>
                <div class="text-end">
                    <div class="small text-muted"><?php echo $item['quantity']; ?> x R <?php echo number_format($item['price_at_purchase'], 2); ?></div>
                    <div class="fw-bold">R <?php echo number_format($item['quantity'] * $item['price_at_purchase'], 2); ?></div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="total-section d-flex justify-content-between align-items-center">
            <div class="label m-0" style="color: #888;">Total Transaction Value</div>
            <div class="h4 m-0 fw-black">R <?php echo number_format($order['total_amount'], 2); ?></div>
        </div>

        <div class="mt-5 text-center">
            <a href="admin_orders.php" class="text-muted small text-decoration-none" style="letter-spacing: 1px;">← RETURN TO LOGS</a>
        </div>
    </div>
</div>

</body>
</html>