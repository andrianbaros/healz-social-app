<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../classes/database.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['action']) && $_GET['action'] === 'get_messages' && isset($_GET['receiver'])) {
    $receiver = $_GET['receiver'];
    $sender = $_SESSION['username'];

    $sql = "SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY created_at ASC";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Error dalam query: " . $conn->error);
    }
    
    $stmt->bind_param("ssss", $sender, $receiver, $receiver, $sender);
    $stmt->execute();
    
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages);
    exit();
}
?>
