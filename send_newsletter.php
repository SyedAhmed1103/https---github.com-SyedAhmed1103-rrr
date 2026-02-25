<?php

header("Content-Type: application/json");

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

$email = htmlspecialchars(trim($_POST['mail'] ?? ''));

if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "Email is required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address"]);
    exit;
}

$mail = new PHPMailer(true);

try {

    // SAME SMTP SETTINGS
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'inquiry@saifandbrothers.com';
    $mail->Password   = 'Inquiry@@1234';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('inquiry@saifandbrothers.com', 'Website Newsletter');
    $mail->addAddress('syedahmedimam377@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = "New Newsletter Subscription";

    $mail->Body = "
    <div style='font-family: Arial; padding:20px; background:#f4f4f4;'>
        <div style='max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:8px;'>
            <h2 style='color:#333;'>New Newsletter Subscriber</h2>
            <hr>
            <p><strong>Email:</strong> $email</p>
            <hr>
            <p style='font-size:12px;color:#777;'>Subscribed from website newsletter form.</p>
        </div>
    </div>";

    $mail->send();

    echo json_encode([
        "status" => "success",
        "message" => "Successfully subscribed! ✅"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Subscription failed. Try again later."
    ]);
}