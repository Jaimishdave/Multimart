<?php
include './includes/header.php';
include('../config/database.php'); // this should define $pdo for PDO
include('includes/header.php');

// Fetch total products
$stmt = $pdo->query("SELECT COUNT(*) as total_products FROM products");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

// Fetch total orders
$stmt = $pdo->query("SELECT COUNT(*) as total_orders FROM orders");
$total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

// Fetch total users
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
?>

<div class="content">
    <h1>Admin Dashboard</h1>
    <div class="stats">
        <div class="stat">
            <h2>Total Products</h2>
            <p><?php echo $total_products; ?></p>
        </div>
        <div class="stat">
            <h2>Total Orders</h2>
            <p><?php echo $total_orders; ?></p>
        </div>
        <div class="stat">
            <h2>Total Users</h2>
            <p><?php echo $total_users; ?></p>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
