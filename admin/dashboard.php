<?php
require_once '../inc/db.php';
require_once '../inc/auth.php';
requireAdminLogin();

$name = $_SESSION['name'];

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pendingBookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'Pending'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Welcome Admin, <?= htmlspecialchars($name) ?> 👋</h3>
        <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
    </div>

    <div class="row text-center mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow p-3 rounded-4 bg-light">
                <h5>Total Users</h5>
                <h3><?= $totalUsers ?></h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow p-3 rounded-4 bg-light">
                <h5>Total Bookings</h5>
                <h3><?= $totalBookings ?></h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow p-3 rounded-4 bg-light">
                <h5>Pending Bookings</h5>
                <h3><?= $pendingBookings ?></h3>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center gap-3">
        <a href="manage-bookings.php" class="btn btn-primary btn-lg px-4">Manage Bookings</a>
        <a href="manage-users.php" class="btn btn-secondary btn-lg px-4">Manage Users</a>
    </div>
</div>

</body>
</html>
