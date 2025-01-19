<?php
require 'db.php';
session_start();

// Check if user is authorized (replace this with actual authorization check)
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invite_code = bin2hex(random_bytes(8)); // Generate a random code

    // Insert invite code into the database
    $stmt = $conn->prepare("INSERT INTO invite_codes (code) VALUES (?)");
    $stmt->bind_param("s", $invite_code);

    if ($stmt->execute()) {
        echo "Invite code generated: $invite_code";
    } else {
        echo "Error generating invite code: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Invite Code</title>
</head>
<body>
    <h1>Generate Invite Code</h1>
    <form action="generate_invite.php" method="POST">
        <button type="submit">Generate Invite Code</button>
    </form>
</body>
</html>
