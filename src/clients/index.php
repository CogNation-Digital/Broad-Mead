<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

// Create required tables if they don't exist
// try {
    // Create email_tracking table
    $db_2->exec("
        CREATE TABLE IF NOT EXISTS `email_tracking` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `client_id` int(11) NOT NULL,
          `consultant_email` varchar(255) NOT NULL,
          `consultant_name` varchar(255) NOT NULL,
          `subject` varchar(500) NOT NULL,
          `sent_date` datetime NOT NULL,
          `status` enum('sent','delivered','opened','replied','failed') DEFAULT 'sent',
          `reply_date` datetime DEFAULT NULL,
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_client_id` (`client_id`),
          KEY `idx_consultant_email` (`consultant_email`),
          KEY `idx_sent_date` (`sent_date`),
          KEY `idx_status` (`status`)
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
    
    // Add indexes if they don't exist
    // try {
    //     $db_2->exec("ALTER TABLE `mailshot_log` ADD INDEX `idx_sent_by_email` (`SentByEmail`)");
    // } catch (Exception $e) {
    //     // Index might already exist
//     // }
//     try {
//         $db_2->exec("ALTER TABLE `mailshot_log` ADD INDEX `idx_consultant_name` (`ConsultantName`)");
//     } catch (Exception $e) {
//         // Index might already exist
//     }

// } catch (Exception $e) {
//     error_log("Database setup error: " . $e->getMessage());
// }

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

// Clean and professional email footer function
function getEmailFooter($consultantEmail, $consultantName) {
    return '
    <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #ffffff; font-family: Arial, sans-serif; border-radius: 8px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #ffffff; font-size: 18px; font-weight: bold;">Nocturnal Recruitment</h3>
            <p style="margin: 5px 0 0 0; color: #b8d4ff; font-size: 14px;">Your Trusted Recruitment Partner</p>
        </div>
        
        <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 20px; flex-wrap: wrap;">
            <div style="margin: 5px 15px; text-align: center;">
                <div style="color: #6daffb; font-size: 12px; margin-bottom: 3px;">📍 Address</div>
                <div style="color: #ffffff; font-size: 11px;">Office 16, 321 High Road, RM6 6AX</div>
            </div>
            <div style="margin: 5px 15px; text-align: center;">
                <div style="color: #6daffb; font-size: 12px; margin-bottom: 3px;">📞 Phone</div>
                <div style="color: #ffffff; font-size: 11px;">0208 050 2708</div>
            </div>
            <div style="margin: 5px 15px; text-align: center;">
                <div style="color: #6daffb; font-size: 12px; margin-bottom: 3px;">📱 Mobile</div>
                <div style="color: #ffffff; font-size: 11px;">0755 357 0871</div>
            </div>
        </div>
        
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="color: #6daffb; font-size: 12px; margin-bottom: 3px;">✉️ Your Consultant</div>
            <div style="color: #ffffff; font-size: 13px; font-weight: bold;">' . $consultantName . '</div>
            <div style="color: #b8d4ff; font-size: 11px;">
                <a href="mailto:' . $consultantEmail . '" style="color: #6daffb; text-decoration: none;">' . $consultantEmail . '</a>
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

// File upload handler for media attachments
function handleFileUploads() {
    $uploadedFiles = [];
    $uploadDir = '../../uploads/mailshot_attachments/';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (isset($_FILES['mailshot_attachments'])) {
        $files = $_FILES['mailshot_attachments'];
        $fileCount = count($files['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = $files['name'][$i];
                $fileTmpName = $files['tmp_name'][$i];
                $fileSize = $files['size'][$i];
                $fileType = $files['type'][$i];
                
                // Validate file type and size (max 10MB per file)
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (in_array($fileType, $allowedTypes) && $fileSize <= 10485760) {
                    $uniqueFileName = uniqid() . '_' . $fileName;
                    $uploadPath = $uploadDir . $uniqueFileName;
                    
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        $uploadedFiles[] = [
                            'name' => $fileName,
                            'path' => $uploadPath,
                            'type' => $fileType,
                            'size' => $fileSize
                        ];
                    }
                }
            }
        }
    }
    
    return $uploadedFiles;
}

// Email Reply Handler Function
function processEmailReply() {
    global $db_2;
    
    // Get the raw POST data (webhook payload)
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        error_log("Invalid webhook payload received");
        http_response_code(400);
        return;
    }
    
    $fromEmail = $data['from'] ?? '';
    $toEmail = $data['to'] ?? '';
    $subject = $data['subject'] ?? '';
    $body = $data['body'] ?? '';
    
    error_log("Processing reply from: $fromEmail to: $toEmail");
    
    // Find the original consultant
    $consultant = findOriginalConsultant($fromEmail, $subject, $db_2);
    
    if ($consultant) {
        forwardReplyToConsultant($consultant, $fromEmail, $subject, $body);
        updateEmailTrackingStatus($fromEmail, $subject, 'replied', $db_2);
    } else {
        forwardToGeneralInbox($fromEmail, $subject, $body);
    }
    
    http_response_code(200);
    echo "OK";
}

function findOriginalConsultant($clientEmail, $subject, $db) {
    try {
        $stmt = $db->prepare("
            SELECT consultant_email, consultant_name 
            FROM email_tracking 
            WHERE client_id IN (
                SELECT ClientID FROM _clients WHERE Email = :client_email
            ) 
            AND subject = :subject 
            ORDER BY sent_date DESC 
            LIMIT 1
        ");
        $stmt->execute([':client_email' => $clientEmail, ':subject' => $subject]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return [
                'email' => $result['consultant_email'],
                'name' => $result['consultant_name']
            ];
        }
        
        // Try most recent email to this client
        $stmt = $db->prepare("
            SELECT consultant_email, consultant_name 
            FROM email_tracking 
            WHERE client_id IN (
                SELECT ClientID FROM _clients WHERE Email = :client_email
            ) 
            ORDER BY sent_date DESC 
            LIMIT 1
        ");
        $stmt->execute([':client_email' => $clientEmail]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return [
                'email' => $result['consultant_email'],
                'name' => $result['consultant_name']
            ];
        }
        
    } catch (Exception $e) {
        error_log("Error finding consultant: " . $e->getMessage());
    }
    
    return null;
}

function forwardReplyToConsultant($consultant, $clientEmail, $subject, $body) {
    try {
        $mail = new PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'learn@natec.icu';
        $mail->Password = '@WhiteDiamond0100';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('learn@natec.icu', 'Nocturnal Recruitment - Reply Handler');
        $mail->addAddress($consultant['email'], $consultant['name']);
        $mail->addReplyTo($clientEmail);
        
        $mail->Subject = "CLIENT REPLY: " . $subject;
        
        $emailBody = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin-bottom: 20px;'>
                <h3 style='margin: 0; color: #007bff;'>Client Reply Received</h3>
                <p style='margin: 5px 0 0 0; color: #6c757d;'>This email was automatically forwarded to you</p>
            </div>
            
            <div style='background: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;'>
                <table style='width: 100%; margin-bottom: 20px;'>
                    <tr>
                        <td style='font-weight: bold; padding: 5px 10px 5px 0; color: #495057;'>From:</td>
                        <td style='padding: 5px 0;'>" . htmlspecialchars($clientEmail) . "</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold; padding: 5px 10px 5px 0; color: #495057;'>Original Subject:</td>
                        <td style='padding: 5px 0;'>" . htmlspecialchars($subject) . "</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold; padding: 5px 10px 5px 0; color: #495057;'>Received:</td>
                        <td style='padding: 5px 0;'>" . date('Y-m-d H:i:s') . "</td>
                    </tr>
                </table>
                
                <div style='border-top: 1px solid #dee2e6; padding-top: 15px;'>
                    <h4 style='color: #495057; margin-bottom: 10px;'>Client Message:</h4>
                    <div style='background: #f8f9fa; padding: 15px; border-radius: 3px; line-height: 1.6;'>
                        " . nl2br(htmlspecialchars($body)) . "
                    </div>
                </div>
            </div>
        </div>";
        
        $mail->isHTML(true);
        $mail->Body = $emailBody;
        $mail->AltBody = "Client Reply from: $clientEmail\n\nSubject: $subject\n\nMessage:\n$body";
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log("Error forwarding to consultant: " . $e->getMessage());
        return false;
    }
}

function forwardToGeneralInbox($clientEmail, $subject, $body) {
    try {
        $mail = new PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'learn@natec.icu';
        $mail->Password = '@WhiteDiamond0100';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('learn@natec.icu', 'Nocturnal Recruitment - Reply Handler');
        $mail->addAddress('info@nocturnalrecruitment.co.uk', 'Nocturnal Recruitment');
        $mail->addReplyTo($clientEmail);
        
        $mail->Subject = "UNASSIGNED CLIENT REPLY: " . $subject;
        
        $emailBody = "
        <div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin-bottom: 20px;'>
            <h3 style='margin: 0; color: #856404;'>Unassigned Client Reply</h3>
            <p style='margin: 5px 0 0 0; color: #856404;'>No consultant found for this client</p>
        </div>
        <p><strong>From:</strong> $clientEmail</p>
        <p><strong>Subject:</strong> $subject</p>
        <p><strong>Message:</strong></p>
        <div style='background: #f8f9fa; padding: 15px; border-radius: 3px;'>
            " . nl2br(htmlspecialchars($body)) . "
        </div>";
        
        $mail->isHTML(true);
        $mail->Body = $emailBody;
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log("Error forwarding to general inbox: " . $e->getMessage());
        return false;
    }
}

function updateEmailTrackingStatus($clientEmail, $subject, $status, $db) {
    try {
        $stmt = $db->prepare("
            UPDATE email_tracking 
            SET status = :status, reply_date = NOW() 
            WHERE client_id IN (
                SELECT ClientID FROM _clients WHERE Email = :client_email
            ) 
            AND subject = :subject 
            ORDER BY sent_date DESC 
            LIMIT 1
        ");
        $stmt->execute([
            ':status' => $status,
            ':client_email' => $clientEmail,
            ':subject' => $subject
        ]);
        
    } catch (Exception $e) {
        error_log("Error updating email tracking: " . $e->getMessage());
    }
}

// Handle email reply webhook
if (isset($_GET['webhook']) && $_GET['webhook'] === 'email_reply') {
    processEmailReply();
    exit;
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

        $createdByMapping = [
            "1" => "Chax Shamwana",
            "10" => "Millie Brown",
            "11" => "Jay Fuller",
            "13" => "Jack Dowler",
            "15" => "Alex Lapompe",
            "2" => "Alex Lapompe",
            "9" => "Jack Dowler"
        ];

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

$allowedMailshotEmails = array_keys($consultantMapping);
$canSendMailshot = in_array($loggedInUserEmail, array_map('strtolower', $allowedMailshotEmails));

error_log("Debug - Can send mailshot: " . ($canSendMailshot ? 'YES' : 'NO'));
error_log("Debug - Logged in user email: " . $loggedInUserEmail);

// Enhanced Mailshot functionality with media attachments and proper logging
if (isset($_POST['send_mailshot']) && !isset($_SESSION['mailshot_processing'])) {
    $_SESSION['mailshot_processing'] = true; // Prevent duplicate submissions
    
    if (!$canSendMailshot) {
        $error_message = "Access Denied: You are not authorized to send mailshots. Only authorized consultants can send mailshots.";
        unset($_SESSION['mailshot_processing']);
    } else {
        $selected_clients = json_decode($_POST['selected_clients'], true) ?? [];
        $mailshot_subject = $_POST['mailshot_subject'] ?? '';
        $mailshot_message = $_POST['mailshot_message'] ?? '';
        $mailshot_template_selected = $_POST['mailshot_template'] ?? 'Custom Mailshot';

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
            
            // Use consultant's information
            $consultant_name = $consultantMapping[$loggedInUserEmail] ?? $loggedInUserName;
            
            // SMTP Configuration
            $smtp_host = 'smtp.titan.email';
            $smtp_username = 'learn@natec.icu';
            $smtp_password = '@WhiteDiamond0100';
            $smtp_port = 587;

            $total_recipients = count($selected_clients);
            $successful_sends = 0;
            $failed_sends = 0;
            $error_details = [];

            // Test SMTP connection
            try {
                $test_mail = new PHPMailer(true);
                $test_mail->isSMTP();
                $test_mail->Host = $smtp_host;
                $test_mail->SMTPAuth = true;
                $test_mail->Username = $smtp_username;
                $test_mail->Password = $smtp_password;
                $test_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $test_mail->Port = $smtp_port;
                
                if (!$test_mail->smtpConnect()) {
                    throw new Exception("SMTP connection failed");
                }
                $test_mail->smtpClose();
            } catch (Exception $e) {
                $error_message = "SMTP Configuration Error: " . $e->getMessage();
                error_log("SMTP Error: " . $e->getMessage());
                unset($_SESSION['mailshot_processing']);
            }

            if (!isset($error_message)) {
                foreach ($selected_clients as $client_id) {
                    try {
                        $client = null;
                        
                        // Try to fetch from _clients table first
                        $stmt = $db_2->prepare("SELECT Name, Email FROM _clients WHERE ClientID = ?");
                        $stmt->execute([$client_id]);
                        $client = $stmt->fetch();
                        
                        // If not found, try clients table
                        if (!$client) {
                            $stmt = $db_1->prepare("SELECT Name, Email FROM clients WHERE id = ?");
                            $stmt->execute([$client_id]);
                            $client = $stmt->fetch();
                        }

                        if ($client && filter_var($client->Email, FILTER_VALIDATE_EMAIL)) {
                            $mail = new PHPMailer(true);
                            $mail->isSMTP();
                            $mail->Host = $smtp_host;
                            $mail->SMTPAuth = true;
                            $mail->Username = $smtp_username;
                            $mail->Password = $smtp_password;
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = $smtp_port;
                            $mail->Timeout = 30;
                            $mail->SMTPOptions = array(
                                'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                )
                            );

                            $personalized_message = str_replace('[CLIENT_NAME]', $client->Name, $mailshot_message);
                            $final_email_body = nl2br(htmlspecialchars($personalized_message)) . getEmailFooter($loggedInUserEmail, $consultant_name);

                            // Set consultant as sender and reply-to
                            $mail->setFrom($smtp_username, $consultant_name);
                            $mail->addAddress($client->Email, $client->Name);
                            $mail->addReplyTo($loggedInUserEmail, $consultant_name);
                            
                            // Add custom headers for tracking
                            $mail->addCustomHeader('X-Consultant-Email', $loggedInUserEmail);
                            $mail->addCustomHeader('X-Consultant-Name', $consultant_name);
                            $mail->addCustomHeader('X-Client-ID', $client_id);
                            
                            // Add file attachments
                            foreach ($uploadedFiles as $file) {
                                $mail->addAttachment($file['path'], $file['name']);
                            }
                            
                            $mail->isHTML(true);
                            $mail->Subject = $mailshot_subject;
                            $mail->Body = $final_email_body;
                            $mail->AltBody = strip_tags($personalized_message);

                            if ($mail->send()) {
                                $successful_sends++;
                                
                                // Log the email send for tracking
                                try {
                                    $log_stmt = $db_2->prepare("INSERT INTO email_tracking (client_id, consultant_email, consultant_name, subject, sent_date, status) VALUES (?, ?, ?, ?, NOW(), 'sent')");
                                    $log_stmt->execute([$client_id, $loggedInUserEmail, $consultant_name, $mailshot_subject]);
                                } catch (Exception $log_e) {
                                    error_log("Error logging email send: " . $log_e->getMessage());
                                }
                            } else {
                                $failed_sends++;
                                $error_details[] = "Failed to send to: {$client->Email} - " . $mail->ErrorInfo;
                            }
                            
                            $mail->clearAddresses();
                            $mail->clearAttachments();
                            usleep(100000); // Small delay between sends
                        } else {
                            $failed_sends++;
                            $error_details[] = "Invalid email for client ID: $client_id";
                        }
                    } catch (Exception $e) {
                        $failed_sends++;
                        $error_details[] = "Error processing client ID: $client_id - " . $e->getMessage();
                    }
                }

                // Enhanced mailshot logging to existing mailshot_log table
                try {
                    $log_template_name = ($mailshot_template_selected !== '') ? $mailshot_template_selected : "Custom Mailshot";
                    
                    // Check if mailshot_log table has the required columns
                    $columns_check = $db_2->query("SHOW COLUMNS FROM mailshot_log");
                    $existing_columns = [];
                    while ($col = $columns_check->fetch(PDO::FETCH_OBJ)) {
                        $existing_columns[] = $col->Field;
                    }
                    
                    // Prepare the insert query based on available columns
                    if (in_array('SentByEmail', $existing_columns) && in_array('ConsultantName', $existing_columns)) {
                        $log_query = $db_2->prepare("INSERT INTO mailshot_log (ClientKeyID, Subject, Template, RecipientsCount, SuccessCount, FailedCount, SentBy, SentByEmail, ConsultantName, SentDate) VALUES (:client_key_id, :subject, :template, :recipients_count, :success_count, :failed_count, :sent_by, :sent_by_email, :consultant_name, NOW())");
                    } else {
                        // Fallback for older table structure
                        $log_query = $db_2->prepare("INSERT INTO mailshot_log (ClientKeyID, Subject, Template, RecipientsCount, SuccessCount, FailedCount, SentBy, SentDate) VALUES (:client_key_id, :subject, :template, :recipients_count, :success_count, :failed_count, :sent_by, NOW())");
                    }
                    
                    $ClientKeyID = $ClientKeyID ?? $_COOKIE['ClientKeyID'] ?? 1;
                    $USERID = $USERID ?? $_COOKIE['USERID'] ?? 1;

                    $log_query->bindParam(':client_key_id', $ClientKeyID);
                    $log_query->bindParam(':subject', $mailshot_subject);
                    $log_query->bindParam(':template', $log_template_name);
                    $log_query->bindParam(':recipients_count', $total_recipients, PDO::PARAM_INT);
                    $log_query->bindParam(':success_count', $successful_sends, PDO::PARAM_INT);
                    $log_query->bindParam(':failed_count', $failed_sends, PDO::PARAM_INT);
                    $log_query->bindParam(':sent_by', $USERID);
                    
                    if (in_array('SentByEmail', $existing_columns)) {
                        $log_query->bindParam(':sent_by_email', $loggedInUserEmail);
                    }
                    if (in_array('ConsultantName', $existing_columns)) {
                        $log_query->bindParam(':consultant_name', $consultant_name);
                    }

                    if (!$log_query->execute()) {
                        error_log("Error inserting mailshot summary log: " . implode(", ", $log_query->errorInfo()));
                    }
                } catch (Exception $e) {
                    error_log("Exception during mailshot summary logging: " . $e->getMessage());
                }

                // Clean up uploaded files after sending
                foreach ($uploadedFiles as $file) {
                    if (file_exists($file['path'])) {
                        unlink($file['path']);
                    }
                }

                // Set success/error messages
                if ($successful_sends > 0 && $failed_sends === 0) {
                    $success_message = "Mailshot successfully sent to $successful_sends clients from $consultant_name ($loggedInUserEmail).";
                    $NOTIFICATION = "$consultant_name has successfully sent mailshot to $successful_sends clients.";
                    
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
                    $NOTIFICATION = "$consultant_name sent mailshot to $successful_sends clients with $failed_sends failures.";
                    if (function_exists('Notify')) {
                        Notify($USERID, $ClientKeyID, $NOTIFICATION);
                    }
                } elseif ($failed_sends > 0) {
                    $error_message = "Mailshot failed for all selected clients ($failed_sends failures).";
                    if (!empty($error_details)) {
                        $error_message .= "\n\nFirst 5 errors:\n" . implode("\n", array_slice($error_details, 0, 5));
                        if (count($error_details) > 5) {
                            $error_message .= "\n... plus " . (count($error_details) - 5) . " more errors.";
                        }
                    }
                }
                
                unset($_SESSION['mailshot_processing']); // Clear processing flag
            }
        }
    }
}

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
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                                                    <?php echo $tab; ?>
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
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                                <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                            </svg>
                                                                        </span>
                                                                        Edit
                                                                    </a>
                                                                    <?php if (isset($USERID) && function_exists('IsCheckPermission') && IsCheckPermission($USERID, "DELETE_CLIENT")) : ?>
                                                                        <a class="dropdown-item delete-entry" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" data-id="<?php echo htmlspecialchars($row->ClientID); ?>" data-name="<?php echo htmlspecialchars($row->Name); ?>">
                                                                            <span class="text-danger">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                    <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                                </svg>
                                                                            </span>
                                                                            Delete
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    <a class="dropdown-item" href="<?php echo $LINK; ?>/view_client/?ID=<?php echo htmlspecialchars($row->ClientID); ?>">
                                                                        <span class="text-warning">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                <g fill="currentColor">
                                                                                    <path d="M6.5 12a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z" />
                                                                                    <path fill-rule="evenodd" d="M11.185 1H4.5A1.5 1.5 0 0 0 3 2.5v15A1.5 1.5 0 0 0 4.5 19h11a1.5 1.5 0 0 0 1.5-1.5V7.202a1.5 1.5 0 0 0-.395-1.014l-4.314-4.702A1.5 1.5 0 0 0 11.185 1M4 2.5a.5.5 0 0 1 .5-.5h6.685a.5.5 0 0 1 .369.162l4.314 4.702a.5.5 0 0 1 .132.338V17.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5z" clip-rule="evenodd" />
                                                                                    <path d="M11 7h5.5a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 1 0z" />
                                                                                </g>
                                                                            </svg>
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

    <!-- Enhanced Mailshot Modal with Media Upload -->
    <?php if ($canSendMailshot): ?>
        <div class="modal fade" id="mailshotModal" tabindex="-1" role="dialog" aria-labelledby="mailshotModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mailshotModalLabel">
                            Send Mailshot to Selected Clients
                            <small class="text-muted d-block">From: <?php echo htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName); ?> (<?php echo htmlspecialchars($loggedInUserEmail); ?>)</small>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="selected_clients" id="mailshotSelectedClients">
                            
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle"></i>
                                <strong>Reply Handling:</strong> When clients reply to this email, their responses will be automatically redirected to your email address (<?php echo htmlspecialchars($loggedInUserEmail); ?>).
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="mailshot_template">Choose Template:</label>
                                        <select class="form-control" id="mailshot_template" name="mailshot_template">
                                            <option value="">-- Select a Template --</option>
                                            <option value="welcome">Welcome Email</option>
                                            <option value="promotion">New Promotion</option>
                                            <option value="followup">Follow-up Reminder</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="mailshot_subject">Subject:</label>
                                        <input type="text" class="form-control" id="mailshot_subject" name="mailshot_subject" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="mailshot_message">Message:</label>
                                        <textarea class="form-control" id="mailshot_message" name="mailshot_message" rows="10" placeholder="Enter your email message here. Use [CLIENT_NAME] for the client's name." required></textarea>
                                        <small class="form-text text-muted">
                                            Use [CLIENT_NAME] to personalize emails with client names. Your signature and contact details will be automatically added.
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="ti ti-paperclip"></i> Media Attachments</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="mailshot_attachments">Add Files:</label>
                                                <input type="file" class="form-control" id="mailshot_attachments" name="mailshot_attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                                                <small class="form-text text-muted">
                                                    Supported: Images (JPG, PNG, GIF), PDF, Word documents<br>
                                                    Max size: 10MB per file
                                                </small>
                                            </div>
                                            
                                            <div id="filePreview" class="mt-3"></div>
                                            
                                            <div class="alert alert-warning alert-sm mt-3">
                                                <small><strong>Note:</strong> Large attachments may slow down email delivery. Consider using links to cloud storage for very large files.</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="ti ti-users"></i> Selected Clients</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-2">Recipients: <span id="mailshotClientCount">0</span></p>
                                            <div id="mailshotClientList" style="max-height: 150px; overflow-y: auto; font-size: 12px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="send_mailshot" class="btn btn-primary" id="sendMailshotBtn">
                                <i class="ti ti-send"></i> Send Mailshot
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

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

            // Email templates with consultant-specific content
            const emailTemplates = {
                'welcome': {
                    subject: 'Welcome to Nocturnal Recruitment!',
                    message: 'Dear [CLIENT_NAME],\n\nWelcome to Nocturnal Recruitment! We are thrilled to have you as part of our community.\n\nAs your dedicated consultant, I am here to help you with all your recruitment needs. Please don\'t hesitate to reach out if you have any questions or if there\'s anything I can assist you with.\n\nI look forward to working with you and helping you achieve your recruitment goals.\n\nBest regards,\n<?php echo htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName); ?>'
                },
                'promotion': {
                    subject: 'Exclusive Recruitment Opportunities - Just for You!',
                    message: 'Hi [CLIENT_NAME],\n\nI hope this email finds you well. I wanted to reach out personally to share some exciting recruitment opportunities that I believe would be perfect for your organization.\n\nWe have access to top-tier candidates in various sectors, and I would love to discuss how we can help you find the right talent for your team.\n\nWould you be available for a brief call this week to explore these opportunities?\n\nLooking forward to hearing from you.\n\nBest regards,\n<?php echo htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName); ?>'
                },
                'followup': {
                    subject: 'Following Up on Our Recent Conversation',
                    message: 'Hello [CLIENT_NAME],\n\nI hope you\'re doing well. I wanted to follow up on our recent discussion about your recruitment needs.\n\nI\'ve been working on finding the perfect candidates for your requirements and have some exciting profiles to share with you.\n\nWould you be available for a quick call to discuss these opportunities? I believe we have some excellent matches that could be exactly what you\'re looking for.\n\nPlease let me know a convenient time for you, and I\'ll be happy to arrange a call.\n\nLooking forward to hearing from you soon.\n\nBest regards,\n<?php echo htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName); ?>'
                }
            };

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
                    
                    // Disable button and show loading state
                    this.disabled = true;
                    this.innerHTML = '<i class="ti ti-loader"></i> Sending...';
                    
                    // Re-enable after a delay if form doesn't submit
                    setTimeout(() => {
                        if (!this.closest('form').checkValidity()) {
                            this.disabled = false;
                            this.innerHTML = '<i class="ti ti-send"></i> Send Mailshot';
                        }
                    }, 1000);
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

            // Clear filters
            window.clearAllFilters = function() {
                document.getElementById('nameFilter').value = '';
                document.getElementById('emailFilter').value = '';
                document.getElementById('statusFilter').value = '';
                document.getElementById('clientTypeFilter').value = '';
                applyFilters();
            };

            // Update CSV export link
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

            // Initialize
            applyFilters();
            updateSelectedCount();
            updateCsvExportLink();
        });
    </script>
</body>
</html>
