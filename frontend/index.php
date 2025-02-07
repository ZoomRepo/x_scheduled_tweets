<?php
require 'db.php';

$result = $conn->query("SELECT id, content, post_time, status FROM posts ORDER BY post_time DESC");
$posts = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Posts</title>  
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/stylesheets/styles.css">
    <link rel="manifest" href="assets/manifest.json">
    <link rel="stylesheet" href="assets/stylesheets/govuk-frontend-5.8.0.min.css">
</head>
<body>
    <div class="container">
        <h1 style="margin-left: 20px; display: inline-block; width: 50%;">Scheduled Posts</h1>
        <a style="display:inline-block; padding-right: 20px;margin-top: 20px;text-decoration:none; color: #FF5E00;" href="submit.php">Create post</a>
        <?php if (empty($posts)): ?>
            <p>No scheduled posts yet.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <p class="content"><?= htmlspecialchars($post['content']) ?></p>
                    <p class="post-time" style="display:inline;">Scheduled for: <?= date('F j, Y, g:i a', strtotime($post['post_time'])) ?></p>
                    <?php if($post['status'] == 'pending') :  ?>
                        <strong style="display:inline;float: right; margin-right: 20px;" class="govuk-tag govuk-tag--red">
                            PENDING
                        </strong>
                    <?php else: ?>
                        <strong style="display:inline;float: right; margin-right: 20px;" class="govuk-tag govuk-tag--green">
                            POSTED
                        </strong>  
                    <?php endif; ?>  
                    <form action="delete.php" method="POST" style="width:100%">
                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        <button type="submit" style="margin-top: 20px;" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                    </form>   
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
