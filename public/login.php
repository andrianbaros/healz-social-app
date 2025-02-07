<?php
require_once 'classes/auth.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $auth = new Auth();
    $error_message = $auth->login($username, $password);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  </head>
  <body class="min-h-screen bg-cover bg-center text-white" style="background-image: url('assets/images/bg.png')">
    <div class="flex justify-between h-screen px-20">
      <div class="flex-2 mt-40 ml-20">
        <h1 class="text-5xl font-bold leading-tight">Welcome Back!</h1>
        <p class="mt-1 text-lg font-awesome">Many people are waiting for you</p>
      </div>

      <div class="mt-40 mr-20 w-1/3 bg-black bg-opacity-20 p-4 rounded-2xl shadow-md mb-40">
        <?php if (!empty($error_message)): ?>
        <div class="bg-red-500 p-3 rounded-lg mb-4">
          <p><?= htmlspecialchars($error_message) ?></p>
        </div>
        <?php endif; ?>
        <form action="" method="POST" class="space-y-4">
          <div>
            <label for="username" class="block text-sm font-medium text-white">Username</label>
            <input id="username" name="username" type="text" required class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-black" placeholder="Enter your username" />
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-white">Password</label>
            <input id="password" name="password" type="password" required class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-black" placeholder="Enter your password" />
          </div>
          <div>
            <button type="submit" class="w-full px-6 py-3 text-white bg-yellow-500 rounded-2xl hover:bg-yellow-600">Sign In</button>
          </div>
          <div class="flex items-center justify-center">
            <span class="text-sm">or</span>
          </div>
          <div>
            <button type="button" class="w-full px-6 py-3 mt-1 text-white bg-gray-500 rounded-2xl hover:bg-gray-600">
              <i class="fab fa-google mr-2"></i>Sign In with Google
            </button>
          </div>
          <div>
            <button type="button" class="w-full px-6 py-3 mt-1 text-white bg-gray-500 rounded-2xl hover:bg-gray-600">
              <i class="fab fa-facebook-f mr-2"></i>Sign In with Facebook
            </button>
          </div>
        </form>
        <div class="text-center mt-3">
          <p class="text-sm">Don’t you have an account? <a href="signup.php" class="font-medium text-white-500 hover:underline">Sign Up</a></p>
        </div>
      </div>
    </div>
  </body>
</html>
