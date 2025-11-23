<?php
require_once '../control/connection.php';

if(!isUserLoggedIn()) {
    header("Location: ../view/login.php");
    exit();
}
    
$userId = $_SESSION['user_id'];

$sql = "SELECT p.*, c.title AS content_title FROM purchases p
        LEFT JOIN contents c ON p.content_id = c.id
        WHERE p.buyer_id = ? ORDER BY p.created_at DESC";

$statement = $conn->prepare($sql);
$statement->bind_param("i", $userId);
$statement->execute();
$logs = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
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
                <a href="dashboard.php" class="nav-link active-menu" >Discover</a>
            </li>
            <li class="nav-item">
                <a href="../control/switch_role.php" class="nav-link">Creator Mode</a>
            </li>
            <li class="nav-item">
                <a href="riwayat.php" class="nav-link active-menu">Riwayat</a>
            </li>
            <hr class="text-secondary">
            <li class="nav-item">
                <a href="logout.php" class="nav-link">Logout</a>
            </li>
        </ul>
    </div>

    <div class="topbar">
        <h5 class="text-light m-0">Discover</h5>
        <div>
        <?php if(isUserLoggedIn()): ?>
            <?php else: ?>
            <a href="login.php" class="btn btn-primary">Login</a>
        <?php endif; ?>
        </div>
    </div>

    <div class="content">
    <h3 class="mb-4 text-center">Riwayat Pembelian</h3>
        <?php if(empty($logs)): ?>
            <p class="text-center">Belum ada riwayat pembelian.</p>
        <?php else: ?>
            <table class="table table-dark table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Konten</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $t): ?>
                        <tr>
                            <td><?= $t['created_at'] ?></td>
                            <td>
                                <?php if ($t['type'] === 'buy') : ?>
                                    <span class="badge bg-success">BUY</span>
                                <?php elseif ($t['type'] === 'support') : ?>
                                    <span class="badge bg-primary">SUPPORT</span>
                                <?php elseif ($t['type'] === 'topup') : ?>
                                    <span class="badge bg-warning text-dark">TOP UP</span>
                                <?php endif; ?>        
                            </td>
                            <td>
                                <?= $t['content_title'] ? $t['content_title'] : '-' ?>
                            </td>
                            <td>Rp <?= number_format($t['amount'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>
