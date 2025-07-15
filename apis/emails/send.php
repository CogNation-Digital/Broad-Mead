<?php
require '../../vendor/autoload.php'; // Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Include for SMTP::DEBUG_SERVER if needed

$mail = new PHPMailer(true);

try {
    // Email details
    $email = "euphemiachikungulu347@gmail.com";
    $name = "Euphemia Chikungulu";
    $subject = "Test Email from Nocturnal Recruitment";

    // Define the signature HTML
    // IMPORTANT: Local image paths (e.g., 'broadmead\logos\FCSA.png') will NOT work in emails.
    // They must be absolute URLs (e.g., 'https://yourdomain.com/path/to/FCSA.png').
    // I'm using placeholder URLs here. You MUST replace them with your actual public image URLs.
    $signature = "
    <br><br>Best regards,<br>Nocturnal Recruitment<br>Office 16, 321 High Road,<br>RM6 6AX<br>Tel: 0208 050 2708<br>
    Mobile: 0755 357 0871<br>
    Email: <a href='mailto:info@nocturnalrecruitment.co.uk'>info@nocturnalrecruitment.co.uk</a><br>
    
    Website: <a href='http://www.nocturnalrecruitment.co.uk'>www.nocturnalrecruitment.co.uk</a><br>
    <br>Company Registration: 11817091<br><br>
    <div style='margin-top: 20px;'>
        <img src='https://yourdomain.com/broadmead/logos/FCSA.png' alt='Logo 1' style='height: 50px; margin-right: 15px;'>
        <img src='https://yourdomain.com/broadmead/logos/nocturnal-recruitment1--e1553184816317.png' alt='Logo 2' style='height: 50px; margin-right: 15px;'>
        <img src='https://yourdomain.com/broadmead/logos/xCorporateMember-dark-2048x584.png' alt='REC Corporate Member' style='height: 50px;'>
    </div>
    <br><strong>Disclaimer:</strong> This email is intended only for the use of the addressee named above and may be confidential or legally privileged. If you are not the addressee, you must not read it and must not use any information contained in nor copy it nor inform any person other than Nocturnal Recruitment or the addressee of its existence or contents. If you have received this email in error, please delete it and notify our team at <a href='mailto:info@nocturnalrecruitment.co.uk'>info@nocturnalrecruitment.co.uk</a></div>"; // Ensure the div is properly closed

    // Construct the full email message by appending the signature
    $message = "
        <p>Dear {$name},</p>
        <p>hey girl you almost got it girllly</p>
        <p>Replying to this will send your email to info@nocturnalrecruitment.co.uk</p>
    " . $signature; // Append the signature here

    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'recruitmentnocturnal@gmail.com';
    $mail->Password = 'hbaa qcvq wxkk kmcm'; // Your 16-character app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('recruitmentnocturnal@gmail.com', 'Nocturnal Recruitment');
    $mail->addReplyTo('info@nocturnalrecruitment.co.uk', 'Nocturnal Recruitment'); // Correct Reply-To
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message); // AltBody for plain text clients

    $mail->send();
    echo '✅ Email sent successfully!';
} catch (Exception $e) {
    echo "❌ Mailer Error: {$mail->ErrorInfo}";
}
