<?php
require_once '../inc/db.php';
require_once '../inc/auth.php';
require_once '../inc/functions.php';

requireUserLogin(); 

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("
    SELECT b.*, r.title AS room_title, r.image, r.price 
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Welcome, <?= e($_SESSION['name']) ?> 👋</h3>
        <div>
            <a href="available-rooms.php" class="btn btn-success me-2">Book a Room</a>
            <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>

    <h4>Your Bookings</h4>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">You haven't made any bookings yet.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($bookings as $booking): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow rounded-4">
                        <img src="../assets/images/<?= e($booking['image']) ?>" class="card-img-top" alt="<?= e($booking['room_title']) ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= e($booking['room_title']) ?></h5>
                            <p class="card-text">
                                <strong>Check-in:</strong> <?= e($booking['check_in']) ?><br>
                                <strong>Check-out:</strong> <?= e($booking['check_out']) ?><br>
                                <strong>Price:</strong> $<?= e($booking['price']) ?>
                            </p>
                            <p class="mt-2">
                                <?= formatStatusBadge($booking['status']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
