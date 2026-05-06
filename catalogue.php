<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Gallery | Pastimes®</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --midnight-navy: #2C3E50;
            --studio-orange: #FF6E14;
            --dark-bg: #111111;
        }

        body { font-family: 'Inter', sans-serif; background-color: #fff; overflow-x: hidden; }

/* --- NAVIGATION --- */
.navbar { background: #fff; border-bottom: 1px solid #eee; padding: 15px 0; }
.navbar-brand { font-weight: 800; font-size: 24px; color: #000 !important; letter-spacing: 1px; }
.nav-link { color: #111 !important; font-weight: 600; font-size: 14px; margin: 0 12px; text-transform: uppercase; }
.nav-link.active { color: var(--primary-blue) !important; }

/* Fixed Sign-Up Button */
.btn-signup { 
    background: #0678eb; 
    color: #fff !important; 
    padding: 8px 22px; 
    border-radius: 0px; 
    font-weight: 600; 
    text-transform: uppercase; 
    font-size: 12px;
    border: 1px solid #0678eb; /* Keeps size consistent on hover */
    transition: all 0.3s ease; /* Smooth color change */
}

/* Hover State Fix */
.btn-signup:hover { 
    background: #000 !important; /* Changes to black on hover */
    color: #fff !important;      /* Ensures text stays visible */
    border-color: #000;          /* Matches the background */
}
 /* --- GALLERY HERO (Centered Fix) --- */
        .gallery-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=2000');
            height: 450px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;      /* Vertical Center */
            justify-content: center;   /* Horizontal Center */
            color: white;
            text-align: center;
        }

        /* Forces internal container to respect centering */
        .gallery-hero .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .gallery-hero h1 { 
            font-size: 60px; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 4px; 
            margin-bottom: 10px;
        }
        
        .gallery-hero p { 
            font-size: 18px; 
            color: #ccc; 
            max-width: 700px; 
            font-weight: 300; 
            line-height: 1.5;
        }
        /* --- FILTER BAR --- */
        .filter-bar { padding: 30px 0; border-bottom: 1px solid #eee; background: #fff; }
        .search-box { position: relative; }
        .search-box i { position: absolute; left: 15px; top: 15px; color: #aaa; }
        .search-box input { padding-left: 45px; height: 48px; border-radius: 0px; border: 1px solid #ddd; }
        
        .gallery-filters { display: flex; gap: 8px; flex-wrap: wrap; }
        .filter-btn { 
            border: 1px solid #ddd; background: none; padding: 10px 20px; border-radius: 0px; 
            font-weight: 700; font-size: 11px; transition: 0.3s; color: #333; text-decoration: none; text-transform: uppercase;
        }
        .filter-btn:hover, .filter-btn.active { background: #0678eb; color: white; border-color: #fff; }

        /* --- GALLERY GRID (The Alignment Fix) --- */
        .gallery-container { padding: 60px 0; }
        .gallery-item { margin-bottom: 30px; position: relative; overflow: hidden; background: #f9f9f9; }
        
        /* This fixed height and object-fit: cover ensures perfect alignment */
        .gallery-item img { 
            width: 100%; 
            height: 450px; 
            object-fit: cover; 
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); 
            cursor: pointer;
        }
        
        .gallery-item:hover img { transform: scale(1.1); }
        
        .gallery-overlay {
            position: absolute; bottom: 0; left: 0; right: 0; 
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            padding: 25px; color: white; opacity: 0; transition: 0.4s;
        }
        .gallery-item:hover .gallery-overlay { opacity: 1; }
        .gallery-overlay h6 { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }

        /* --- FOOTER --- */
        footer { background-color: #000; padding: 40px 0; color: #fff; border-top: 1px solid #222; }
        footer h5 { letter-spacing: 4px; font-weight: 800; text-transform: uppercase; }
        .social-icons a { color: #fff; font-size: 22px; margin: 0 15px; transition: 0.3s; text-decoration: none; }
        .social-icons a:hover { color: var(--studio-orange); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            Pastimes<i class="fa-solid fa-registered" style="font-size: 10px; vertical-align: top;"></i> 
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="listings.php">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="catalogue.php">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <a href="cart.php" class="text-dark position-relative">
                    <i class="fa-solid fa-bag-shopping fs-5"></i>
                    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            <?php echo count($_SESSION['cart']); ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="authenticate.php" class="text-dark text-decoration-none small fw-bold text-uppercase">Sign In</a>
                <a href="sign-up.php" class="btn btn-signup">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

<section class="gallery-hero">
    <div class="container">
        <h1>Our Gallery</h1>
        <p>Capturing the premium oversized aesthetic and the vibrant street culture of Midrand.</p>
    </div>
</section>

<section class="filter-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="gallery-filters">
                    <a href="#" class="filter-btn active">All Moments</a>
                    <a href="#" class="filter-btn">Street Style</a>
                    <a href="#" class="filter-btn">Lookbook</a>
                    <a href="#" class="filter-btn">Events</a>
                    <a href="#" class="filter-btn">Culture</a>
                </div>
            </div>
            <div class="col-md-5 mt-3 mt-md-0">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="form-control" placeholder="Search the collection...">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container gallery-container">
    <div class="row g-4"> <div class="col-md-4">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1000" alt="Style 1">
                <div class="gallery-overlay"><h6>Heritage Drop</h6><p class="small">Pastimes® Essentials.</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gallery-item">
                <img src="images/9.jpeg" alt="Style 2">
                <div class="gallery-overlay"><h6>Midrand Shoot</h6><p class="small">Community fashion highlights.</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=1000" alt="Style 3">
                <div class="gallery-overlay"><h6>The Runway</h6><p class="small">Showcasing the oversized aesthetic.</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=1000" alt="Style 4">
                <div class="gallery-overlay"><h6>Culture & Art</h6><p class="small">Mural art meets modern threads.</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1000" alt="Style 5">
                <div class="gallery-overlay"><h6>Store Launch</h6><p class="small">Opening doors in Gauteng.</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=1000" alt="Style 6">
                <div class="gallery-overlay"><h6>Night Vibes</h6><p class="small">Street light series.</p></div>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container text-center">
        <h5 class="mt-3 mb-1" style="letter-spacing: 2px;">STUDIO 1685</h5>
        
        <p class="small mb-2" style="color: #bbb; font-size: 11px;">
            &copy; 2026 Studio 1685&reg; Collective. <br>
            Headquartered in Midrand, South Africa.
        </p>
        
        <div class="social-icons mb-3">
            <a href="#" style="margin: 0 10px;"><i class="fab fa-instagram"></i></a>
            <a href="#" style="margin: 0 10px;"><i class="fab fa-tiktok"></i></a>
            <a href="#" style="margin: 0 10px;"><i class="fab fa-facebook-f"></i></a>
        </div>

        <div class="pt-3" style="border-top: 1px solid #333; max-width: 340px; margin: 0 auto;">
            <p class="mb-1" style="font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: #aaa;">
                Digital Architecture & Direction
            </p>
            <p class="mb-0">
                <a href="#" class="text-white text-decoration-none fw-bold" style="letter-spacing: 4px; font-size: 12px;">
                    Amo Matlhaga & Kea Masole
                </a>
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>