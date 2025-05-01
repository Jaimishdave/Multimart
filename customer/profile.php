<?php
include './includes/header.php';
include '../config/database.php';

$customer_id = $_SESSION['customer_id'] ?? null;
if (!$customer_id) {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
$stmt->execute([$customer_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Update profile
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    $update = $pdo->prepare("UPDATE customer SET name = ?, phone = ?, address = ? WHERE id = ?");
    if ($update->execute([$name, $phone, $address, $customer_id])) {
        $message = "Profile updated successfully.";
        $stmt->execute([$customer_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .profile-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .profile-header h2 {
            font-size: 24px;
            margin: 0;
        }
        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .update-button {
            width: 100%;
            padding: 10px;
            background: #ff9900;
            color: #fff;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }
        .update-button:hover {
            background: #e68a00;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-header">
           
            <h2>My Account</h2>
        </div>
        
        <?php if ($message): ?>
            <p class="message"> <?= htmlspecialchars($message); ?> </p>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" required><?= htmlspecialchars($user['address'] ?? '', ENT_QUOTES); ?></textarea>
            </div>
            <button type="submit" class="update-button">Update Profile</button>
        </form>
    </div>
</body>
</html>

<?php include './includes/footer.php'; ?>
