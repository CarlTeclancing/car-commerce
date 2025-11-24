<?php
// Check if user is admin
$is_admin = isset($_SESSION['admin_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gadvision - Buy Your Dream Car</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>assets/logo.png" alt="gadvision Logo"></a>
        </div>
        <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">☰</button>
        <ul class="nav-menu">
            <li><a href="<?php echo SITE_URL; ?>index.php">Home</a></li>
            <li><a href="<?php echo SITE_URL; ?>contacts.php">Contacts</a></li>
            <?php if ($is_admin): ?>
                <li><a href="<?php echo ADMIN_URL; ?>index.php">Admin Dashboard</a></li>
                <li><a href="<?php echo ADMIN_URL; ?>logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo ADMIN_URL; ?>login.php">Admin Login</a></li>
                <li><a href="<?php echo SITE_URL; ?>create-admin.php">Create Admin</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<main>
