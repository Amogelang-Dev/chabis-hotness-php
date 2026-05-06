<?php
session_start();
include 'DBConn.php'; 

// 1. Initialize the WHERE clause logic
$where = " WHERE 1=1"; 

// 2. Capture and Sanitize Filter Inputs
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    
    // UPDATED: This now checks BOTH the name and the category
    $where .= " AND (product_name LIKE '%$search_query%' OR category LIKE '%$search_query%')"; 
}

$active_cat = "";
if (isset($_GET['cat']) && !empty($_GET['cat'])) {
    $active_cat = mysqli_real_escape_string($conn, $_GET['cat']);
    $where .= " AND category = '$active_cat'"; 
}

// 3. Execute the Dynamic Query
$query = "SELECT * FROM products $where ORDER BY id DESC"; 
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

$count = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop All | Chabi's Hotness®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --chabis-red: #b22222; }
        body { font-family: 'Inter', sans-serif; background-color: #fff; }

        /* --- NAVIGATION --- */
        .navbar { background: #fff; border-bottom: 1px solid #eee; padding: 15px 0; }
        .navbar-brand { font-weight: 900; font-size: 24px; color: var(--chabis-red) !important; letter-spacing: 1px; }
        .nav-link { color: #111 !important; font-weight: 600; font-size: 14px; margin: 0 12px; text-transform: uppercase; }
        .nav-link.active { color: var(--chabis-red) !important; }

        .btn-signup { 
            background: var(--chabis-red); 
            color: #fff !important; 
            padding: 8px 22px; 
            border-radius: 0px; 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 12px;
            border: 1px solid var(--chabis-red);
            transition: all 0.3s ease;
        }
        .btn-signup:hover { background: #000 !important; border-color: #000; }

        /* Banner */
        .shop-banner {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('images/background.jpeg');
            height: 500px; background-size: cover; background-position: center;
            display: flex; flex-direction: column; align-items: center; justify-content: center; color: white;
        }

        /* Filter System */
        .filter-section { border-bottom: 1px solid #eee; padding: 25px 0; background: #fdfdfd; }
        .cat-btn { 
            border: 1px solid #ddd; background: #fff; padding: 10px 20px; 
            font-weight: 600; text-decoration: none; color: #333; 
            display: inline-block; text-transform: uppercase; font-size: 11px; margin-right: 5px;
            transition: 0.3s;
        }
        .cat-btn:hover, .cat-btn.active { background: var(--chabis-red); color: white; border-color: var(--chabis-red); }

        /* Cards */
        .product-card { border: none; margin-bottom: 30px; }
        .product-card img { height: 300px; object-fit: contain; width: 100%; background: #fff; border: 1px solid #eee; padding: 15px; }
        .product-title { font-weight: 800; font-size: 16px; text-transform: uppercase; margin-top: 15px; }
        .product-price { font-weight: 800; color: var(--chabis-red); font-size: 18px; }
        
        .btn-view { 
            background: #000; color: #fff; width: 100%; display: block; 
            text-align: center; padding: 12px; text-decoration: none; 
            font-weight: 700; text-transform: uppercase; font-size: 11px; margin-top: 10px;
        }
        .btn-view:hover { background: var(--chabis-red); color: #fff; }

        footer { background-color: #000; padding: 50px 0; color: #fff; border-top: 5px solid var(--chabis-red); }
        .social-icons a { color: #fff; font-size: 22px; margin: 0 15px; transition: 0.3s; }
    </style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            CHABIS HOTNESS <i class="fa-solid fa-fire" style="font-size: 18px;"></i> 
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="shop.php">Our Sauces</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">The Story</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <!-- Cart Icon -->
                <a href="cart.php" class="text-dark position-relative me-2">
                    <i class="fa-solid fa-cart-shopping fs-5"></i>
                    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            <?php echo count($_SESSION['cart']); ?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- LOGGED IN: Display Name and Dropdown -->
                    <div class="dropdown">
                        <a class="text-dark text-decoration-none small fw-bold text-uppercase dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-user me-1"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            <li><a class="dropdown-item small fw-bold" href="profile.php">My Profile</a></li>
                            <li><a class="dropdown-item small fw-bold" href="orders.php">My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item small fw-bold text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- LOGGED OUT: Display Sign In and Sign Up -->
                    <a href="authenticate.php" class="text-dark text-decoration-none small fw-bold text-uppercase">Sign In</a>
                    <a href="sign-up.php" class="btn btn-signup">Join the Heat</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<section class="shop-banner">
    <h1 class="fw-black text-uppercase">The Sauce Collection</h1>
    <p class="small text-uppercase tracking-widest">Bottled in Midrand, Gauteng</p>
</section>

<section class="filter-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <a href="shop.php" class="cat-btn <?php echo ($active_cat == "") ? 'active' : ''; ?>">All</a>
                <a href="shop.php?cat=Hot" class="cat-btn <?php echo ($active_cat == "Hot") ? 'active' : ''; ?>">Hot</a>
                <a href="shop.php?cat=Mild" class="cat-btn <?php echo ($active_cat == "Mild") ? 'active' : ''; ?>">Mild</a>
            </div>
            <div class="col-lg-4">
                <form action="shop.php" method="GET">
                    <?php if(!empty($active_cat)): ?>
                        <input type="hidden" name="cat" value="<?php echo htmlspecialchars($active_cat); ?>">
                    <?php endif; ?>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control rounded-0" placeholder="Search Chabi's..." value="<?php echo htmlspecialchars($search_query); ?>">
                        <button class="btn btn-dark rounded-0" type="submit">Go</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="container my-5">
    <div class="row">
        <?php if($count > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-3">
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product" onerror="this.src='https://via.placeholder.com/400x500?text=Chabis+Hotness'">
                    <div class="text-center">
                        <h5 class="product-title"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                        <p class="product-price">R 8.00 - R 3599.00</p>
                        <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn-view">View Details</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <h3>No heat found.</h3>
                <p>We couldn't find any products matching your selection.</p>
                <a href="shop.php" class="btn btn-dark px-4 mt-3">Clear Filters</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<footer>
    <div class="container text-center">
        <h5 class="mt-3 mb-1" style="letter-spacing: 2px;">CHABIS HOTNESS®</h5>
        <p class="small mb-2" style="color: #bbb; font-size: 11px;">
            &copy; 2026 Chabi's Hotness. All Rights Reserved. <br>
            Crafted with Organic Passion in South Africa.
        </p>
        <div class="social-icons mb-3">
            <a href="https://www.instagram.com/chabis.hotness" style="margin: 0 10px;"><i class="fab fa-instagram"></i></a>
            <a href="https://wa.me/+27625805348" style="margin: 0 10px;"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.facebook.com/mmadichabasharolmolate" style="margin: 0 10px;"><i class="fab fa-facebook-f"></i></a>
        </div>
        <div class="pt-3" style="border-top: 1px solid #333; max-width: 340px; margin: 0 auto;">
            <p class="mb-1" style="font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: #aaa;">Digital Architecture & Direction</p>
            <p class="mb-0">
                <a href="admin_login.php" class="text-white text-decoration-none fw-bold" style="letter-spacing: 4px; font-size: 12px;">SyreTech SOLUTIONS</a>
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>