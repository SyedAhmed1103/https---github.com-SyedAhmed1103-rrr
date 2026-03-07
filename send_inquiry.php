<?php

header("Content-Type: application/json");

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$logFile = "inquiry_log.txt";

function writeLog($message) {
    global $logFile;
    $time = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$time] $message\n", FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

/* INPUT SANITIZE */

$fname   = htmlspecialchars(trim($_POST['fname'] ?? ''));
$lname   = htmlspecialchars(trim($_POST['lname'] ?? ''));
$phone   = htmlspecialchars(trim($_POST['phone'] ?? ''));
$email   = htmlspecialchars(trim($_POST['email'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

/* PRODUCT NAME (OPTIONAL) */

$product = htmlspecialchars(trim($_POST['product_name'] ?? ''));

if(!empty($product)){
    $inquirySource = "Product Inquiry - $product";
}else{
    $inquirySource = "Form Submitted from Contact Page";
}


/* VALIDATION */

if (empty($fname) || empty($lname) || empty($phone) || empty($email)) {
    echo json_encode([
        "status" => "error",
        "message" => "All required fields are mandatory"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email address"
    ]);
    exit;
}


$mail = new PHPMailer(true);

try {

    /* SMTP CONFIG */

    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'inquiry@saifandbrothers.com';
    $mail->Password   = 'Inquiry@@1234';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('inquiry@saifandbrothers.com', 'Website Inquiry');

    $mail->addAddress('syedahmedimam377@gmail.com');

    $mail->isHTML(true);

    /* SUBJECT */

    $mail->Subject = "$inquirySource from $fname $lname";


    /* EMAIL TEMPLATE */

    $mail->Body = "

    <div style='font-family:Arial;padding:20px;background:#f4f4f4;'>

        <div style='max-width:600px;margin:auto;background:#fff;padding:25px;border-radius:8px;'>

            <h2 style='color:#333;'>New Website Inquiry</h2>

            <hr>

            <p><strong>Inquiry Source:</strong> $inquirySource</p>

            <p><strong>Name:</strong> $fname $lname</p>

            <p><strong>Email:</strong> $email</p>

            <p><strong>Phone:</strong> $phone</p>

            <p><strong>Message:</strong><br>$message</p>

            <hr>

            <p style='font-size:12px;color:#777;'>
            This inquiry was submitted from your website.
            </p>

        </div>

    </div>";

    $mail->send();

    writeLog("SUCCESS: $inquirySource from $email");

    echo json_encode([
        "status" => "success",
        "message" => "Thank you! Your inquiry has been sent successfully ✅"
    ]);

} catch (Exception $e) {

    writeLog("ERROR: " . $mail->ErrorInfo);

    echo json_encode([
        "status" => "error",
        "message" => "Mail could not be sent. Please try again later."
    ]);
}