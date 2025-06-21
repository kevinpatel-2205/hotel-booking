<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4">Forgot Password</h4>

                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-info">
                            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="../otp/process_otp.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Enter Your Registered Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="send_otp" class="btn btn-primary">Send OTP</button>
                        </div>
                        <div class="text-center mt-3">
                            <a href="login.php">Back to Login</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
