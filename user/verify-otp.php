<?php
session_start();
require_once '../inc/db.php';

$errors = [];

if (!isset($_SESSION['reset_email'])) {
    $_SESSION['message'] = "Please enter your email to receive an OTP.";
    header("Location: forgot-password.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'] ?? '';

    if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry'])) {
        $_SESSION['message'] = "OTP session expired. Please request a new OTP.";
        header("Location: forgot-password.php");
        exit;
    } elseif (time() > $_SESSION['otp_expiry']) {
        $_SESSION['message'] = "OTP has expired. Please request a new one.";
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);
        header("Location: forgot-password.php");
        exit;
    } elseif ($entered_otp == $_SESSION['otp']) {
        $_SESSION['otp_verified'] = true;
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);
        header("Location: reset-password.php");
        exit;
    } else {
        $errors[] = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <h4 class="mb-4 text-center">Verify OTP</h4>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error) echo "<div>$error</div>"; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-info"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Enter the OTP sent to your email</label>
                            <input type="text" name="otp" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Verify OTP</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        Didn't receive the OTP? <a href="forgot-password.php">Request again</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
