<?php
// session_start(); // Wajib untuk notifikasi sesi

require_once 'connection.php';

$userData = getCurrentUser($conn);

// 1. Cek apakah user sudah login
if (!$userData) {
    header("Location: ../public/login.php");
    exit();
}

// 2. Cek apakah data dikirimkan melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $content_id = $_POST['content_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $user_id = $userData['id']; // Ambil user_id dari sesi/userData untuk keamanan

    // Validasi dasar
    if (empty($title) || empty($description) || !is_numeric($price) || $price < 0 || empty($content_id)) {
    $_SESSION['status'] = 'danger';
    $_SESSION['message'] = "All required fields must be filled and valid.";
    header("Location: ../view/edit_content.php?id=" . $content_id); // <<< PASTIKAN MENGGUNAKAN ../view/
    exit;
}

    $price = (int)$price;

    // 3. Persiapkan dan jalankan statement UPDATE
    // Pastikan hanya konten milik user ini yang bisa diupdate (WHERE id = ? AND user_id = ?)
    $sql = "UPDATE contents SET title = ?, description = ?, price = ?, created_at = NOW() WHERE id = ? AND user_id = ?";
    
    $statement = $conn->prepare($sql);
    
    // Bind parameter: sssii (title, description, price (string/int), content_id, user_id)
    $statement->bind_param("ssiii", $title, $description, $price, $content_id, $user_id);
    
    if ($statement->execute()) {
    $_SESSION['status'] = 'success'; // <<< SUKSES UPDATE
    $_SESSION['message'] = "Content successfully updated!";
    header("Location: ../view/creator_dashboard.php"); // <<< KE DASHBOARD (view)
    exit;
} else {
    $_SESSION['status'] = 'danger';
    $_SESSION['message'] = "Error updating content: " . $statement->error;
    header("Location: ../view/edit_content.php?id=" . $content_id); // <<< KE EDIT (view)
    exit;
}

    $statement->close();
} else {
    // Jika tidak diakses melalui POST, redirect ke dashboard
    header("Location: ../view/creator_dashboard.php");
    exit;
}
?>