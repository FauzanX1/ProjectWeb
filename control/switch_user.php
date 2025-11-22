<?php 
require_once 'connection.php';
if(!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$sql = "UPDATE users SET role = 'user' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
header("Location: ../view/user_dashboard.php");
exit();
?>