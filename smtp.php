<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // or adjust path to your PHPMailer files

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.zoho.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'euphemiachikungulu347@zoho.com';
    $mail->Password = 'Brave220'; // Replace with actual password or app password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = '465';

    $mail->setFrom('euphemiachikungulu347@zoho.com', 'Zoho Test Sender');
    $mail->addAddress('euphemiachikungulu347@zoho.com', 'Test Recipient'); // Replace with your own test email

    $mail->isHTML(true);
    $mail->Subject = 'Zoho SMTP Test';
    $mail->Body = '<b>This is a test email sent via Zoho SMTP.</b>';
    $mail->AltBody = 'This is a test email sent via Zoho SMTP.';

    $mail->send();
    echo "✅ Test email sent successfully!";
} catch (Exception $e) {
    echo "❌ Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
