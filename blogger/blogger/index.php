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

$posts = $conn->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.author_id = users.id ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$categories = ['Technology', 'Lifestyle', 'Business', 'Travel'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger Clone - Homepage</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background: linear-gradient(135deg, #1e90ff, #00b4db);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .search-bar {
            margin: 20px 0;
            display: flex;
            justify-content: center;
        }
        .search-bar input {
            padding: 10px;
            width: 300px;
            border: none;
            border-radius: 20px 0 0 20px;
            outline: none;
        }
        .search-bar button {
            padding: 10px;
            border: none;
            background: #ff6f61;
            color: white;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-bar button:hover {
            background: #e55a50;
        }
        .categories {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }
        .categories a {
            text-decoration: none;
            color: #1e90ff;
            font-weight: bold;
            padding: 10px 20px;
            background: white;
            border-radius: 20px;
            transition: background 0.3s;
        }
        .categories a:hover {
            background: #e0f7fa;
        }
        .post-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .post-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s;
        }
        .post-card:hover {
            transform: translateY(-5px);
        }
        .post-card h2 {
            font-size: 1.5em;
            margin: 0 0 10px;
            color: #1e90ff;
        }
        .post-card p {
            color: #666;
        }
        .create-post {
            text-align: center;
            margin: 20px 0;
        }
        .create-post button {
            padding: 10px 20px;
            border: none;
            background: #ff6f61;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background 0.3s;
        }
        .create-post button:hover {
            background: #e55a50;
        }
        @media (max-width: 768px) {
            .search-bar input {
                width: 200px;
            }
            .categories {
                flex-direction: column;
                align-items: center;
            }
            .post-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Blogger Clone</h1>
        <div class="search-bar">
            <input type="text" id="search" placeholder="Search posts...">
            <button onclick="searchPosts()">Search</button>
        </div>
    </header>
    <div class="container">
        <div class="categories">
            <?php foreach ($categories as $category): ?>
                <a href="#" onclick="filterCategory('<?php echo $category; ?>')"><?php echo $category; ?></a>
            <?php endforeach; ?>
        </div>
        <div class="create-post">
            <button onclick="window.location.href='create_post.php'">Create New Post</button>
        </div>
        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <div class="post-card" onclick="window.location.href='post.php?id=<?php echo $post['id']; ?>'">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p><?php echo substr(htmlspecialchars($post['content']), 0, 100) . '...'; ?></p>
                    <p><small>By <?php echo htmlspecialchars($post['username']); ?> on <?php echo $post['created_at']; ?></small></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function searchPosts() {
            const query = document.getElementById('search').value;
            window.location.href = `index.php?search=${encodeURIComponent(query)}`;
        }
        function filterCategory(category) {
            window.location.href = `index.php?category=${encodeURIComponent(category)}`;
        }
    </script>
</body>
</html>
