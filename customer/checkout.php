<?php
include './includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$success_message = '';
$error_message = '';
$total_paid = '';

// Detect if Buy Now mode
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;
$buy_now_qty = isset($_GET['qty']) ? intval($_GET['qty']) : 1;
$cart_items = [];

if ($product_id) {
    // BUY NOW MODE
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $cart_items[] = [
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $buy_now_qty
        ];
    }
} else {
    // CART MODE
    $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    if (!empty($cart_items) && !empty($address) && !empty($payment_method)) {
        // Calculate total amount
        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        try {
            // Insert order
            $stmt = $pdo->prepare("INSERT INTO orders (customer_id, address, payment_method, total) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customer_id, $address, $payment_method, $total_amount]);
            $order_id = $pdo->lastInsertId();

            // Insert order items
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cart_items as $item) {
                $stmt_item->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            }

            // Clear cart if it's cart mode
            if (!$product_id) {
                unset($_SESSION['cart']);
            }

            $success_message = "✅ Order placed successfully!";
            $total_paid = "💰 Total Paid: ₹" . number_format($total_amount, 2);
        } catch (PDOException $e) {
            $error_message = "❌ Error placing order: " . $e->getMessage();
        }
    } else {
        $error_message = "❌ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Multimart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .cart-summary h3 {
            margin-bottom: 15px;
        }
        .message-success {
            background: #d4edda;
            border-left: 5px solid #28a745;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: #155724;
        }
        .message-error {
            background: #f8d7da;
            border-left: 5px solid #dc3545;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>

        <?php if (!empty($success_message)): ?>
            <div class="message-success">
                <strong><?php echo $success_message; ?></strong><br>
                <?php echo $total_paid; ?>
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="message-error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (₹)</th>
                    <th>Total (₹)</th>
                </tr>
                <?php
                $total_price = 0;
                foreach ($cart_items as $item) {
                    $item_total = $item['price'] * $item['quantity'];
                    $total_price += $item_total;
                    echo "<tr>
                            <td>" . htmlspecialchars($item['product_name']) . "</td>
                            <td>{$item['quantity']}</td>
                            <td>₹" . number_format($item['price'], 2) . "</td>
                            <td>₹" . number_format($item_total, 2) . "</td>
                          </tr>";
                }
                ?>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Price:</strong></td>
                    <td><strong>₹<?php echo number_format($total_price, 2); ?></strong></td>
                </tr>
            </table>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="address">Shipping Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select id="payment_method" name="payment_method" required>
                    <!-- <option value="">Select Payment Method</option> -->
                    <!-- <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option> -->
                    <option value="cash_on_delivery">Cash on Delivery</option>
                </select>
            </div>
            <button type="submit">Place Order</button>
        </form>
    </div>

    <?php include './includes/footer.php'; ?>
</body>
</html>
