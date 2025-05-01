<?php
// Always set session_name before session_start for admin session
if (session_status() === PHP_SESSION_NONE) {
    session_name("admin_session");
    session_start();
}

// Optional: Check if admin is logged in to show menu
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$menuItems = [
    'admin_index.php' => 'Dashboard',
    'manage_products.php' => 'Manage Products',
    'manage_users.php' => 'Manage Users',
    'manage_delivery_person.php' => 'Manage Delivery Persons',
    'manage_orders.php' => 'Manage Orders',
    'admin_logout.php' => 'Logout'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multimart Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
    <style>
        header {
            background-color: #333;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 26px;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 25px;
            padding: 10px 0;
            background-color: #222;
            margin: 0;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<header>
    <h1>Welcome to Multimart Admin</h1>
    <nav>
        <ul>
            <?php foreach ($menuItems as $file => $label): ?>
                <?php if ($file !== $currentPage): ?>
                    <li><a href="<?= $file ?>"><?= $label ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>
