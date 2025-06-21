<?php
require_once '../inc/db.php';
require_once '../inc/auth.php';
require_once '../inc/functions.php';

requireAdminLogin();

if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];

    $pdo->prepare("DELETE FROM bookings WHERE user_id = ?")->execute([$userId]);

    $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'user'")->execute([$userId]);

    header("Location: manage-users.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Manage Users</h3>
        <a href="dashboard.php" class="btn btn-outline-primary">Admin Dashboard</a>
    </div>

    <?php if (empty($users)): ?>
        <div class="alert alert-info">No users found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $i => $u): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= e($u['name']) ?></td>
                            <td><?= e($u['email']) ?></td>
                            <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
                            <td>
                                <a href="?delete=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmAction('Are you sure you want to delete this user?')">Delete</a>
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
