<?php
require '../../vendor/autoload.php'; // Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Email details
    $email = "euphemiachikungulu347@gmail.com";
    $name = "Euphemia Chikungulu";
    $subject = "Test Email from Nocturnal Recruitment";

    $message = "
        <p>Dear {$name},</p>
        <p>This is a test email sent from recruitmentnocturnal@gmail.com</p>
        <p>Replying to this will send your email to info@nocturnalrecruitment.co.uk</p>
    ";

    // SMTP settings
    $mail->isSMTP(); // <--- UNCOMMENT THIS LINE
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'recruitmentnocturnal@gmail.com';
    $mail->Password = 'hbaa qcvq wxkk kmcm'; // 16-character app password - MAKE SURE THIS IS AN APP PASSWORD
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('recruitmentnocturnal@gmail.com', 'Nocturnal Recruitment');
    $mail->addReplyTo('info@nocturnalrecruitment.co.uk', 'Nocturnal Recruitment');
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = strip_tags($message);

    $mail->send();
    echo '✅ Email sent successfully!';
} catch (Exception $e) {
    echo "❌ Mailer Error: {$mail->ErrorInfo}";
}