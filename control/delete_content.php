<?php
require_once 'connection.php';
getCurrentUser($conn);

$userData = getCurrentUser($conn);

// 1. Cek apakah user sudah login
if (!$userData) {
    header("Location: ../view/login.php");
    exit();
}

// 2. Cek apakah content ID dikirimkan melalui GET request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['status'] = 'danger'; // Gunakan Sesi
    $_SESSION['message'] = "Content ID is missing."; // Gunakan Sesi
    header("Location: ../view/creator_dashboard.php"); // Redirect tanpa parameter URL
    exit();
}

$content_id = $_GET['id'];
$user_id = $userData['id'];

// 3. Verifikasi kepemilikan konten sebelum menghapus (PENTING!)
// Hanya izinkan user menghapus konten miliknya sendiri
$sql_check = "SELECT user_id FROM contents WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $content_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    $_SESSION['status'] = 'danger'; // Gunakan Sesi
    $_SESSION['message'] = "Content not found."; // Gunakan Sesi
    header("Location: ../view/creator_dashboard.php"); // Redirect tanpa parameter URL
    exit();
}

$content_data = $result_check->fetch_assoc();
$stmt_check->close();

if ($content_data['user_id'] != $user_id) {
    $_SESSION['status'] = 'danger'; // Gunakan Sesi
    $_SESSION['message'] = "You are not authorized to delete this content."; // Gunakan Sesi
    header("Location: ../view/creator_dashboard.php"); // Redirect tanpa parameter URL
    exit();
}

// 4. Proses Penghapusan
$sql_delete = "DELETE FROM contents WHERE id = ? AND user_id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("ii", $content_id, $user_id);

if ($stmt_delete->execute()) {
    $_SESSION['status'] = 'success'; // <<< SUKSES DELETE
    $_SESSION['message'] = "Content deleted successfully!";
    header("Location: ../view/creator_dashboard.php"); // Redirect tanpa parameter URL
    exit();
} else {
    $_SESSION['status'] = 'danger';
    $_SESSION['message'] = "Error deleting content: " . $stmt_delete->error;
    header("Location: ../view/creator_dashboard.php"); // Redirect tanpa parameter URL
    exit();
}

$stmt_delete->close();
$conn->close();

?>