<?php
require_once 'connection.php';

$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(empty($username) || empty($email) || empty($password)){
        $errors[] = "Semua field harus diisi.";
    }

    if(empty($errors)) {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result(); 
        if($result->fetch_assoc()){
            $errors[] = "Username atau email sudah terdaftar."; 
        }
        $stmt->close();

        if(empty($errors)) {
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $password);
            $stmt->execute();
            $stmt->close();

            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <body class="bg-light">
        <div class="container d-flex justify-content-center" style="min-height: 65vh; margin-top: 80px; align-items: center; display: flex;">
            <div class="card shadow-sm mb-3" style="width: 420px;">
                <div class="card-body">
                    <h4 class="card-title text-center mb-5">REGISTER</h4>
                    <form method="POST" action="">
                        <div class="mb-2">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                      <div class="mt-3 text-center">
                            <p>Sudah punya akun? <a href="login.php">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</body>
</html>