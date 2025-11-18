<?php
// This is a temporary password reset script
// After using it, DELETE this file immediately!

require_once('includes/db.php');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($new_password) || strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update admin password
        $sql = "UPDATE admin SET password = ? WHERE username = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $hashed_password);

        if ($stmt->execute()) {
            $success = 'Admin password reset successfully! You can now login with the new password. DELETE THIS FILE IMMEDIATELY!';
        } else {
            $error = 'Error updating password: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <h1>🚗 CarStore Admin</h1>
        </div>
    </div>
</nav>

<div class="login-container" style="max-width: 500px;">
    <h2>Reset Admin Password</h2>
    
    <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 1rem; border-radius: 5px; margin-bottom: 1.5rem; color: #856404;">
        <strong>⚠️ Security Warning:</strong> This file is for emergency password reset only. DELETE IT immediately after use!
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required minlength="6" autofocus>
            <small>Minimum 6 characters</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </div>

        <button type="submit" class="btn btn-block">Reset Password</button>
    </form>

    <hr style="margin: 1.5rem 0;">

    <h3>Current Admin User</h3>
    <p><strong>Username:</strong> admin</p>

    <h3 style="margin-top: 1.5rem;">Instructions</h3>
    <ol style="margin-left: 1.5rem; line-height: 1.8;">
        <li>Set a new password for the admin account</li>
        <li>Click "Reset Password"</li>
        <li>Login to the admin panel with: <strong>admin</strong> / <strong>your_new_password</strong></li>
        <li><strong>DELETE THIS FILE IMMEDIATELY</strong></li>
    </ol>
</div>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Car Store. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
