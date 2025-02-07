<?php
session_start();
require_once '../classes/database.php';
require_once '../actions/get_messages.php';
require_once '../actions/send_messages.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="icon" type="image/png" href="assets/images/hkuning.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="h-screen flex overflow-hidden">
    <div class="bg-yellow-400 w-16 min-h-screen p-4 hidden md:block">
        <nav class="space-y-4 mt-16">
            <a class="flex items-center ml-2 hover:text-gray-600" href="dashboard.php">
                <i class="fas fa-home"></i>
            </a>
            <a class="flex items-center ml-2 hover:text-gray-600" href="markethealz.php">
                <i class="fas fa-store"></i>
            </a>
            <a class="flex items-center ml-2 hover:text-gray-600" href="#">
                <i class="fas fa-envelope"></i>
            </a>
            <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="#">
                <i class="fas fa-bookmark"></i>
            </a>
            <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="profile.php">
                <i class="fas fa-user"></i>
            </a>
            <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="#">
                <i class="fas fa-cog"></i>
            </a>
        </nav>
    </div>

    <div class="flex flex-1">
        <div class="w-1/4 bg-yellow-300 p-4">
            <input type="text" id="search-user" placeholder="Search messages..." class="w-full px-4 py-2 rounded-lg border border-gray-400">
            <div id="user-list" class="mt-4">
                <?php
                $db = new Database();
                $conn = $db->getConnection();
                $sql = "SELECT username, profile_picture FROM users";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()): ?>
                    <div class="flex items-center mb-4 cursor-pointer user-item" data-username="<?= htmlspecialchars($row['username']); ?>">
                        <img src="<?= $row['profile_picture'] ? $row['profile_picture'] : 'assets/images/default-avatar.png'; ?>" class="w-10 h-10 bg-gray-400 rounded-full mr-4">
                        <div class="font-bold"><?= htmlspecialchars($row['username']); ?></div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="flex-1 flex flex-col">
            <div id="chat-header" class="flex items-center p-4 border-b border-gray-400">
                <img id="chat-profile" src="assets/images/default-avatar.png" class="w-10 h-10 bg-gray-400 rounded-full mr-4">
                <div class="font-bold" id="chat-username">Pilih pengguna...</div>
            </div>
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto">
                <div id="default-message" class="flex items-center justify-center h-full text-gray-600">
                    Kirim foto dan pesan pribadi ke teman atau grup
                </div>
            </div>
            <div class="p-4 bg-yellow-300 flex items-center">
                <input type="text" id="message-input" placeholder="Send Your Messages" class="flex-1 p-2 rounded-lg">
                <button id="send-button" class="mx-2">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const userItems = document.querySelectorAll(".user-item");
        const chatMessages = document.getElementById("chat-messages");
        const defaultMessage = document.getElementById("default-message");
        const chatUsernameElement = document.getElementById("chat-username");
        const messageInput = document.getElementById("message-input");
        const sendButton = document.getElementById("send-button");
        let selectedUsername = "";

        userItems.forEach(item => {
            item.addEventListener("click", function () {
                selectedUsername = this.getAttribute("data-username");
                chatUsernameElement.textContent = selectedUsername;
                defaultMessage.style.display = "none";
                fetchMessages(selectedUsername);
            });
        });

        function fetchMessages(receiver) {
            fetch(`actions/get_messages.php?action=get_messages&receiver=${receiver}`)
                .then(response => response.json())
                .then(data => {
                    chatMessages.innerHTML = "";
                    data.forEach(msg => {
                        const msgDiv = document.createElement("div");
                        msgDiv.className = `mb-4 flex ${msg.sender === "<?php echo $_SESSION['username']; ?>" ? 'justify-end' : 'justify-start'}`;
                        msgDiv.innerHTML = `<div class="bg-${msg.sender === "<?php echo $_SESSION['username']; ?>" ? 'yellow' : 'gray'}-300 p-2 rounded-lg max-w-xs">${msg.message}</div>`;
                        chatMessages.appendChild(msgDiv);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
        }

        sendButton.addEventListener("click", function () {
            sendMessage();
        });

        messageInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                sendMessage();
            }
        });

function sendMessage() {
    const message = messageInput.value.trim();
    if (message !== "" && selectedUsername !== "") {
        const formData = new FormData();
        formData.append("action", "send_message");
        formData.append("receiver", selectedUsername);
        formData.append("message", message);

        fetch("actions/send_messages.php", { // Sesuaikan path agar sesuai dengan pemisahan file
            method: "POST",
            body: formData,
        })
        .then(response => response.text()) // Ambil respons sebagai teks
        .then(text => {
            console.log("Server Response:", text); // Log respons ke konsol untuk debugging
            return JSON.parse(text); // Coba parse JSON
        })
        .then(data => {
            if (data.status === "success") {
                fetchMessages(selectedUsername); // Refresh pesan setelah mengirim
                messageInput.value = ""; // Kosongkan input
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error parsing JSON:", error));
    } else {
        alert("Masukkan pesan atau pilih pengguna.");
    }
}


    });
    </script>
</body>
</html>
