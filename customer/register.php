<?php 
session_name("customer_session");
session_start();
include '../config/database.php';

$username = $email = $password = $confirm_password = $address = $phone = "";
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : "";
    $address = isset($_POST['address']) ? trim($_POST['address']) : "";
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            try {
                $stmt = $pdo->prepare("SELECT id FROM customer WHERE email = ?");
                $stmt->execute([$email]);

                if ($stmt->rowCount() > 0) {
                    $error = "Email is already registered!";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO customer (username, email, password, address, phone) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$username, $email, $hashed_password, $address, $phone]);

                    $success = "Registration successful! <a href='login.php'>Login here</a>";
                }
            } catch (PDOException $e) {
                die("❌ Database error: " . $e->getMessage());
            }
        } else {
            $error = "Passwords do not match!";
        }
    } else {
        $error = "Please fill in all required fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }
        form {
            background: #fff;
            max-width: 400px;
            margin: 30px auto;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        .message {
            max-width: 400px;
            margin: 20px auto;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }
        .error {
            background-color: #fdd;
            color: #a00;
            border: 1px solid #f99;
        }
        .success {
            background-color: #e6ffed;
            color: #088;
            border: 1px solid #8f8;
        }
    </style>
</head>
<body>

<h2>Customer Registration</h2>

<?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>
<?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
    
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    
    <label>Password:</label>
    <input type="password" name="password" required>
    
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required>
    
    <label>Address:</label>
    <input type="text" name="address" value="<?= htmlspecialchars($address) ?>">
    
    <label>Phone:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">
    
    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

</body>
</html>
