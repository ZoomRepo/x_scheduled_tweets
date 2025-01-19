<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $invite_code = $_POST['invite_code'];

    // Validate fields
    if (empty($email) || empty($password) || empty($invite_code)) {
        $error = "All fields are required.";
    } else {
        // Check if invite code is valid and unused
        $stmt = $conn->prepare("SELECT id FROM invite_codes WHERE code = ? AND is_used = FALSE");
        $stmt->bind_param("s", $invite_code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Generate password hash with salting
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $stmt = $conn->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password_hash);

            if ($stmt->execute()) {
                // Mark invite code as used
                $stmt = $conn->prepare("UPDATE invite_codes SET is_used = TRUE WHERE code = ?");
                $stmt->bind_param("s", $invite_code);
                $stmt->execute();

                echo "Registration successful!";
                exit;
            } else {
                $error = "Error creating account: " . $stmt->error;
            }
        } else {
            $error = "Invalid or already used invite code.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        <label for="invite_code">Invite Code:</label>
        <input type="text" name="invite_code" id="invite_code" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
