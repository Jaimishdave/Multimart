<?php
include './includes/header.php';
// Check if the user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Include the database connection file
include '../config/database.php';

// Ensure `$pdo` is available
if (!isset($pdo)) {
    die(" Database connection not established.");
}

// Fetch all products using PDO
try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die(" Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    
    <main>
    <h2>Product List</h2>
    <a href="add_product.php" class="add-button">➕ Add New Product</a>
    <table border="1">
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Stock</th>
                <!-- <th>Image</th> -->
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td><?= htmlspecialchars($product['stock']) ?></td>
                    <!-- <td><img src="<?= htmlspecialchars($product['image']) ?>" width="50"></td> -->
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a> | 
                        <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
