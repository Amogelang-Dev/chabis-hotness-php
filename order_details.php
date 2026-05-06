<?php
session_start();
include 'DBConn.php';

// 1. Capture the ID from the URL (e.g., order_details.php?id=5)
$order_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

if ($order_id == 0) {
    die("Error: No valid Order ID provided in the URL.");
}

/**
 * 2. Fetch Order Summary
 * Column 'id' matches Screenshot 2026-05-04 204441_2.png
 */
$order_query = "SELECT * FROM orders WHERE id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);

if (!$order_result) {
    die("Database Error (Orders Table): " . mysqli_error($conn));
}

$order_data = mysqli_fetch_assoc($order_result);

if (!$order_data) {
    die("Error: Order #$order_id was not found in the system.");
}

/**
 * 3. Fetch Items with Join
 * Fix for Screenshot 2026-05-04 205146.png: 
 * We use 'product_name' instead of 'name' to match your product table schema.
 */
$items_query = "SELECT oi.*, p.product_name 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = '$order_id'";

$items_result = mysqli_query($conn, $items_query);

if (!$items_result) {
    die("Database Error (Items Join): " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $order_id; ?> | Chabi's Hotness®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --chabis-red: #b22222; }
        body { background-color: #000; color: #fff; font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; }
        .invoice-card { 
            background: #0a0a0a; 
            border: 2px solid var(--chabis-red); 
            padding: 40px; 
            width: 100%; 
            max-width: 850px; 
            margin: auto; 
        }
        .brand-header { font-weight: 900; color: var(--chabis-red); text-transform: uppercase; letter-spacing: -1px; }
        .status-pill { background: var(--chabis-red); color: #fff; padding: 5px 15px; font-weight: 800; text-transform: uppercase; font-size: 12px; }
        .table { color: #fff; --bs-table-bg: transparent; border-color: #333; }
        .text-muted-custom { color: #888 !important; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; }
        .btn-return { 
            background: transparent; 
            color: #fff; 
            border: 1px solid #fff; 
            border-radius: 0; 
            padding: 10px 30px; 
            font-weight: 700; 
            transition: 0.3s; 
        }
        .btn-return:hover { background: #fff; color: #000; }
    </style>
</head>
<body>

<div class="container">
    <div class="invoice-card shadow-lg">
        <div class="d-flex justify-content-between align-items-start mb-5">
            <div>
                <h1 class="brand-header m-0">CHABI'S HOTNESS®</h1>
                <p class="text-muted small m-0">Official Order Invoice</p>
            </div>
            <span class="status-pill"><?php echo htmlspecialchars($order_data['status']); ?></span>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <p class="text-muted-custom mb-1">Order Reference</p>
                <h4 class="fw-bold">#<?php echo $order_id; ?></h4>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted-custom mb-1">Date Processed</p>
                <h4 class="fw-bold"><?php echo date('d M Y', strtotime($order_data['order_date'])); ?></h4>
            </div>
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-muted-custom border-0">Product Details</th>
                    <th class="text-center text-muted-custom border-0">Qty</th>
                    <th class="text-end text-muted-custom border-0">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = mysqli_fetch_assoc($items_result)): ?>
                <tr>
                    <td class="py-3 fw-bold text-uppercase">
                        <?php echo htmlspecialchars($item['product_name']); ?>
                    </td>
                    <td class="py-3 text-center"><?php echo $item['quantity']; ?></td>
                    <td class="py-3 text-end fw-bold">
                        R <?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="pt-5 text-end text-muted-custom">Grand Total Paid</td>
                    <td class="pt-5 text-end h3 fw-black" style="color: var(--chabis-red);">
                        R <?php echo number_format($order_data['total_amount'], 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-5 pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
            <p class="small text-muted m-0">Thank you for fueling your heat with Chabi's Hotness®.</p>
            <a href="index.php" class="btn btn-return">Back to Shop</a>
        </div>
    </div>
</div>

</body>
</html>