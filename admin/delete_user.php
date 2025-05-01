<?php
include './includes/header.php';
include '../config/database.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Choose the correct table (change 'customer' to 'admin' or 'delivery_person' if needed)
    $query = "DELETE FROM customer WHERE id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "User deleted successfully.";
            header("Location: manage_users.php"); // Redirect to the user management page
            exit();
        } else {
            echo "Error deleting user.";
        }
    } else {
        echo "Failed to prepare query.";
    }
} else {
    echo "Invalid request.";
}
?>
