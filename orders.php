<?php
session_start();
include 'DBConn.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: authenticate.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all orders for the user
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$current_orders = [];
$past_orders = [];

// Sort orders into two categories based on status
while ($order = $result->fetch_assoc()) {
    if (in_array($order['status'], ['Pending', 'Processing', 'Shipped', 'Out for Delivery'])) {
        $current_orders[] = $order;
    } else {
        // Delivered or Cancelled
        $past_orders[] = $order;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Chabi's Hotness®</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --chabis-red: #b22222;
            --dark-bg: #000000;
        }

        body { font-family: 'Inter', sans-serif; background-color: #fdfdfd; color: #333; }

        /* --- NAVIGATION --- */
        .navbar { background: #fff; border-bottom: 1px solid #eee; padding: 15px 0; }
        .navbar-brand { font-weight: 900; font-size: 24px; color: var(--chabis-red) !important; letter-spacing: 1px; }
        .nav-link { color: #111 !important; font-weight: 600; font-size: 14px; margin: 0 12px; text-transform: uppercase; }
        
        /* --- HEADERS --- */
        .page-header{
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/background.jpeg');
            height: 300px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .section-title { 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            border-bottom: 2px solid var(--chabis-red); 
            display: inline-block; 
            margin-bottom: 30px;
            font-size: 1.2rem;
        }

        /* --- ORDER CARDS --- */
        .order-card { 
            background: #fff; 
            border: 1px solid #eee; 
            padding: 25px; 
            margin-bottom: 20px; 
            transition: 0.3s;
        }
        .current-card { border-left: 5px solid var(--chabis-red); }
        .history-card { border-left: 5px solid #ccc; opacity: 0.85; }
        
        .order-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.05); opacity: 1; }
        
        .status-badge { 
            padding: 5px 12px; 
            font-size: 10px; 
            text-transform: uppercase; 
            font-weight: 700; 
            border-radius: 0;
        }
        
        .order-id { font-weight: 900; font-size: 18px; color: #000; }
        .order-meta { font-size: 13px; color: #777; }
        .order-total { font-weight: 800; color: var(--chabis-red); font-size: 20px; }

        footer { background-color: #000; padding: 50px 0; color: #fff; border-top: 5px solid var(--chabis-red); margin-top: 80px;}
    </style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">CHABIS HOTNESS <i class="fa-solid fa-fire"></i></a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="shop.php">Our Sauces</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">The Story</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <a class="text-dark text-decoration-none small fw-bold text-uppercase dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-user me-1"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                        <li><a class="dropdown-item small fw-bold" href="profile.php">My Profile</a></li>
                        <li><a class="dropdown-item small fw-bold active" href="orders.php">My Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item small fw-bold text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<header class="page-header text-center">
    <div class="container">
        <h1 class="fw-bold text-uppercase">Your Orders</h1>
        <p class="mb-0 text-white-50">Manage your spice collection and track deliveries.</p>
    </div>
</header><br>

<main class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <!-- CURRENT ORDERS SECTION -->
            <div class="mb-5">
                <h3 class="section-title">Current Orders</h3>
                <?php if (!empty($current_orders)): ?>
                    <?php foreach ($current_orders as $order): ?>
                        <div class="order-card current-card d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <span class="order-id">Order #<?php echo $order['id']; ?></span>
                                    <span class="badge status-badge bg-warning text-dark">
                                        <i class="fa-solid fa-truck-fast me-1"></i> <?php echo $order['status']; ?>
                                    </span>
                                </div>
                                <div class="order-meta">
                                    <span class="me-3"><i class="fa-regular fa-calendar me-1"></i> <?php echo date('M j, Y', strtotime($order['order_date'])); ?></span>
                                    <span><i class="fa-solid fa-box me-1"></i> <?php echo $order['item_count']; ?> Items</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="order-total mb-2">R <?php echo number_format($order['total_amount'], 2); ?></div>
                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-dark rounded-0 fw-bold px-3">TRACK ORDER</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted italic small">No active orders at the moment.</p>
                <?php endif; ?>
            </div>

            <!-- ORDER HISTORY SECTION -->
            <div class="mt-5">
                <h3 class="section-title">Order History</h3>
                <?php if (!empty($past_orders)): ?>
                    <?php foreach ($past_orders as $order): ?>
                        <div class="order-card history-card d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <span class="order-id">Order #<?php echo $order['id']; ?></span>
                                    <span class="badge status-badge <?php echo ($order['status'] == 'Delivered') ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $order['status']; ?>
                                    </span>
                                </div>
                                <div class="order-meta">
                                    <span class="me-3"><i class="fa-regular fa-calendar me-1"></i> <?php echo date('M j, Y', strtotime($order['order_date'])); ?></span>
                                    <span><i class="fa-solid fa-check-double me-1"></i> Completed</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="order-total mb-2" style="color: #666;">R <?php echo number_format($order['total_amount'], 2); ?></div>
                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-secondary rounded-0 fw-bold px-3">REORDER</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted small">No past orders found.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

<footer>
    <div class="container text-center">
        <h5 class="mt-3 mb-1" style="letter-spacing: 2px;">CHABIS HOTNESS®</h5>
        <p class="small mb-0" style="color: #bbb; font-size: 11px;">
            &copy; 2026 Chabi's Hotness. All Rights Reserved.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>