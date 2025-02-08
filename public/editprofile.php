<?php
require_once '../classes/User.php';
require_once '../classes/Database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = new User();
$userData = $user->getUserById($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="icon" type="image/png" href="../assets/images/hkuning.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="w-full h-full bg-white shadow-md flex flex-col">
        <img alt="Header image" class="w-full h-48 object-cover" 
            src="<?= htmlspecialchars($userData['header_image'] ?? 'https://storage.googleapis.com/a1aa/image/kS9f5d6NUxyHIan1RA6kUB6QGDpBXSLikypQTIoe8ctJBrGUA.jpg') ?>" />

        <div class="flex flex-1 p-6">
            <div class="w-1/4 text-center relative">
                <label for="profileUpload" class="cursor-pointer relative inline-block">
                    <img id="profileImage" alt="Profile image"
                        class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white shadow-md"
                        src="<?= htmlspecialchars($userData['profile_picture']) ?>"/>

                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                        <p class="text-white text-sm">Change Photo</p>
                    </div>
                </label>
            </div>

            <div class="p-2 w-3/4 overflow-y-auto">
                <form class="space-y-4 mr-40" action="../actions/update_profile.php" method="POST" enctype="multipart/form-data">
                    <input type="file" id="profileUpload" class="hidden" accept="image/*" name="profile_image"
                        onchange="previewImage(event)" />

                    <div class="flex items-center">
                        <label class="w-1/4 text-gray-700" for="username">Username</label>
                        <input class="w-3/4 bg-gray-200 rounded-md px-2 py-2"
                            id="username" name="username" type="text" value="<?= htmlspecialchars($userData['username']) ?>" required />
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/4 text-gray-700" for="bio">Bio</label>
                        <input class="w-3/4 bg-gray-200 rounded-md px-2 py-2"
                            id="bio" name="bio" type="text" value="<?= htmlspecialchars($userData['bio'] ?? '') ?>" />
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/4 text-gray-700" for="email">Email</label>
                        <input class="w-3/4 bg-gray-200 rounded-md px-2 py-2"
                            id="email" name="email" type="email" value="<?= htmlspecialchars($userData['email']) ?>" required />
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/4 text-gray-700" for="password">Password</label>
                        <input class="w-3/4 bg-gray-200 rounded-md px-2 py-2"
                            id="password" name="password" type="password" placeholder="Enter new password (optional)" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-2 bg-yellow-500 text-white rounded-full">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                document.getElementById('profileImage').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
