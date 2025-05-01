<?php
// Always set session_name before session_start for delivery session
if (session_status() === PHP_SESSION_NONE) {
    session_name("delivery_session");
    session_start();
}

// Check if delivery person is logged in
if (!isset($_SESSION['delivery_id'])) {
    header("Location: delivery_login.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$menuItems = [
    // 'delivery_dashboard.php' => 'Dashboard',
    // 'assigned_orders.php' => 'Assigned Orders',
    // 'update_status.php' => 'Update Delivery Status',
    // 'delivery_profile.php' => 'My Profile',
    'delivery_logout.php' => 'Logout'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multimart Delivery Panel</title>
    <link rel="stylesheet" href="../assets/css/delivery_styles.css">
    <style>
        header {
            background-color: #007b5e;
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
            background-color: #00604c;
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
            background-color: #007b5e;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<header>
    <h1>Welcome Delivery Person - <?= htmlspecialchars($_SESSION['delivery_username']) ?></h1>
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
