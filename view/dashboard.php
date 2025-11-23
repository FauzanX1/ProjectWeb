<?php
require_once '../control/connection.php';

$userData = getCurrentUser($conn);

$sql = "SELECT c.*, u.username FROM contents c JOIN users u ON u.id = c.user_id ORDER BY c.created_at DESC";
$statement = $conn->prepare($sql);
$statement->execute();
$result = $statement->get_result();
$allContents = $result->fetch_all(MYSQLI_ASSOC);
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
                <a href="riwayat.php" class="nav-link">Riwayat</a>
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
            <?php if ($userData): ?>
            <span class="me-3">Saldo: <strong>Rp <?php echo number_format($userData['balance'], 0, ',', '.'); ?></strong></span>
            <div class="dropdown d-inline">
                <a href="#" class="text-light dropdown-toggle" data-bs-toggle="dropdown">
                <?php echo $userData['username']; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="creator_dashboard.php">Creator Mode</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
            <?php else: ?>
            <a href="login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="content">
        <h2 class="mb-4">All Contents</h2>
        <hr class="boerder-secondary">
        
        <div class="row">
            <?php foreach ($allContents as $content): ?>
                <div class="col-md-4">
                    <div class="card card-dark mb-3">
                        <div class="card-body">
                            <h5><?php echo $content['title']; ?></h5>
                            <p>by <?php echo $content['username']; ?></p>
                            <p><?php echo $content['description']; ?></p>
                            <p><strong>Price: Rp <?php echo number_format($content['price'], 0, ',', '.'); ?></strong></p>

                            <?php if ($userData): ?>
                                <form method="post" action="../control/buy.php" class="d-inline">
                                    <input type="hidden" name="content_id" value="<?php echo $content['id']; ?>">
                                    <button type="submit" class="btn btn-success">Buy</button>
                                </form>
                                <form method="post" action="../control/buy.php" class="d-inline">
                                    <input type="hidden" name="content_id" value="<?php echo $content['id']; ?>">
                                    <input type="hidden" name="support" value="1">
                                    <button type="submit" class="btn btn-warning">Support Creator</button>
                                </form>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-primary">Login to Buy</a>
                            <?php endif; ?>    
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
