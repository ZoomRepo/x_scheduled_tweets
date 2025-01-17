<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];
    $post_time = $_POST['post_time'];

    $stmt = $conn->prepare("INSERT INTO posts (content, post_time) VALUES (?, ?)");
    $stmt->bind_param("ss", $content, $post_time);

    if ($stmt->execute()) {
        $success = "Post scheduled successfully!";
    } else {
        $error = "Error scheduling post: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Post</title>
    <link rel="stylesheet" href="assets/stylesheets/styles.css">
</head>
<body>
    <div class="container">
        <h1 style="margin-left: 20px; display: inline-block; width: 60%;">Schedule a Post</h1>
        <a style="float: right; padding-right: 20px;margin-top: 20px;text-decoration:none; color: #FF5E00;" href="index.php">View posts</a>
        <?php if (!empty($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php elseif (!empty($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form action="submit.php" method="POST">
            <textarea id="post-textarea" name="content" rows="5" placeholder="What's happening?" required></textarea>
            <input type="datetime-local" name="post_time" required>
            <button class="schedule-button" type="submit">Schedule Post</button>
        </form>
    </div>
</body>
</html>
