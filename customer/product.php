<?php
include('../config/database.php');
include('includes/header.php');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Invalid product ID.";
    exit();
}
?>

<div class="container">
    <div class="product-detail-box" style="max-width: 800px; margin: 40px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; gap: 20px; align-items: center;">
        <div class="product-image">
            <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 250px; border-radius: 8px;">
        </div>
        <div class="product-info">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>Price:</strong> ₹<?php echo number_format($product['price'], 2); ?></p>

            <!-- Quantity Selector -->
            <form action="cart.php" method="get" style="margin-top: 15px;">
                <input type="hidden" name="add" value="<?php echo $product['id']; ?>">
                <label for="quantity"><strong>Quantity:</strong></label>
                <input type="number" name="qty" id="quantity" value="1" min="1" max="10" style="width: 60px; padding: 5px; margin-left: 5px;">

                <!-- Add to Cart Button -->
                <button type="submit" class="btn" style="background-color: green; color: white; padding: 8px 14px; border: none; border-radius: 4px; margin-left: 10px;">Add to Cart</button>

                <!-- Wishlist Button -->
                <a href="wishlist.php?toggle=<?php echo $product['id']; ?>" class="btn" style="background-color: crimson; color: white; padding: 8px 14px; border-radius: 4px; text-decoration: none; margin-left: 10px;">❤️ Wishlist</a>

                <!-- Buy Now Button (Triggers JS redirect to checkout.php) -->
                <button type="button" onclick="buyNow(<?php echo $product['id']; ?>)" class="btn" style="background-color: #007bff; color: white; padding: 8px 14px; border: none; border-radius: 4px; margin-left: 10px;">Buy Now</button>
            </form>
        </div>
    </div>
</div>

<script>
// Buy Now Function to redirect with selected quantity
function buyNow(productId) {
    let qty = document.getElementById('quantity').value;
    if (qty < 1) qty = 1;
    if (qty > 10) qty = 10;
    window.location.href = 'checkout.php?product_id=' + productId + '&qty=' + qty;
}
</script>

<?php include('./includes/footer.php'); ?>
