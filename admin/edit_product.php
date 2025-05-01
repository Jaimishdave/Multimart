<?php
include './includes/header.php';
include('../config/database.php');

// ✅ Session check using 'user_role'
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
    die("Error: Product ID is missing.");
}

$product_id = $_GET['id'];

// Fetch product details using PDO
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        die("Error: Product not found.");
    }
} catch (PDOException $e) {
    die("Error fetching product: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];

    try {
        $update_stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, category=?, stock=? WHERE id=?");
        $update_stmt->execute([$name, $description, $price, $category, $stock, $product_id]);

        // Redirect on success
        header("Location: manage_products.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating product: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <h1>Edit Product</h1>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label>Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>

        <button type="submit">Update Product</button>
    </form>
</body>
</html>
