<?php
require_once 'db.php';

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = $conn->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.author_id = users.id WHERE posts.id = $post_id")->fetch(PDO::FETCH_ASSOC);
$comments = $conn->query("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = $post_id ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$related_posts = $conn->query("SELECT * FROM posts WHERE category = '{$post['category']}' AND id != $post_id ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>window.location.href='login.php';</script>";
        exit();
    }
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");
    $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id, 'content' => $comment]);
    echo "<script>window.location.href='post.php?id=$post_id';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Blogger Clone</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #1e90ff;
        }
        .post-meta {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        .post-content {
            line-height: 1.6;
        }
        .comments {
            margin-top: 30px;
        }
        .comment {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .comment-form {
            margin-top: 20px;
        }
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
        }
        .comment-form button {
            padding: 10px 20px;
            border: none;
            background: #ff6f61;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .comment-form button:hover {
            background: #e55a50;
        }
        .related-posts {
            margin-top: 30px;
        }
        .related-posts h3 {
            color: #1e90ff;
        }
        .related-post {
            padding: 10px;
            background: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .related-post:hover {
            background: #e0f7fa;
        }
        .post-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .post-actions button {
            padding: 10px 20px;
            border: none;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background 0.3s;
        }
        .post-actions .edit-btn {
            background: #28a745;
        }
        .post-actions .edit-btn:hover {
            background: #218838;
        }
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .post-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <div class="post-meta">
            By <?php echo htmlspecialchars($post['username']); ?> on <?php echo $post['created_at']; ?> | Category: <?php echo $post['category']; ?>
        </div>
        <div class="post-content">
            <?php echo $post['content']; ?>
        </div>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['author_id']): ?>
            <div class="post-actions">
                <button class="edit-btn" onclick="window.location.href='edit_post.php?id=<?php echo $post_id; ?>'">Edit Post</button>
            </div>
        <?php endif; ?>
        <div class="comments">
            <h2>Comments</h2>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['content']); ?></p>
                    <small><?php echo $comment['created_at']; ?></small>
                </div>
            <?php endforeach; ?>
            <div class="comment-form">
                <form method="POST">
                    <textarea name="comment" placeholder="Add a comment..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            </div>
        </div>
        <div class="related-posts">
            <h3>Related Posts</h3>
            <?php foreach ($related_posts as $related): ?>
                <div class="related-post" onclick="window.location.href='post.php?id=<?php echo $related['id']; ?>'">
                    <?php echo htmlspecialchars($related['title']); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
