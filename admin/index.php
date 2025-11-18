<?php
session_start();
require_once('../includes/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get statistics
$car_count = $conn->query("SELECT COUNT(*) as count FROM cars")->fetch_assoc()['count'];
$contact_count = $conn->query("SELECT COUNT(*) as count FROM contacts")->fetch_assoc()['count'];
$pending_count = $conn->query("SELECT COUNT(*) as count FROM contacts WHERE status = 'pending'")->fetch_assoc()['count'];

// Get recent contacts
$recent_sql = "SELECT c.*, ca.name as car_name FROM contacts c 
               LEFT JOIN cars ca ON c.car_id = ca.id 
               ORDER BY c.created_at DESC LIMIT 5";
$recent_result = $conn->query($recent_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Car Store</title>
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
            <li><a href="manage-admins.php">Manage Admins</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<main>
    <div class="page-title">
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
    </div>

    <div class="cars-grid">
        <div class="car-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div style="padding: 2rem; text-align: center;">
                <h3 style="color: white; margin-bottom: 1rem;">Total Cars</h3>
                <p style="font-size: 3rem; font-weight: bold;"><?php echo $car_count; ?></p>
            </div>
        </div>

        <div class="car-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div style="padding: 2rem; text-align: center;">
                <h3 style="color: white; margin-bottom: 1rem;">Total Contacts</h3>
                <p style="font-size: 3rem; font-weight: bold;"><?php echo $contact_count; ?></p>
            </div>
        </div>

        <div class="car-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div style="padding: 2rem; text-align: center;">
                <h3 style="color: white; margin-bottom: 1rem;">Pending Requests</h3>
                <p style="font-size: 3rem; font-weight: bold;"><?php echo $pending_count; ?></p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
        <div class="admin-sidebar">
            <h3>Quick Actions</h3>
            <ul class="admin-menu">
                <li><a href="add-car.php">+ Add New Car</a></li>
                <li><a href="manage-cars.php">View All Cars</a></li>
                <li><a href="manage-contacts.php">View Contact Requests</a></li>
                <li><a href="manage-admins.php">Manage Admin Users</a></li>
            </ul>
        </div>

        <div class="table-container">
            <h3 style="padding: 1rem 1.5rem; background: #667eea; color: white; margin: 0;">Recent Contact Requests</h3>
            <?php if ($recent_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Car</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $recent_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['car_name'] ?? 'N/A'); ?></td>
                                <td><span class="status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="padding: 1.5rem;">No contact requests yet.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Car Store. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
