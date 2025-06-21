<?php
require_once '../inc/db.php';
require_once '../inc/auth.php';
require_once '../inc/functions.php';

requireAdminLogin();

if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $booking_id = $_GET['id'];

    if (in_array($action, ['Confirmed', 'Cancelled'])) {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$action, $booking_id]);
        header("Location: manage-bookings.php");
        exit;
    }
}

$stmt = $pdo->query("
    SELECT b.*, u.name AS user_name, r.title AS room_title
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN rooms r ON b.room_id = r.id
    ORDER BY b.created_at DESC
");
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function confirmAction(msg) {
            return confirm(msg);
        }
    </script>
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .Pending { background-color: #ffc107; color: #000; }
        .Confirmed { background-color: #28a745; color: #fff; }
        .Cancelled { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Manage Bookings</h3>
        <a href="dashboard.php" class="btn btn-outline-primary">Admin Dashboard</a>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">No bookings yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $i => $b): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= e($b['user_name']) ?></td>
                            <td><?= e($b['room_title']) ?></td>
                            <td><?= e($b['check_in']) ?></td>
                            <td><?= e($b['check_out']) ?></td>
                            <td><?= formatStatusBadge($b['status']) ?></td>
                            <td>
                                <?php if ($b['status'] === 'Pending'): ?>
                                    <a href="?action=Confirmed&id=<?= $b['id'] ?>" class="btn btn-success btn-sm" onclick="return confirmAction('Confirm this booking?')">Confirm</a>
                                    <a href="?action=Cancelled&id=<?= $b['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmAction('Cancel this booking?')">Cancel</a>
                                <?php else: ?>
                                    <span class="text-muted">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
