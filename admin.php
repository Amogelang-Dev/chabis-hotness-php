<?php
session_start();
include 'DBConn.php';

// Security: Only allow Admins to see this page[cite: 2]
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: authenticate.php");
    exit();
}

// Fetch all Users and Clothes
$users = $conn->query("SELECT * FROM tblUser");
$clothes = $conn->query("SELECT * FROM tblClothes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel | Pastimes®</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #000; color: #fff; font-family: 'Inter', sans-serif; }
        .container { margin-top: 50px; }
        .table { background: #111; color: #fff; border: 1px solid #333; }
        .btn-action { text-transform: uppercase; font-weight: 800; font-size: 10px; letter-spacing: 1px; }
        .status-pending { color: #ffcc00; }
        .status-approved { color: #00ff00; }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-uppercase mb-5">Admin Dashboard</h2>

    <!-- User Management Section[cite: 7, 8] -->
    <h4 class="mb-3">Customer Verification</h4>
    <table class="table mb-5">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                <td>
                    <?php if($row['status'] == 'Pending'): ?>
                        <a href="admin_actions.php?approve_id=<?php echo $row['userID']; ?>" class="btn btn-sm btn-success btn-action">Approve</a>
                    <?php endif; ?>
                    <a href="admin_actions.php?delete_user=<?php echo $row['userID']; ?>" class="btn btn-sm btn-danger btn-action">Remove</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Clothing Inventory Section[cite: 2, 8] -->
    <div class="d-flex justify-content-between mb-3">
        <h4>Inventory</h4>
        <a href="add_item.php" class="btn btn-outline-light btn-action">Add New Item</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $clothes->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['clothName']; ?></td>
                <td>R<?php echo $row['price']; ?></td>
                <td><?php echo $row['stockQuantity']; ?></td>
                <td>
                    <a href="edit_item.php?id=<?php echo $row['clothID']; ?>" class="btn btn-sm btn-light btn-action">Edit</a>
                    <a href="admin_actions.php?delete_cloth=<?php echo $row['clothID']; ?>" class="btn btn-sm btn-danger btn-action">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>