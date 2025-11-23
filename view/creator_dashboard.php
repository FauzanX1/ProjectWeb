<?php
require_once '../control/connection.php';

$userData = getCurrentUser($conn);

if (!$userData) {
    header("Location: login.php");
    exit();
}

if ($userData['role'] !== 'creator') {
    header("Location: ../control/switch_role.php");
    exit();
}

$sql = "SELECT * FROM contents WHERE user_id = ? ORDER BY created_at DESC";
$statement = $conn->prepare($sql);
$statement->bind_param("i", $userData['id']);
$statement->execute();
$result = $statement->get_result();
$userContents = $result->fetch_all(MYSQLI_ASSOC);
$statement->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - MiniTrakteer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background-color: #1d1f23;
        color: #eee;
    }
    /* Sidebar */
    .sidebar {
        width: 240px;
        height: 100vh;
        background-color: #151619;
        position: fixed;
        left: 0;
        top: 0;
        padding: 20px;
        overflow-y: auto;
        border-right: 1px solid #2a2c31;
    }
    .sidebar .nav-link {
        color: #bdbdbd;
        margin-bottom: 10px;
        font-size: 15px;
    }
    .sidebar .nav-link:hover {
        background-color: #2a2c31;
        border-radius: 6px;
        color: white;
    }
    .active-menu {
        background-color: #b11226;
        color: white !important;
        border-radius: 6px;
    }

    /* Topbar */
    .topbar {
        background-color: #151619;
        border-bottom: 1px solid #2a2c31;
        padding: 12px 30px;
        margin-left: 240px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 999;
    }
    .topbar input {
        background-color: #2a2c31;
        border: none;
        color: #ddd;
    }

    /* Content area */
    .content {
        margin-left: 260px;
        padding: 30px;
    }

    .card-dark {
        background-color: #222427;
        border: 1px solid #333;
        color: white;
    }
</style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-light mb-4">MiniTrakteer</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link">Discover</a>
            </li>
            <li class="nav-item">
                <a href="../control/switch_user.php" class="nav-link active-menu">User Mode</a>
            </li>
            <hr class="text-secondary">
            <li class="nav-item">
                <a href="logout.php" class="nav-link">Logout</a>
            </li>
        </ul>
    </div>

    <div class="topbar">
        <h5 class="text-light m-0">Creator Dashboard</h5>
        <div>
            <span class="me-3">Saldo:
                <strong>Rp <?= number_format($userData['balance'], 0, ',', '.') ?></strong>
            </span>
            
            <div class="dropdown d-inline">
                <a href="#" class="text-light dropdown-toggle" data-bs-toggle="dropdown">
                <?php echo $userData['username']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="../control/switch_user.php">Kembali ke User Mode</a></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    </div>
    
    <div class="content">
        <h3 class="mb-4">Your Contents</h3>
        <div class="card card-dark p-4 mb-4">
            <h4 class="mb-3">Top UP Saldo</h4>
            <form class="row g-2" method="POST" action="topup.php">
                <div class="col-md-4">
                    <input name="amount" type="number" step="0.01" class="form-control" placeholder="Masukan jumlah top up">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Top UP</button>
                </div>
            </form>
        </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Konten KU</h3>
        <a href="add_content.php" class="btn btn-success">Tambah Konten</a>
    </div>

    <div class="row">
        <?php if(empty($creatorContents)): ?>
            <p>Kamu belum memiliki konten.</p>
        <?php else: ?>
            
            <?php foreach ($creatorContents as $content): ?>
                <div class="col-md-4">
                    <div class="card card-dark mb-3">
                        <div class="card-body">
                            <h5><?php echo $content['title']; ?></h5>
                            <p><?php echo $content['description']; ?></p>
                            <p><strong>Price: Rp <?php echo number_format($content['price'], 0, ',', '.'); ?></strong></p>
                            <a href="edit_content.php?id=<?php echo $content['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_content.php?id=<?php echo $content['id']; ?>" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus konten ini?');">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
</body>