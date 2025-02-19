<?php
session_start();

require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Post.php';
require_once '../classes/Like.php';
require_once '../classes/Comment.php';

$user = new User();
$post = new Post();
$like = new Like();
$comment = new Comment();

if ($user->isLoggedIn()) {
    $user_id = $user->getUserId();
    $profile_picture = $user->getProfilePicture($user_id);
    $user_data = $user->getUserById($user_id);  // Assuming this will return user data including username, bio, email
} else {
    header('Location: login.php');
}

$posts = $post->getAllPosts();


// Instantiate the Database class
$database = new Database();

// Get the connection
$conn = $database->getConnection();

// Ensure $post_user_id is properly set (for example, coming from session or request)
$post_user_id = $user_id; // Replace this with actual user ID

// SQL query to fetch user profile data
$query = "SELECT profile_picture, username FROM users WHERE id = ?";

// Prepare the statement
$stmt = $conn->prepare($query);

// Check if preparation was successful
if ($stmt === false) {
    // Output the error message from MySQL
    die("Failed to prepare query: " . $conn->error);
}

// Bind the user_id parameter
$stmt->bind_param('i', $post_user_id); // 'i' for integer

// Execute the query
$stmt->execute();

// Store result
$stmt->store_result();

// Check if a record was found and fetch the data
if ($stmt->num_rows > 0) {
    $stmt->bind_result($profile_picture, $username);
    $stmt->fetch(); // Fetch the result

} else {

}

// Close the statement and connection
$stmt->close();
$database->closeConnection();
?>
<script>
    const currentUsername = "<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : ''; ?>";
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Healz Dashboard</title>
    <link rel="icon" type="image/png" href="../assets/images/hkuning.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Roboto", sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="bg-yellow-400 w-64 min-h-screen p-4 hidden md:block">
            <div class="text-2xl font-bold ml-2 mb-8">Healz.</div>
            <nav class="space-y-4">
                <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="dashboard.php">
                    <i class="fas fa-home"></i> <span>Home</span>
                </a>
                <a class="flex items-center ml-2 space-x-2 hover:text-gray-600" href="markethealz.php">
                    <i class="fas fa-store"></i> <span>MarketHealz</span>
                </a>
                <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="messages.php">
                    <i class="fas fa-envelope"></i> <span>Messages</span>
                </a>

                <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-gray-600" href="profile.php">
                    <i class="fas fa-user"></i> <span>Profile</span>
                </a>
                <a class="flex items-center ml-2 space-x-2 text-gray-800 hover:text-red-800" href="../actions/logout.php">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h2 class="text-xl font-bold mb-4">
                Welcome, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>!
            </h2>

            <div class="bg-white p-4 rounded shadow-md mb-4">
                <form id="postForm" enctype="multipart/form-data">
                    <textarea name="content" class="w-full p-2 border rounded" placeholder="Share Your Idea!" required></textarea>

                    <div class="flex items-center justify-between mt-2">
                        <!-- Ikon Upload -->
                        <input type="file" id="imageUpload" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">

                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">POST</button>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="previewContainer" class="mt-4 hidden">
                        <img id="imagePreview" class="w-full p-2 border rounded" alt="Preview Gambar">
                    </div>
                </form>
            </div>

            <div class="container mt-6">
                <?php foreach ($posts as $row): ?>
    <div class="bg-white p-4 rounded shadow-md mb-4">
        <div class="flex items-center space-x-2 mb-2">
            <!-- Ganti foto profil dengan gambar dari database -->
            <?php 
                $profile_picture = $user->getProfilePicture($row['user_id']);
            ?>
            <img src="../uploads/<?= htmlspecialchars($profile_picture); ?>" alt="User Profile Picture" class="w-8 h-8 rounded-full">
            <span class="font-bold text-gray-800"><?= htmlspecialchars($row['username']); ?></span>
        </div>
        <p><?= htmlspecialchars($row['content']); ?></p>
        
        <?php if (!empty($row['image'])): ?>
            <img src="<?= htmlspecialchars($row['image']); ?>" alt="Posted Image" class="mt-2 w-32 rounded-md shadow">
        <?php endif; ?>

        <div class="actions mt-2 flex space-x-2">
            <button class="text-yellow-500 px-3 py-1 rounded like-btn" data-postid="<?= $row['id']; ?>">
                <i class="fas fa-heart"></i> Like (<span id="like-count-<?= $row['id']; ?>"><?= $like->getLikeCount($row['id']); ?></span>)
            </button>
            <button class="text-gray-500 px-3 py-1 rounded comment-btn" data-postid="<?= $row['id']; ?>">
                <i class="fas fa-comment"></i> Comment
            </button>
        </div>
    </div>
<?php endforeach; ?>    
    
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('postForm');

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(form);
        const content = form.querySelector("textarea[name='content']").value.trim();
        const imageInput = document.getElementById("imageUpload");

        if (!content && (!imageInput.files || imageInput.files.length === 0)) {
            alert("Please enter text or upload an image.");
            return;
        }

        fetch('http://localhost/healz-social-app/actions/post_posting.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Idea Posted Successfully!');

                const newPostContent = data.new_post_content || "";
                const newPostId = data.new_post_id;
                const username = data.username;
                const imageUrl = data.image;
                const profileImage = data.profile_picture; // Ambil URL gambar profil dari respons

                const postContainer = document.createElement('div');
                postContainer.classList.add('bg-white', 'p-4', 'rounded', 'shadow-md', 'mb-4');
                postContainer.innerHTML = `
                    <div class="flex items-center space-x-2 mb-2">
                        <!-- Ganti gambar profil dengan data yang diterima dari backend -->
                        <img src="${profileImage ? '../uploads/' + profileImage : '../assets/images/default-avatar.png'}" alt="User Profile Picture" class="w-8 h-8 rounded-full">
                        <span class="font-bold text-gray-800">${username}</span>
                    </div>
                    ${newPostContent ? `<p class="text-gray-800">${newPostContent}</p>` : ""}
                    ${imageUrl ? `<img src="${imageUrl}" alt="Posted Image" class="mt-2 w-32 rounded-md shadow">` : ""}
                    <div class="actions mt-2 flex space-x-2">
                        <button class="text-yellow-500 px-3 py-1 rounded like-btn" data-postid="${newPostId}">
                            <i class="fas fa-heart"></i> Like (<span id="like-count-${newPostId}">0</span>)
                        </button>
                        <button class="text-gray-500 px-3 py-1 rounded comment-btn" data-postid="${newPostId}">
                            <i class="fas fa-comment"></i> Comment
                        </button>
                    </div>
                `;

                const postList = document.querySelector('.container.mt-6');
                postList.insertBefore(postContainer, postList.firstChild);

                form.reset();
                document.getElementById("previewContainer").classList.add("hidden");
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membuat post.');
        });
});


    document.body.addEventListener("click", function (event) {
        let likeButton = event.target.closest(".like-btn"); 
        if (likeButton) {
            let postId = likeButton.dataset.postid;
            fetch("http://localhost/healz-social-app/actions/like_post.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "post_id=" + postId
            })
            .then(response => response.json())
            .then(data => {
                if (data.like_count !== undefined) {
                    document.getElementById("like-count-" + postId).textContent = data.like_count;
                    likeButton.classList.toggle("liked", data.action === "liked");
                }
            });
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target && event.target.classList.contains("comment-btn")) {
            let postId = event.target.dataset.postid;
            const commentSection = document.getElementById("comments-" + postId);

            if (!commentSection) {
                const newCommentSection = document.createElement('div');
                newCommentSection.classList.add('comments-section', 'mt-3', 'hidden');
                newCommentSection.id = "comments-" + postId;

                newCommentSection.innerHTML = `
                    <input type="text" class="border p-2 w-full rounded comment-input" placeholder="Add Comment...">
                    <button class="text-yellow-500 px-4 py-1 rounded mt-1 submit-comment" data-postid="${postId}">Send Comment</button>
                    <div class="comments-list mt-2"></div>
                `;

                event.target.parentElement.parentElement.appendChild(newCommentSection);
            }

            document.getElementById("comments-" + postId).classList.toggle("hidden");

            let commentsList = document.getElementById("comments-" + postId).querySelector(".comments-list");
            if (commentsList.children.length === 0) {
                fetch(`http://localhost/healz-social-app/actions/get_comments.php?post_id=${postId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.comments) {
                            commentsList.innerHTML = "";
                            data.comments.forEach(comment => {
                                let commentElement = document.createElement("div");
                                commentElement.classList.add("comment", "mb-2");
                                commentElement.innerHTML = `
                                    <div class="flex items-center space-x-2 mb-2">
                                        <i class="fas fa-user-circle text-gray-500 text-xl"></i>
                                        <span class="font-bold text-gray-800">${comment.username || "Unknown"}</span>
                                    </div>
                                    <p>${comment.content}</p>
                                `;
                                commentsList.appendChild(commentElement);
                            });
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }
    });

    document.body.addEventListener("click", function (event) {
        if (event.target && event.target.classList.contains("submit-comment")) {
            let postId = event.target.dataset.postid;
            let commentInput = document.querySelector("#comments-" + postId + " .comment-input");
            let commentText = commentInput.value;

            if (commentText.trim() === "") {
                alert("Please enter a comment.");
                return;
            }

            fetch("http://localhost/healz-social-app/actions/post_comment.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `post_id=${postId}&comment_text=${encodeURIComponent(commentText)}&username=${encodeURIComponent(currentUsername)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let commentsList = document.querySelector("#comments-" + postId + " .comments-list");
                    let newComment = document.createElement("div");
                    newComment.classList.add('comment', 'mb-2');
                    newComment.innerHTML = `
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-user-circle text-gray-500 text-xl"></i>
                            <span class="font-bold text-gray-800">${currentUsername}</span>
                        </div>
                        <p>${commentText}</p>
                    `;
                    commentsList.appendChild(newComment);
                    commentInput.value = "";
                } else {
                    alert(data.error || "Failed to post the comment.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the comment.');
            });
        }
    });
});

function previewImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById("previewContainer");
    const imagePreview = document.getElementById("imagePreview");

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.classList.remove("hidden");
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>
