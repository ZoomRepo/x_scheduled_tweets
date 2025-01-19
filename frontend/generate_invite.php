<?php
session_start();
require 'db.php';

// Enable detailed error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php'); // Redirect to login if not logged in
        exit();
    }

    // Check if the user has admin privileges
    $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($is_admin);
    $stmt->fetch();
    $stmt->close();

    if (!$is_admin) {
        throw new Exception("Unauthorized access. Only admin users can generate invite codes.");
    }

    $success = null;

    // Generate invite code on POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $invite_code = bin2hex(random_bytes(8)); // Generate a random invite code

        // Insert invite code into the database
        $stmt = $conn->prepare("INSERT INTO invite_codes (code) VALUES (?)");
        if (!$stmt) {
            throw new Exception("Prepare failed for invite code insert: " . $conn->error);
        }
        $stmt->bind_param("s", $invite_code);
        $stmt->execute();
        $stmt->close();

        $success = "Invite code generated successfully: <strong>$invite_code</strong>";
    }
} catch (Exception $e) {
    $error = $e->getMessage(); // Capture the error message
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Invite Code</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f8fa;
            font-family: Arial, sans-serif;
        }
        .generate-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 100px;
        }
        .btn-primary {
            width: 100%;
            border-radius: 5px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="generate-container">
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <h3 class="text-center">Generate Invite Code</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php elseif (isset($success)): ?>
            <div class="alert alert-success text-center" role="alert">
                <?= $success ?>
            </div>
        <?php endif; ?>
        <form action="generate_invite.php" method="POST">
            <button type="submit" class="btn btn-primary">Generate Invite Code</button>
        </form>
        <div class="mt-3 text-center">
            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
