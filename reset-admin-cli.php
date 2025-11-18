<?php
/**
 * Direct Database Update - Reset Admin Password
 * 
 * This script directly updates the admin password in the database.
 * The new password will be: admin123
 * 
 * Usage: php reset-admin-cli.php
 */

require_once('includes/db.php');

// The password "admin123" hashed with PASSWORD_DEFAULT (bcrypt)
// This hash is consistent across systems
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Resetting admin password...\n";
echo "Username: admin\n";
echo "New Password: admin123\n\n";

// Update the admin user's password
$sql = "UPDATE admin SET password = ? WHERE username = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed_password);

if ($stmt->execute()) {
    echo "✅ Success! Admin password has been reset to: admin123\n";
    echo "You can now login with:\n";
    echo "  Username: admin\n";
    echo "  Password: admin123\n";
} else {
    echo "❌ Error: " . $conn->error . "\n";
}

$conn->close();
?>
