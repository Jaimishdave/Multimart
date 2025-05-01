<?php
include './includes/header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Include database connection
include '../config/database.php'; // Ensure $pdo is initialized properly

// Fetch delivery persons
try {
    $stmt = $pdo->query("SELECT id, username, email, phone FROM delivery_person");
    $deliveryPersons = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Database error: " . $e->getMessage());
}

// Handle DELETE request
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    try {
        $delete_stmt = $pdo->prepare("DELETE FROM delivery_person WHERE id = ?");
        $delete_stmt->execute([$delete_id]);

        // Redirect back after deletion
        header('Location: manage_delivery_person.php');
        exit();
    } catch (PDOException $e) {
        die("❌ Deletion failed: " . $e->getMessage());
    }
}
?>

<!-- HTML Page Starts -->
<div class="content">
    <h1>Manage Delivery Person</h1>

    <main>
        <h2>Delivery Person List</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($deliveryPersons as $person): ?>
                <tr>
                    <td><?= htmlspecialchars($person['id']) ?></td>
                    <td><?= htmlspecialchars($person['username']) ?></td>
                    <td><?= htmlspecialchars($person['email']) ?></td>
                    <td><?= htmlspecialchars($person['phone']) ?></td>
                    <td>
                        <a href="edit_delivery_person.php?id=<?= $person['id'] ?>">✏️ Edit</a> |
                        <a href="manage_delivery_person.php?delete=<?= $person['id'] ?>" onclick="return confirm('Are you sure you want to delete this delivery person?')">❌ Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</div>

<?php include('includes/footer.php'); ?>
