<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $invite_code = $_POST['invite_code'];

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Check if the invite code is valid
        $stmt = $conn->prepare("SELECT id FROM invite_codes WHERE code = ? AND is_used = FALSE");
        $stmt->bind_param("s", $invite_code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Hash the password securely
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $stmt = $conn->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password_hash);

            if ($stmt->execute()) {
                // Mark the invite code as used
                $stmt = $conn->prepare("UPDATE invite_codes SET is_used = TRUE WHERE code = ?");
                $stmt->bind_param("s", $invite_code);
                $stmt->execute();

                $success = "Registration successful! You can now <a href='login.php'>log in</a>.";
            } else {
                $error = "Error creating account. Please try again.";
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f8fa;
            font-family: Arial, sans-serif;
        }
        .register-container {
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
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            width: 100%;
            border-radius: 5px;
            padding: 10px;
        }
        .footer-text {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <!-- Replace with your logo -->
            <img src="assets/images/x_scheduler_keys_registration.png" alt="Logo">
        </div>
        <h3 class="text-center">Register</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php elseif (isset($success)): ?>
            <div class="alert alert-success text-center" role="alert">
                <?= $success ?>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="mb-3">
                <label for="invite_code" class="form-label">Invite Code</label>
                <input type="text" name="invite_code" id="invite_code" class="form-control" placeholder="Enter your invite code" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <div class="footer-text">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
