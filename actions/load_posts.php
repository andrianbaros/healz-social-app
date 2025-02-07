<?php
require_once 'Post.php';
require_once 'Like.php';

$post = new Post();
$like = new Like();
$posts = $post->getAllPosts();

foreach ($posts as $row) {
    $post_id = $row['id'];
    $content = $row['content'];
    $like_count = $like->getLikeCount($post_id);

    echo "<div class='post' id='post-$post_id'>";
    echo "<p>$content</p>";
    echo "<button class='like-btn' data-postid='$post_id'>Like (<span id='like-count-$post_id'>$like_count</span>)</button>";
    echo "<button class='comment-btn' data-postid='$post_id'>Comment</button>";
    echo "<div class='comments-section' id='comments-$post_id' style='display:none;'></div>";
    echo "</div>";
}
?>
