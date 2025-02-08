<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../classes/database.php';
session_start();

$db = new Database();
$conn = $db->conn;

class Profile {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getUserProfile($user_id) {
        $sql = "SELECT username, profile_picture, latitude, longitude FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function updateUserLocation($user_id, $latitude, $longitude) {
        $sql = "UPDATE users SET latitude = ?, longitude = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ddi", $latitude, $longitude, $user_id);
        return $stmt->execute();
    }
    
    public function getPosts($user_id) {
        $sql = "SELECT posts.id, posts.content, posts.image, posts.created_at, users.username, users.profile_picture 
                FROM posts 
                INNER JOIN users ON posts.user_id = users.id 
                WHERE posts.user_id = ? 
                ORDER BY posts.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    
}

$user_id = $_SESSION['user_id'] ?? null;
$profile = new Profile($conn);
$userData = $user_id ? $profile->getUserProfile($user_id) : null;
$posts = $user_id ? $profile->getPosts($user_id) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    if ($user_id) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $profile->updateUserLocation($user_id, $latitude, $longitude);
        $userData = $profile->getUserProfile($user_id);
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profile Page</title>
    <link rel="icon" type="image/png" href="../assets/images/hkuning.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-yellow-500 p-4 text-white text-center text-2xl font-bold">
        User Profile
    </header>

    <div class="mx-auto bg-white shadow-md">
        <div class="flex p-6">
            <div class="w-1/4 text-center">
                <?php 
                    $profilePic = !empty($userData['profile_picture']) ? $userData['profile_picture'] : null;
                    $username = $userData['username'] ?? 'Guest';
                ?>
                <?php if ($profilePic): ?>
                    <img alt="Profile image" class="w-24 h-24 rounded-full object-cover mx-auto" src="<?= htmlspecialchars($profilePic) ?>" />
                <?php endif; ?>
                
                <h2 class="mt-4 text-xl font-semibold"><?= htmlspecialchars($username) ?></h2>
                <p class="text-gray-600">Freelance Graphic Designer</p>
                <a href='joinascreator.php'><button class="mt-10 px-16 py-2 bg-yellow-500 text-white rounded-full">Join As Creator</button></a>
                <a href="editprofile.php"><button class="mt-2 px-20 py-2 bg-yellow-500 text-white rounded-full">Edit Profile</button></a>
                
                <!-- Leaflet Map -->
                <div id="map" class="w-full h-48 mt-6 rounded-lg shadow"></div>

                <!-- Update Location Button -->
                    <button id="update-location" class="mt-4 px-16 py-2 bg-yellow-500 text-white rounded-full">
    Update Location by GPS
</button>

            </div>

            <div class="w-3/4 pl-6">
                <div class="flex space-x-4 mb-4">
                    <a href="dashboard.php"><button class="px-4 py-2 bg-yellow-500 text-white rounded-full">Home</button></a>
                    <button class="px-4 py-2 bg-yellow-500 text-white rounded-full">Post</button>
                </div>

                <!-- Daftar Postingan Pengguna -->
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="bg-gray-100 p-4 rounded-lg mb-4">
                            <div class="flex items-start">
                                <?php 
                                    $postProfilePic = !empty($post['profile_picture']) ? $post['profile_picture'] : null;
                                    $postImage = !empty($post['image']) ? $post['image'] : null;
                                ?>
                                 
                                <?php if ($postProfilePic): ?>
                                    <img alt="User profile" class="w-10 h-10 rounded-full object-cover mr-4" src="<?= htmlspecialchars($postProfilePic) ?>" />
                                <?php endif; ?>

                                <div class="flex-1">
                                    <h3 class="font-semibold"><?= htmlspecialchars($post['username']) ?></h3>
                                    <p class="text-gray-700 mt-2"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                                    <?php if ($postImage): ?>
                                        <img alt="Post image" class="w-full h-64 rounded-lg object-cover mt-2" src="<?= htmlspecialchars($postImage) ?>" />
                                    <?php endif; ?>

                                    <div class="flex items-center mt-4 text-gray-500">
                                        <button class="flex items-center mr-4"><i class="far fa-heart mr-1"></i> Like</button>
                                        <button class="flex items-center mr-4"><i class="far fa-comment mr-1"></i> Comment</button>
                                        <button class="flex items-center"><i class="far fa-bookmark mr-1"></i> Save</button>
                                    </div>
                                </div>
                                <button class="text-gray-500"><i class="fas fa-ellipsis-h"></i></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-600">No posts available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        
function getLocation() {
    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            alert("Lokasi ditemukan! Latitude: " + latitude + ", Longitude: " + longitude);

            // Update peta dengan koordinat baru
            map.setView([latitude, longitude], 14);
            L.marker([latitude, longitude]).addTo(map)
                .bindPopup("Your current location")
                .openPopup();

            // Kirim data lokasi ke server
            updateLocation(latitude, longitude);
        },
        function(error) {
            alert("Geolocation error: " + error.message);
        }
    );
}
function updateLocation(latitude, longitude) {
    console.log("Mengirim data ke server:", latitude, longitude);
    fetch('../actions/update_location.php', {
    method: 'POST',
    body: new URLSearchParams({
        'latitude': newLat,
        'longitude': newLng
    }),
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    }
})
.then(response => response.json())  // Parse JSON response
.then(data => {
    if (data.success) {
        alert("Location updated successfully!");
    } else {
        alert("Failed to update location: " + data.message);
    }
})
.catch(error => {
    console.error("Fetch error:", error);
    alert("Error: " + error.message);
});

}






        // Leaflet Map Initialization
        var latitude = <?= isset($userData['latitude']) && $userData['latitude'] ? $userData['latitude'] : 'null' ?>;
        var longitude = <?= isset($userData['longitude']) && $userData['longitude'] ? $userData['longitude'] : 'null' ?>;

        var map = L.map('map').setView([latitude || -6.200000, longitude || 106.816666], 14); // Default ke Jakarta jika tidak ada data

        // Menambahkan tile layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Jika data latitude dan longitude ada, menambahkan marker di peta
        if (latitude && longitude) {
            L.marker([latitude, longitude]).addTo(map)
                .bindPopup("Location of <?= htmlspecialchars($username) ?>")
                .openPopup();
        } else {
            L.marker([-6.200000, 106.816666]).addTo(map) // Default di Jakarta
                .bindPopup("Location not available")
                .openPopup();
        }

        // Fungsi untuk memperbarui lokasi berdasarkan GPS
        document.getElementById('update-location').onclick = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var newLat = position.coords.latitude;
                    var newLng = position.coords.longitude;
                    
                    // Perbarui lokasi di peta
                    map.setView([newLat, newLng], 14);
                    L.marker([newLat, newLng]).addTo(map)
                        .bindPopup("Your new location")
                        .openPopup();

                    // Kirim data lokasi baru ke server untuk diperbarui di database
                    fetch('../actions/update_location.php', {
                        method: 'POST',
                        body: new URLSearchParams({
                            'latitude': newLat,
                            'longitude': newLng
                        }),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              alert("Location updated successfully!");
                          } else {
                              alert("Failed to update location.");
                          }
                      });
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        };
    </script>

</body>
</html>
