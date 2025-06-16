<?php
$signature = "<br><br>Best regards,<br>Nocturnal Recruitment<br>Office 16, 321 High Road,<br>RM6 6AX<br>Tel: 0208 050 2708<br>
    Mobile: 0755 357 0871<br>
    Email: <a href='mailto:info@nocturnalrecruitment.co.uk'>info@nocturnalrecruitment.co.uk</a><br>
    Website: <a href='http://www.nocturnalrecruitment.co.uk'>www.nocturnalrecruitment.co.uk</a><br>
    <br>Company Registration: 11817091<br><br>
    <div style='margin-top: 20px;'>
        <img src='broadmead\logos\FCSA.png' alt='Logo 1' style='height: 50px; margin-right: 15px;'>
        <img src='broadmead\logos\nocturnal-recruitment1--e1553184816317.png' alt='Logo 2' style='height: 50px; margin-right: 15px;'>
        <img src='broadmead\logos\xCorporateMember-dark-2048x584.png.pagespeed.ic.gZuAKa4fmz.png' alt='REC Corporate Member' style='height: 50px;'>
    </div>
    <br><strong>Disclaimer:</strong> This email is confidential...</div>";

$to = "euphemiachikungulu347@gmail.com"; // Replace with your email
$subject = "TEST: Email Signature Preview";
$message = "<p>This is a test email to check how the signature renders.</p>" . $signature;
$headers = "From: info@nocturnalrecruitment.co.uk\r\n";
$headers .= "Reply-To: info@nocturnalrecruitment.co.uk\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

mail($to, $subject, $message, $headers);
echo "Test email sent! Check your inbox.";
?>