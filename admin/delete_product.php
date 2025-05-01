<?php
include './includes/header.php';
// Check if the user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Include the database connection file (make sure this sets up `$pdo`)
include '../config/database.php';

if (!isset($pdo)) {
    die("❌ Database connection not established.");
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
    die("❌ Error: Product ID is missing.");
}

$product_id = $_GET['id'];

try {
    // Prepare and execute the delete query using PDO
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $product_id]);

    // Redirect back to the manage products page
    header('Location: manage_products.php');
    exit();
} catch (PDOException $e) {
    die("❌ Error deleting product: " . $e->getMessage());
}
