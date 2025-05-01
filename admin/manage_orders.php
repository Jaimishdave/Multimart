<?php
include './includes/header.php';
include('../config/database.php'); // This must define $pdo (PDO connection)

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Fetch Order History using PDO
try {
    $stmt = $pdo->prepare("
        SELECT orders.id, customer.username AS customer_name, 
               orders.total, orders.status, 
               orders.created_at, orders.address, orders.payment_method
        FROM orders
        JOIN customer ON orders.customer_id = customer.id
        ORDER BY orders.id ASC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query Failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Multimart</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        h1 {
            margin-top: 20px;
        }
    </style>
</head>
<body>

  


    <div class="main-content">
        <h1>Order History</h1>

        <?php if (!empty($orders)) { ?>
            <table>
            <thead>
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Order Date</th>
        <th>Address</th>
        <th>Payment Method</th>
        <!-- <th>Delivery Message</th> New Column -->
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($orders as $order) { ?>
        <tr>
            <td><?= htmlspecialchars($order['id']) ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td>₹<?= number_format($order['total'], 2) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td>
            <td><?= date('d-m-Y H:i:s', strtotime($order['created_at'])) ?></td>
            <td><?= htmlspecialchars($order['address']) ?></td>
            <td><?= htmlspecialchars($order['payment_method']) ?></td>
            <!-- <td><?= htmlspecialchars($order['delivery_message'] ?? 'No message') ?></td> Show Message -->
            <td>
                <a href="edit_order.php?id=<?= $order['id'] ?>" style="color: blue;">Edit</a>
            </td>
        </tr>
    <?php } ?>
</tbody>

            </table>
        <?php } else { ?>
            <p>No orders found.</p>
        <?php } ?>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>
