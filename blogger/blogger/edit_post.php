<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = $conn->query("SELECT * FROM posts WHERE id = $post_id AND author_id = {$_SESSION['user_id']}")->fetch(PDO::FETCH_ASSOC);
if (!$post) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$categories = ['Technology', 'Lifestyle', 'Business', 'Travel'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $stmt = $conn->prepare("UPDATE posts SET title = :title, content = :content, category = :category WHERE id = :id AND author_id = :author_id");
    $stmt->execute(['title' => $title, 'content' => $content, 'category' => $category, 'id' => $post_id, 'author_id' => $_SESSION['user_id']]);
    echo "<script>window.location.href='post.php?id=$post_id';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Blogger Clone</title>
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
            text-align: center;
            color: #1e90ff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }
        textarea {
            height: 300px;
            resize: vertical;
        }
        button {
            padding: 10px 20px;
            border: none;
            background: #28a745;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background 0.3s;
        }
        button:hover {
            background: #218838;
        }
        #editor {
            min-height: 300px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .toolbar {
            margin-bottom: 10px;
        }
        .toolbar button {
            margin-right: 5px;
            background: #1e90ff;
        }
        .toolbar button:hover {
            background: #0d6efd;
        }
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>
        <form id="postForm" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category; ?>" <?php echo $category == $post['category'] ? 'selected' : ''; ?>><?php echo $category; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="editor">Content</label>
                <div class="toolbar">
                    <button type="button" onclick="formatText('bold')">Bold</button>
                    <button type="button" onclick="formatText('italic')">Italic</button>
                    <button type="button" onclick="formatText('underline')">Underline</button>
                </div>
                <div id="editor" contenteditable="true"><?php echo $post['content']; ?></div>
                <textarea name="content" id="content" style="display:none;"></textarea>
            </div>
            <button type="submit">Update Post</button>
        </form>
    </div>
    <script>
        function formatText(command) {
            document.execCommand(command, false, null);
        }
        document.getElementById('postForm').addEventListener('submit', function() {
            document.getElementById('content').value = document.getElementById('editor').innerHTML;
        });
    </script>
</body>
</html>
