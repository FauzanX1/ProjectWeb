<?php
require_once 'connection.php'; 
getCurrentUser($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $user_id = $_POST['user_id'] ?? 0; 
    
    if (empty($title) || empty($description) || !is_numeric($price) || $price < 0 || empty($user_id)) {
        $message = "All fields are required and price must be a valid number.";
        header("Location: ../view/add_content.php?status=danger&message=" . urlencode($message));
        exit;
    }

    $price = (int)$price;

    $sql = "INSERT INTO contents (user_id, title, description, price, created_at) VALUES (?, ?, ?, ?, NOW())";
    
    $statement = $conn->prepare($sql);
    
    $statement->bind_param("issi", $user_id, $title, $description, $price);
    
    if ($statement->execute()) {
        $message = "Content uploaded successfully!";
        header("Location: ../view/dashboard.php?status=success&message=" . urlencode($message));
        exit;
    } else {
        $message = "Error uploading content: " . $statement->error;
        header("Location: ../view/dashboard.php?status=danger&message=" . urlencode($message));
        exit;
    }

    $statement->close();
} else {
    header("Location: ../public/dashboard.php");
    exit;
}
?>