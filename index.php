<?php
// 1. Start the session at the very top to access login data
session_start(); 

// 2. Database Connection
include 'DBConn.php'; 

// 3. Fetch Featured Products (Limited to 8 for the home page)
$sql_all = "SELECT * FROM products LIMIT 8";
$result_all = mysqli_query($conn, $sql_all);

// 4. Fetch specific "Fan Favorites" (Based on highest price or specific criteria)
$sql_best = "SELECT * FROM products ORDER BY price DESC LIMIT 3";
$result_best = mysqli_query($conn, $sql_best);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chabis Hotness® | Authentic Organic Chillie Sauces</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --chabis-red: #b22222; 
            --chabis-gold: #daa520; 
            --dark-bg: #000000;
        }

        body { font-family: 'Inter', sans-serif; overflow-x: hidden; background-color: #fff; }

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

        /* --- HERO --- */
        .hero-video-container { position: relative; height: 75vh; width: 100%; overflow: hidden; display: flex; align-items: center; justify-content: center; color: white; text-align: center; }
        .hero-video { position: absolute; top: 50%; left: 50%; min-width: 100%; min-height: 100%; width: auto; height: auto; z-index: 0; transform: translate(-50%, -50%); object-fit: cover; }
        .video-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1; }
        .hero-content { position: relative; z-index: 2; }
        .hero-content h1 { font-size: 4rem; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; text-shadow: 2px 2px 15px rgba(0,0,0,0.7); }
        
        .btn-main { background-color: var(--chabis-red); color: white; padding: 12px 30px; font-weight: 700; text-decoration: none; text-transform: uppercase; font-size: 13px; display: inline-block; border: none; transition: 0.3s ease; }
        .btn-main:hover { background-color: var(--chabis-gold); color: #000; }

        /* --- CATEGORIES & PRODUCTS --- */
        .cat-card img { height: 280px; object-fit: contain; background: #f9f9f9; padding: 20px; transition: 0.3s; }
        .cat-card:hover img { transform: scale(1.05); }
        .cat-card h5 { font-size: 14px; font-weight: 800; text-transform: uppercase; margin-top: 15px; color: var(--chabis-red); }

        .product-card { transition: 0.3s; border: 1px solid #f0f0f0 !important; border-radius: 0; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .price-tag { color: var(--chabis-red); font-size: 1.2rem; font-weight: 800; }
        
        /* --- FOOTER --- */
        footer { background-color: #000; padding: 60px 0 30px; color: #fff; border-top: 5px solid var(--chabis-red); }
        .social-icons a { color: #fff; font-size: 22px; margin: 0 15px; transition: 0.3s; }
        .social-icons a:hover { color: var(--chabis-red); }
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

<!-- HERO SECTION -->
<section class="hero-video-container">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="images/chilli_clash2.mp4" type="video/mp4">
        <img src="https://images.unsplash.com/photo-1588251213028-1115f6063414" alt="Chabis Hotness Header">
    </video>
    <div class="video-overlay"></div>
    <div class="container hero-content">
        <h1>Unleash the Flavor.</h1>
        <p>Premium, 100% Organic Chillie Sauces handcrafted with vinaigrette and fresh garden peppers. Authentically South African.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="shop.php" class="btn-main">Shop Collection</a>
            <a href="about.php" class="btn-main" style="background: transparent; border: 1px solid #fff;">Our History</a>
        </div>
    </div>
</section>

<!-- FLAVOR CATEGORIES -->
<section class="container my-5 py-5">
    <h3 class="text-center fw-bold mb-5" style="text-transform: uppercase; letter-spacing: 2px;">Explore the Heat Levels</h3>
    <div class="row g-4 text-center">
        <div class="col-6 col-md-3">
            <a href="shop.php?cat=Hot" class="text-decoration-none text-dark">
                <div class="cat-card">
                    <img src="images/original.jpeg" class="w-100" alt="Original Hot" onerror="this.src='https://placehold.co/400x500?text=Hot'">
                    <h5>Original Hot</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="shop.php?cat=ExtraHot" class="text-decoration-none text-dark">
                <div class="cat-card">
                    <img src="images/extrahot.jpeg" class="w-100" alt="Extra Hot" onerror="this.src='https://placehold.co/400x500?text=Extra+Hot'">
                    <h5>Extra Hot</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="shop.php?cat=Mild" class="text-decoration-none text-dark">
                <div class="cat-card">
                    <img src="images/lemon & herb.jpeg" class="w-100" alt="Lemon Herb" onerror="this.src='https://placehold.co/400x500?text=Mild'">
                    <h5>Lemon & Herb</h5>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="shop.php?cat=SweetChilli" class="text-decoration-none text-dark">
                <div class="cat-card">
                    <img src="images/sweet chilli.jpeg" class="w-100" alt="Sweet Chillie" onerror="this.src='https://placehold.co/400x500?text=Sweet'">
                    <h5>Sweet Chillie</h5>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- FAN FAVORITES SECTION -->
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="text-transform: uppercase; font-size: 1.5rem; color: var(--chabis-red);">Fan Favorites</h2>
        <a href="shop.php" class="text-dark text-decoration-none small fw-bold">BROWSE ALL FLAVORS &rarr;</a>
    </div>

    <div class="row g-4">
        <?php if ($result_best && mysqli_num_rows($result_best) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result_best)): ?>
                <div class="col-md-4">
                    <div class="card product-card h-100 text-center p-3">
                        <!-- CHANGED: 'image' to 'image_path' -->
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                             class="card-img-top" 
                             alt="Product" 
                             onerror="this.src='https://placehold.co/400x500?text=Sauce'">
                        
                        <div class="card-body">
                            <!-- CHANGED: 'name' to 'product_name' -->
                            <h5 class="card-title fw-bold text-uppercase" style="font-size: 1.1rem;">
                                <?php echo htmlspecialchars($row['product_name']); ?>
                            </h5>
                            <p class="price-tag mb-3">R <?php echo number_format($row['price'], 2); ?></p>
                            <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn-view">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Our kitchen is currently restocking. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="container text-center">
        <h5 class="mt-3 mb-1" style="letter-spacing: 2px;">CHABIS HOTNESS®</h5>
        <p class="small mb-2" style="color: #bbb; font-size: 11px;">
            &copy; <?php echo date('Y'); ?> Chabi's Hotness. All Rights Reserved. <br>
            Crafted with Organic Passion in South Africa.
        </p>
        
        <div class="social-icons mb-4">
            <a href="https://www.instagram.com/chabis.hotness"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://wa.me/+27625805348"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="https://www.facebook.com/mmadichabasharolmolate"><i class="fa-brands fa-facebook-f"></i></a>
        </div>

        <div class="pt-4" style="border-top: 1px solid #333; max-width: 400px; margin: 0 auto;">
            <p class="mb-1" style="font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: #aaa;">Digital Architecture & Direction</p>
            <p class="mb-0">
                <a href="admin_login.php" class="text-white text-decoration-none fw-bold" style="letter-spacing: 4px; font-size: 12px;">SyreTech SOLUTIONS</a>
            </p>
        </div>
    </div>
</footer>

<!-- JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>