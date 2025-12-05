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

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_id'])) {
    $contact_id = intval($_POST['contact_id']);
    $status = trim($_POST['status'] ?? '');

    if (in_array($status, ['pending', 'contacted', 'completed', 'cancelled'])) {
        $sql = "UPDATE contacts SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $contact_id);
        
        if ($stmt->execute()) {
            $success = 'Status updated successfully!';
        } else {
            $error = 'Error updating status';
        }
    }
}

// Get all contacts with car info
$sql = "SELECT c.*, ca.name as car_name FROM contacts c 
        LEFT JOIN cars ca ON c.car_id = ca.id 
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);
$contacts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contacts - Admin Dashboard</title>
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
        <h2>Contact Requests</h2>
        <p>Manage customer contact and order requests</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (!empty($contacts)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Car</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contact['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($contact['customer_email']); ?></td>
                            <td><?php echo htmlspecialchars($contact['customer_phone']); ?></td>
                            <td><?php echo htmlspecialchars($contact['car_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars(substr($contact['message'], 0, 30)) . '...'; ?></td>
                            <td>
                                <form method="POST" style="display: inline-flex; gap: 0.5rem;">
                                    <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending" <?php if ($contact['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                        <option value="contacted" <?php if ($contact['status'] === 'contacted') echo 'selected'; ?>>Contacted</option>
                                        <option value="completed" <?php if ($contact['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                                        <option value="cancelled" <?php if ($contact['status'] === 'cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="view-contact.php?id=<?php echo $contact['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View</a>
                                    <button onclick="deleteContact(<?php echo $contact['id']; ?>)" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>No Contact Requests</h3>
            <p>Contact requests from customers will appear here</p>
        </div>
    <?php endif; ?>
</main>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Car Store. All rights reserved.</p>
    </div>
</footer>
<script src="../js/script.js"></script>
</body>
</html>
