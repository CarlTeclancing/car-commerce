<?php
session_start();
require_once('includes/db.php');

// Get all cars from database
$sql = "SELECT * FROM cars ORDER BY created_at DESC";
$result = $conn->query($sql);
$cars = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}
?>
<?php require_once('includes/header.php'); ?>

<div class="page-title">
    <h2>Welcome to Car Store</h2>
    <p>Find your dream car from our collection</p>
</div>

<?php if (!empty($cars)): ?>
    <div class="cars-grid">
        <?php foreach ($cars as $car): ?>
            <div class="car-card">
                <div class="car-image">
                    <?php if (!empty($car['image']) && file_exists('uploads/' . $car['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
                    <?php else: ?>
                        🚗
                    <?php endif; ?>
                </div>
                <div class="car-info">
                    <h3><?php echo htmlspecialchars($car['name']); ?></h3>
                    <p class="car-brand"><?php echo htmlspecialchars($car['brand']); ?> | <?php echo htmlspecialchars($car['model']); ?></p>
                    <div class="car-specs">
                        <span>Year: <?php echo $car['year']; ?></span>
                        <span><?php echo htmlspecialchars($car['fuel_type']); ?></span>
                        <span><?php echo htmlspecialchars($car['transmission']); ?></span>
                    </div>
                    <p class="car-price">$<?php echo number_format($car['price'], 2); ?></p>
                    <a href="car-details.php?id=<?php echo $car['id']; ?>" class="btn btn-block">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h3>No cars available yet</h3>
        <p>Please check back soon for our latest inventory</p>
    </div>
<?php endif; ?>

<?php require_once('includes/footer.php'); ?>
