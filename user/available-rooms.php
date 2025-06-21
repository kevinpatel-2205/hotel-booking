<?php
require_once '../inc/db.php';
require_once '../inc/auth.php';
requireUserLogin(); 


$stmt = $pdo->query("SELECT * FROM rooms ORDER BY id DESC");
$rooms = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Rooms</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Available Rooms</h3>
    <div>
      <a href="dashboard.php" class="btn btn-secondary">My Bookings</a>
      <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>

  <div class="row">
    <?php foreach ($rooms as $room): ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow rounded-4">
          <img src="../assets/images/<?= htmlspecialchars($room['image']) ?>" class="card-img-top" alt="<?= $room['title'] ?>" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($room['title']) ?></h5>
            <p class="card-text">
              <?= nl2br(htmlspecialchars($room['description'])) ?><br>
              <strong>Price: $<?= $room['price'] ?>/night</strong>
            </p>
            <a href="book-room.php?room_id=<?= $room['id'] ?>" class="btn btn-primary w-100">Book Now</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
