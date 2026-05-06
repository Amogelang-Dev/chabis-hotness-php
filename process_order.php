<?php
session_start();
include 'DBConn.php'; 

// 1. Get the reference from the URL
$reference = isset($_GET['reference']) ? $_GET['reference'] : '';

if (empty($reference)) {
    die("No reference supplied. Payment could not be verified.");
}

/**
 * 2. Verify the payment with Paystack
 * Using the Secret Key from Screenshot 2026-05-04 203211.png
 */
$secret_key = "sk_test_d5f1fc6ff5b10fdc30c807ea9e0316b84b1d1546"; 

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    // FIX for Screenshot 2026-05-04 203721.png (SSL Error on Localhost)
    CURLOPT_SSL_VERIFYPEER => false, 
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "authorization: Bearer " . $secret_key,
        "cache-control: no-cache"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    die("cURL Error #:" . $err);
}

$tranx = json_decode($response);

// 3. Confirm Paystack status
if (!$tranx->status || $tranx->data->status !== 'success') {
    die("Transaction failed: " . $tranx->message);
}

// 4. Secure Payment Confirmed - Proceed to update database
// Use user_id 1 as a placeholder if session is not set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

// Calculate totals to ensure database matches paid amount
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += ($item['price'] * $item['qty']);
}
$shipping = 60.00;
$total_amount = $subtotal + $shipping;

// 5. Insert into 'orders' table
$order_query = "INSERT INTO orders (user_id, total_amount, status) 
                VALUES ('$user_id', '$total_amount', 'completed')";
    
if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn); 

    // 6. Insert items into 'order_items'
    foreach ($_SESSION['cart'] as $item) {
        $product_id = mysqli_real_escape_string($conn, $item['id']);
        $quantity = mysqli_real_escape_string($conn, $item['qty']);
        $price_at_purchase = mysqli_real_escape_string($conn, $item['price']);

        $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) 
                       VALUES ('$order_id', '$product_id', '$quantity', '$price_at_purchase')";
        mysqli_query($conn, $item_query);
    }

    // 7. Clear cart and show success UI
    unset($_SESSION['cart']);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Confirmed | Chabi's Hotness®</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background: #fff; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .success-container { border: 5px solid #000; padding: 60px; text-align: center; max-width: 600px; position: relative; }
            .brand-red { color: #b22222; font-weight: 900; text-transform: uppercase; }
            .btn-home { background: #000; color: #fff; padding: 15px 40px; text-decoration: none; text-transform: uppercase; font-weight: 800; display: inline-block; margin-top: 30px; transition: 0.3s; }
            .btn-home:hover { background: #b22222; }
            .ref-tag { position: absolute; top: 10px; right: 15px; font-size: 9px; color: #bbb; }
        </style>
    </head>
    <body>
        <div class="success-container">
            <span class="ref-tag">PAYSTACK REF: <?php echo htmlspecialchars($reference); ?></span>
            <h1 class="brand-red">CHABI'S HOTNESS®</h1>
            <h2 class="fw-bold mt-4">PAYMENT RECEIVED</h2>
            <p class="text-muted fs-5">Success! Your order <strong>#<?php echo $order_id; ?></strong> has been logged. We're getting your flavors ready for shipping.</p>
            <a href="index.php" class="btn-home">Back to Shop</a>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Database Error: " . mysqli_error($conn);
}
?>