<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer autoloader
require 'vendor/autoload.php'; // adjust if PHPMailer is in a different folder

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = 2; // Verbose debug output (change to 0 to disable)
    $mail->isSMTP();
    $mail->Host = 'smtp.zoho.com';   // Correct
    $mail->SMTPAuth   = true;
    $mail->Username   = 'bervinitsolutions@zohomail.com';
    $mail->Password   = '!3erv!n@6!S4@Z0H0';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = '587';


    // Recipients
    $mail->setFrom('bervinitsolutions@zohomail.com', 'Nocturnal Recruitment');
    $mail->addAddress('euphemiachikungulu347@gmail.com', 'Test Recipient'); // Replace with your own test email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'SMTP Test Email';
    $mail->Body    = '<b>This is a test email using PHPMailer SMTP.</b>';
    $mail->AltBody = 'This is a test email using PHPMailer SMTP.';

    $mail->send();
    echo "✅ Test email sent successfully!";
} catch (Exception $e) {
    echo "❌ Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

