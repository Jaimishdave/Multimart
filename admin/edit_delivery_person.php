<?php
include './includes/header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Include database connection
include '../config/database.php';

// Get the delivery person ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Invalid ID provided.");
}

$delivery_id = $_GET['id'];

// Fetch current details
try {
    $stmt = $pdo->prepare("SELECT * FROM delivery_person WHERE id = ?");
    $stmt->execute([$delivery_id]);
    $person = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$person) {
        die("❌ Delivery person not found.");
    }
} catch (PDOException $e) {
    die("❌ Error fetching data: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    try {
        $update = $pdo->prepare("UPDATE delivery_person SET username = ?, email = ?, phone = ? WHERE id = ?");
        $update->execute([$username, $email, $phone, $delivery_id]);

        // Redirect after update
        header("Location: manage_delivery_person.php");
        exit();
    } catch (PDOException $e) {
        die("❌ Update failed: " . $e->getMessage());
    }
}
?>

<!-- HTML Form Starts -->
<div class="content">
    <h1>Edit Delivery Person</h1>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($person['username']) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($person['email']) ?>" required><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone" value="<?= htmlspecialchars($person['phone']) ?>"><br><br>

        <button type="submit">Update</button>
        <a href="manage_delivery_person.php">Cancel</a>
    </form>
</div>

<?php include('./includes/footer.php'); ?>
