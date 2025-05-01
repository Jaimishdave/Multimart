<?php
include './includes/header.php';
include('../config/database.php');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid Order ID.";
    exit();
}

$orderId = $_GET['id'];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];

    $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($update->execute([$status, $orderId])) {
        $message = "Order status updated successfully.";
    } else {
        $message = "Update failed. Try again.";
    }
}

// Fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Order #<?= $orderId ?></h2>

    <?php if ($message) echo "<p class='message'>$message</p>"; ?>

    <form method="post">
    <label for="status">Order Status:</label>
    <select name="status" required>
        <option value="On Time" <?= $order['status'] == 'On Time' ? 'selected' : '' ?>>On Time</option>
        <option value="Delayed" <?= $order['status'] == 'Delayed' ? 'selected' : '' ?>>Delayed</option>
        <option value="Early Delivery" <?= $order['status'] == 'Early Delivery' ? 'selected' : '' ?>>Early Delivery</option>
        <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
    </select>

    <button type="submit">Update Status</button>
</form>


    <p><a href="manage_orders.php">← Back to Order History</a></p>
</div>

</body>
</html>
