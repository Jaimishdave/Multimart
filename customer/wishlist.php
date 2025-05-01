<?php

include './includes/header.php';
include '../config/database.php'; // ✅ Make sure $pdo is defined here

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Wishlist toggle logic (add/remove product)
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $product_id = intval($_GET['toggle']);

    // Check if product is already in wishlist
    $check_stmt = $pdo->prepare("SELECT id FROM wishlist WHERE customer_id = ? AND product_id = ?");
    $check_stmt->execute([$customer_id, $product_id]);

    if ($check_stmt->rowCount() > 0) {
        // Product exists in wishlist — remove it
        $remove_stmt = $pdo->prepare("DELETE FROM wishlist WHERE customer_id = ? AND product_id = ?");
        $remove_stmt->execute([$customer_id, $product_id]);
        $_SESSION['message'] = "Product removed from wishlist.";
    } else {
        // Product not in wishlist — fetch product details
        $product_stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
        $product_stmt->execute([$product_id]);
        $product = $product_stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Add to wishlist
            $insert_stmt = $pdo->prepare("INSERT INTO wishlist (customer_id, product_id, product_name, price) VALUES (?, ?, ?, ?)");
            $insert_stmt->execute([$customer_id, $product_id, $product['name'], $product['price']]);
            $_SESSION['message'] = "Product added to wishlist.";
        } else {
            $_SESSION['message'] = "Product not found!";
        }
    }

    // Redirect back
    header("Location: wishlist.php");
    exit();
}

// Fetch all wishlist items
$wishlist_stmt = $pdo->prepare("SELECT * FROM wishlist WHERE customer_id = ?");
$wishlist_stmt->execute([$customer_id]);
$wishlist_items = $wishlist_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Wishlist</title>
    <style>
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #444;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .btn {
            padding: 6px 12px;
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }
        .btn:hover {
            background-color: #b02a37;
        }
        .message {
            text-align: center;
            color: green;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Wishlist</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <?php if (empty($wishlist_items)): ?>
        <p>Your wishlist is empty.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price (₹)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($wishlist_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <a href="wishlist.php?toggle=<?php echo $item['product_id']; ?>" class="btn">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include './includes/footer.php'; ?>
</body>
</html>
