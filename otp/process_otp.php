<?php
session_start();
ob_start();

require '../inc/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_otp'])) {
        sendOtp($pdo);
    } elseif (isset($_POST['verify_otp'])) {
        verifyOtp();
    }
}

function generateOTP($length = 6)
{
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

function sendOtp($pdo)
{
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        header("Location: ../user/forgot-password.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['message'] = "Email not registered. Please sign up first.";
        header("Location: ../user/forgot-password.php");
        exit;
    }

    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['reset_email'] = $email;
    $_SESSION['otp_expiry'] = time() + 300;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '';
        $mail->Password   = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('', 'Hotel Booking OTP');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your One-Time Password (OTP) is: $otp\n\nIt is valid for 5 minutes.";

        $mail->send();
        $_SESSION['message'] = "OTP sent successfully to $email";

        header("Location: ../user/verify-otp.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['message'] = "OTP could not be sent. Error: {$mail->ErrorInfo}";
        header("Location: ../user/forgot-password.php");
        exit;
    }
}

function verifyOtp()
{
    $enteredOTP = $_POST['otp'] ?? '';

    if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry']) || !isset($_SESSION['reset_email'])) {
        $_SESSION['message'] = "OTP session expired. Please request a new OTP.";
        header("Location: ../user/forgot-password.php");
        exit;
    }

    if (time() > $_SESSION['otp_expiry']) {
        $_SESSION['message'] = "OTP has expired. Please request a new one.";
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);
        header("Location: ../user/forgot-password.php");
        exit;
    }

    if ($enteredOTP == $_SESSION['otp']) {
        $_SESSION['otp_verified'] = true;
        header("Location: ../user/reset-password.php");
        exit;
    } else {
        $_SESSION['message'] = "Invalid OTP. Please try again.";
        header("Location: ../user/verify-otp.php");
        exit;
    }
}
