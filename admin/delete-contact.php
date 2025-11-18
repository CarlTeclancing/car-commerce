<?php
session_start();
require_once('../includes/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$contact_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($contact_id <= 0) {
    die('Invalid contact ID');
}

// Delete contact from database
$sql = "DELETE FROM contacts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contact_id);
$stmt->execute();

// Redirect back
header('Location: manage-contacts.php');
exit;
