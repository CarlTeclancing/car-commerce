<?php
session_start();
require_once('../includes/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validation
    if (empty($username) || strlen($username) < 3) {
        $error = 'Username must be at least 3 characters long';
    } elseif (empty($password) || strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif ($password !== $password_confirm) {
        $error = 'Passwords do not match';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        // Check if username already exists
        $sql = "SELECT id FROM admin WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username already exists. Please choose a different username.';
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM admin WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = 'Email already exists. Please use a different email.';
            } else {
                // Hash password and insert new admin
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO admin (username, password, email) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $hashed_password, $email);

                if ($stmt->execute()) {
                    $success = 'Admin user created successfully!';
                    $_POST = array();
                } else {
                    $error = 'Error creating admin user. Please try again.';
                }
            }
        }
    }
}

// Get all admin users
$sql = "SELECT id, username, email, created_at FROM admin ORDER BY created_at DESC";
$result = $conn->query($sql);
$admins = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - Car Store</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <h1>🚗 CarStore Admin</h1>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="manage-cars.php">Manage Cars</a></li>
            <li><a href="add-car.php">Add Car</a></li>
            <li><a href="manage-contacts.php">Contact Requests</a></li>
            <li><a href="manage-admins.php">Manage Admins</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<main>
    <div class="page-title">
        <h2>Manage Admin Users</h2>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Create New Admin Form -->
        <div class="contact-form">
            <h3>Create New Admin User</h3>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required minlength="3">
                    <small>Minimum 3 characters</small>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required minlength="6">
                    <small>Minimum 6 characters</small>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password *</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
                </div>

                <button type="submit" class="btn">Create Admin User</button>
            </form>
        </div>

        <!-- Admin List -->
        <div>
            <h3 style="margin-bottom: 1.5rem;">Existing Admin Users</h3>
            
            <?php if (!empty($admins)): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($admin['username']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($admin['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>No admin users found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="contact-form" style="max-width: 100%; background: #f0f4ff; border-left: 4px solid #667eea;">
        <h3>ℹ️ Security Notes</h3>
        <ul style="margin-left: 1.5rem; color: #666;">
            <li>Each admin user must have a unique username</li>
            <li>Each admin user must have a unique email address</li>
            <li>Passwords are securely hashed using PHP's password_hash() function</li>
            <li>Passwords are never stored in plain text</li>
            <li>Only logged-in admins can create new admin users</li>
            <li>Change the default admin password (admin/admin123) immediately</li>
        </ul>
    </div>

    <div style="margin-top: 2rem;">
        <a href="index.php" class="btn">Back to Dashboard</a>
    </div>
</main>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Car Store. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
