<?php
session_start();
require_once('includes/db.php');

// Get all contacts
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
<?php require_once('includes/header.php'); ?>

<div class="page-title">
    <h2>Contact Requests</h2>
    <p>View all contact and order requests received</p>
</div>

<?php if (!empty($contacts)): ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Car</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contact['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($contact['customer_email']); ?></td>
                        <td><?php echo htmlspecialchars($contact['customer_phone']); ?></td>
                        <td><?php echo htmlspecialchars($contact['car_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars(substr($contact['message'], 0, 50)) . '...'; ?></td>
                        <td>
                            <span class="status-<?php echo $contact['status']; ?>">
                                <?php echo ucfirst($contact['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h3>No Contact Requests Yet</h3>
        <p>Contact requests from customers will appear here</p>
    </div>
<?php endif; ?>

<?php require_once('includes/footer.php'); ?>
