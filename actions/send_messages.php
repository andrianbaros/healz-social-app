<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../classes/database.php';


$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {
    if (!isset($_SESSION['username'])) {
        echo json_encode(["status" => "error", "message" => "User not logged in"]);
        exit();
    }

    $sender = $_SESSION['username'];
    $receiver = $_POST['receiver'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($receiver) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "Receiver or message cannot be empty"]);
        exit();
    }

    $sql = "INSERT INTO messages (sender, receiver, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $sender, $receiver, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mengirim pesan", "error" => $stmt->error]);
    }
    exit();
}
?>
