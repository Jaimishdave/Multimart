<?php
include './includes/header.php';
// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Include the database connection
include '../config/database.php'; // Make sure this file has PDO connection as $pdo



try {
    // Fetch users
    $stmt = $pdo->query("SELECT id, username, email, address, phone FROM customer");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Database error: " . $e->getMessage());
}

// Handle DELETE request
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    try {
        $delete_stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
        $delete_stmt->execute([$delete_id]);

        // Redirect back to the page after deletion
        header('Location: manage_users.php');
        exit();
    } catch (PDOException $e) {
        die("❌ Deletion failed: " . $e->getMessage());
    }
}
?>

<!-- HTML Page Starts -->
<div class="content">
    <h1>Manage Users</h1>
    
    <main>
        <h2>User List</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['address']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>">✏️ Edit</a> | 
                        <a href="manage_users.php?delete=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">❌ Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</div>

<?php include('includes/footer.php'); ?>
