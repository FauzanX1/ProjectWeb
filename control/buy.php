<?php
require '../control/connection.php';
if (!isUserLoggedIn()) {
    header("Location: ../view/login.php");
    exit();
}   

$userId = $_SESSION['user_id'];
$contentId = $_POST['content_id'];

$isSupported = isset($_POST['support']);

//Ambil harga konten
$sql = "SELECT price FROM contents WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contentId);
$stmt->execute();
$result = $stmt->get_result();
$content = $result->fetch_assoc();
if (!$content) {
    echo "<script>alert('Konten tidak ditemukan.'); window.history.back();</script>";
    exit();
}

// Harga Support
$price = $content['price'];
$amount = $isSupported ? $price * 0.5 : $price;

//Ambil saldo user
$sql = "SELECT balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc()['balance'];
$stmt->close();
if ($user < $amount) {
    echo "<script>alert('Saldo tidak cukup. Silakan top-up terlebih dahulu.'); window.history.back();</script>";
    exit();
}

//Kurangi saldo user
$sql = "UPDATE users SET balance = balance - ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $amount, $userId);
$stmt->execute();
$stmt->close();

//Tambahkan saldo creator
$sql = "UPDATE usesrs SET balance = balance + ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $amount, $contentId);
$stmt->execute();
$stmt->close();
echo "<script>alert('Pembelian berhasil!'); window.location.href = '../view/dashboard.php';</script>";
exit();
?>