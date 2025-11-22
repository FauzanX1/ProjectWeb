<!-- untuk koneksi mysql dengan PHP -->
<?php
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser($conn) {
    if (!isUserLoggedIn()) {
        return null;
    }

    $userId = $_SESSION['user_id'];
    $sql = "SELECT id, username, role, balance FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    return $user;
}

?>