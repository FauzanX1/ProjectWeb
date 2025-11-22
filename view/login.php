<?php
require_once 'connection.php';

$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(empty($username) || empty($password)){
        $errors[] = "Semua field harus diisi.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $user = $result->fetch_assoc();
        if($user){
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Username atau password salah.";
        }
        $stmt->close();
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
            <div class="card shadow-sm mb-3" style="width: 380px;">
                <div class="card-body">
                    <h4 class="card-title text-center mb-5">LOGIN</h4>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                      <div class="mt-3 text-center">
                            <p>Belum punya akun? <a href="register.php">Daftar</a></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</body>
</html>