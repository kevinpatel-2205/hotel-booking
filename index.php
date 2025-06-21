<?php
require_once 'inc/db.php';
session_start();

$stmt = $pdo->query("SELECT * FROM rooms ORDER BY id DESC");
$rooms = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>


    <style>
        body {
            background: #f8f9fa;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        .card {
            transition: transform 0.3s ease;
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🏨 Available Rooms</h2>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="user/dashboard.php" class="btn btn-outline-primary me-2">My Dashboard</a>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            <?php else: ?>
                <a href="user/login.php" class="btn btn-outline-primary me-2">Login</a>
                <a href="user/register.php" class="btn btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <?php foreach ($rooms as $room): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="assets/images/<?= htmlspecialchars($room['image']) ?>" class="card-img-top" alt="<?= $room['title'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($room['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($room['description'], 0, 100)) ?>...</p>
                        <p><strong>Price:</strong> $<?= $room['price'] ?>/night</p>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                            <a href="user/book-room.php?room_id=<?= $room['id'] ?>" class="btn btn-success">Book Now</a>
                        <?php else: ?>
                            <a href="user/login.php" class="btn btn-outline-secondary">Login to Book</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
