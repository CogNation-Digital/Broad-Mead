<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . "$DIRECTORY/vendor/autoload.php";

function sendVerificationEmail($to, $name, $code)
{
    global $DIRECTORY;
    $mail = new PHPMailer(true);
 

    try {
        // Server settings

        $host = "broad-mead.com";
        $user = "nocturnalrecruitment@broad-mead.com";
        $pass = "@Michael1693250341";
        $mail = new PHPMailer(true);
 
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('nocturnalrecruitment@broad-mead.com', 'Nocturnal Recruitment');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';

        // Load HTML template
        $template = $_SERVER['DOCUMENT_ROOT'] . "$DIRECTORY/landing/email/template.html";

        $htmlBody = file_get_contents($template);
        $htmlBody = str_replace('{name}', $name, $htmlBody);
        $htmlBody = str_replace('{code}', $code, $htmlBody);

        $mail->Body = $htmlBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo} :: {$e->getMessage()}";
        return false;
    }
}
