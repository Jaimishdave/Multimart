<?php
session_name("delivery_session");
session_start();
include '../config/database.php'; // Make sure this file uses PDO and is correct

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($email) && !empty($password)) {
        try {
            // Fetch delivery person by email
            $stmt = $pdo->prepare("SELECT * FROM delivery_person WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $delivery_person = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($delivery_person) {
                // Verify hashed password
                if (password_verify($password, $delivery_person['password'])) {
                    // Set session variables
                    $_SESSION['delivery_id'] = $delivery_person['id'];
                    $_SESSION['delivery_email'] = $delivery_person['email'];
                    $_SESSION['delivery_username'] = $delivery_person['username'];
                    $_SESSION['user_role'] = 'delivery_person';

                    // Redirect to delivery dashboard
                    header("Location: delivery_index.php");
                    exit();
                } else {
                    $error = "❌ Invalid password!";
                }
            } else {
                $error = "❌ Email not found!";
            }
        } catch (PDOException $e) {
            $error = "❌ Database error: " . $e->getMessage();
        }
    } else {
        $error = "⚠️ Email and password are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Person Login</title>
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
        input[type="email"], input[type="password"] {
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
        <h2>Delivery Person Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <?php if (!empty($error)) { echo "<p class='message' style='color:red;'>$error</p>"; } ?>

        <p>Don't have an account? <a href="delivery_register.php">Register here</a>.</p>
    </div>
</body>
</html>
