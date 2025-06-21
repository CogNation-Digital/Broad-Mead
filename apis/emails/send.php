<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $host = "mail.nocturnalrecruitment.co.uk"; // Updated to your domain's mail server
    $user = "info@nocturnalrecruitment.co.uk"; // Corrected email format
    $pass = "@Michael1693250341"; // Using existing password

    $mail->isSMTP();
$mail->Host = 'smtp.nocturnalrecruitment.co.uk'; // Your SMTP server
$mail->SMTPAuth = true;
$mail->Username = 'info@nocturnalrecruitment.co.uk'; // Your email
$mail->Password = 'access220'; // Your password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
$mail->Port = 587; // Standard port for TLS

    // Recipients
    $mail->setFrom('info@nocturnalrecruitment.co.uk', 'Nocturnal Recruitment'); // Updated sender
    $mail->addReplyTo('info@nocturnalrecruitment.co.uk', 'Nocturnal Recruitment'); // Added reply-to
    $mail->addAddress($email, "name");

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = strip_tags($message);

    // Add professional email footer
    $signature = '
<div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #f0f0f0; font-family: Arial, sans-serif;">
    <table style="width: 100%; max-width: 600px;">
        <tr>
            <td style="text-align: center; padding-bottom: 20px;">
                <img src="https://nocturnalrecruitment.co.uk/logo.png" alt="Nocturnal Recruitment" style="max-width: 200px; height: auto;">
            </td>
        </tr>
        <tr>
            <td style="text-align: center; color: #333; font-size: 14px; line-height: 1.6;">
                <strong>Nocturnal Recruitment</strong><br>
                Office 16, 321 High Road, RM6 6AX<br><br>
                
                <div style="margin: 15px 0;">
                    <a href="tel:02080502708" style="color: #007bff; text-decoration: none;">0208 050 2708</a> &nbsp;&nbsp;
                    <a href="tel:07553570871" style="color: #007bff; text-decoration: none;">0755 357 0871</a><br>
                    <a href="mailto:chax@nocturnalrecruitment.co.uk" style="color: #007bff; text-decoration: none;">chax@nocturnalrecruitment.co.uk</a><br>
                    <a href="https://www.nocturnalrecruitment.co.uk" style="color: #007bff; text-decoration: none;">www.nocturnalrecruitment.co.uk</a>
                </div>
                
                <div style="margin: 20px 0; font-size: 12px; color: #666;">
                    <strong>Company Registration – 11817091</strong>
                </div>
                
                <div style="margin-top: 25px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; font-size: 11px; color: #666; text-align: left;">
                    <strong>Disclaimer*</strong> This email is intended only for the use of the addressee named above and may be confidential or legally privileged. If you are not the addressee, you must not read it and must not use any information contained in nor copy it nor inform any person other than Nocturnal Recruitment or the addressee of its existence or contents. If you have received this email in error, please delete it and notify our team at <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #007bff;">info@nocturnalrecruitment.co.uk</a>
                </div>
            </td>
        </tr>
    </table>
</div>';

    $mail->Body .= $signature;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
