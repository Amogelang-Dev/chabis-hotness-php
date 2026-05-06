<?php
session_start();
include 'DBConn.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Story | Chabi's Hotness®</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --chabis-red: #b22222;
            --chabis-gold: #daa520;
            --dark-bg: #000000;
        }

        body { font-family: 'Inter', sans-serif; color: #333; overflow-x: hidden; background-color: #fff; }

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

        /* --- ABOUT HERO --- */
        .about-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/background.jpeg');
            height: 500px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .about-hero h1 { 
            font-size: 64px; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 4px; 
        }
        
        .about-hero p { 
            font-size: 1.3rem; 
            color: #f4f4f4; 
            max-width: 800px; 
            margin: 20px auto; 
            text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
        }

        /* --- OUR STORY SECTION --- */
        .story-section { padding: 100px 0; }
        .story-content h2 { font-weight: 800; font-size: 40px; margin-bottom: 25px; text-transform: uppercase; color: var(--chabis-red); }
        .story-content p { font-size: 17px; line-height: 1.9; color: #555; margin-bottom: 20px; }
        .story-image img { border-radius: 0px; height: 620px; width: 100%; object-fit: cover; }

        /* --- VALUES SECTION --- */
        .values-section { padding: 100px 0; }
        .value-card { 
            background: whitesmoke; 
            padding: 50px 40px; 
            border-radius: 0px; 
            text-align: center; 
            height: 100%;
            transition: 0.3s;
            border-bottom: 4px solid transparent;
        }
        .value-card:hover { transform: translateY(-10px); border-color: var(--chabis-red); }
        .value-icon { 
            width: 80px; height: 80px; 
            background: var(--dark-bg); 
            color: #fff; 
            display: flex; align-items: center; justify-content: center; 
            border-radius: 50%; margin: 0 auto 25px; font-size: 30px;
        }
        .value-card h4 { font-weight: 800; text-transform: uppercase; margin-bottom: 15px; }

        /* --- CTA SECTION --- */
        .cta-section { padding: 120px 0; text-align: center; background: #fff; }
        .cta-section h2 { font-weight: 900; font-size: 45px; margin-bottom: 20px; text-transform: uppercase; }
        .btn-shop-now { 
            background: var(--chabis-red); 
            color: white; 
            padding: 18px 45px; 
            font-weight: 700; 
            text-decoration: none; 
            display: inline-block;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .btn-shop-now:hover { background: var(--chabis-gold); color: #000; transform: scale(1.05); }

        /* --- FOOTER --- */
        footer { background-color: #000; padding: 50px 0; color: #fff; border-top: 5px solid var(--chabis-red); }
        .social-icons a { color: #fff; font-size: 22px; margin: 0 15px; transition: 0.3s; }
        .social-icons a:hover { color: var(--chabis-gold); }
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
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="shop.php">Our Sauces</a></li>
                <li class="nav-item"><a class="nav-link active" href="about.php">The Story</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <a href="cart.php" class="text-dark position-relative me-2">
                    <i class="fa-solid fa-cart-shopping fs-5"></i>
                    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            <?php echo count($_SESSION['cart']); ?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
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
                    <a href="authenticate.php" class="text-dark text-decoration-none small fw-bold text-uppercase">Sign In</a>
                    <a href="sign-up.php" class="btn btn-signup">Join the Heat</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<section class="about-hero">
    <div class="container">
        <h1>Born in the Heat.</h1>
        <p>Premium, 100% Organic Chillie Sauces handcrafted with vinaigrette and fresh garden peppers. Authentically South African.</p>
    </div>
</section>

<section class="story-section container">
    <div class="row align-items-center g-5">
        <div class="col-md-6 story-content">
            <h2>We are North West's best recommended chilli company</h2>
            <p>Chabis Hotness (Pty) Limited, is a company that started in 2018 and officially registered in 2019. It produces and sells Chillie Garnish Sauces. Do allow us to add some flavour and exotic taste to your foods, through our products Chabis Hotness Chillie peppers. These little bombshells of hotness flavour and spices, from the mild to the wild are the key ingredients in our products, farmed and harvested locally in South Africa, Limpopo, Gauteng and North West province farmers.</p>
            <p>We use one of the seething methods which has a very beneficial preservative that eliminates any bacterial growth (e.g. listeria) and contamination. The product has gone through microbiological, laboratory testing and has been approved to have a shelf life of 6months from date of opening. It has an advantage of being kept at a room temperature without the necessity of refrigeration. The Chabis Hotness Chillie Garnish sauce is GS1 barcode registered. It has 2 full-time management employees (Obakeng Letshwiti and Stefan Phiri), 10 part-time workers and 10 sales agents from different towns and provinces within South Africa.</p>
        </div>
        <div class="col-md-6 story-image">
            <img src="images/history.jpeg" alt="Our Chilli History">
        </div>
    </div>
</section>

<section class="values-section" style="background-color: #fafafa;">
    <div class="container">
        <div class="row align-items-center g-5 flex-md-row-reverse">
            <div class="col-md-7">
                <h2 class="fw-bold text-uppercase mb-4">The face of the company</h2>
                <div style="font-size: 16px; line-height: 1.8; color: #555;">
                    <p>Well, well, well! Are you loving our Hotness already? Let’s take you on a short-right preview about the founder…</p>
                    <p>She is young, vibrant, ambitious, resilient, and goal-oriented, with expertise in Procurement, Sourcing, Commercial, Purchasing, Contracting, and Supply Chain Management. A businesswoman through and through, she embodies fierceness, confidence, and enthusiasm. Her brand, Chabis Hotness (Pty) Ltd, reflects her dynamic personality and unwavering drive. The name 'Chabi' is derived from her own name, Mmadichaba, which means 'Mother of Nations,' symbolizing her nurturing spirit and leadership. Hailing from the sophisticated village of Moruleng, near Sun City in the North West province, she was raised by her late father, Ntsowe Joseph Molate, a man renowned for his courage and prominence, and her mother, Naome Seronamang Molate (née Letshwiti), a beautiful, prayerful, and warm-hearted woman.</p>
                    <p>Both parents were successful entrepreneurs, leaving behind a legacy of resilience and achievement. Committed to making a meaningful impact within her industry and community, she actively engages in initiatives that support local talent and foster sustainable practices. Through her work and brand, she aims to inspire others to embrace their potential and pursue their dreams with passion and purpose.</p>
                </div>
            </div>
            <div class="col-md-5">
                <img src="images/founder.jpeg" class="img-fluid shadow" style="height: 600px; width: 500px; object-fit: cover;" alt="The Founder">
            </div>
        </div>
    </div>
</section>

<section class="values-section">
    <div class="container text-center mb-5">
        <h2 class="fw-bold text-uppercase" style="letter-spacing: 2px;">Our Pillars</h2>
        <p class="text-muted">The core philosophy behind every bottle we craft.</p>
    </div>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fa-solid fa-leaf"></i></div>
                    <h4>100% Organic</h4>
                    <p class="text-muted">No chemicals. No fillers. We rely purely on the natural flavors of earth-grown peppers and spices.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                    <h4>Handcrafted</h4>
                    <p class="text-muted">Every batch is meticulously prepared to ensure the heat profile and consistency meet our premium standards.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fa-solid fa-flask"></i></div>
                    <h4>The Vinaigrette Base</h4>
                    <p class="text-muted">Our signature tang comes from a premium vinaigrette blend that preserves the sauce naturally.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section container">
    <h2>Ready to Feel the Fire?</h2>
    <p class="text-muted mb-5">Experience the authentic South African heat that everyone is talking about.</p>
    <a href="shop.php" class="btn-shop-now">Shop the Collection <i class="fa-solid fa-fire ms-2"></i></a>
</section>

<footer>
    <div class="container text-center">
        <h5 class="mt-3 mb-1" style="letter-spacing: 2px;">CHABIS HOTNESS®</h5>
        <p class="small mb-2" style="color: #bbb; font-size: 11px;">
            &copy; 2026 Chabi's Hotness. All Rights Reserved. <br>
            Crafted with Organic Passion in South Africa.
        </p>
        <div class="social-icons mb-3">
            <a href="https://www.instagram.com/chabis.hotness"><i class="fab fa-instagram"></i></a>
            <a href="https://wa.me/+27625805348"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.facebook.com/mmadichabasharolmolate"><i class="fab fa-facebook-f"></i></a>
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