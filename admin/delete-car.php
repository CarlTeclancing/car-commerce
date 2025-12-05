<?php
session_start();
require_once('../includes/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($car_id <= 0) {
    die('Invalid car ID');
}

// Get car details to find image
$sql = "SELECT image FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $car = $result->fetch_assoc();
    
    // Delete image file(s) if exists (support multiple images separated by |)
    if (!empty($car['image'])) {
        $imgs = (strpos($car['image'], '|') !== false) ? explode('|', $car['image']) : [$car['image']];
        foreach ($imgs as $img) {
            $img = trim($img);
            if (!$img) continue;
            $image_path = __DIR__ . '/../uploads/' . $img;
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
        }
    }
}

// Delete car from database
$sql = "DELETE FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();

// Redirect back
header('Location: manage-cars.php');
exit;
