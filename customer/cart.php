<?php
include './includes/header.php';
include '../config/database.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

try {
    // Handle item removal from cart
    if (isset($_POST['remove_item'])) {
        $item_id = $_POST['item_id'];

        $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE id = ? AND customer_id = ?");
        $stmt->execute([$item_id, $customer_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            if ($item['quantity'] > 1) {
                $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE id = ? AND customer_id = ?")
                    ->execute([$item_id, $customer_id]);
            } else {
                $pdo->prepare("DELETE FROM cart WHERE id = ? AND customer_id = ?")
                    ->execute([$item_id, $customer_id]);
            }
        }
        header("Location: cart.php");
        exit();
    }

    // Handle add to cart (increase quantity)
    if (isset($_GET['add'])) {
        $product_id = $_GET['add'];

        // Check if item exists in cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE customer_id = ? AND product_id = ?");
        $stmt->execute([$customer_id, $product_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE customer_id = ? AND product_id = ?")
                ->execute([$customer_id, $product_id]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $pdo->prepare("INSERT INTO cart (customer_id, product_id, product_name, price, quantity)
                               VALUES (?, ?, ?, ?, ?)")
                    ->execute([$customer_id, $product_id, $product['name'], $product['price'], 1]);
            }
        }
        header("Location: cart.php");
        exit();
    }

    // Handle quantity update via slider
    if (isset($_POST['update_qty'])) {
        $item_id = $_POST['item_id'];
        $updated_qty = max(1, min(10, (int)$_POST['updated_qty'])); // Clamp between 1 and 10

        $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND customer_id = ?")
            ->execute([$updated_qty, $item_id, $customer_id]);

        header("Location: cart.php");
        exit();
    }

    // Fetch cart items
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Total Price Calculation
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<style>
    input[type=range] {
        width: 120px;
        margin-right: 5px;
    }
    .container {
        padding: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ccc;
    }
    th {
        background: #f2f2f2;
    }
</style>

<div class="container">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price (Each)</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($cart_items as $item): 
                $productName = $item['product_name'];
                $priceEach = $item['price'];
                $quantity = $item['quantity'];
                $total = $priceEach * $quantity;
            ?>
            <tr>
                <td><?= htmlspecialchars($productName) ?></td>

                <!-- Quantity Slider + Update -->
                <td>
                    <form method="post" action="cart.php" style="display:inline;">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <input type="range" name="updated_qty" min="1" max="10" value="<?= $quantity ?>" 
                            oninput="document.getElementById('qtyDisplay<?= $item['id'] ?>').innerText = this.value">
                        <span id="qtyDisplay<?= $item['id'] ?>"><?= $quantity ?></span>
                        <button type="submit" name="update_qty">Update</button>
                    </form>
                </td>

                <td>₹<?= number_format($priceEach, 2) ?></td>
                <td>₹<?= number_format($total, 2) ?></td>

                <td>
                    <!-- Remove One -->
                    <form method="post" action="cart.php" style="display:inline;">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="remove_item">Remove One</button>
                    </form>

                    <!-- Add One -->
                    <!-- <form method="get" action="cart.php" style="display:inline;">
                        <input type="hidden" name="add" value="<?= $item['product_id'] ?>">
                        <button type="submit" name="add_item">Add One</button>
                    </form> -->

                    <!-- Buy Now -->
                    <form method="get" action="checkout.php" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                        <input type="hidden" name="qty" value="<?= $item['quantity'] ?>">
                        <button type="submit">Buy Now</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Total Price: ₹<?= number_format($total_price, 2) ?></h3>
    <?php endif; ?>
</div>

<?php include './includes/footer.php'; ?>
