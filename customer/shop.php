<?php
include './includes/header.php';
include '../config/database.php'; // Ensure $pdo is defined here

// Fetch categories
$categories_stmt = $pdo->query("SELECT * FROM categories");
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Category filter logic
$category_id = isset($_GET['category']) && is_numeric($_GET['category']) ? intval($_GET['category']) : null;

if ($category_id) {
    $products_stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
    $products_stmt->execute([$category_id]);
} else {
    $products_stmt = $pdo->query("SELECT * FROM products");
}

$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <style>
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 equal-width columns*/
        gap: 30px 25px;
        justify-content: center;
        margin: 30px auto;
        padding: 10px 20px;
        max-width: 1300px;
    }

    .product-card {
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        height: 350px;
        transition: transform 0.3s ease;
        text-align: center; /* ✅ Center everything inside card */
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-card img {
        width: 100%;
        height: 160px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .product-title {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 6px;
    }

    .product-desc {
        font-size: 13px;
        color: #555;
        margin-bottom: 10px;
        height: 38px;
        overflow: hidden;
    }

    .product-price {
        font-size: 15px;
        color: #333;
        margin-bottom: 10px;
        font-weight: bold;
        width: 100%;
        text-align: center; /* ✅ Center the price text */
    }

    .product-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-cart, .btn-wishlist {
        padding: 6px 12px;
        font-size: 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-cart {
        background-color: #007bff;
        color: white;
    }

    .btn-wishlist {
        background-color: #dc3545;
        color: white;
    }

    .btn-cart:hover {
        background-color: #0056b3;
    }

    .btn-wishlist:hover {
        background-color: #b02a37;
    }

    /* Responsive */
    @media screen and (max-width: 1200px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media screen and (max-width: 900px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media screen and (max-width: 600px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }
</style>



</head>
<body>

<div class="container">
    <h2>Shop</h2>

    <!-- Category Filter -->
    <div class="filter-container">
        <form method="GET">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Product Grid -->
    <!-- Product Grid -->
<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <a href="product.php?id=<?= $product['id']; ?>" style="text-decoration: none; color: inherit; width: 100%;">
                    <img src="../<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                    <div class="product-title"><?= htmlspecialchars($product['name']); ?></div>
                    <div class="product-desc"><?= htmlspecialchars(substr($product['description'], 0, 60)); ?>...</div>
                    <div class="product-price">Price: ₹<?= number_format($product['price'], 2); ?></div>
                </a>
                <div class="product-actions">
                    <a href="cart.php?add=<?= $product['id']; ?>" class="btn-cart">Add to Cart</a>
                    <a href="wishlist.php?toggle=<?= $product['id']; ?>" class="btn-wishlist">❤️</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


</body>
</html>
<?php include './includes/footer.php'; ?>