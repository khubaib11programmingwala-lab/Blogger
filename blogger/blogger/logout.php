<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out - Blogger Clone</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #f4f4f9, #e0e7ff);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #1e90ff;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.1em;
            margin-bottom: 20px;
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
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Logged Out</h1>
        <p>You have been successfully logged out.</p>
        <button onclick="window.location.href='index.php'">Go to Homepage</button>
    </div>
</body>
</html>
