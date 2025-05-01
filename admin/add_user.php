<?php
include './includes/header.php';
include('../config/database.php');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        header("Location: manage_users.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Multimart</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <div class="main-content">
        <h1>Add New User</h1>
        <form action="add_user.php" method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" class="btn">Add User</button>
        </form>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>
