<?php
require_once '../inc/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = "";
$room_id = $_GET['room_id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if (!$room) {
    die("Room not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in  = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if (!$check_in || !$check_out) {
        $errors[] = "Both check-in and check-out dates are required.";
    } elseif ($check_in >= $check_out) {
        $errors[] = "Check-out date must be after check-in date.";
    } else {
        $stmt = $pdo->prepare("
            SELECT * FROM bookings 
            WHERE room_id = ? AND status != 'Cancelled'
            AND (
                (check_in <= ? AND check_out > ?) OR
                (check_in < ? AND check_out >= ?) OR
                (check_in >= ? AND check_out <= ?)
            )
        ");
        $stmt->execute([$room_id, $check_in, $check_in, $check_out, $check_out, $check_in, $check_out]);

        if ($stmt->fetch()) {
            $errors[] = "Room is already booked for selected dates.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, status) VALUES (?, ?, ?, ?, 'Pending')");
            if ($stmt->execute([$_SESSION['user_id'], $room_id, $check_in, $check_out])) {
                $success = "Room booked successfully! Awaiting confirmation.";
            } else {
                $errors[] = "Failed to book room.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js"></script>

    <style>
        .card-img-top {
            max-height: 250px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <a href="dashboard.php" class="btn btn-secondary mb-4">← Back to Dashboard</a>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <img src="../assets/images/<?= htmlspecialchars($room['image']) ?>" class="card-img-top" alt="<?= $room['title'] ?>">
                <div class="card-body">
                    <h4 class="card-title"><?= $room['title'] ?></h4>
                    <p><?= $room['description'] ?></p>
                    <p><strong>Price:</strong> $<?= $room['price'] ?> per night</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h4>Book This Room</h4>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error) echo "<div>$error</div>"; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Check-in Date</label>
                    <input type="date" name="check_in" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Check-out Date</label>
                    <input type="date" name="check_out" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary">Book Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
