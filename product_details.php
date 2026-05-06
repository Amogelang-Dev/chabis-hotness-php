<?php
include 'DBConn.php'; // 1. Connect to the database

// 2. Get the ID from the URL (e.g., product_details.php?id=5)
if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // 3. Fetch product details based on your table columns
    $sql = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    // If product doesn't exist, redirect back to listings
    if (!$product) {
        header("Location: listings.php");
        exit();
    }
} else {
    header("Location: listings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> | Chabi's Hotness®</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --chabis-red: #b22222;
            --dark-bg: #000000;
        }

        body { font-family: 'Inter', sans-serif; background-color: #fff; color: #111; }
        
        /* Navigation */
        .navbar-brand { font-weight: 900; color: var(--chabis-red) !important; letter-spacing: 1px; }
        .back-link { color: #555; text-decoration: none; font-size: 13px; font-weight: 700; transition: 0.3s; }
        .back-link:hover { color: var(--chabis-red); }

        /* Product Image UI */
        .product-img-container {
            background-color: #f9f9f9;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #eee;
        }

        .product-img { 
            max-width: 100%; 
            height: 500px; 
            max-height: 700px;
            object-fit: contain;
        }

        /* Pricing & Badges */
        .price-tag { color: var(--chabis-red); font-weight: 800; font-size: 2.2rem; }
        
        .badge-organic {
            background-color: #1e4620;
            color: white;
            padding: 6px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: 700;
        }

        /* Custom Button */
        .btn-buy { 
            background-color: var(--chabis-red); 
            color: white; 
            padding: 18px; 
            font-weight: 700; 
            border: none; 
            text-transform: uppercase; 
            width: 100%; 
            transition: 0.3s ease-in-out;
            letter-spacing: 2px;
        }

        .btn-buy:hover { background-color: #000; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        .heat-level i { font-size: 18px; margin-right: 2px; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white border-bottom py-3">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            CHABI'S HOTNESS<i class="fa-solid fa-fire-flame-curved ms-1"></i> 
        </a>
        <a href="shop.php" class="back-link">
            <i class="fa-solid fa-chevron-left me-2"></i>EXPLORE COLLECTION
        </a>
    </div>
</nav>

<div class="container my-5">
    <div class="row g-5">
        <!-- Product Image Section -->
        <div class="col-md-6">
            <div class="product-img-container">
                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" 
                     class="product-img shadow-sm" 
                     id="product-main-image"
                     alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                     onerror="this.src='https://via.placeholder.com/500x600?text=Chabis+Hotness+Premium'">
            </div>
        </div>

        <!-- Product Information Section -->
        <div class="col-md-6">
            <div class="badge-organic">100% Organic Ingredients</div>
            
            <p class="text-muted text-uppercase small mb-1" style="letter-spacing: 1px;">Handcrafted in Midrand</p>
            <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($product['product_name']); ?></h1>
            
            <!-- Dynamic Price Display -->
            <h3 class="price-tag mb-4">R <span id="display-price">40.00</span></h3>
            
            <hr>
            
            <div class="py-3">
                <h6 class="fw-bold text-uppercase small" style="letter-spacing: 1px;">Product Description</h6>
                <p class="lead text-muted mb-4" style="font-size: 1.1rem; line-height: 1.8;">
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold text-uppercase small mb-2" style="letter-spacing: 1px;">Heat Intensity</h6>
                <div class="heat-level" style="color: var(--chabis-red);">
                    <i class="fa-solid fa-fire"></i>
                    <i class="fa-solid fa-fire"></i>
                    <i class="fa-solid fa-fire"></i>
                    <i class="fa-solid fa-fire" style="color: #eee;"></i>
                    <i class="fa-solid fa-fire" style="color: #eee;"></i>
                </div>
            </div>

            <!-- Dynamic Pricing Form -->
            <form action="cart.php" method="POST" id="purchase-form">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
                <input type="hidden" name="selected_price" id="hidden-price" value="40.00">
                
                <div class="mb-4">
                    <label class="form-label fw-bold small">SELECT BOTTLE VOLUME</label>
                    <select name="bottle_size" id="size-selector" class="form-select form-select-lg shadow-none" style="border-radius:0; border: 2px solid #000;" required>
                        <option value="45ml" data-price="8.00">45ml - R 8,00</option>
                        <option value="100ml" data-price="20.00">100ml - R 20,00</option>
                        <option value="250ml" data-price="40.00" selected>250ml - R 40,00</option>
                        <option value="350ml" data-price="50.00">350ml - R 50,00</option>
                        <option value="450ml" data-price="80.00">450ml - R 80,00</option>
                        <option value="750ml" data-price="99.00">750ml - R 99,00</option>
                        <option value="1liter" data-price="150.00">1 Liter - R 150,00</option>
                        <option value="5liter" data-price="750.00">5 Liter - R 750,00</option>
                        <option value="10liter" data-price="1499.00">10 Liter - R 1499,00</option>
                        <option value="20liter" data-price="2999.00">20 Liter - R 2999,00</option>
                        <option value="25liter" data-price="3599.00">25 Liter - R 3599,00</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-buy">
                    Add to Bag — R <span id="button-price">40.00</span>
                </button>
            </form>
            
            <div class="mt-4 p-3 border-start border-4 border-danger bg-light">
                <small class="text-muted d-block">
                    <i class="fa-solid fa-truck-fast me-2"></i>Available for delivery from Midrand.
                </small>
            </div>
        </div>
    </div>
</div>

<footer class="py-5 bg-black text-white mt-5">
    <div class="container text-center">
        <p class="small mb-0" style="letter-spacing: 2px; opacity: 0.8;">
            &copy; 2026 CHABI'S HOTNESS® | SyreTech SOLUTIONS
        </p>
    </div>
</footer>

<!-- Dynamic Price Update Script -->
<script>
    const sizeSelector = document.getElementById('size-selector');
    const displayPrice = document.getElementById('display-price');
    const buttonPrice = document.getElementById('button-price');
    const hiddenPrice = document.getElementById('hidden-price');

    sizeSelector.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const newPrice = parseFloat(selectedOption.getAttribute('data-price')).toFixed(2);

        // Update UI elements instantly
        displayPrice.innerText = newPrice;
        buttonPrice.innerText = newPrice;
        
        // Update hidden field for form submission
        hiddenPrice.value = newPrice;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>