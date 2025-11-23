<?php
require_once 'connection.php';

$userData = getCurrentUser($conn);

if (!$userData) {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $content_id = $_POST['content_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $user_id = $userData['id']; 

    if (empty($title) || empty($description) || !is_numeric($price) || $price < 0 || empty($content_id)) {
    $_SESSION['status'] = 'danger';
    $_SESSION['message'] = "All required fields must be filled and valid.";
    header("Location: ../view/edit_content.php?id=" . $content_id); 
    exit;
}

    $price = (int)$price;

    $sql = "UPDATE contents SET title = ?, description = ?, price = ?, created_at = NOW() WHERE id = ? AND user_id = ?";
    
    $statement = $conn->prepare($sql);
    
    $statement->bind_param("ssiii", $title, $description, $price, $content_id, $user_id);
    
    if ($statement->execute()) {
    $_SESSION['status'] = 'success'; 
    $_SESSION['message'] = "Content successfully updated!";
    header("Location: ../view/creator_dashboard.php"); 
    exit;
} else {
    $_SESSION['status'] = 'danger';
    $_SESSION['message'] = "Error updating content: " . $statement->error;
    header("Location: ../view/edit_content.php?id=" . $content_id); 
    exit;
}

    $statement->close();
} else {
    header("Location: ../view/creator_dashboard.php");
    exit;
}
?>