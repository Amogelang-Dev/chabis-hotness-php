<?php
session_start();
include 'DBConn.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get In Touch | Chabi's Hotness®</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --chabis-red: #b22222;
            --chabis-gold: #daa520;
            --dark-bg: #000000;
            --light-grey: #f8f9fa;
        }

        body { font-family: 'Inter', sans-serif; color: #333; }

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

        /* --- CONTACT HERO --- */
        .contact-hero {
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

        .contact-hero h1 { 
            font-size: 64px; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 4px; 
        }

        /* --- CONTACT INFO & FORM --- */
        .contact-section { padding: 80px 0; }
        .info-box { margin-bottom: 30px; display: flex; align-items: flex-start; gap: 15px; }
        .info-box i { color: var(--chabis-red); font-size: 22px; margin-top: 5px; }
        .info-box h5 { font-weight: 700; margin-bottom: 5px; font-size: 18px; }
        .info-box p { color: #666; font-size: 15px; line-height: 1.6; }

        .contact-form { background: #fff; padding: 40px; border-radius: 0px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); border-top: 4px solid var(--chabis-red); }
        .form-control { border: 1px solid #ddd; padding: 12px; border-radius: 0px; margin-bottom: 15px; }
        .btn-send { background: var(--chabis-red); color: white; border: none; padding: 14px; width: 100%; font-weight: 700; font-size: 16px; text-transform: uppercase; transition: 0.3s; }
        .btn-send:hover { background: var(--dark-bg); }

        /* --- MAP SECTION --- */
        .map-container { height: 500px; width: 90%; margin: 0 auto; border-top: 2px solid var(--chabis-red); border-bottom: 2px solid var(--chabis-red); }

        /* --- FAQ SECTION --- */
        .faq-section { padding: 80px 0; }
        .faq-item i { color: var(--chabis-red); font-size: 24px; margin-bottom: 15px; display: block; }
        .faq-item h5 { font-weight: 700; margin-bottom: 10px; }

        /* --- FOOTER --- */
        footer { background-color: #000; padding: 50px 0; color: #fff; border-top: 5px solid var(--chabis-red); }
        .social-icons a { color: #fff; font-size: 22px; margin: 0 15px; transition: 0.3s; }
        .social-icons a:hover { color: var(--chabis-gold); }
    </style>
</head>
<body>

<!-- UPDATED NAVIGATION -->
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
                <li class="nav-item"><a class="nav-link" href="about.php">The Story</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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
                    <!-- LOGGED IN STATE -->
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
                    <!-- LOGGED OUT STATE -->
                    <a href="authenticate.php" class="text-dark text-decoration-none small fw-bold text-uppercase">Sign In</a>
                    <a href="sign-up.php" class="btn btn-signup">Join the Heat</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<section class="contact-hero">
    <div class="container">
        <h1>Contact the Fire</h1>
        <p>From the farms of Limpopo to your table—reach out and let us add some exotic flavor to your life.</p>
    </div>
</section>

<section class="contact-section container">
    <div class="row g-5">
        <div class="col-md-5">
            <h2 class="fw-bold mb-4">Get In Touch</h2>
            <p class="text-muted mb-5">Our sauces are farmed and harvested locally. Visit our partners or reach out directly for wholesale inquiries.</p>
            
            <div class="info-box">
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <h5>Featured Partner</h5>
                    <p>Molate General Dealer<br>Moruleng, North West<br>South Africa</p>
                </div>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-envelope"></i>
                <div>
                    <h5>Email Us</h5>
                    <p>hello@chabishotness.co.za<br>orders@chabishotness.co.za</p>
                </div>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-phone"></i>
                <div>
                    <h5>Call Us</h5>
                    <p>+27 62 580 5348<br>Mon-Fri: 9am - 5pm</p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="contact-form">
                <h3 class="fw-bold mb-4">Send a Message</h3>
                <form action="process_contact.php" method="POST">
                    <div class="row">
                        <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
                        <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Your Email" required></div>
                    </div>
                    <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                    <textarea name="message" class="form-control" rows="5" placeholder="How can we help you today?" required></textarea>
                    <button type="submit" class="btn btn-send">Submit Message <i class="fa-solid fa-paper-plane ms-2"></i></button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="map-container">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3610.7964091742574!2d27.17452187516292!3d-25.17635137772442!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ebe810da800c1e9%3A0x6e7374eb9f3b3aac!2sMolate%20general%20dealer!5e0!3m2!1sen!2sza!4v1777811984978!5m2!1sen!2sza" 
        width="100%" 
        height="100%" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</section>

<section class="faq-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Customer FAQs</h2>
        <div class="row g-5">
            <div class="col-md-4 faq-item">
                <i class="fa-solid fa-vial"></i>
                <h5>Quality & Safety</h5>
                <p>Our sauces undergo strict microbiological laboratory testing and have a shelf life of 6 months after opening.</p>
            </div>
            <div class="col-md-4 faq-item">
                <i class="fa-solid fa-leaf"></i>
                <h5>100% Organic</h5>
                <p>Handcrafted with vinaigrette and fresh garden peppers from local South African farmers.</p>
            </div>
            <div class="col-md-4 faq-item">
                <i class="fa-solid fa-briefcase"></i>
                <h5>Wholesale</h5>
                <p>Want to stock Chabi's Hotness? Send us a message and our team will get back to you with pricing.</p>
            </div>
        </div>
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