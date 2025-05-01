<?php
session_name("delivery_session");
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['delivery_id'])) {
    header("Location: delivery_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6f2ff;
            padding: 30px;
        }
        .container {
            background: #fff;
            max-width: 700px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .welcome {
            font-size: 18px;
            margin-bottom: 20px;
            text-align: center;
        }
        .menu {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .menu a {
            display: block;
            text-decoration: none;
            background: #007bff;
            color: #fff;
            padding: 12px;
            text-align: center;
            border-radius: 6px;
            font-size: 16px;
        }
        .menu a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, Delivery Person</h2>
        <p class="welcome">Hello, <strong><?php echo $_SESSION['delivery_username']; ?></strong>!</p>

        <div class="menu">
        <a href="manage_orders.php">📦Manage Orders</a>
            <!-- <a href="assigned_orders.php">📦 View Assigned Orders</a>
            <a href="update_status.php">✅ Update Delivery Status</a>
            <a href="delivery_profile.php">👤 My Profile</a> -->
            <a href="delivery_logout.php" style="background: crimson;">🚪 Logout</a>
        </div>
    </div>
</body>
</html>
