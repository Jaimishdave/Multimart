<?php
session_name("customer_session");
session_start();

// Include the database connection
include '../config/database.php'; // Ensure the correct path

// Initialize variables
$email = "";
$password = "";
$error = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";

    if (!empty($email) && !empty($password)) {
        try {
            // Check if customer exists
            $stmt = $pdo->prepare("SELECT id, username, password FROM customer WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // ✅ Login successful
                $_SESSION['customer_id'] = $user['id'];
                $_SESSION['customer_username'] = $user['username'];
                $_SESSION['user_role'] = 'customer';

                // Redirect to customer dashboard/homepage
                header("Location: customer_index.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } catch (PDOException $e) {
            die("❌ Database error: " . $e->getMessage());
        }
    } else {
        $error = "Please enter both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <link rel="stylesheet" href="../assets/css/customer_styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        form {
            max-width: 400px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

    <h2 style="text-align:center;">Customer Login</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p style="text-align:center; margin-top: 20px;">Don't have an account? <a href="register.php">Register here</a></p>

</body>
</html>
