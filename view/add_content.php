<?php
require_once '../control/connection.php';

$userData = getCurrentUser($conn);

// $sql = "SELECT c.*, u.username FROM contents c JOIN users u ON u.id = c.user_id ORDER BY c.created_at DESC";
// $statement = $conn->prepare($sql);
// $statement->execute();
// $result = $statement->get_result();
// $allContents = $result->fetch_all(MYSQLI_ASSOC);
// $statement->close();

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
                <a href="" class="nav-link active-menu" >Add Content</a>
            </li>
            <li class="nav-item">
                <a href="../control/switch_role.php" class="nav-link">Creator Mode</a>
            </li>
            <hr class="text-secondary">
            <li class="nav-item">
                <a href="logout.php" class="nav-link">Logout</a>
            </li>
        </ul>
    </div>

    <div class="topbar">
        <h5 class="text-light m-0">Add Content</h5>
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
    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-<?php echo ($_GET['status'] == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <h2 class="mb-4">Add Content</h2>
    <hr class="boerder-secondary">

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-dark">
                <div class="card-body">
                    <form method="POST" action="../control/add_content.php">
                        <input type="hidden" name="user_id" value="<?php echo $userData['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (Rp)</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Upload Content</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
        <div class="row">
            <div>
                <h3></h3>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
