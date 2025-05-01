<?php
include './includes/header.php';
include '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: ../login.php');
    exit();
}

$customer_id = $_SESSION['customer_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f6f6; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; margin-top: 30px; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: left; }
        .order-box { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #333; }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Orders</h2>

        <?php
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$customer_id]);
        $orders = $stmt->fetchAll();

        if ($orders) {
            foreach ($orders as $order) {
                echo '<div class="order-box">';
                echo '<strong>Order #'.$order['id'].'</strong> | <strong>Date:</strong> '.$order['created_at'].'<br>';
                echo '<strong>Status:</strong> '.$order['status'].'<br>';
                echo '<strong>Total:</strong> ₹'.$order['total'].'<br><br>';

                echo '<table>';
                echo '<tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>';

                $stmtItems = $pdo->prepare("SELECT p.name AS product_name, oi.quantity, oi.price
                                            FROM order_items oi
                                            JOIN products p ON p.id = oi.product_id
                                            WHERE oi.order_id = ?");
                $stmtItems->execute([$order['id']]);
                $items = $stmtItems->fetchAll();

                foreach ($items as $item) {
                    $subtotal = $item['quantity'] * $item['price'];
                    echo '<tr>';
                    echo '<td>'.$item['product_name'].'</td>';
                    echo '<td>'.$item['quantity'].'</td>';
                    echo '<td>₹'.$item['price'].'</td>';
                    echo '<td>₹'.$subtotal.'</td>';
                    echo '</tr>';
                }

                echo '</table>';
                echo '</div>';
            }
        } else {
            echo "<p>You have not placed any orders yet.</p>";
        }
        ?>
    </div>
    <?php include('./includes/footer.php'); ?>
</body>
</html>
