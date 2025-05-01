<?php
include './includes/header.php';
include('../config/database.php');

// Check if the user is a delivery person
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'delivery_person') {
    header("Location: delivery_login.php");
    exit();
}

// Fetch assigned orders
$delivery_person_id = $_SESSION['delivery_id'];

$stmt = $pdo->prepare("
    SELECT orders.id, customer.username AS customer_name, orders.address, orders.status 
    FROM orders 
    JOIN customer ON orders.customer_id = customer.id
    WHERE orders.status != 'Delivered'
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    $message = $_POST['message'];

    // Update order status and insert a message for admin
    $update = $pdo->prepare("UPDATE orders SET status = 'Delivered', delivery_message = ? WHERE id = ?");
    if ($update->execute([$message, $orderId])) {
        echo "<p style='color: green;'>Order #$orderId marked as Delivered.</p>";
    } else {
        echo "<p style='color: red;'>Failed to update order.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Orders</title>
</head>
<body>
    <h2>Your Assigned Orders</h2>
    <?php if (!empty($orders)) { ?>
        <table border="1">
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Address</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($orders as $order) { ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['customer_name'] ?></td>
                    <td><?= $order['address'] ?></td>
                    <td><?= $order['status'] ?></td>
                    <td>
                        <?php if ($order['status'] !== 'Delivered') { ?>
                            <form method="post">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <input type="text" name="message" placeholder="Optional message" required>
                                <button type="submit">Mark as Delivered</button>
                            </form>
                        <?php } else { ?>
                            <p style="color: green;">Delivered</p>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No assigned orders.</p>
    <?php } ?>
</body>
</html>
