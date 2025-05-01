<?php
include './includes/header.php';
// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Include database
include '../config/database.php';

// Get user ID from URL
if (!isset($_GET['id'])) {
    die("❌ No user ID provided.");
}

$user_id = $_GET['id'];

// Fetch user data
try {
    $stmt = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("❌ User not found.");
    }
} catch (PDOException $e) {
    die("❌ Database error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    try {
        $update_stmt = $pdo->prepare("UPDATE customer SET username = ?, email = ?, address = ?, phone = ? WHERE id = ?");
        $update_stmt->execute([$username, $email, $address, $phone, $user_id]);

        header("Location: manage_users.php");
        exit();
    } catch (PDOException $e) {
        die("❌ Update failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <header>
        <h1>Edit User</h1>
        
    </header>
    
    <main>
        <h2>Edit User Information</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Address:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>">

            <label>Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">

            <button type="submit">Update</button>
        </form>
    </main>
</body>
</html>
