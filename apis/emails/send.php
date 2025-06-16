<?php
include "../../includes/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed. Only POST requests are allowed.']);
    exit();
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Extract data
$email = strtolower($data['email']);
$email = str_replace(' ', '', $email);
$emailID = $data['emailID'];
$templateID = $data['templateID'];
$subject = $data['subject'];
$pageURL = $data['pageURL'];
$userID = $data['userID'];
$EmailListID = $data['EmailListID'];
$Message = "";

$TemplateUrl = $LINK . '/templates/email/?TemplateID=' . $templateID;

// Check if the email log already exists
$stmt = $conn->prepare("SELECT Status FROM `_email_logs` WHERE EmailID = :EmailID AND Email = :Email");
$stmt->bindParam(':EmailID', $emailID);
$stmt->bindParam(':Email', $email);
$stmt->execute();
$existingLog = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingLog) {
    // Email log already exists
    $response['status'] = $existingLog['Status'];
    $response['message'] = $existingLog['Status'] === 'Success' ? 'Email already sent' : 'Failed to send email previously';
} else {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $host = "broad-mead.com";
        $user = "@nocturnalrecruitment.co.uk"; // Updated domain
        $pass = "@Michael1693250341";

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('nocturnalrecruitment@broad-mead.co.uk', 'Nocturnal Recruitment'); // Updated domain
        $mail->addAddress($email, "name");

        // Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';  // Set UTF-8 encoding
        $mail->addCustomHeader('Content-Type', 'text/html; charset=UTF-8'); // Optional but recommended
        $mail->Subject = $subject;

        if ($templateID) {
            $templateContent = file_get_contents($TemplateUrl);
            if ($templateContent !== false) {
                // Replace {message} placeholder with the actual message content
                $mail->Body = str_replace('{message}', $Message, $templateContent);
            } else {
                throw new Exception("Failed to fetch email template.");
            }
        } else {
            $mail->Body = $Message;
        }

        // Add email signature
        $signature = "<br><br>Best regards,<br>Nocturnal Recruitment<br>Office 16, 321 High Road,<br>RM6 6AX<br>Tel: 0208 050 2708<br>
        Mobile: 0755 357 0871<br>
        Email: <a href='mailto:info@nocturnalrecruitment.co.uk'>info@nocturnalrecruitment.co.uk</a><br>
        Website: <a href='http://www.nocturnalrecruitment.co.uk'>www.nocturnalrecruitment.co.uk</a><br>
        <br>Company Registration: 11817091<br><br>
        
        <!-- Insert logos here -->
        <div style='margin-top: 20px;'>
            <img src='logos/logo1.png' alt='Logo 1' style='height: 50px; margin-right: 15px;'>
            <img src='logos/logo2.png' alt='Logo 2' style='height: 50px; margin-right: 15px;'>
            <img src='logos/logo3.png' alt='Logo 3' style='height: 50px;'>
        </div>
        
        <br><strong>Disclaimer:</strong> This email is intended only for the use of the addressee named above and may be confidential or legally privileged.
         If you are not the addressee, you must not read it and must not use any information contained in nor copy it nor inform any person other than Nocturnal Recruitment or the addressee of its existence or contents. 
         If you have received this email in error, please delete it and notify our team at <a href='mailto:info@nocturnalrecruitment.co.uk'>info@nocturnalrecruitment.co.uk</a>.";
        // Define your signature
        $mail->Body .= $signature; // Append signature

        $mail->send();
        $response['status'] = 'Success';
        $response['message'] = 'Email sent successfully';
        $Description = "Email was successfully sent to $email";
        $status = "Success";
    } catch (Exception $e) {
        // Log the error message
        error_log('Mail Error: ' . $e->getMessage());
        $response['status'] = 'failed';
        $response['message'] = 'Failed to send email';
        $Description = "Error. Email failed to send to $email";
        $status = "Fail";
    }

    // Insert new log
    $stmt = $conn->prepare("INSERT INTO `_email_logs` (ClientKeyID, Description, EmailID, Email, Status, PageUrl, CreatedBy, Date) VALUES (:ClientKeyID, :Description, :EmailID, :Email, :Status, :PageUrl, :CreatedBy, NOW())");
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':Description', $Description);
    $stmt->bindParam(':EmailID', $emailID);
    $stmt->bindParam(':Email', $email);
    $stmt->bindParam(':Status', $status);
    $stmt->bindParam(':PageUrl', $pageURL);
    $stmt->bindParam(':CreatedBy', $userID);
    $stmt->execute();
}

// Count the emails sent
$totalEmailsSent = $conn->query("SELECT COUNT(*) FROM `_email_logs` WHERE EmailID = '$emailID' AND Status = 'Success'")->fetchColumn();
$totalEmailsList = $conn->query("SELECT COUNT(*) FROM `_email_list` WHERE ListID = '$EmailListID' AND Email != ''")->fetchColumn();

if ($totalEmailsList == $totalEmailsSent) {
    $update = $conn->query("UPDATE `_emails` SET Status = 'Sent' WHERE EmailID = '$emailID'");
} else {
    $update = $conn->query("UPDATE `_emails` SET Status = 'Sending...' WHERE EmailID = '$emailID'");
}

header('Content-Type: application/json');
echo json_encode($response);

