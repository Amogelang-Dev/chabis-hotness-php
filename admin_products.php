<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['is_admin'])) { 
    header("Location: admin_login.php"); 
    exit(); 
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: admin_products.php");
    exit();
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management | Pastimes®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap');

        :root {
            --accent-blue: #0678eb;
            --border-color: #222;
            --bg-dark: #000;
            --card-bg: #0a0a0a;
        }

        body { 
            background-color: var(--bg-dark); 
            color: #fff; 
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.5px;
        }

        /* Header Styling */
        .page-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 40px;
        }

        .fw-black { font-weight: 900; letter-spacing: 2px; }

        /* Modern Add Product Section */
        .admin-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 30px;
            margin-bottom: 50px;
        }

        .form-label-custom {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: #666;
            margin-bottom: 10px;
            display: block;
        }

        .form-control-custom {
            background: #000 !important;
            border: 1px solid #333 !important;
            color: #fff !important;
            border-radius: 0 !important;
            padding: 12px;
            font-size: 13px;
            transition: 0.3s;
        }

        .form-control-custom:focus {
            border-color: var(--accent-blue) !important;
            box-shadow: none;
        }

        /* Professional Inventory Table */
        .inventory-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .inventory-table thead th {
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #444;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .inventory-row {
            background: #080808;
            transition: 0.3s;
        }

        .inventory-row:hover {
            background: #111;
            transform: scale(1.005);
        }

        .inventory-row td {
            padding: 20px 15px;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .inventory-row td:first-child { border-left: 1px solid var(--border-color); }
        .inventory-row td:last-child { border-right: 1px solid var(--border-color); }

        .prod-img-container {
            width: 70px;
            height: 85px;
            background: #111;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #222;
        }

        .prod-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .price-tag { font-weight: 700; color: #fff; }
        
        .btn-delete {
            color: #ff3e3e;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-delete:hover { color: #fff; }

        .btn-action {
            background: var(--accent-blue);
            color: #fff;
            border-radius: 0;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 12px 25px;
            border: none;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center page-header">
        <div>
            <h2 class="fw-black m-0">INVENTORY</h2>
            <p class="text-muted small m-0">Archive Management System</p>
        </div>
        <a href="admin_dashboard.php" class="btn btn-outline-light btn-sm px-4" style="border-radius:0; font-size:10px;">BACK TO DASHBOARD</a>
    </div>

    <!-- Add Section -->
    <div class="admin-card">
        <form action="process_product.php" method="POST" enctype="multipart/form-data" class="row g-4">
            <div class="col-md-5">
                <label class="form-label-custom">Product Identification</label>
                <input type="text" name="product_name" class="form-control-custom w-100" placeholder="e.g. Pastimes Utility Jacket" required>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Valuation (ZAR)</label>
                <input type="number" step="0.01" name="price" class="form-control-custom w-100" placeholder="0.00" required>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Asset Visual</label>
                <input type="file" name="image_path" class="form-control-custom w-100" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-action w-100">Add to Archive</button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="table-responsive">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th width="100">Visual</th>
                    <th>Product Description</th>
                    <th>Category</th>
                    <th>Valuation</th>
                    <th class="text-end">Management</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($products)): ?>
                <tr class="inventory-row">
                    <td>
                        <div class="prod-img-container">
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product">
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-uppercase" style="font-size: 14px; letter-spacing: 1px;">
                            <?php echo htmlspecialchars($row['product_name']); ?>
                        </div>
                        <div class="text-muted" style="font-size: 11px;">ID: #PX-0<?php echo $row['id']; ?></div>
                    </td>
                    <td>
                        <span class="badge border border-secondary text-uppercase" style="font-size: 9px; font-weight: 400; border-radius: 0;">
                            <?php echo htmlspecialchars($row['category'] ?? 'Apparel'); ?>
                        </span>
                    </td>
                    <td class="price-tag">
                        R <?php echo number_format($row['price'], 2); ?>
                    </td>
                    <td class="text-end">
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Remove asset from system?')">
                            <i class="fa-solid fa-trash-can me-1"></i> Remove
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>