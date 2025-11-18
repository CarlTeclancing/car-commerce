<?php
// Database Configuration

$db_host = 'localhost';
$db_user = 'root';
$db_password = ''; // Leave empty if no password
$db_name = 'car_store';

// Connection string
define('DB_HOST', $db_host);
define('DB_USER', $db_user);
define('DB_PASS', $db_password);
define('DB_NAME', $db_name);

// Site Configuration
define('SITE_URL', 'http://localhost/thegame/');
define('ADMIN_URL', 'http://localhost/thegame/admin/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Session timeout (in seconds)
define('SESSION_TIMEOUT', 3600);
