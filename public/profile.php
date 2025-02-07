<?php
require '../classes/database.php';
session_start(); // Pastikan session dimulai untuk mendapatkan user yang sedang login

$db = new Database(); // Buat instance Database
$conn = $db->conn; // Ambil koneksi database

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
        $sql = "SELECT posts.id, posts.content, posts.image, posts.created_at, users.username 
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

$user_id = $_SESSION['user_id'] ?? null; // Ambil ID user dari session

$profile = new Profile($conn);
$userData = $user_id ? $profile->getUserProfile($user_id) : null;
$posts = $user_id ? $profile->getPosts($user_id) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    if ($user_id) {
        // Update lokasi pengguna di database
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $profile->updateUserLocation($user_id, $latitude, $longitude);
        $userData = $profile->getUserProfile($user_id); // Ambil data terbaru setelah update
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
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="mx-auto bg-white shadow-md">
        <img alt="Header image" class="w-full h-48 object-cover" src="https://storage.googleapis.com/a1aa/image/kS9f5d6NUxyHIan1RA6kUB6QGDpBXSLikypQTIoe8ctJBrGUA.jpg" />
        <div class="flex p-6">
            <div class="w-1/4 text-center">
                <?php 
                    $profilePic = $userData['profile_picture'] ?? 'assets/images/default-profile.jpg';
                    $username = $userData['username'] ?? 'Guest';
                ?>
                <img alt="Profile image" class="w-24 h-24 rounded-full mx-auto" src="<?= htmlspecialchars($profilePic) ?>" />
                <h2 class="mt-4 text-xl font-semibold"><?= htmlspecialchars($username) ?></h2>
                <p class="text-gray-600">Freelance Graphic Designer</p>
                <a href='joinascreator.php'><button class="mt-10 px-16 py-2 bg-yellow-500 text-white rounded-full">Join As Creator</button></a>
                <a href="editprofile.php"><button class="mt-2 px-20 py-2 bg-yellow-500 text-white rounded-full">Edit Profile</button></a>
            </div>
            <div class="w-3/4 pl-6">
                <div class="flex space-x-4 mb-4">
                    <a href="dashboard.php"><button class="px-4 py-2 bg-yellow-500 text-white rounded-full">Home</button></a>
                    <button class="px-4 py-2 bg-yellow-500 text-white rounded-full">Post</button>
                </div>

                <!-- Peta Lokasi Pengguna -->
                <div id="map" style="width: 100%; height: 400px;"></div>
                <button id="get-location" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-full">Use IP Geolocation</button>
                <script>
                    var userLatitude = <?= isset($userData['latitude']) ? $userData['latitude'] : '0' ?>;
                    var userLongitude = <?= isset($userData['longitude']) ? $userData['longitude'] : '0' ?>;
                    var map = L.map('map').setView([userLatitude, userLongitude], 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    var marker = L.marker([userLatitude, userLongitude]).addTo(map);
                    marker.bindPopup("<b>Location of <?= htmlspecialchars($username) ?></b>").openPopup();

                    document.getElementById('get-location').addEventListener('click', function() {
                        var apiKey = '7178ec1a5444408da61d28e9a71e23a7';
                        
                        // Menggunakan IPGeolocation API untuk mendapatkan lokasi berdasarkan IP
                        $.ajax({
                            url: `https://api.ipgeolocation.io/ipgeo?apiKey=${apiKey}`,
                            type: 'GET',
                            success: function(data) {
                                var latitude = data.latitude;
                                var longitude = data.longitude;
                                var location = data.city + ', ' + data.country_name;
                                
                                alert('Your location: ' + location);

                                // Update peta dengan koordinat lokasi baru
                                map.setView([latitude, longitude], 14);
                                marker.setLatLng([latitude, longitude]);

                                // Kirim data lokasi ke server untuk memperbarui di database
                                $.ajax({
                                    url: '', // Re-submit form ke halaman yang sama untuk menangani pembaruan
                                    type: 'POST',
                                    data: {
                                        latitude: latitude,
                                        longitude: longitude
                                    },
                                    success: function(response) {
                                        alert('Location updated successfully!');
                                    },
                                    error: function() {
                                        alert('Error updating location.');
                                    }
                                });
                            },
                            error: function() {
                                alert('Error fetching location from IPGeolocation.');
                            }
                        });
                    });
                </script>

                <!-- Daftar Postingan Pengguna -->
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="bg-gray-100 p-4 rounded-lg mb-4">
                            <div class="flex items-start">
                                <img alt="Post image" class="w-10 h-10 rounded-full mr-4" src="<?= htmlspecialchars($post['image'] ?: 'assets/images/default.jpg') ?>" />
                                <div class="flex-1">
                                    <h3 class="font-semibold"><?= htmlspecialchars($post['username']) ?></h3>
                                    <p class="text-gray-700 mt-2"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
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
</body>
</html>
