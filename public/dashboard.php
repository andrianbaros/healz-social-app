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

if (!$user->isLoggedIn()) {
    header("Location: public/login.php");
    exit();
}

$posts = $post->getAllPosts();


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

            </nav>
        </div>

<!-- Main Content -->
<div class="flex-1 p-6">
    <h2 class="text-xl font-bold mb-4">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>

    <div class="bg-white p-4 rounded shadow-md mb-4">
        <form id="postForm" method="POST" action="post_handler.php" enctype="multipart/form-data">
            <textarea name="content" class="w-full p-2 border rounded" placeholder="Share Your Idea!" required></textarea>

            <label for="imageUpload" class="cursor-pointer">
                <i class="fas fa-paperclip text-gray-500 text-xl"></i>
            </label>
            <input type="file" id="imageUpload" name="image" accept="image/*" class="hidden">
            <br>
            
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded mt-2">POST</button>
        </form>
    </div>



            <div class="container mt-6">
   <?php foreach ($posts as $row): ?><?php
   $likeCount = $like->getLikeCount($row['id']);?>
     <div class="bg-white p-4 rounded shadow-md mb-4">
        <div class="flex items-center space-x-2 mb-2">
            <i class="fas fa-user-circle text-gray-500 text-xl"></i>
            <span class="font-bold text-gray-800"><?= htmlspecialchars($row['username']); ?></span> <!-- Display username -->
        </div>
        <p><?= htmlspecialchars($row['content']); ?></p>
        <div class="actions mt-2 flex space-x-2">
            <button class="text-yellow-500 px-3 py-1 rounded like-btn liked" data-postid="<?= $row['id']; ?>">
                <i class="fas fa-heart"></i> Like (<span id="like-count-<?= $row['id']; ?>"><?= $likeCount; ?></span>)
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
        event.preventDefault(); // Prevent form from normal submission

        const formData = new FormData(form);

        // AJAX request to post content
fetch('http://localhost/healhealz-social-app/actions/post_posting.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        alert('Idea Posted Successfully!');

        const newPostContent = data.new_post_content;
        const newPostId = data.new_post_id;
        const username = data.username;  

        // Buat elemen postingan baru
        const postContainer = document.createElement('div');
        postContainer.classList.add('bg-white', 'p-4', 'rounded', 'shadow-md', 'mb-4');
        postContainer.innerHTML = `
            <div class="flex items-center space-x-2 mb-2">
                <i class="fas fa-user-circle text-gray-500 text-xl"></i>
                <span class="font-bold text-gray-800">${username}</span>
            </div>
            <p class="text-gray-800">${newPostContent}</p>
            <div class="actions mt-2 flex space-x-2">
                <button class="text-yellow-500 px-3 py-1 rounded like-btn flex items-center space-x-1" data-postid="${newPostId}">
                    <i class="fas fa-heart"></i>
                    <span>Like (<span id="like-count-${newPostId}">0</span>)</span>
                </button>
                <button class="text-gray-500 px-3 py-1 rounded comment-btn flex items-center space-x-1" data-postid="${newPostId}">
                    <i class="fas fa-comment"></i>
                    <span>Comment</span>
                </button>
            </div>
            <div class="comments-section mt-3 hidden" id="comments-${newPostId}">
                <input type="text" class="border p-2 w-full rounded comment-input" placeholder="Add Comment...">
                <button class="text-yellow-500 px-4 py-1 rounded mt-1 submit-comment" data-postid="${newPostId}">Send Comment</button>
                <div class="comments-list mt-2"></div>
            </div>
        `;

        // Tambahkan postingan ke awal daftar postingan
        const postList = document.querySelector('.container');
        postList.insertBefore(postContainer, postList.firstChild);

        // Tambahkan event listener ke tombol like baru
        const likeButton = postContainer.querySelector('.like-btn');
        likeButton.addEventListener('click', function () {
            handleLike(newPostId, likeButton);
        });

        // Tambahkan event listener ke tombol comment baru
        const commentButton = postContainer.querySelector('.comment-btn');
        commentButton.addEventListener('click', function () {
            toggleCommentSection(newPostId);
        });

        // Reset form setelah post
        form.reset();
    } else if (data.error) {
        alert(data.error);
    }
})
.catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat membuat post.');
});
// Fungsi untuk menangani aksi like
function handleLike(postId, button) {
    fetch("http://localhost/healz-social-app/actions/like_post.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "post_id=" + postId
    })
    .then(response => response.json())
    .then(data => {
        if (data.like_count !== undefined) {
            document.getElementById("like-count-" + postId).textContent = data.like_count;
            if (data.action === "liked") {
                button.classList.add("liked");
            } else {
                button.classList.remove("liked");
            }
        }
    });
}

// Fungsi untuk menampilkan atau menyembunyikan kolom komentar
function toggleCommentSection(postId) {
    const commentSection = document.getElementById("comments-" + postId);
    if (commentSection) {
        commentSection.classList.toggle("hidden");
    }
}

    });});


        // Handle like button click
        document.querySelectorAll(".like-btn").forEach(button => {
            button.addEventListener("click", function () {
                let postId = this.dataset.postid;
                let likeButton = this;

                fetch("http://localhost/healz-social-app/actions/like_post.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "post_id=" + postId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.like_count !== undefined) {
                        document.getElementById("like-count-" + postId).textContent = data.like_count;
                        if (data.action === "liked") {
                            likeButton.classList.add("liked");
                        } else {
                            likeButton.classList.remove("liked");
                        }
                    }
                });
            });
        });

 // Handle comment button click
 document.querySelectorAll(".comment-btn").forEach(button => {
    button.addEventListener("click", function () {
        let postId = this.dataset.postid;
        const commentSection = document.getElementById("comments-" + postId);

        if (!commentSection) {
            // Jika belum ada, buat comment section
            const newCommentSection = document.createElement('div');
            newCommentSection.classList.add('comments-section', 'mt-3', 'hidden');
            newCommentSection.id = "comments-" + postId;

            newCommentSection.innerHTML = `
                <input type="text" class="border p-2 w-full rounded comment-input" placeholder="Add Comment...">
                <button class="text-yellow-500 px-4 py-1 rounded mt-1 submit-comment" data-postid="${postId}">Send Comment</button>
                <div class="comments-list mt-2"></div>
            `;

            this.parentElement.parentElement.appendChild(newCommentSection);
        }

        // Tampilkan atau sembunyikan comment section
        document.getElementById("comments-" + postId).classList.toggle("hidden");

        // Ambil komentar dari server jika belum dimuat
        let commentsList = document.getElementById("comments-" + postId).querySelector(".comments-list");
        if (commentsList.children.length === 0) {
            fetch(`http://localhost/healz-social-app/actions/get_comments.php?post_id=${postId}`)
    .then(response => response.json())
    .then(data => {
        console.log("Komentar dari server:", data);  // Debugging response
        if (data.comments) {
            commentsList.innerHTML = "";  // Kosongkan daftar sebelumnya
            data.comments.forEach(comment => {
                console.log("Komentar:", comment);  // Debugging per komentar
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
    });
});
    // Event delegation for the submit-comment button (to handle dynamically added comments)
    document.body.addEventListener("click", function (event) {
    if (event.target && event.target.classList.contains("submit-comment")) {
        let postId = event.target.dataset.postid;
        let commentInput = document.querySelector("#comments-" + postId + " .comment-input");
        let commentText = commentInput.value;

        if (commentText.trim() === "") {
            alert("Please enter a comment.");
            return;
        }

        // Send the comment via AJAX
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
                <span class="font-bold text-gray-800">${currentUsername}</span> <!-- Gunakan currentUsername -->
            </div>
            <p>${commentText}</p>
        `;
        commentsList.appendChild(newComment);
        commentInput.value = "";  // Bersihkan input setelah komentar dikirim
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




</script>
</body>
</html>

