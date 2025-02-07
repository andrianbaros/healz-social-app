<?php
require_once '../classes/auth.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        $error_message = "Password dan Konfirmasi Password tidak sesuai.";
    } else {
        $auth = new Auth();
        $error_message = $auth->register($username, $email, $password);
        if (!$error_message) {
            echo "<script>
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 3000);
            </script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body class="min-h-screen bg-cover bg-center text-white" style="background-image: url('../assets/images/bg.jpg')">
    <div class="flex justify-between h-screen px-20">
        <div class="flex-2 mt-32 ml-20">
            <h1 class="text-5xl font-bold leading-tight">Let's Start Your Journey!</h1>
            <p class="mt-1 text-lg font-awesome">Many people want you to join</p>
        </div>

        <div class="mt-32 mr-20 w-1/3 bg-black bg-opacity-20 p-4 rounded-2xl shadow-md mb-16">
            <?php if (!empty($error_message)): ?>
            <div class="bg-red-500 p-3 rounded-lg mb-4">
                <p><?= htmlspecialchars($error_message) ?></p>
            </div>
            <?php endif; ?>

            <form action="signup.php" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-white">Username</label>
                    <input id="username" name="username" type="text" required
                        class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-black" />
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-white">Email</label>
                    <input id="email" name="email" type="email" required
                        class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-black" />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-white">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-black" />
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-white">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required
                        class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-black" />
                </div>
                <div>
                    <button type="submit" class="w-full px-6 py-3 text-white bg-yellow-500 rounded-2xl hover:bg-yellow-600">
                        Sign Up
                    </button>
                </div>
            </form>
            <div class="text-center mt-3">
                <p class="text-sm">
                    Already have an account?
                    <a href="login.php" class="font-medium text-white-500 hover:underline">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
