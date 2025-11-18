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
    $name = trim($_POST['name'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = intval($_POST['year'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $mileage = intval($_POST['mileage'] ?? 0);
    $fuel_type = trim($_POST['fuel_type'] ?? '');
    $transmission = trim($_POST['transmission'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = '';

    // Validation
    if (empty($name) || strlen($name) < 3) {
        $error = 'Please enter a valid car name';
    } elseif (empty($brand) || strlen($brand) < 2) {
        $error = 'Please enter a valid brand';
    } elseif (empty($model) || strlen($model) < 2) {
        $error = 'Please enter a valid model';
    } elseif ($year < 1900 || $year > date('Y') + 1) {
        $error = 'Please enter a valid year';
    } elseif ($price <= 0) {
        $error = 'Please enter a valid price';
    } else {
        // Handle file upload
        if (!empty($_FILES['image']['name'])) {
            $target_dir = __DIR__ . '/../uploads/';
            
            // Create uploads directory if it doesn't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $file_name = basename($_FILES['image']['name']);
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_size = $_FILES['image']['size'];
            $file_error = $_FILES['image']['error'];

            // Validate file
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($file_error === 0) {
                if ($file_size > 5000000) { // 5MB limit
                    $error = 'File size is too large (max 5MB)';
                } elseif (!in_array($file_ext, $allowed)) {
                    $error = 'Invalid file type. Only JPG, PNG, GIF allowed.';
                } else {
                    // Generate unique filename
                    $new_file_name = uniqid() . "." . $file_ext;
                    $target_file = $target_dir . $new_file_name;

                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $image = $new_file_name;
                    } else {
                        $error = 'Error uploading file';
                    }
                }
            }
        }

        // If no error, insert into database
        if (empty($error)) {
            $sql = "INSERT INTO cars (name, brand, model, year, price, mileage, fuel_type, transmission, color, description, image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                // types: name(s), brand(s), model(s), year(i), price(d), mileage(i), fuel_type(s), transmission(s), color(s), description(s), image(s)
                $stmt->bind_param("sssidiissss", $name, $brand, $model, $year, $price, $mileage, $fuel_type, $transmission, $color, $description, $image);
                
                if ($stmt->execute()) {
                    $success = 'Car added successfully!';
                    $_POST = array();
                } else {
                    $error = 'Error adding car to database';
                }
            } else {
                $error = 'Database error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car - Admin Dashboard</title>
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
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<main>
    <div class="page-title">
        <h2>Add New Car</h2>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="contact-form" style="max-width: 800px;">
        <form method="POST" enctype="multipart/form-data" id="carForm">
            <div class="form-group">
                <label for="name">Car Name *</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="brand">Brand *</label>
                    <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($_POST['brand'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="model">Model *</label>
                    <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($_POST['model'] ?? ''); ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="year">Year *</label>
                    <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($_POST['year'] ?? ''); ?>" min="1900" max="<?php echo date('Y') + 1; ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">Price ($) *</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="fuel_type">Fuel Type</label>
                    <select id="fuel_type" name="fuel_type">
                        <option value="">Select Fuel Type</option>
                        <option value="Petrol" <?php if (($_POST['fuel_type'] ?? '') === 'Petrol') echo 'selected'; ?>>Petrol</option>
                        <option value="Diesel" <?php if (($_POST['fuel_type'] ?? '') === 'Diesel') echo 'selected'; ?>>Diesel</option>
                        <option value="Electric" <?php if (($_POST['fuel_type'] ?? '') === 'Electric') echo 'selected'; ?>>Electric</option>
                        <option value="Hybrid" <?php if (($_POST['fuel_type'] ?? '') === 'Hybrid') echo 'selected'; ?>>Hybrid</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="transmission">Transmission</label>
                    <select id="transmission" name="transmission">
                        <option value="">Select Transmission</option>
                        <option value="Manual" <?php if (($_POST['transmission'] ?? '') === 'Manual') echo 'selected'; ?>>Manual</option>
                        <option value="Automatic" <?php if (($_POST['transmission'] ?? '') === 'Automatic') echo 'selected'; ?>>Automatic</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="mileage">Mileage (km)</label>
                    <input type="number" id="mileage" name="mileage" value="<?php echo htmlspecialchars($_POST['mileage'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($_POST['color'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Car Image (JPG, PNG, GIF - Max 5MB)</label>
                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif">
            </div>

            <button type="submit" class="btn">Add Car</button>
            <a href="manage-cars.php" class="btn btn-secondary" style="margin-left: 0.5rem;">Cancel</a>
        </form>
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
