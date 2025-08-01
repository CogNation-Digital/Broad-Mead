<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/login");
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname1 = 'broadmead';
$dbname2 = 'broadmead_v3';

try {
    $db_1 = new PDO('mysql:host=' . $host . ';dbname=' . $dbname1, $user, $password);
    $db_1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
    $db_2 = new PDO('mysql:host=' . $host . ';dbname=' . $dbname2, $user, $password);
    $db_2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<b>Database Connection Error: </b> " . $e->getMessage();
    exit;
}

try {
    // Create email_tracking table with mailshot_id reference
    $db_2->exec("
        CREATE TABLE IF NOT EXISTS `email_tracking` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `mailshot_id` int(11) DEFAULT NULL,
          `client_id` int(11) NOT NULL,
          `consultant_email` varchar(255) NOT NULL,
          `consultant_name` varchar(255) NOT NULL,
          `subject` varchar(500) NOT NULL,
          `sent_date` datetime NOT NULL,
          `delivery_status` enum('sent','delivered','bounced','failed') DEFAULT 'sent',
          `read_status` enum('unread','opened','replied') DEFAULT 'unread',
          `reply_date` datetime DEFAULT NULL,
          `bounce_reason` text DEFAULT NULL,
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_mailshot_id` (`mailshot_id`),
          KEY `idx_client_id` (`client_id`),
          KEY `idx_consultant_email` (`consultant_email`),
          KEY `idx_sent_date` (`sent_date`),
          KEY `idx_delivery_status` (`delivery_status`),
          KEY `idx_read_status` (`read_status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Update mailshot_log table with consultant columns
    $columns_check = $db_2->query("SHOW COLUMNS FROM mailshot_log");
    $existing_columns = [];
    while ($col = $columns_check->fetch(PDO::FETCH_OBJ)) {
        $existing_columns[] = $col->Field;
    }
   
    if (!in_array('SentByEmail', $existing_columns)) {
        $db_2->exec("ALTER TABLE `mailshot_log` ADD COLUMN `SentByEmail` varchar(255) DEFAULT NULL AFTER `SentBy`");
    }
    if (!in_array('ConsultantName', $existing_columns)) {
        $db_2->exec("ALTER TABLE `mailshot_log` ADD COLUMN `ConsultantName` varchar(255) DEFAULT NULL AFTER `SentByEmail`");
    }
    if (!in_array('is_completed', $existing_columns)) {
        $db_2->exec("ALTER TABLE `mailshot_log` ADD COLUMN `is_completed` tinyint(1) DEFAULT 0 AFTER `ConsultantName`");
    }
} catch (Exception $e) {
    error_log("Database setup error: " . $e->getMessage());
}

$loggedInUserEmail = '';
$loggedInUserName = '';
$USERID = $_COOKIE['USERID'] ?? null;

if ($USERID) {
    try {
        $stmt = $db_2->prepare("SELECT Email, Name FROM users WHERE UserID = :userid");
        $stmt->bindParam(':userid', $USERID);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if ($user) {
            $loggedInUserEmail = strtolower($user->Email);
            $loggedInUserName = $user->Name ?? 'Consultant';
        }
    } catch (PDOException $e) {
        error_log("Error fetching user email: " . $e->getMessage());
    }
}

// Consultant mapping with names
$consultantMapping = [
    'jayden@nocturnalrecruitment.co.uk' => 'Jayden',
    'jourdain@nocturnalrecruitment.co.uk' => 'Jourdain',
    'junaid@nocturnalrecruitment.co.uk' => 'Junaid',
    'casey@nocturnalrecruitment.co.uk' => 'Casey',
    'samantha@nocturnalrecruitment.co.uk' => 'Samantha',
    'millie@nocturnalrecruitment.co.uk' => 'Millie Brown',
    'valter@nocturnalrecruitment.co.uk' => 'Valter',
    'euphemiachikungulu347@gmail.com' => 'Euphemia',
    'alex@nocturnalrecruitment.co.uk' => 'Alex Lapompe',
    'j.dowler@nocturnalrecruitment.co.uk' => 'Jack Dowler',
    'chax@nocturnalrecruitment.co.uk' => 'Chax Shamwana'
];

$allowedExportEmails = [
    'alex@nocturnalrecruitment.co.uk',
    'j.dowler@nocturnalrecruitment.co.uk',
    'chax@nocturnalrecruitment.co.uk'
];

// ENHANCED SMTP Configuration - Anti-Spam Optimized
function getSMTPConfig() {
    return [
        'host' => 'smtp.titan.email',
        'username' => 'learn@natec.icu',
        'password' => '@WhiteDiamond0100',
        'port' => 587,
        'encryption' => PHPMailer::ENCRYPTION_STARTTLS,
        'auth' => true,
        'timeout' => 60,
        'keepalive' => true,
        'options' => [
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => false,
                // 'cafile' => '/etc/ssl/certs/ca-certificates.crt'
            ]
        ]
    ];
}

// Enhanced email footer with proper HTML structure
function getEmailFooter($consultantEmail, $consultantName) {
    return '
    <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #ffffff; font-family: Arial, sans-serif; border-radius: 8px; max-width: 600px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #ffffff; font-size: 18px; font-weight: bold;">Nocturnal Recruitment</h3>
            <p style="margin: 5px 0 0 0; color: #b8d4ff; font-size: 14px;">Your Trusted Recruitment Partner</p>
        </div>
       
        <table style="width: 100%; margin-bottom: 20px;" cellpadding="5" cellspacing="0">
            <tr>
                <td style="text-align: center; vertical-align: top; width: 33%;">
                    <div style="color: #6daffb; font-size: 12px; margin-bottom: 3px;">📍 Address</div>
                    <div style="color: #ffffff; font-size: 11px;">Office 16, 321 High Road, RM6 6AX</div>
                </td>
                <td style="text-align: center; vertical-align: top; width: 33%;">
                    <div style="color: #6daffb; font-size: 12px; margin-bottom: 3px;">📞 Phone</div>
                    <div style="color: #ffffff; font-size: 11px;">0208 050 2708</div>
                </td>
                <td style="text-align: center; vertical-align: top; width: 33%;">
                    <div style="color: #6daffb; font-size: 12px; margin-bottom: 3px;">📱 Mobile</div>
                    <div style="color: #ffffff; font-size: 11px;">0755 357 0871</div>
                </td>
            </tr>
        </table>
       
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="color: #6daffb; font-size: 12px; margin-bottom: 3px;">✉️ Your Consultant</div>
            <div style="color: #ffffff; font-size: 13px; font-weight: bold;">' . htmlspecialchars($consultantName) . '</div>
            <div style="color: #b8d4ff; font-size: 11px;">
                <a href="mailto:' . htmlspecialchars($consultantEmail) . '" style="color: #6daffb; text-decoration: none;">' . htmlspecialchars($consultantEmail) . '</a>
            </div>
        </div>
       
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="color: #6daffb; font-size: 12px; margin-bottom: 8px;">🌐 Connect With Us</div>
            <div>
                <a href="https://www.nocturnalrecruitment.co.uk" target="_blank" style="color: #6daffb; text-decoration: none; margin: 0 5px; font-size: 11px;">Website</a> |
                <a href="https://www.linkedin.com/company/nocturnalrecruitment" target="_blank" style="color: #6daffb; text-decoration: none; margin: 0 5px; font-size: 11px;">LinkedIn</a> |
                <a href="https://www.instagram.com/nocturnalrecruitment" target="_blank" style="color: #6daffb; text-decoration: none; margin: 0 5px; font-size: 11px;">Instagram</a> |
                <a href="https://www.facebook.com/nocturnalrecruitment" target="_blank" style="color: #6daffb; text-decoration: none; margin: 0 5px; font-size: 11px;">Facebook</a>
            </div>
        </div>
       
        <div style="text-align: center; border-top: 1px solid #4a6fa5; padding-top: 15px;">
            <div style="color: #b8d4ff; font-size: 10px; margin-bottom: 5px;">Company Registration: 11817091 | REC Corporate Member</div>
            <div style="color: #8bb3e8; font-size: 9px; line-height: 1.3;">
                This email is confidential and intended only for the addressee. If you are not the intended recipient,
                please delete this email and notify us at <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #6daffb;">info@nocturnalrecruitment.co.uk</a>
            </div>
        </div>
       
        <div style="text-align: center; margin-top: 10px; font-size: 9px; color: #8bb3e8;">
            BroadMead 3.0 © 2025 - Powered by <a href="https://www.cog-nation.com" target="_blank" style="color: #E1AD01; text-decoration: none; font-weight: bold;">CogNation Digital</a>
        </div>
    </div>';
}

// Enhanced file upload handler with better security
function handleFileUploads() {
    $uploadedFiles = [];
    $uploadDir = '../../uploads/mailshot_attachments/';
   
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
   
    if (isset($_FILES['mailshot_attachments']) && is_array($_FILES['mailshot_attachments']['name'])) {
        $files = $_FILES['mailshot_attachments'];
        $fileCount = count($files['name']);
       
        // Allowed MIME types and extensions
        $allowedMimeTypes = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ];
       
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
       
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = $files['name'][$i];
                $fileTmpName = $files['tmp_name'][$i];
                $fileSize = $files['size'][$i];
                $fileType = $files['type'][$i];
               
                // Get file extension
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
               
                // Validate file
                if (in_array($fileType, $allowedMimeTypes) &&
                    in_array($fileExtension, $allowedExtensions) &&
                    $fileSize <= 10485760) { // 10MB limit
                   
                    // Generate secure filename
                    $uniqueFileName = uniqid('mailshot_', true) . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $uniqueFileName;
                   
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        $uploadedFiles[] = [
                            'name' => $fileName,
                            'path' => $uploadPath,
                            'type' => $fileType,
                            'size' => $fileSize
                        ];
                    }
                } else {
                    error_log("File rejected: $fileName (Type: $fileType, Size: $fileSize, Extension: $fileExtension)");
                }
            }
        }
    }
   
    return $uploadedFiles;
}

// Enhanced email sending function with anti-spam measures
function sendOptimizedEmail($recipientEmail, $recipientName, $subject, $htmlBody, $textBody, $consultantEmail, $consultantName, $attachments = []) {
    $config = getSMTPConfig();
   
    try {
        $mail = new PHPMailer(true);
       
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = $config['auth'];
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port = $config['port'];
        $mail->Timeout = $config['timeout'];
        $mail->SMTPKeepAlive = $config['keepalive'];
       
        // Enhanced SMTP options for deliverability
        $mail->SMTPOptions = $config['options'];
       
        // Set encoding to prevent spam issues
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
       
        // Sender configuration - ANTI-SPAM CRITICAL
        $mail->setFrom($config['username'], $consultantName . ' - Nocturnal Recruitment');
        $mail->addReplyTo($consultantEmail, $consultantName);
       
        // Recipient
        $mail->addAddress($recipientEmail, $recipientName);
       
        // ANTI-SPAM HEADERS
        $mail->addCustomHeader('Return-Path', $config['username']);
        $mail->addCustomHeader('X-Mailer', 'BroadMead CRM v3.0');
        $mail->addCustomHeader('X-Priority', '3');
        $mail->addCustomHeader('X-MSMail-Priority', 'Normal');
        $mail->addCustomHeader('Importance', 'Normal');
        $mail->addCustomHeader('X-Consultant-Email', $consultantEmail);
        $mail->addCustomHeader('X-Consultant-Name', $consultantName);
        $mail->addCustomHeader('List-Unsubscribe', '<mailto:unsubscribe@nocturnalrecruitment.co.uk>');
        $mail->addCustomHeader('List-Id', 'Nocturnal Recruitment Updates <updates.nocturnalrecruitment.co.uk>');
       
        // Message configuration
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $textBody;
       
        // Add attachments
        foreach ($attachments as $attachment) {
            if (file_exists($attachment['path'])) {
                $mail->addAttachment($attachment['path'], $attachment['name']);
            }
        }
       
        // Send email
        $result = $mail->send();
        $mail->clearAddresses();
        $mail->clearAttachments();
       
        return ['success' => true, 'message' => 'Email sent successfully'];
       
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Enhanced mailshot functionality - ONLY WHEN MANUALLY TRIGGERED
if (isset($_POST['send_mailshot']) && $_POST['send_mailshot'] === '1' && !isset($_SESSION['mailshot_processing'])) {
    $_SESSION['mailshot_processing'] = true;
   
    $allowedMailshotEmails = array_keys($consultantMapping);
    $canSendMailshot = in_array($loggedInUserEmail, array_map('strtolower', $allowedMailshotEmails));
   
    if (!$canSendMailshot) {
        $error_message = "Access Denied: You are not authorized to send mailshots.";
        unset($_SESSION['mailshot_processing']);
    } else {
        $selected_clients = json_decode($_POST['selected_clients'] ?? '[]', true);
        $mailshot_subject = trim($_POST['mailshot_subject'] ?? '');
        $mailshot_message = trim($_POST['mailshot_message'] ?? '');
        $mailshot_template_selected = $_POST['mailshot_template'] ?? 'Custom Mailshot';
       
        // Validation
        if (empty($selected_clients)) {
            $error_message = "Please select at least one client.";
            unset($_SESSION['mailshot_processing']);
        } elseif (empty($mailshot_subject)) {
            $error_message = "Email subject is required.";
            unset($_SESSION['mailshot_processing']);
        } elseif (empty($mailshot_message)) {
            $error_message = "Email message is required.";
            unset($_SESSION['mailshot_processing']);
        } else {
            // Handle file uploads
            $uploadedFiles = handleFileUploads();
            $consultant_name = $consultantMapping[$loggedInUserEmail] ?? $loggedInUserName;
           
            try {
                $ClientKeyID = $ClientKeyID ?? $_COOKIE['ClientKeyID'] ?? 1;
                $USERID = $USERID ?? $_COOKIE['USERID'] ?? 1;
                $total_recipients = count($selected_clients);
               
                $log_query = $db_2->prepare("
                    INSERT INTO mailshot_log
                    (ClientKeyID, Subject, Template, RecipientsCount, SuccessCount, FailedCount, SentBy, SentByEmail, ConsultantName, is_completed, SentDate)
                    VALUES (:client_key_id, :subject, :template, :recipients_count, 0, 0, :sent_by, :sent_by_email, :consultant_name, 0, NOW())
                ");
               
                $log_query->execute([
                    ':client_key_id' => $ClientKeyID,
                    ':subject' => $mailshot_subject,
                    ':template' => $mailshot_template_selected,
                    ':recipients_count' => $total_recipients,
                    ':sent_by' => $USERID,
                    ':sent_by_email' => $loggedInUserEmail,
                    ':consultant_name' => $consultant_name
                ]);
               
                $mailshot_id = $db_2->lastInsertId();
               
            } catch (Exception $e) {
                error_log("Error creating mailshot log: " . $e->getMessage());
                $error_message = "Failed to initialize mailshot. Please try again.";
                unset($_SESSION['mailshot_processing']);
            }
           
            if (!isset($error_message)) {
                $successful_sends = 0;
                $failed_sends = 0;
                $error_details = [];
               
                foreach ($selected_clients as $client_id) {
                    try {
                        
                        $client = null;
                        $stmt = $db_2->prepare("SELECT Name, Email FROM _clients WHERE ClientID = ?");
                        $stmt->execute([$client_id]);
                        $client = $stmt->fetch();
                       
                        if (!$client) {
                            $stmt = $db_1->prepare("SELECT Name, Email FROM clients WHERE id = ?");
                            $stmt->execute([$client_id]);
                            $client = $stmt->fetch();
                        }
                       
                        if ($client && filter_var($client->Email, FILTER_VALIDATE_EMAIL)) {
                         
                            $personalized_message = str_replace('[CLIENT_NAME]', $client->Name, $mailshot_message);
                           
                          
                            $html_body = '
                            <!DOCTYPE html>
                            <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>' . htmlspecialchars($mailshot_subject) . '</title>
                            </head>
                            <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
                                <div style="background: #f9f9f9; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                                    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                        ' . nl2br(htmlspecialchars($personalized_message)) . '
                                    </div>
                                </div>
                                ' . getEmailFooter($loggedInUserEmail, $consultant_name) . '
                            </body>
                            </html>';
                           
                            $text_body = $personalized_message . "\n\n" .
                                "---\n" .
                                "Best regards,\n" .
                                $consultant_name . "\n" .
                                "Nocturnal Recruitment\n" .
                                "Email: " . $loggedInUserEmail . "\n" .
                                "Phone: 0208 050 2708";
                           
                            $result = sendOptimizedEmail(
                                $client->Email,
                                $client->Name,
                                $mailshot_subject,
                                $html_body,
                                $text_body,
                                $loggedInUserEmail,
                                $consultant_name,
                                $uploadedFiles
                            );
                           
                            if ($result['success']) {
                                $successful_sends++;
                               
                                // Log individual email tracking
                                try {
                                    $tracking_stmt = $db_2->prepare("
                                        INSERT INTO email_tracking
                                        (id, client_id, consultant_email, consultant_name, subject, sent_date, status, read_status)
                                        VALUES (?, ?, ?, ?, ?, NOW(), 'sent', 'unread')
                                    ");
                                    $tracking_stmt->execute([$id, $client_id, $loggedInUserEmail, $consultant_name, $mailshot_subject]);
                                } catch (Exception $log_e) {
                                    error_log("Error logging email tracking: " . $log_e->getMessage());
                                }
                            } else {
                                $failed_sends++;
                                $error_details[] = "Failed to send to: {$client->Email} - " . $result['message'];
                            }
                           
                            // Small delay to prevent overwhelming the SMTP server
                            usleep(250000); // 0.25 seconds
                           
                        } else {
                            $failed_sends++;
                            $error_details[] = "Invalid email for client ID: $client_id";
                        }
                    } catch (Exception $e) {
                        $failed_sends++;
                        $error_details[] = "Error processing client ID: $client_id - " . $e->getMessage();
                    }
                }
               
                // Update mailshot log with final results and mark as completed
                try {
                    $update_log = $db_2->prepare("
                        UPDATE mailshot_log
                        SET SuccessCount = ?, FailedCount = ?, is_completed = 1
                        WHERE id = ?
                    ");
                    $update_log->execute([$successful_sends, $failed_sends, $mailshot_id]);
                } catch (Exception $e) {
                    error_log("Error updating mailshot log: " . $e->getMessage());
                }
               
                // Clean up uploaded files after sending
                foreach ($uploadedFiles as $file) {
                    if (file_exists($file['path'])) {
                        unlink($file['path']);
                    }
                }
               
                // Set result messages
                if ($successful_sends > 0 && $failed_sends === 0) {
                    $success_message = "Mailshot successfully sent to $successful_sends clients from $consultant_name ($loggedInUserEmail).";
                    $NOTIFICATION = "$consultant_name successfully sent mailshot to $successful_sends clients.";
                   
                    if (function_exists('Notify')) {
                        Notify($USERID, $ClientKeyID, $NOTIFICATION);
                    }
                } elseif ($successful_sends > 0 && $failed_sends > 0) {
                    $error_message = "Mailshot completed with some issues: $successful_sends succeeded, $failed_sends failed.";
                    if (!empty($error_details)) {
                        $error_message .= "\n\nFirst 5 errors:\n" . implode("\n", array_slice($error_details, 0, 5));
                        if (count($error_details) > 5) {
                            $error_message .= "\n... plus " . (count($error_details) - 5) . " more errors.";
                        }
                    }
                } else {
                    $error_message = "Mailshot failed for all selected clients ($failed_sends failures).";
                    if (!empty($error_details)) {
                        $error_message .= "\n\nFirst 5 errors:\n" . implode("\n", array_slice($error_details, 0, 5));
                    }
                }
               
                unset($_SESSION['mailshot_processing']);
            }
        }
    }
}

$SearchID = isset($_GET['q']) ? $_GET['q'] : "";
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "all";
$clients_status = ['targeted', 'not updated', 'active', 'inactive', 'archived'];

$createdByMapping = [
    "1" => "Chax Shamwana",
    "10" => "Millie Brown",
    "11" => "Jay Fuller",
    "13" => "Jack Dowler",
    "15" => "Alex Lapompe",
    "2" => "Alex Lapompe",
    "9" => "Jack Dowler"
];

$ClientKeyID = $ClientKeyID ?? $_COOKIE['ClientKeyID'] ?? 1;
$USERID = $USERID ?? $_COOKIE['USERID'] ?? 1;
$NAME = $NAME ?? 'Guest User';

$showCsvExportButton = in_array($loggedInUserEmail, $allowedExportEmails);
$allowedMailshotEmails = array_keys($consultantMapping);
$canSendMailshot = in_array($loggedInUserEmail, array_map('strtolower', $allowedMailshotEmails));

// Search functionality
if (isset($_POST['Search'])) {
    $Name = $_POST['Name'] ?? '';
    $ClientType = $_POST['ClientType'] ?? '';
    $_client_id = $_POST['_client_id'] ?? '';
    $EmailAddress = $_POST['Email'] ?? '';
    $PhoneNumber = $_POST['Number'] ?? '';
    $Address = $_POST['Address'] ?? '';
    $Postcode = $_POST['Postcode'] ?? '';
    $City = $_POST['City'] ?? '';
}

// Delete functionality
if (isset($_POST['delete'])) {
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];
   
    $stmt = $db_2->prepare("DELETE FROM `_clients` WHERE ClientID = :ID");
    $stmt->bindParam(':ID', $ID);
   
    if ($stmt->execute()) {
        $NOTIFICATION = ($NAME ?? 'A user') . " has successfully deleted the client named '$name'. Reason for deletion: $reason.";
       
        if (function_exists('Notify')) {
            Notify($USERID, $ClientKeyID, $NOTIFICATION);
        }
    } else {
        error_log("Error deleting record: " . implode(", ", $stmt->errorInfo()));
    }
}

// CSV Export functionality
if (isset($_GET['export_csv']) && $_GET['export_csv'] === 'true') {
    if (!in_array($loggedInUserEmail, $allowedExportEmails)) {
        die("Access Denied: You do not have permission to export client data.");
    }
   
    try {
        $nameFilter = $_GET['nameFilter'] ?? '';
        $emailFilter = $_GET['emailFilter'] ?? '';
        $statusFilter = $_GET['statusFilter'] ?? '';
        $clientTypeFilter = $_GET['clientTypeFilter'] ?? '';
        $isTab = $_GET['isTab'] ?? 'all';
       
        $export_where_conditions = ["ClientKeyID = :client_key_id", "isBranch IS NULL"];
        $ClientKeyID = $ClientKeyID ?? $_COOKIE['ClientKeyID'] ?? 1;
        $export_params = [':client_key_id' => $ClientKeyID];
       
        if (!empty($nameFilter)) {
            $export_where_conditions[] = "Name LIKE :name";
            $export_params[':name'] = '%' . $nameFilter . '%';
        }
        if (!empty($emailFilter)) {
            $export_where_conditions[] = "Email LIKE :email";
            $export_params[':email'] = '%' . $emailFilter . '%';
        }
        if (!empty($statusFilter)) {
            $export_where_conditions[] = "Status = :status";
            $export_params[':status'] = $statusFilter;
        }
        if (!empty($clientTypeFilter)) {
            $export_where_conditions[] = "ClientType = :client_type";
            $export_params[':client_type'] = $clientTypeFilter;
        }
        if ($isTab !== "all") {
            $export_where_conditions[] = "Status = :is_tab_status";
            $export_params[':is_tab_status'] = $isTab;
        }
       
        $export_where_clause = 'WHERE ' . implode(' AND ', $export_where_conditions);
        $export_query = "SELECT ClientID, Name, Email, Number, Address, Postcode, City, ClientType, Status, CreatedBy, Date FROM `_clients` $export_where_clause ORDER BY Name ASC";
       
        $stmt = $db_2->prepare($export_query);
        $stmt->execute($export_params);
        $clients_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        if (empty($clients_data)) {
            die("No clients found for export with the applied filters.");
        }
       
        if (ob_get_level()) {
            ob_end_clean();
        }
       
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="clients_filtered_' . date('Y-m-d') . '.csv"');
       
        $output = fopen('php://output', 'w');
        $headers = array_keys($clients_data[0]);
        $createdByHeaderIndex = array_search('CreatedBy', $headers);
        if ($createdByHeaderIndex !== false) {
            $headers[$createdByHeaderIndex] = 'Created By Name';
        }
       
        fputcsv($output, $headers);
       
        foreach ($clients_data as $row) {
            if (isset($row['CreatedBy'])) {
                $row['CreatedBy'] = $createdByMapping[$row['CreatedBy']] ?? 'Unknown';
            }
            if (isset($row['Date'])) {
                $row['Date'] = date('Y-m-d H:i:s', strtotime($row['Date']));
            }
            fputcsv($output, $row);
        }
       
        fclose($output);
        exit;
    } catch (Exception $e) {
        die("CSV Export Failed: " . $e->getMessage());
    }
}

// Get consultant name for JavaScript templates
$currentConsultantName = $consultantMapping[$loggedInUserEmail] ?? $loggedInUserName;
?>
<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>
<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php"; ?>
   
    <div class="pc-container" style="margin-left: 280px;">
        <div class="pc-content">
            <?php include "../../includes/breadcrumb.php"; ?>
           
            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check-circle"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
           
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle"></i>
                    <?php echo nl2br(htmlspecialchars($error_message)); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
           
            <!-- Processing Indicator -->
            <?php if (isset($_SESSION['mailshot_processing'])): ?>
                <div class="alert alert-info" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <strong>Processing mailshot...</strong> Please wait while we send your emails. Do not refresh the page.
                    </div>
                </div>
            <?php endif; ?>
           
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Clients</h5>
                                <?php if ($canSendMailshot): ?>
                                    <div class="badge bg-success">
                                        <i class="ti ti-mail"></i> Consultant: <?php echo htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical f-18"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?php echo $LINK; ?>/create_client">
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                </svg>
                                            </span>
                                            Create
                                        </a>
                                        <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14" />
                                                </svg>
                                            </span>
                                            Advanced Search
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="card-body" style="padding-bottom: 0;">
                            <!-- Filter Section -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Filter by Name:</label>
                                    <input type="text" class="form-control" id="nameFilter" placeholder="Enter name..." onkeyup="applyFilters()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Filter by Email:</label>
                                    <input type="text" class="form-control" id="emailFilter" placeholder="Enter email..." onkeyup="applyFilters()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Filter by Status:</label>
                                    <select class="form-select" id="statusFilter" onchange="applyFilters()">
                                        <option value="">All Statuses</option>
                                        <option value="targeted">Targeted</option>
                                        <option value="not updated">Not Updated</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Filter by Client Type:</label>
                                    <select class="form-select" id="clientTypeFilter" onchange="applyFilters()">
                                        <option value="">All Types</option>
                                        <?php
                                        $client_types_query = $db_2->query("SELECT DISTINCT ClientType FROM _clients WHERE ClientType IS NOT NULL AND ClientType != '' ORDER BY ClientType ASC");
                                        $client_types = $client_types_query->fetchAll(PDO::FETCH_COLUMN);
                                        foreach ($client_types as $type) { ?>
                                            <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                           
                            <!-- Action Buttons -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button type="button" style="background-color: #0d6efd; color: white; border: 1px solid #0d6efd; padding: 0.25rem 0.5rem; border-radius: 0.25rem; margin-right: 0.5rem;" onclick="clearAllFilters()">
                                        <i class="ti ti-refresh"></i> Clear All Filters
                                    </button>
                                    <button type="button" style="background-color: #0d6efd; color: white; border: 1px solid #0d6efd; padding: 0.25rem 0.5rem; border-radius: 0.25rem;" onclick="applyFilters()">
                                        <i class="ti ti-filter"></i> Apply Filters
                                    </button>
                                    <?php if ($showCsvExportButton): ?>
                                        <a href="#" id="exportCsvBtn" style="background-color: #21a366; color: white; border: 1px solid #21a366; padding: 0.25rem 0.5rem; border-radius: 0.25rem; text-decoration: none; display: inline-flex; align-items: center; margin-left: 0.5rem;" onclick="return confirm('Export filtered clients to CSV?')">
                                            <i class="ti ti-file-text"></i> Export CSV
                                        </a>
                                    <?php endif; ?>
                                    <span id="filterResults" style="margin-left: 1rem; color: #6c757d;"></span>
                                </div>
                                <div style="margin-left: auto; padding-left: 15px;">
                                    <?php if ($canSendMailshot && !isset($_SESSION['mailshot_processing'])): ?>
                                        <button type="button" style="background-color: #0d6efd; color: white; border: 1px solid #0d6efd; padding: 0.25rem 0.5rem; border-radius: 0.25rem; display: none;" id="mailshotBtn" onclick="openMailshotModal()">
                                            <i class="ti ti-mail"></i> Send Mailshot (<span id="selectedCount">0</span>)
                                        </button>
                                    <?php elseif (isset($_SESSION['mailshot_processing'])): ?>
                                        <button type="button" style="background-color: #6c757d; color: white; border: 1px solid #6c757d; padding: 0.25rem 0.5rem; border-radius: 0.25rem;" disabled>
                                            <i class="ti ti-loader"></i> Processing...
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                           
                            <!-- Enhanced Mailshot Modal -->
                            <?php if ($canSendMailshot): ?>
                                <div class="modal fade" id="mailshotModal" tabindex="-1" role="dialog" aria-labelledby="mailshotModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="mailshotModalLabel">
                                                    <i class="ti ti-mail"></i> Send Professional Mailshot
                                                    <small class="text-muted d-block">From: <?php echo htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName); ?> (<?php echo htmlspecialchars($loggedInUserEmail); ?>)</small>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="" enctype="multipart/form-data" id="mailshotForm">
                                                <div class="modal-body">
                                                    <input type="hidden" name="selected_clients" id="mailshotSelectedClients">
                                                    <input type="hidden" name="send_mailshot" value="1">
                                                   
                                                    <!-- Anti-Spam Notice -->
                                                    <div class="alert alert-info">
                                                        <i class="ti ti-shield-check"></i>
                                                        <strong>Professional Email Delivery:</strong> This system uses anti-spam measures to ensure your emails reach clients' inboxes. All replies will be forwarded to your email (<?php echo htmlspecialchars($loggedInUserEmail); ?>).
                                                    </div>
                                                   
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group mb-3">
                                                                <label for="mailshot_template"><i class="ti ti-template"></i> Choose Template:</label>
                                                                <select class="form-control" id="mailshot_template" name="mailshot_template">
                                                                    <option value="">-- Select a Template --</option>
                                                                    <option value="welcome">Welcome Email</option>
                                                                    <option value="promotion">New Opportunities</option>
                                                                    <option value="followup">Follow-up Reminder</option>
                                                                </select>
                                                            </div>
                                                           
                                                            <div class="form-group mb-3">
                                                                <label for="mailshot_subject"><i class="ti ti-message"></i> Subject Line:</label>
                                                                <input type="text" class="form-control" id="mailshot_subject" name="mailshot_subject" required placeholder="Enter compelling subject line...">
                                                            </div>
                                                           
                                                            <div class="form-group mb-3">
                                                                <label for="mailshot_message"><i class="ti ti-edit"></i> Email Message:</label>
                                                                <textarea class="form-control" id="mailshot_message" name="mailshot_message" rows="12" placeholder="Enter your professional email message here. Use [CLIENT_NAME] for personalization." required></textarea>
                                                                <small class="form-text text-muted">
                                                                    <i class="ti ti-info-circle"></i> Use [CLIENT_NAME] to personalize emails. Professional signature and contact details will be automatically added.
                                                                </small>
                                                            </div>
                                                        </div>
                                                       
                                                        <div class="col-md-4">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h6 class="mb-0"><i class="ti ti-paperclip"></i> Attachments</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group mb-3">
                                                                        <label for="mailshot_attachments">Add Files:</label>
                                                                        <input type="file" class="form-control" id="mailshot_attachments" name="mailshot_attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
                                                                        <small class="form-text text-muted">
                                                                            Supported: Images, PDF, Word, Text<br>
                                                                            Max: 10MB per file
                                                                        </small>
                                                                    </div>
                                                                   
                                                                    <div id="filePreview" class="mt-3"></div>
                                                                </div>
                                                            </div>
                                                           
                                                            <div class="card mt-3">
                                                                <div class="card-header">
                                                                    <h6 class="mb-0"><i class="ti ti-users"></i> Recipients</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="text-muted mb-2">Total: <span id="mailshotClientCount" class="badge bg-primary">0</span></p>
                                                                    <div id="mailshotClientList" style="max-height: 150px; overflow-y: auto; font-size: 12px;"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="ti ti-x"></i> Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary" id="sendMailshotBtn">
                                                        <i class="ti ti-send"></i> Send Professional Mailshot
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                           
                            <!-- Tabs -->
                            <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist" style="margin-left:30px;">
                                <li class="nav-item" role="presentation">
                                    <a href="<?php echo $LINK; ?>/clients<?php echo !empty($SearchID) ? "/?q=$SearchID" : "" ?>">
                                        <button class="nav-link <?php echo ($isTab == "all") ? 'active' : ''; ?>">All Clients</button>
                                    </a>
                                </li>
                                <ul class="nav">
                                    <?php foreach ($clients_status as $tab) : ?>
                                        <li class="nav-item" role="presentation">
                                            <a href="<?php echo $LINK; ?>/clients<?php echo !empty($SearchID) ? "/?q=$SearchID" : "/?i=0" ?>&isTab=<?php echo $tab; ?>">
                                                <button class="nav-link <?php echo ($isTab == $tab) ? 'active' : ''; ?>">
                                                    <?php echo ucwords($tab); ?>
                                                </button>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </ul>
                           
                            <!-- Clients Table -->
                            <div class="card-body">
                                <div class="table-responsive dt-responsive">
                                    <?php if (isset($USERID) && function_exists('IsCheckPermission') && IsCheckPermission($USERID, "VIEW_CLIENTS")) : ?>
                                        <table class="table table-bordered" id="clientsTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <?php if ($canSendMailshot): ?>
                                                        <th>
                                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                                            <label for="selectAll" class="form-check-label ms-1">Select All</label>
                                                        </th>
                                                    <?php endif; ?>
                                                    <th>Client Name</th>
                                                    <th>Client ID</th>
                                                    <th>Client Type</th>
                                                    <th>Status</th>
                                                    <th>Email Address</th>
                                                    <th>Phone Number</th>
                                                    <th>Location</th>
                                                    <th>Created By</th>
                                                    <th>Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_display = "SELECT * FROM `_clients` WHERE ClientKeyID = :client_key_id AND isBranch IS NULL ";
                                                $params_display = [':client_key_id' => $ClientKeyID];
                                               
                                                if (!empty($SearchID)) {
                                                    $qu = $db_2->prepare("SELECT `column`, `value` FROM `search_queries` WHERE SearchID = :search_id");
                                                    $qu->bindParam(':search_id', $SearchID);
                                                    $qu->execute();
                                                    while ($r = $qu->fetchObject()) {
                                                        $column = $r->column;
                                                        $value = $r->value;
                                                        $allowed_columns = ['Name', 'ClientType', '_client_id', 'Email', 'Number', 'Address', 'Postcode', 'City'];
                                                        if (in_array($column, $allowed_columns)) {
                                                            $query_display .= " AND " . $column . " LIKE :" . $column;
                                                            $params_display[':' . $column] = '%' . $value . '%';
                                                        }
                                                    }
                                                }
                                               
                                                if ($isTab !== "all") {
                                                    $query_display .= " AND Status = :is_tab";
                                                    $params_display[':is_tab'] = $isTab;
                                                }
                                               
                                                $query_display .= " ORDER BY Name ASC";
                                                $stmt_display = $db_2->prepare($query_display);
                                                $stmt_display->execute($params_display);
                                                $n = 1;
                                               
                                                while ($row = $stmt_display->fetchObject()) { ?>
                                                    <?php
                                                    $CreatedBy = $createdByMapping[$row->CreatedBy] ?? 'Unknown';
                                                    $status_class = strtolower($row->Status ?? 'not updated');
                                                    ?>
                                                    <tr class="client-row"
                                                        data-name="<?php echo strtolower($row->Name); ?>"
                                                        data-email="<?php echo strtolower($row->Email); ?>"
                                                        data-status="<?php echo $status_class; ?>"
                                                        data-clienttype="<?php echo strtolower($row->ClientType); ?>">
                                                        <td><?php echo $n++; ?></td>
                                                        <?php if ($canSendMailshot): ?>
                                                            <td>
                                                                <input class="form-check-input checkbox-item"
                                                                       type="checkbox"
                                                                       value="<?php echo $row->ClientID; ?>"
                                                                       data-name="<?php echo htmlspecialchars($row->Name); ?>"
                                                                       data-email="<?php echo htmlspecialchars($row->Email); ?>"
                                                                       onchange="updateSelectedCount()">
                                                            </td>
                                                        <?php endif; ?>
                                                        <td><?php echo htmlspecialchars($row->Name); ?></td>
                                                        <td><?php echo htmlspecialchars($row->_client_id); ?></td>
                                                        <td><?php echo htmlspecialchars($row->ClientType); ?></td>
                                                        <td>
                                                            <?php if ($row->Status == "Active") : ?>
                                                                <span class="badge bg-success">Active</span>
                                                            <?php elseif ($row->Status == "Archived") : ?>
                                                                <span class="badge bg-warning">Archived</span>
                                                            <?php elseif ($row->Status == "Inactive") : ?>
                                                                <span class="badge bg-danger">Inactive</span>
                                                            <?php elseif ($row->Status == "Targeted") : ?>
                                                                <span class="badge bg-info">Targeted</span>
                                                            <?php else : ?>
                                                                <span class="badge bg-danger">Not Updated</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row->Email); ?></td>
                                                        <td><?php echo htmlspecialchars($row->Number); ?></td>
                                                        <td><?php echo htmlspecialchars($row->City . ', ' . $row->Address); ?></td>
                                                        <td><?php echo htmlspecialchars($CreatedBy); ?></td>
                                                        <td><?php echo htmlspecialchars(function_exists('FormatDate') ? FormatDate($row->Date) : $row->Date); ?></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical f-18"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="<?php echo $LINK; ?>/edit_client/?ID=<?php echo htmlspecialchars($row->ClientID); ?>">
                                                                        <span class="text-info">
                                                                            <i class="ti ti-edit"></i>
                                                                        </span>
                                                                        Edit
                                                                    </a>
                                                                    <?php if (isset($USERID) && function_exists('IsCheckPermission') && IsCheckPermission($USERID, "DELETE_CLIENT")) : ?>
                                                                        <a class="dropdown-item delete-entry" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" data-id="<?php echo htmlspecialchars($row->ClientID); ?>" data-name="<?php echo htmlspecialchars($row->Name); ?>">
                                                                            <span class="text-danger">
                                                                                <i class="ti ti-trash"></i>
                                                                            </span>
                                                                            Delete
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    <a class="dropdown-item" href="<?php echo $LINK; ?>/view_client/?ID=<?php echo htmlspecialchars($row->ClientID); ?>">
                                                                        <span class="text-warning">
                                                                            <i class="ti ti-eye"></i>
                                                                        </span>
                                                                        View
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php else : ?>
                                        <div class="alert alert-warning" role="alert">
                                            <i class="ti ti-alert-triangle"></i>
                                            You do not have permission to view clients.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Delete Modal -->
    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="ID" id="deleteClientId">
                        <input type="hidden" name="name" id="deleteClientName">
                        <p>Are you sure you want to delete client: <strong id="modalClientName"></strong>?</p>
                        <div class="form-group">
                            <label for="reason">Reason for deletion:</label>
                            <textarea class="form-control" name="reason" id="reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete modal functionality
            var deleteModal = document.getElementById('DeleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var clientId = button.getAttribute('data-id');
                    var clientName = button.getAttribute('data-name');
                   
                    var modalClientIdInput = deleteModal.querySelector('#deleteClientId');
                    var modalClientNameInput = deleteModal.querySelector('#deleteClientName');
                    var modalClientNameDisplay = deleteModal.querySelector('#modalClientName');
                   
                    modalClientIdInput.value = clientId;
                    modalClientNameInput.value = clientName;
                    modalClientNameDisplay.textContent = clientName;
                });
            }
           
            // File upload preview functionality
            const fileInput = document.getElementById('mailshot_attachments');
            const filePreview = document.getElementById('filePreview');
           
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    filePreview.innerHTML = '';
                    const files = this.files;
                   
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const fileSize = (file.size / 1024 / 1024).toFixed(2);
                       
                        const fileItem = document.createElement('div');
                        fileItem.className = 'alert alert-light p-2 mb-2';
                        fileItem.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small><strong>${file.name}</strong></small><br>
                                    <small class="text-muted">${fileSize} MB</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${i})">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        `;
                        filePreview.appendChild(fileItem);
                    }
                });
            }
           
            // Mailshot functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const mailshotBtn = document.getElementById('mailshotBtn');
            const selectedCountSpan = document.getElementById('selectedCount');
            const filterResultsSpan = document.getElementById('filterResults');
            const mailshotSubjectField = document.getElementById('mailshot_subject');
            const mailshotMessageField = document.getElementById('mailshot_message');
            const mailshotTemplateDropdown = document.getElementById('mailshot_template');
           
            // Email templates with consultant-specific content (fixed for JavaScript)
            const emailTemplates = {
                'welcome': {
                    subject: 'Welcome to Nocturnal Recruitment!',
                    message: 'Dear [CLIENT_NAME],\n\nWelcome to Nocturnal Recruitment! We are thrilled to have you as part of our community.\n\nAs your dedicated consultant, I am here to help you with all your recruitment needs. Please don\'t hesitate to reach out if you have any questions or if there\'s anything I can assist you with.\n\nI look forward to working with you and helping you achieve your recruitment goals.\n\nBest regards,\n<?php echo htmlspecialchars($currentConsultantName); ?>'
                },
                'promotion': {
                    subject: 'Exclusive Recruitment Opportunities - Just for You!',
                    message: 'Hi [CLIENT_NAME],\n\nI hope this email finds you well. I wanted to reach out personally to share some exciting recruitment opportunities that I believe would be perfect for your organization.\n\nWe have access to top-tier candidates in various sectors, and I would love to discuss how we can help you find the right talent for your team.\n\nWould you be available for a brief call this week to explore these opportunities?\n\nLooking forward to hearing from you.\n\nBest regards,\n<?php echo htmlspecialchars($currentConsultantName); ?>'
                },
                'followup': {
                    subject: 'Following Up on Our Recent Conversation',
                    message: 'Hello [CLIENT_NAME],\n\nI hope you\'re doing well. I wanted to follow up on our recent discussion about your recruitment needs.\n\nI\'ve been working on finding the perfect candidates for your requirements and have some exciting profiles to share with you.\n\nWould you be available for a quick call to discuss these opportunities? I believe we have some excellent matches that could be exactly what you\'re looking for.\n\nPlease let me know a convenient time for you, and I\'ll be happy to arrange a call.\n\nLooking forward to hearing from you soon.\n\nBest regards,\n<?php echo htmlspecialchars($currentConsultantName); ?>'
                }
            };
           
            // Fix the templates by replacing PHP with actual consultant name
            const consultantName = '<?php echo htmlspecialchars($currentConsultantName); ?>';
            Object.keys(emailTemplates).forEach(key => {
              emailTemplates[key].message = emailTemplates[key].message.replace(/<?php echo htmlspecialchars($currentConsultantName); ?>/g, consultantName);
            });
           
            if (mailshotTemplateDropdown) {
                mailshotTemplateDropdown.addEventListener('change', function() {
                    const selectedTemplateId = this.value;
                    if (selectedTemplateId && emailTemplates[selectedTemplateId]) {
                        const template = emailTemplates[selectedTemplateId];
                        mailshotSubjectField.value = template.subject;
                        mailshotMessageField.value = template.message;
                    } else {
                        mailshotSubjectField.value = '';
                        mailshotMessageField.value = '';
                    }
                });
            }
           
            // Reset modal when closed
            var mailshotModal = document.getElementById('mailshotModal');
            if (mailshotModal) {
                mailshotModal.addEventListener('hidden.bs.modal', function () {
                    if (mailshotTemplateDropdown) mailshotTemplateDropdown.value = '';
                    if (mailshotSubjectField) mailshotSubjectField.value = '';
                    if (mailshotMessageField) mailshotMessageField.value = '';
                    if (fileInput) fileInput.value = '';
                    if (filePreview) filePreview.innerHTML = '';
                });
            }
           
            // Prevent form submission during processing
            const sendMailshotBtn = document.getElementById('sendMailshotBtn');
            if (sendMailshotBtn) {
                sendMailshotBtn.addEventListener('click', function(e) {
                    const isProcessing = document.querySelector('.alert-info .spinner-border');
                    if (isProcessing) {
                        e.preventDefault();
                        alert('Mailshot is currently being processed. Please wait...');
                        return false;
                    }
                   
                    // Validate form before submission
                    const form = this.closest('form');
                    if (!form.checkValidity()) {
                        return;
                    }
                   
                    // Disable button and show loading state
                    this.disabled = true;
                    this.innerHTML = '<i class="ti ti-loader"></i> Sending...';
                   
                    // Submit the form
                    form.submit();
                });
            }
           
            // Select all functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.checkbox-item');
                    checkboxes.forEach(checkbox => {
                        if (checkbox.closest('tr').style.display !== 'none') {
                            checkbox.checked = this.checked;
                        }
                    });
                    updateSelectedCount();
                });
            }
           
            // Update selected count
            window.updateSelectedCount = function() {
                const checkedCheckboxes = document.querySelectorAll('.checkbox-item:checked');
                if (selectedCountSpan) selectedCountSpan.textContent = checkedCheckboxes.length;
               
                if (mailshotBtn) {
                    if (checkedCheckboxes.length > 0) {
                        mailshotBtn.style.display = 'inline-block';
                    } else {
                        mailshotBtn.style.display = 'none';
                    }
                }
            };
           
            // Open mailshot modal
            window.openMailshotModal = function() {
                const checkedCheckboxes = document.querySelectorAll('.checkbox-item:checked');
                const selectedClientIds = [];
                const selectedClientNames = [];
               
                checkedCheckboxes.forEach(checkbox => {
                    selectedClientIds.push(checkbox.value);
                    selectedClientNames.push(checkbox.dataset.name);
                });
               
                document.getElementById('mailshotSelectedClients').value = JSON.stringify(selectedClientIds);
                document.getElementById('mailshotClientCount').textContent = selectedClientIds.length;
               
                // Display client names in a more organized way
                const clientListDiv = document.getElementById('mailshotClientList');
                clientListDiv.innerHTML = '';
                selectedClientNames.forEach(name => {
                    const clientItem = document.createElement('div');
                    clientItem.className = 'badge bg-light text-dark me-1 mb-1';
                    clientItem.textContent = name;
                    clientListDiv.appendChild(clientItem);
                });
               
                var mailshotBootstrapModal = new bootstrap.Modal(document.getElementById('mailshotModal'));
                mailshotBootstrapModal.show();
            };
           
            // Remove file function
            window.removeFile = function(index) {
                const fileInput = document.getElementById('mailshot_attachments');
                const dt = new DataTransfer();
                const files = fileInput.files;
               
                for (let i = 0; i < files.length; i++) {
                    if (i !== index) {
                        dt.items.add(files[i]);
                    }
                }
               
                fileInput.files = dt.files;
                fileInput.dispatchEvent(new Event('change'));
            };
           
            // Filter functionality
            window.applyFilters = function() {
                const nameFilter = document.getElementById('nameFilter').value.toLowerCase();
                const emailFilter = document.getElementById('emailFilter').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
                const clientTypeFilter = document.getElementById('clientTypeFilter').value.toLowerCase();
                const rows = document.querySelectorAll('#clientsTable tbody tr');
                let visibleRowCount = 0;
               
                rows.forEach(row => {
                    const name = row.dataset.name;
                    const email = row.dataset.email;
                    const status = row.dataset.status;
                    const clientType = row.dataset.clienttype;
                   
                    const nameMatch = name.includes(nameFilter);
                    const emailMatch = email.includes(emailFilter);
                    const statusMatch = statusFilter === '' || status === statusFilter;
                    const clientTypeMatch = clientTypeFilter === '' || clientType === clientTypeFilter;
                   
                    if (nameMatch && emailMatch && statusMatch && clientTypeMatch) {
                        row.style.display = '';
                        visibleRowCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
               
                if (filterResultsSpan) filterResultsSpan.textContent = `Showing ${visibleRowCount} results.`;
                updateSelectedCount();
                updateCsvExportLink();
            };
           
          
            window.clearAllFilters = function() {
                document.getElementById('nameFilter').value = '';
                document.getElementById('emailFilter').value = '';
                document.getElementById('statusFilter').value = '';
                document.getElementById('clientTypeFilter').value = '';
                applyFilters();
            };
           
           
            window.updateCsvExportLink = function() {
                const nameFilter = document.getElementById('nameFilter').value;
                const emailFilter = document.getElementById('emailFilter').value;
                const statusFilter = document.getElementById('statusFilter').value;
                const clientTypeFilter = document.getElementById('clientTypeFilter').value;
                const urlParams = new URLSearchParams(window.location.search);
                const isTab = urlParams.get('isTab') || 'all';
               
                let exportUrl = `?export_csv=true`;
                exportUrl += `&nameFilter=${encodeURIComponent(nameFilter)}`;
                exportUrl += `&emailFilter=${encodeURIComponent(emailFilter)}`;
                exportUrl += `&statusFilter=${encodeURIComponent(statusFilter)}`;
                exportUrl += `&clientTypeFilter=${encodeURIComponent(clientTypeFilter)}`;
                exportUrl += `&isTab=${encodeURIComponent(isTab)}`;
               
                const exportBtn = document.getElementById('exportCsvBtn');
                if (exportBtn) exportBtn.href = exportUrl;
            };
        
            applyFilters();
            updateSelectedCount();
            updateCsvExportLink();
        });
    </script>
</body>
</html>