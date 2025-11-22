<?php 
require_once 'connection.php';
$userId = $_SESSION['user_id'];
$sql = "UPDATE users SET role = 'creator' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
header("Location: ../view/creator_dashboard.php");
exit();
?>