<?php
// Remove the 'use' statements for now, as Composer's autoloader handles namespaces.
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\SMTP; // No longer needed if using Composer autoloader

// This is the ONLY require statement you typically need when using Composer.
// It will be found relative to your 'broadmead' root, so you need to go up two levels.
require '../../vendor/autoload.php';

// Now you can use the PHPMailer classes directly with their full namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // If you explicitly use SMTP constant, include this

$mail = new PHPMailer(true);

try {
    // --- Define your email content here for testing ---
    $email = "euphemiachikungulu347@gmail.com"; // Your target email address
    $name = "Euphemia Chikungulu"; // Name of the recipient
    $subject = "Test Email from Nocturnal Recruitment"; // Your email subject
    $message = "
        <p>Dear {$name},</p>
        <p>This is a test email sent from your PHPMailer script on Nocturnal Recruitment's server.</p>
        <p>If you received this, it means your SMTP settings are likely correct!</p>
        <p>Best regards,</p>
        <p>The Nocturnal Recruitment Team</p>
    ";
    // ---------------------------------------------------

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.nocturnalrecruitment.co.uk'; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'info@nocturnalrecruitment.co.uk'; // Your email
    $mail->Password = 'access220'; // Your password for info@nocturnalrecruitment.co.uk
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
    $mail->Port = 587; // Standard port for TLS

    // *** IMPORTANT: Enable detailed debugging for troubleshooting ***
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;// Shows SMTP server communication
    $mail->Debugoutput = function($str, $level) {
        error_log("PHPMailer Debug ($level): " . $str); // Log to PHP error log
        echo "PHPMailer Debug ($level): " . htmlspecialchars($str) . "<br>"; // Also output to screen
    };


    // Recipients
    $mail->setFrom('info@nocturnalrecruitment.co.uk', 'Nocturnal Recruitment'); // Sender email and name
    $mail->addReplyTo('info@nocturnalrecruitment.co.uk', 'Nocturnal Recruitment'); // Added reply-to
    $mail->addAddress($email, $name); // Recipient email and name

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = strip_tags($message); // Plain text version for non-HTML clients

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

    $mail->Body .= $signature; // Append signature to the email body

    $mail->send();
    echo 'Message has been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    // You can also log this to a file for more persistent debugging:
    error_log("PHPMailer Error: {$mail->ErrorInfo}");
}