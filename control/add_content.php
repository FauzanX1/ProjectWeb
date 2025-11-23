<?php
// Pastikan file koneksi dan fungsi user tersedia
require_once 'connection.php'; 
// require_once 'auth.php'; // Ganti dengan file yang berisi fungsi autentikasi/user Anda (misal: getCurrentUser)
getCurrentUser($conn);

// Cek apakah data dikirimkan melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $user_id = $_POST['user_id'] ?? 0; // Menggunakan hidden field user_id dari form
    
    // Validasi dasar
    if (empty($title) || empty($description) || !is_numeric($price) || $price < 0 || empty($user_id)) {
        $message = "All fields are required and price must be a valid number.";
        header("Location: ../view/add_content.php?status=danger&message=" . urlencode($message));
        exit;
    }

    // Ubah harga menjadi integer jika perlu (tergantung skema DB)
    $price = (int)$price;

    // Persiapkan statement SQL untuk memasukkan data
    $sql = "INSERT INTO contents (user_id, title, description, price, created_at) VALUES (?, ?, ?, ?, NOW())";
    
    // Menggunakan prepared statement untuk keamanan (menghindari SQL injection)
    $statement = $conn->prepare($sql);
    
    // Bind parameter: i=integer, s=string, d=double. user_id(int), title(string), description(string), price(int)
    $statement->bind_param("issi", $user_id, $title, $description, $price);
    
    // Eksekusi statement
    if ($statement->execute()) {
        $message = "Content uploaded successfully!";
        // Redirect kembali ke halaman dashboard dengan pesan sukses
        header("Location: ../view/dashboard.php?status=success&message=" . urlencode($message));
        exit;
    } else {
        $message = "Error uploading content: " . $statement->error;
        // Redirect kembali dengan pesan error
        header("Location: ../view/dashboard.php?status=danger&message=" . urlencode($message));
        exit;
    }

    // Tutup statement
    $statement->close();
} else {
    // Jika tidak diakses melalui POST, redirect kembali
    header("Location: ../public/dashboard.php");
    exit;
}
?>