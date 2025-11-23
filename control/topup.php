<?php
require '../control/connection.php';
if (!isUserLoggedIn()) {
    header("Location: ../view/login.php");
    exit();
}

$amount = $_POST['amount'];
if ($amount <= 0) {
    echo "<script>alert('Jumlah top-up tidak valid.'); window.history.back();</script>";
    exit();
}

$userId = $_SESSION['user_id'];
$sql = "UPDATE users SET balance = balance + ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $amount, $userId);
$stmt->execute();
$stmt->close();

echo "<script>alert('Top-up berhasil! Saldo Anda telah diperbarui.'); window.location.href = '../view/dashboard.php';</script>";
exit();
?>
