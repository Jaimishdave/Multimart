<?php
include './includes/header.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Include the database connection file
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $image = $_POST['image']; // Assuming image URL is provided

    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, stock, image) VALUES (:name, :description, :price, :category, :stock, :image)");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'stock' => $stock,
            'image' => $image
        ]);

        // Redirect on success
        header('Location: manage_products.php');
        exit();

    } catch (PDOException $e) {
        die("❌ Error adding product: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <header>
        <h1>Add Product</h1>
       
    </header>
    <main>
        <h2>Add New Product</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" required>
            </div>
            <div class="form-group">
                <label for="image">Image URL:</label>
                <input type="text" id="image" name="image" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </main>
</body>
</html>
