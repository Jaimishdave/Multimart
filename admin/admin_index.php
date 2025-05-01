<?php
include './includes/header.php';
include '../config/database.php'; // Ensure this contains the PDO connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch total number of products
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_products FROM products");
$stmt->execute();
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

// Fetch total number of orders
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_orders FROM orders");
$stmt->execute();
$total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

// Fetch total number of customers
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_customers FROM customer");
$stmt->execute();
$total_customers = $stmt->fetch(PDO::FETCH_ASSOC)['total_customers'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Internal CSS styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        main {
            padding: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 250px;
            text-align: center;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 22px;
            color: #333;
        }

        .card p {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

 

    <main>
        <h2>Dashboard Overview</h2>
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Products</h3>
                <p><?= $total_products; ?></p>
            </div>
            <div class="card">
                <h3>Total Orders</h3>
                <p><?= $total_orders; ?></p>
            </div>
            <div class="card">
                <h3>Total Customers</h3>
                <p><?= $total_customers; ?></p>
            </div>
        </div>
    </main>

</body>
</html>
