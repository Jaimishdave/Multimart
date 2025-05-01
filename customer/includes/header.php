<?php
// Set a unique session name for customers (optional, for separation from admin)
if (session_status() === PHP_SESSION_NONE) {
    session_name("customer_session");
    session_start();
}

$currentCustomerPage = basename($_SERVER['PHP_SELF']);
$customerMenuItems = [
    'customer_index.php' => 'Home',
    'shop.php' => 'Shop', // ✅ NEW LINE ADDED
    'cart.php' => 'Cart',
    'wishlist.php' => 'Wishlist'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multimart - Customer Panel</title>
    <link rel="stylesheet" href="../assets/css/customer_styles.css">
    <style>
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        nav ul li {
            display: inline-block;
            position: relative;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 150px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            right: 0;
        }

        .dropdown-content a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-weight: normal;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .profile-name {
            cursor: pointer;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
<h1><a href="customer_index.php" style="color: white; text-decoration: none;" onclick="window.location.href='customer_index.php'">Multimart</a></h1>

    <nav>
        <ul>
            <?php foreach ($customerMenuItems as $file => $label): ?>
                <li><a href="<?= $file ?>"><?= $label ?></a></li>
            <?php endforeach; ?>

            <?php if (isset($_SESSION['customer_id'])): ?>
                <li class="dropdown">
                    <span class="profile-name">👤 My Account</span>
                    <div class="dropdown-content">
                        <a href="profile.php">My Profile</a>
                        <a href="my_orders.php">My Orders</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
