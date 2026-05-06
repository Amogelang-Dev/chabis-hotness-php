<?php
session_start();
include 'DBConn.php'; 

// 1. Security Check: Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$subtotal = 0;
$shipping = 60.00; // Standard Midrand/Gauteng delivery

// 2. Calculate Totals
foreach ($_SESSION['cart'] as $item) {
    $subtotal += ($item['price'] * $item['qty']);
}
$total_amount = $subtotal + $shipping;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Chabi's Hotness®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --chabis-red: #b22222; }
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; }
        .checkout-container { max-width: 1000px; margin: 60px auto; }
        
        /* SyreTech Minimalist Branding */
        .form-control { border-radius: 0; border: 1px solid #ddd; padding: 12px; }
        .form-control:focus { border-color: var(--chabis-red); box-shadow: none; }
        .summary-box { background: #fff; border: 1px solid #eee; padding: 30px; position: sticky; top: 20px; border-top: 5px solid var(--chabis-red); }
        
        .btn-pay { 
            background: var(--chabis-red); 
            color: #fff; 
            width: 100%; 
            padding: 18px; 
            font-weight: 800; 
            text-transform: uppercase; 
            border: none; 
            letter-spacing: 2px; 
            transition: 0.3s;
        }
        .btn-pay:hover { background: #000; transform: translateY(-2px); }
        .checkout-header { font-weight: 900; text-transform: uppercase; letter-spacing: -1px; color: #000; }
    </style>
</head>
<body>

<div class="container checkout-container">
    <div class="mb-5 text-center">
        <h1 class="checkout-header">Checkout</h1>
        <a href="cart.php" class="text-muted small text-decoration-none">
            <i class="fa-solid fa-arrow-left me-1"></i> Return to Bag
        </a>
    </div>

    <div class="row g-5">
        <!-- Left Column: Shipping & Payment Form -->
        <div class="col-lg-7">
            <h5 class="fw-bold text-uppercase mb-4">Shipping Details</h5>
            <form id="paymentForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted text-uppercase">First Name</label>
                        <input type="text" id="fname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted text-uppercase">Last Name</label>
                        <input type="text" id="lname" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold text-muted text-uppercase">Email Address</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold text-muted text-uppercase">Delivery Address</label>
                        <input type="text" id="address" class="form-control" placeholder="Street name and house number" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted text-uppercase">City</label>
                        <input type="text" id="city" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold text-muted text-uppercase">Postal Code</label>
                        <input type="text" id="zip" class="form-control" required>
                    </div>
                </div>

                <h5 class="fw-bold text-uppercase mt-5 mb-4">Secure Payment Method</h5>
                <div class="border p-4 mb-3 bg-white">
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input" type="radio" name="payment" id="pay1" checked>
                        <label class="form-check-label fw-bold ms-3" for="pay1">
                            Paystack (Secure EFT / Credit Card)
                            <div class="mt-1">
                                <i class="fa-brands fa-cc-visa text-muted fa-lg me-2"></i>
                                <i class="fa-brands fa-cc-mastercard text-muted fa-lg"></i>
                            </div>
                        </label>
                    </div>
                </div>
                
                <p class="small text-muted mb-4">
                    By clicking "Confirm Purchase", you agree to the Chabi's Hotness® terms of service.
                </p>
                
                <button type="submit" class="btn-pay mt-2">Confirm Purchase &mdash; R <?php echo number_format($total_amount, 2); ?></button>
            </form>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="col-lg-5">
            <div class="summary-box shadow-sm">
                <h5 class="fw-bold text-uppercase mb-4 border-bottom pb-2">Your Hot Selection</h5>
                
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <span class="fw-bold text-uppercase d-block" style="font-size: 0.85rem;">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </span>
                            <small class="text-muted">Size: <?php echo $item['size']; ?> | Qty: <?php echo $item['qty']; ?></small>
                        </div>
                        <span class="fw-bold text-dark">R <?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold">R <?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Shipping (Gauteng)</span>
                    <span class="fw-bold">R <?php echo number_format($shipping, 2); ?></span>
                </div>
                
                <div class="d-flex justify-content-between border-top pt-4">
                    <span class="fw-black h4 text-uppercase">Total</span>
                    <span class="h4 fw-bold" style="color: var(--chabis-red);">R <?php echo number_format($total_amount, 2); ?></span>
                </div>
                
                <div class="mt-4 p-3 bg-light text-center">
                    <small class="text-muted">
                        <i class="fa-solid fa-shield-halved me-1"></i> Secure checkout powered by SyreTech
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Paystack Script -->
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
const paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener("submit", payWithPaystack, false);

function payWithPaystack(e) {
    e.preventDefault();

    let handler = PaystackPop.setup({
        // Key from Screenshot 2026-05-04 203211.png
        key: 'pk_test_77114fa4c825872d13b5d99aab3fcf6a74357aec', 
        email: document.getElementById("email").value,
        amount: <?php echo ($total_amount * 100); ?>, // Convert Rands to Cents
        currency: "ZAR",
        ref: 'CHABI_'+Math.floor((Math.random() * 1000000000) + 1),
        metadata: {
            custom_fields: [
                {
                    display_name: "Full Name",
                    variable_name: "full_name",
                    value: document.getElementById("fname").value + " " + document.getElementById("lname").value
                }
            ]
        },
        onClose: function(){
            alert('Transaction was not completed.');
        },
        callback: function(response){
            // Redirect to processing page after success
            window.location.href = "process_order.php?reference=" + response.reference;
        }
    });

    handler.openIframe();
}
</script>
</body>
</html>