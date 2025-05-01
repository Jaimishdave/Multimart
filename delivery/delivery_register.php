<?php
session_name("delivery_session");
session_start();
include '../config/database.php'; // PDO connection

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password === $confirm_password) {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM delivery_person WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "⚠️ Email already registered!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO delivery_person (username, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $phone, $hashed_password]);
                $success = "✅ Delivery Person registered successfully!";
            }
        } catch (PDOException $e) {
            $error = "❌ Error: " . $e->getMessage();
        }
    } else {
        $error = "❌ Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Person Register</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }
        .container {
            background: #fff;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        h2 { text-align: center; }
        .form-group {
            margin-bottom: 20px;
        }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #007BFF;
            color: #fff;
            border: none;
            font-size: 16px;
            border-radius: 6px;
        }
        p { text-align: center; margin-top: 15px; }
        .message { text-align: center; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delivery Person Register</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" name="phone" pattern="[0-9]{10,15}" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit">Register</button>
        </form>

        <?php
            if (!empty($success)) echo "<p class='message' style='color:green;'>$success</p>";
            if (!empty($error)) echo "<p class='message' style='color:red;'>$error</p>";
        ?>

        <p>Already have an account? <a href="delivery_login.php">Login here</a>.</p>
    </div>
</body>
</html>
