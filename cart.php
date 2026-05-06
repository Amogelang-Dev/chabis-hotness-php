<?php
session_start();
include 'DBConn.php'; // Using your standard connection file

// 1. ADD TO BAG LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $p_id = $_POST['product_id'];
    $p_size = $_POST['bottle_size'];
    $p_price = $_POST['selected_price'];

    // Create a unique key for this specific product + size combination
    $cart_key = $p_id . "_" . $p_size;

    if (!isset($_SESSION['cart'])) { 
        $_SESSION['cart'] = array(); 
    }

    if (isset($_SESSION['cart'][$cart_key])) { 
        $_SESSION['cart'][$cart_key]['qty'] += 1; 
    } else { 
        // Store name, price, size, and qty in the session
        $_SESSION['cart'][$cart_key] = array(
            "id" => $p_id,
            "name" => $_POST['product_name'],
            "price" => $p_price,
            "size" => $p_size,
            "qty" => 1
        );
    }
    header("Location: cart.php");
    exit();
}

// 2. UPDATE QUANTITY LOGIC
if (isset($_POST['update_qty'])) {
    $cart_key = $_POST['cart_key'];
    $new_qty = (int)$_POST['quantity'];
    if ($new_qty > 0) {
        $_SESSION['cart'][$cart_key]['qty'] = $new_qty;
    } else {
        unset($_SESSION['cart'][$cart_key]);
    }
    header("Location: cart.php");
    exit();
}

// 3. REMOVE FROM BAG
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
    exit();
}

$subtotal = 0;
$shipping = 60.00; // Standard Midrand delivery rate
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Bag | Chabi's Hotness®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --chabis-red: #b22222; }
        body { font-family: 'Inter', sans-serif; background-color: #fff; }
        .navbar-brand { font-weight: 900; color: var(--chabis-red) !important; letter-spacing: 1px; }
        .cart-header { padding: 40px 0 20px; border-bottom: 2px solid #000; margin-bottom: 30px; text-transform: uppercase; font-weight: 900; }
        .item-price, .accent-price { color: var(--chabis-red); font-weight: 800; }
        
        .qty-input { width: 60px; border: 1px solid #000; text-align: center; font-weight: bold; height: 35px; border-radius: 0; }
        .btn-update { font-size: 11px; padding: 0 15px; text-transform: uppercase; font-weight: 700; border: 1px solid #000; background: #000; color: #fff; height: 35px; transition: 0.3s; }
        
        .summary-container { background: #f9f9f9; border-top: 4px solid var(--chabis-red); padding: 40px; width: 100%; }
        .btn-checkout { 
            background: var(--chabis-red); 
            color: #fff; 
            width: 100%; 
            padding: 22px; 
            font-weight: 900; 
            text-transform: uppercase; 
            border: none; 
            letter-spacing: 3px; 
            transition: 0.4s ease;
        }
        .btn-checkout:hover { background: #000; transform: translateY(-2px); }
        footer { background-color: #000; padding: 60px 0 40px; color: #fff; margin-top: 80px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg border-bottom bg-white">
    <div class="container">
        <a class="navbar-brand" href="index.php">CHABIS HOTNESS <i class="fa-solid fa-fire"></i></a>
        <a href="shop.php" class="text-dark small fw-bold text-uppercase text-decoration-none">Continue Shopping</a>
    </div>
</nav>

<div class="container mb-5">
    <div class="cart-header"><h1>Your Bag</h1></div>
    
    <div class="row">
        <div class="col-12">
            <?php 
            if (!empty($_SESSION['cart'])):
                foreach ($_SESSION['cart'] as $key => $item):
                    $p_id = mysqli_real_escape_string($conn, $item['id']);
                    // Fetch image from DB
                    $res = mysqli_query($conn, "SELECT image_path FROM products WHERE id = '$p_id'");
                    $db_item = mysqli_fetch_assoc($res);
                    
                    $line_total = $item['price'] * $item['qty'];
                    $subtotal += $line_total;
            ?>
                <!-- Individual Item Row -->
                <div class="d-flex align-items-center border-bottom py-4">
                    <img src="<?php echo htmlspecialchars($db_item['image_path']); ?>" style="width: 100px; height: 100px; object-fit: contain; background: #f4f4f4;" onerror="this.src='https://via.placeholder.com/100?text=Sauce'">
                    <div class="px-4 flex-grow-1">
                        <h4 class="fw-bold text-uppercase mb-1" style="font-size:16px;"><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p class="text-muted small mb-3 text-uppercase fw-bold">Size: <?php echo $item['size']; ?></p>
                        
                        <form action="cart.php" method="POST" class="d-flex align-items-center gap-2 mb-3">
                            <input type="hidden" name="cart_key" value="<?php echo $key; ?>">
                            <label class="small text-muted text-uppercase fw-bold">Qty:</label>
                            <input type="number" name="quantity" value="<?php echo $item['qty']; ?>" min="1" class="qty-input">
                            <button type="submit" name="update_qty" class="btn-update">Update</button>
                        </form>

                        <a href="cart.php?remove=<?php echo $key; ?>" class="text-danger small fw-bold text-decoration-none text-uppercase">Remove</a>
                    </div>
                    <div class="item-price fs-4">R <?php echo number_format($line_total, 2); ?></div>
                </div>
            <?php endforeach; ?>

            <div class="mt-5">
                <div class="summary-container">
                    <h5 class="fw-bold text-uppercase mb-4">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">R <?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Shipping (Gauteng Standard)</span>
                        <span class="fw-bold">R <?php echo number_format($shipping, 2); ?></span>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between fs-2 fw-bold mb-5">
                        <span class="text-uppercase">Total</span>
                        <span class="accent-price">R <?php echo number_format($subtotal + $shipping, 2); ?></span>
                    </div>
                     <a href="checkout.php" class="text-decoration-none">
                            <button class="btn-checkout">Secure Checkout &rarr;</button>
                     </a>
                </div>
            </div>

            <?php else: ?>
                <div class="text-center py-5">
                    <h3 class="text-uppercase fw-light">Your bag is empty</h3>
                    <a href="listings.php" class="btn btn-dark mt-3 px-5 py-3 rounded-0 fw-bold">SHOP THE COLLECTION</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer>
    <div class="container text-center">
        <h5 class="mb-2 text-uppercase" style="letter-spacing: 2px;">Chabi's Hotness®</h5>
        <div class="pt-3" style="border-top: 1px solid #333; max-width: 340px; margin: 0 auto;">
            <p class="mb-1" style="font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: #aaa;">Digital Platform by</p>
            <p class="mb-0"><a href="#" class="text-white text-decoration-none fw-bold" style="letter-spacing: 4px; font-size: 12px;">SyreTech SOLUTIONS</a></p>
        </div>
    </div>
</footer>

</body>
</html>