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
    <link rel="icon" type="image/png" href="../assets/images/hkuning.png">
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
            <a class="flex items-center ml-2 hover:text-gray-600" href="messages.php">
                <i class="fas fa-envelope"></i>
            </a>
            <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="profile.php">
                <i class="fas fa-user"></i>
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
                        <img src="<?= $row['profile_picture'] ? $row['profile_picture'] : '../assets/images/default-avatar.png'; ?>" class="w-10 h-10 bg-gray-400 rounded-full mr-4">
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
                
                <!-- Location Button -->
                <button id="send-location" class="mx-2">
                    <i class="fas fa-location-arrow"></i> Send Location
                </button>

                <button id="send-button" class="mx-2">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

 <!-- Include Leaflet.js (move this to the bottom to ensure it's loaded properly) -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const userItems = document.querySelectorAll(".user-item");
        const chatMessages = document.getElementById("chat-messages");
        const defaultMessage = document.getElementById("default-message");
        const chatUsernameElement = document.getElementById("chat-username");
        const messageInput = document.getElementById("message-input");
        const sendButton = document.getElementById("send-button");
        const sendLocationButton = document.getElementById("send-location");
        let selectedUsername = "";
        let userLatitude = null;
        let userLongitude = null;

        userItems.forEach(item => {
            item.addEventListener("click", function () {
                selectedUsername = this.getAttribute("data-username");
                chatUsernameElement.textContent = selectedUsername;
                defaultMessage.style.display = "none";
                fetchMessages(selectedUsername);
            });
        });

        function fetchMessages(receiver) {
            fetch(`../actions/get_messages.php?action=get_messages&receiver=${receiver}`)
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

sendLocationButton.addEventListener("click", function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;

            // Reverse geocoding to get the location name (city, country, etc.)
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${userLatitude}&lon=${userLongitude}&format=json`)
                .then(response => response.json())
                .then(data => {
                    const locationName = data.display_name; // Get the full address
                    const locationMessage = `Location: ${locationName} (Lat: ${userLatitude}, Lon: ${userLongitude})`;

                    // Display map in the sender's chat bubble
                    const mapHtml = generateMapHtml(userLatitude, userLongitude, locationName);

                    // Send message with location and map HTML
                    sendMessage(locationMessage, mapHtml);
                })
                .catch(error => console.error("Geocoding error:", error));
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
});

function generateMapHtml(lat, lon, locationName) {
    const mapContainerId = `map-${lat}-${lon}`;
    
    // Create the HTML structure for the map container
    const mapHtml = `
        <div class="map-container" style="width: 100%; height: 200px;">
            <div id="${mapContainerId}" style="height: 100%;"></div>
        </div>
    `;
    
    // Add the map container HTML to the message
    setTimeout(() => {
        initializeMap(mapContainerId, lat, lon, locationName);
    }, 0);

    return mapHtml;
}

function initializeMap(mapContainerId, lat, lon, locationName) {
    // Ensure the map is initialized after the container is rendered
    const mapContainer = document.getElementById(mapContainerId);
    if (!mapContainer) return; // Exit if the container isn't available

    const map = L.map(mapContainerId).setView([lat, lon], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([lat, lon]).addTo(map)
        .bindPopup(locationName)
        .openPopup();
}


function sendMessage(locationMessage = "", mapHtml = "") {
    const message = messageInput.value.trim() + locationMessage;
    if (message !== "" && selectedUsername !== "") {
        const formData = new FormData();
        formData.append("action", "send_message");
        formData.append("receiver", selectedUsername);
        formData.append("message", message + mapHtml); // Include map HTML in the message

        fetch("../actions/send_messages.php", { method: "POST", body: formData })
            .then(response => response.text()) // Get response as text
            .then(text => {
                console.log("Server Response:", text); // Log response for debugging
                return JSON.parse(text); // Try parsing JSON
            })
            .then(data => {
                if (data.status === "success") {
                    fetchMessages(selectedUsername); // Refresh messages after sending
                    messageInput.value = ""; // Clear input
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Error parsing JSON:", error));
    } else {
        alert("Please enter a message or select a user.");
    }
}

    });
    </script>
</body>
</html>