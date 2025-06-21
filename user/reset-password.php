<?php
session_start();
require_once '../inc/db.php';

$errors = [];
$success = '';

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true || !isset($_SESSION['reset_email'])) {
    $_SESSION['message'] = "Unauthorized access to password reset.";
    header("Location: forgot-password.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirm)) {
        $errors[] = "Both password fields are required.";
    } elseif ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$password, $_SESSION['reset_email']]);

        unset($_SESSION['otp_verified']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);

        $success = "Password reset successfully. <a href='login.php'>Click here to login</a>.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <h4 class="mb-4 text-center">Reset Password</h4>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error) echo "<div>$error</div>"; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php else: ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label>New Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
