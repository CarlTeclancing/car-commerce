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

// Get contact details
$sql = "SELECT c.*, ca.name as car_name FROM contacts c 
        LEFT JOIN cars ca ON c.car_id = ca.id 
        WHERE c.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contact_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Contact not found');
}

$contact = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contact - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <img src="<?php echo SITE_URL; ?>assets/logo.png" alt="gadvision Logo">
        </div>
        <ul class="nav-menu">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="manage-cars.php">Manage Cars</a></li>
            <li><a href="add-car.php">Add Car</a></li>
            <li><a href="manage-contacts.php">Contact Requests</a></li>
            <li><a href="../about.php">About</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<main>
    <div class="page-title">
        <h2>Contact Details</h2>
    </div>

    <div class="contact-form" style="max-width: 800px;">
        <h3>Customer Information</h3>
        
        <div class="detail-specs">
            <div class="spec-item">
                <div class="spec-label">Name</div>
                <div class="spec-value"><?php echo htmlspecialchars($contact['customer_name']); ?></div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Email</div>
                <div class="spec-value"><a href="mailto:<?php echo htmlspecialchars($contact['customer_email']); ?>"><?php echo htmlspecialchars($contact['customer_email']); ?></a></div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Phone</div>
                <div class="spec-value"><a href="tel:<?php echo htmlspecialchars($contact['customer_phone']); ?>"><?php echo htmlspecialchars($contact['customer_phone']); ?></a></div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Status</div>
                <div class="spec-value"><?php echo ucfirst($contact['status']); ?></div>
            </div>
        </div>

        <h3 style="margin-top: 2rem;">Car Information</h3>
        <div class="detail-specs">
            <div class="spec-item">
                <div class="spec-label">Car</div>
                <div class="spec-value"><?php echo htmlspecialchars($contact['car_name'] ?? 'N/A'); ?></div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Date Submitted</div>
                <div class="spec-value"><?php echo date('M d, Y H:i', strtotime($contact['created_at'])); ?></div>
            </div>
        </div>

        <h3 style="margin-top: 2rem;">Message</h3>
        <div style="background: #f9f9f9; padding: 1rem; border-radius: 5px; margin-top: 1rem;">
            <p><?php echo nl2br(htmlspecialchars($contact['message'])); ?></p>
        </div>

        <div style="margin-top: 2rem;">
            <a href="manage-contacts.php" class="btn">Back to Contacts</a>
            <button onclick="deleteContact(<?php echo $contact['id']; ?>)" class="btn btn-danger" style="margin-left: 0.5rem;">Delete</button>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Car Store. All rights reserved.</p>
    </div>
</footer>
<script src="../js/script.js"></script>
</body>
</html>
