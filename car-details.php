<?php
session_start();
require_once('includes/db.php');

$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';
$success = '';

if ($car_id <= 0) {
    die('Invalid car ID');
}

// Get car details
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Car not found');
}

$car = $result->fetch_assoc();

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['customer_email'] ?? '');
    $phone = trim($_POST['customer_phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($name) || strlen($name) < 3) {
        $error = 'Please enter a valid name';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (empty($phone) || strlen($phone) < 10) {
        $error = 'Please enter a valid phone number';
    } elseif (empty($message) || strlen($message) < 10) {
        $error = 'Please enter a message (at least 10 characters)';
    } else {
        // Insert contact
        $sql = "INSERT INTO contacts (car_id, customer_name, customer_email, customer_phone, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $car_id, $name, $email, $phone, $message);
        
        if ($stmt->execute()) {
            $success = 'Thank you! Your contact request has been sent. We will get back to you soon.';
            // Clear form
            $_POST = array();
        } else {
            $error = 'Error submitting form. Please try again.';
        }
    }
}
?>
<?php require_once('includes/header.php'); ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="car-detail-container">
    <div class="car-detail-image">
        <?php if (!empty($car['image']) && file_exists('uploads/' . $car['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
        <?php else: ?>
            🚗
        <?php endif; ?>
    </div>

    <div class="car-detail-info">
        <h1><?php echo htmlspecialchars($car['name']); ?></h1>
        <p class="car-brand"><?php echo htmlspecialchars($car['brand']); ?> <?php echo htmlspecialchars($car['model']); ?> (<?php echo $car['year']; ?>)</p>
        
        <p class="car-price">$<?php echo number_format($car['price'], 2); ?></p>

        <div class="detail-specs">
            <div class="spec-item">
                <div class="spec-label">Fuel Type</div>
                <div class="spec-value"><?php echo htmlspecialchars($car['fuel_type'] ?? 'N/A'); ?></div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Transmission</div>
                <div class="spec-value"><?php echo htmlspecialchars($car['transmission'] ?? 'N/A'); ?></div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Color</div>
                <div class="spec-value"><?php echo htmlspecialchars($car['color'] ?? 'N/A'); ?></div>
            </div>
            <div class="spec-item">
                <div class="spec-label">Mileage</div>
                <div class="spec-value"><?php echo ($car['mileage'] ?? 'N/A'); ?> km</div>
            </div>
        </div>

        <h3>Description</h3>
        <p><?php echo nl2br(htmlspecialchars($car['description'] ?? '')); ?></p>
    </div>
</div>

<div class="contact-form">
    <h3>Interested in this car?</h3>
    <p>Fill out the form below and we'll contact you with more details.</p>
    
    <form method="POST" id="contactForm" onsubmit="return validateContactForm()">
        <div class="form-group">
            <label for="customer_name">Your Name *</label>
            <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($_POST['customer_name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="customer_email">Email Address *</label>
            <input type="email" id="customer_email" name="customer_email" value="<?php echo htmlspecialchars($_POST['customer_email'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="customer_phone">Phone Number *</label>
            <input type="tel" id="customer_phone" name="customer_phone" value="<?php echo htmlspecialchars($_POST['customer_phone'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="message">Message *</label>
            <textarea id="message" name="message" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn">Send Contact Request</button>
    </form>
</div>

<?php require_once('includes/footer.php'); ?>
