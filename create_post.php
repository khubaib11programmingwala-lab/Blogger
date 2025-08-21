<?php
session_start();
$servername = "localhost";
$username = "udgdaytfymzuz";
$password = "mnc9mcdeej1i";
$dbname = "dbx5mbrweded2m";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

$categories = ['Technology', 'Lifestyle', 'Business', 'Travel'];
$author_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Default to user ID 1 for demo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $stmt = $conn->prepare("INSERT INTO posts (title, content, category, author_id) VALUES (:title, :content, :category, :author_id)");
    $stmt->execute(['title' => $title, 'content' => $content, 'category' => $category, 'author_id' => $author_id]);
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - Blogger Clone</title>
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
            background: #ff6f61;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background 0.3s;
        }
        button:hover {
            background: #e55a50;
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
        <h1>Create New Post</h1>
        <form id="postForm" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
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
                <div id="editor" contenteditable="true"></div>
                <textarea name="content" id="content" style="display:none;"></textarea>
            </div>
            <button type="submit">Publish</button>
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
