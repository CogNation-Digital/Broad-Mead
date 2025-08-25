<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/landing/login.php");
    exit; 
}

$serverName = $_SERVER['SERVER_NAME'];


if ($serverName === 'localhost') {
   
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname1 = 'broadmead';
    $dbname2 = 'broadmead_v3';
} elseif ($serverName === 'broad-mead.com') {
 
    $host = 'localhost';
    $user = 'xuwl9qaw_mike';
    $password = '@Michael1693250341';
    $dbname1 = 'xuwl9qaw_v3';  
    $dbname2 = 'xuwl9qaw_v3';
} else {
    echo "<b>Database Configuration Error: </b> Unknown server environment: " . $serverName;
    exit;
}
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


function getConsultantDetails($db_2, $USERID) {
    $stmt = $db_2->prepare("SELECT Name, Email, Number, Position FROM users WHERE UserID = :userid");
    $stmt->bindParam(':userid', $USERID);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    return [
        'name' => $user->Name ?? 'Consultant',
        'email' => $user->Email ?? '',
        'number' => $user->Number ?? '',
        'title' => $user->Position ?? 'Consultant'
    ];
function getEmailFooter($consultantEmail, $consultantName, $consultantNumber = '', $consultantTitle = 'Consultant') {
    $baseUrl = 'https://broad-mead.com/images/';
    $images = [
        'logo' => $baseUrl . 'image001.jpg',
        'linkedin' => $baseUrl . 'image009.jpg',
        'facebook' => $baseUrl . 'Facebook logo.jpg',
        'instagram' => $baseUrl . 'image011.jpg',
        'cyber' => $baseUrl . 'image008.png',
        'rec' => $baseUrl . 'image012.jpg'
    ];
    return '
    <div style="max-width: 600px; font-family: Arial, sans-serif; line-height: 1.4; margin: auto; border-top: 2px solid #333; padding-top: 20px; margin-top: 30px;">
        <div style="margin-bottom: 20px;">
            <p style="margin: 0; color: #333; font-size: 14px;">Best regards,<br>
            <strong>' . htmlspecialchars($consultantName) . '</strong><br>
            ' . htmlspecialchars($consultantTitle) . '</p>
        </div>
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="' . $images['logo'] . '" alt="Nocturnal Recruitment Solutions" style="max-width: 280px; height: auto; display: block; margin: 0 auto;">
        </div>
        <div style="text-align: center; margin-bottom: 20px; font-size: 14px; color: #333;">
            <div style="margin-bottom: 8px;">
                <span style="color: #666;">📍</span>
                <a href="https://maps.google.com/?q=Office+16,+321+High+Road,+RM6+6AX" style="color: #0066cc; text-decoration: none;">Nocturnal Recruitment, Office 16, 321 High Road, RM6 6AX</a>
            </div>
            <div style="margin-bottom: 8px;">
                <span style="color: #666;">☎️</span> <a href="tel:02080502708" style="color: #333; text-decoration: none;">0208 050 2708</a>
                <span style="margin-left: 20px; color: #666;">📱</span> <a href="tel:07827519020" style="color: #333; text-decoration: none;">07827 519020</a>
            </div>
            <div style="margin-bottom: 15px;">
                <span style="color: #666;">✉️</span> <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: none;">info@nocturnalrecruitment.co.uk</a>
                <span style="margin-left: 20px; color: #666;">🌐</span> <a href="https://www.nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: none;">www.nocturnalrecruitment.co.uk</a>
            </div>
        </div>
        <div style="text-align: center; margin-bottom: 20px;">
            <table style="margin: 0 auto; border-collapse: collapse;">
                <tr>
                    <td style="text-align: center; padding: 5px;">
                        <a href="https://www.linkedin.com/company/nocturnal-recruitment-solutions/" target="_blank">
                            <img src="' . $images['linkedin'] . '" alt="LinkedIn" style="height: 30px; width: auto; border: none; display: block;">
                        </a>
                    </td>
                    <td style="text-align: center; padding: 5px;">
                        <a href="https://www.facebook.com/nocturnalrecruitment/" target="_blank">
                            <img src="' . $images['facebook'] . '" alt="Facebook" style="height: 30px; width: auto; border: none; display: block;">
                        </a>
                    </td>
                    <td style="text-align: center; padding: 5px;">
                        <a href="https://www.instagram.com/nocturnalrecruitment/" target="_blank">
                            <img src="' . $images['instagram'] . '" alt="Instagram" style="height: 30px; width: auto; border: none; display: block;">
                        </a>
                    </td>
                    <td style="text-align: center; padding: 5px;">
                        <img src="' . $images['cyber'] . '" alt="Cyber Essentials Certified" style="height: 35px; width: auto; border: none; display: block;">
                    </td>
                    <td style="text-align: center; padding: 5px;">
                        <img src="' . $images['rec'] . '" alt="REC Corporate Member" style="height: 35px; width: auto; border: none; display: block;">
                    </td>
                </tr>
            </table>
        </div>
        <div style="text-align: center; color: #333333; font-size: 14px; font-weight: bold; margin-bottom: 20px;">
            Company Registration – 11817091
        </div>
        <div style="font-size: 11px; color: #666666; line-height: 1.4; border-top: 1px solid #dddddd; padding-top: 15px; margin-top: 20px;">
            <strong style="color: #c41e3a;">Disclaimer:</strong> This email is intended only for the use of the addressee named above and may be confidential or legally privileged. If you are not the addressee, you must not read it and must not use any information contained in nor copy it nor inform any person other than <a href="https://www.nocturnalrecruitment.co.uk" style="color: #c41e3a; text-decoration: none; font-weight: bold;">Nocturnal Recruitment</a> or the addressee of its existence or contents. If you have received this email in error, please delete it and notify our team at <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: none;">info@nocturnalrecruitment.co.uk</a>
        </div>
    </div>';
}

// Helper function to convert images to base64
function convertImageToBase64($imagePath) {
    if (file_exists($imagePath)) {
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
        return 'data:image/' . $imageType . ';base64,' . $imageData;
    }
    return false;
}

// Example usage to convert your existing images
/*
// Download your images from Google Drive first, then use:
$logoBase64 = convertImageToBase64('path/to/your/logo.png');
$linkedinBase64 = convertImageToBase64('path/to/your/linkedin.png');
// etc...
*/

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
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]
    ];
}

// function getEmailFooter($consultantEmail, $consultantName) {
//     // ... [same as your original, omitted for brevity] ...
//     return '...'; // Use your full HTML signature from the original
// }

// Secure file upload handler
function handleFileUploads() {
    $uploadedFiles = [];
    $uploadDir = '../../uploads/mailshot_attachments/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    if (isset($_FILES['mailshot_attachments'])) {
        $files = $_FILES['mailshot_attachments'];
        $allowedMimeTypes = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'
        ];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = $files['name'][$i];
                $fileTmpName = $files['tmp_name'][$i];
                $fileSize = $files['size'][$i];
                $fileType = $files['type'][$i];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if (in_array($fileType, $allowedMimeTypes) &&
                    in_array($fileExtension, $allowedExtensions) &&
                    $fileSize <= 10485760) {
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
                }
            }
        }
    }
    return $uploadedFiles;
}


function sendOptimizedEmail($recipientEmail, $recipientName, $subject, $htmlBody, $textBody, $consultantEmail, $consultantName, $attachments = []) {
    $config = getSMTPConfig();
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = $config['auth'];
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port = $config['port'];
        $mail->Timeout = $config['timeout'];
        $mail->SMTPKeepAlive = $config['keepalive'];
        $mail->SMTPOptions = $config['options'];
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->SMTPDebug = 0;

        // Always use the authenticated SMTP address as the sender
        $mail->setFrom($config['username'], $consultantName . ' - Nocturnal Recruitment');
        // Set consultant as Reply-To so replies go to them
        $mail->addReplyTo($consultantEmail, $consultantName);
        $mail->addAddress($recipientEmail, $recipientName);

        $mail->addCustomHeader('Return-Path', $config['username']);
        $mail->addCustomHeader('X-Mailer', 'BroadMead CRM v3.0');
        $mail->addCustomHeader('X-Priority', '3');
        $mail->addCustomHeader('X-MSMail-Priority', 'Normal');
        $mail->addCustomHeader('Importance', 'Normal');
        $mail->addCustomHeader('X-Consultant-Email', $consultantEmail);
        $mail->addCustomHeader('X-Consultant-Name', $consultantName);

        $mail->isHTML(true); // Send as HTML to display images
        $mail->Subject = $subject;

        // If HTML body is provided, use it; otherwise convert text to HTML
        if (!empty($htmlBody)) {
            $mail->Body = $htmlBody;
        } else {
            $mail->Body = nl2br(htmlspecialchars($textBody));
        }

        foreach ($attachments as $attachment) {
            $mail->addAttachment($attachment['path'], $attachment['name']);
        }

        $maxRetries = 3;
        $retryDelay = 2;

        for ($i = 0; $i < $maxRetries; $i++) {
            try {
                if ($mail->send()) {
                    return ['success' => true, 'message' => 'Email sent successfully'];
                }
            } catch (Exception $e) {
                if ($i === $maxRetries - 1) throw $e;
                sleep($retryDelay);
            }
        }

        return ['success' => false, 'message' => 'Failed after retries'];
    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

$loggedInUserEmail = '';
$loggedInUserName = '';
$USERID = $_COOKIE['USERID'] ?? null;
if ($USERID) {
    $stmt = $db_2->prepare("SELECT Email, Name FROM users WHERE UserID = :userid");
    $stmt->bindParam(':userid', $USERID);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    if ($user) {
        $loggedInUserEmail = strtolower($user->Email);
        $loggedInUserName = $user->Name ?? 'Consultant';
    }
}


$allowedMailshotEmails = array_keys($consultantMapping);
$canSendMailshot = in_array($loggedInUserEmail, array_map('strtolower', $allowedMailshotEmails));

if (isset($_POST['send_mailshot']) && !isset($_SESSION['mailshot_processing'])) {
    $_SESSION['mailshot_processing'] = true;
    if (!$canSendMailshot) {
        $error_message = "Access Denied: You are not authorized to send mailshots.";
        unset($_SESSION['mailshot_processing']);
    } else {
        $selected_clients = json_decode($_POST['selected_clients'], true) ?? [];
        $mailshot_subject = trim($_POST['mailshot_subject'] ?? '');
        $mailshot_message = trim($_POST['mailshot_message'] ?? '');
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
            $uploadedFiles = handleFileUploads();
            $consultant_name = $consultantMapping[$loggedInUserEmail] ?? $loggedInUserName;
          
            $ClientKeyID = $_COOKIE['ClientKeyID'] ?? 1;
            $USERID = $_COOKIE['USERID'] ?? 1;
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

            $successful_sends = 0;
            $failed_sends = 0;
            $error_details = [];
            foreach ($selected_clients as $client_id) {
                try {
                    $stmt = $db_2->prepare("SELECT Name, Email FROM _clients WHERE ClientID = ?");
                    $stmt->execute([$client_id]);
                    $client = $stmt->fetch(PDO::FETCH_OBJ);
                    if (!$client && $db_1) {
                        $stmt = $db_1->prepare("SELECT Name, Email FROM clients WHERE id = ?");
                        $stmt->execute([$client_id]);
                        $client = $stmt->fetch(PDO::FETCH_OBJ);
                    }
                    if ($client && filter_var($client->Email, FILTER_VALIDATE_EMAIL)) {
                        // Use first word of Name as first name, and full Name for full_name
                        $first_name = strtok($client->Name, ' ');
                        $full_name = $client->Name;
                        $personalized_message = str_replace('[CLIENT_NAME]', $first_name, $mailshot_message);
                        // Convert plain text message to HTML and add HTML footer
                        $html_body = nl2br(htmlspecialchars($personalized_message)) . getEmailFooter($loggedInUserEmail, $consultant_name);
                        $result = sendOptimizedEmail(
                            $client->Email, $full_name, $mailshot_subject, $html_body, '',
                            $loggedInUserEmail, $consultant_name, $uploadedFiles
                        );
                        if ($result['success']) {
                            $successful_sends++;
                            $tracking_stmt = $db_2->prepare("
                                INSERT INTO email_tracking (id, client_id, consultant_email, consultant_name, subject, sent_date, status)
                                VALUES (?, ?, ?, ?, ?, NOW(), 'sent')
                            ");
                            $tracking_stmt->execute([
                                $mailshot_id, $client_id, $loggedInUserEmail, $consultant_name, $mailshot_subject
                            ]);
                        } else {
                            $failed_sends++;
                            $error_details[] = "Failed for {$client->Email}: " . $result['message'];
                        }
                    } else {
                        $failed_sends++;
                        $error_details[] = "Invalid email for client ID: $client_id";
                    }
                } catch (Exception $e) {
                    $failed_sends++;
                    $error_details[] = "Error for client ID $client_id: " . $e->getMessage();
                }
            }
       
            $update_log = $db_2->prepare("
                UPDATE mailshot_log SET SuccessCount = ?, FailedCount = ?, is_completed = 1 WHERE id = ?
            ");
            $update_log->execute([$successful_sends, $failed_sends, $mailshot_id]);
       
            foreach ($uploadedFiles as $file) {
                if (file_exists($file['path'])) unlink($file['path']);
            }
         
            if ($successful_sends > 0 && $failed_sends === 0) {
                $success_message = "Mailshot successfully sent to $successful_sends clients.";
            } elseif ($successful_sends > 0 && $failed_sends > 0) {
                $error_message = "Mailshot completed with issues: $successful_sends succeeded, $failed_sends failed.\nFirst errors: " . implode("\n", array_slice($error_details, 0, 5));
            } else {
                $error_message = "Mailshot failed for all clients. First errors: " . implode("\n", array_slice($error_details, 0, 5));
            }
            unset($_SESSION['mailshot_processing']);
            $_SESSION['mailshot_completed'] = true;
            $_SESSION['mailshot_result'] = $success_message ?? $error_message ?? 'Mailshot completed.';
        }
    }
}


if (isset($_SESSION['mailshot_completed'])) {
    if (isset($_SESSION['mailshot_result'])) {
        if (strpos($_SESSION['mailshot_result'], 'successfully') !== false) {
            $success_message = $_SESSION['mailshot_result'];
        } else {
            $error_message = $_SESSION['mailshot_result'];
        }
    }
    unset($_SESSION['mailshot_completed'], $_SESSION['mailshot_result']);
}



$SearchID = isset($_GET['q']) ? $_GET['q'] : "";
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "all";
$clients_status = ['targeted', 'not updated'];

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
        $export_query = "SELECT 
            _client_id, ClientID, Name, Email, Number, Address, Postcode, City, ClientType, Status, CreatedBy, Date
            FROM `_clients` $export_where_clause ORDER BY Name ASC";
        $stmt = $db_2->prepare($export_query);
        $stmt->execute($export_params);
        $clients_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($clients_data)) {
            die("No clients found for export with the applied filters.");
        }

        if (ob_get_level()) {
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="clients_filtered_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');

        // Build headers for export: include 'Client ID' (from _client_id), then all other columns except ClientID and _client_id
        $headers = array_keys($clients_data[0]);
        $exportHeaders = [];
        $exportHeaders[] = 'Client ID';
        foreach ($headers as $header) {
            if ($header !== 'ClientID' && $header !== '_client_id') {
                $exportHeaders[] = $header;
            }
        }
        fputcsv($output, $exportHeaders);
        foreach ($clients_data as $row) {
            $exportRow = [];
            // Always use _client_id for 'Client ID' column
            $exportRow[] = $row['_client_id'] ?? $row['ClientID'] ?? '';
            foreach ($headers as $header) {
                if ($header !== 'ClientID' && $header !== '_client_id') {
                    // Map CreatedBy to readable name if possible
                    if ($header === 'CreatedBy') {
                        $exportRow[] = $createdByMapping[$row['CreatedBy']] ?? $row['CreatedBy'];
                    } elseif ($header === 'Date') {
                        // Format the date for Excel compatibility
                        $dateValue = $row['Date'];
                        if ($dateValue && $dateValue !== '0000-00-00 00:00:00') {
                            $formattedDate = date('Y-m-d H:i:s', strtotime($dateValue));
                            // Prefix with single quote for Excel display
                            $formattedDate = "'" . $formattedDate;
                        } else {
                            $formattedDate = '';
                        }
                        $exportRow[] = $formattedDate;
                    } else {
                        $exportRow[] = $row[$header];
                    }
                }
            }
            fputcsv($output, $exportRow);
        }

        fclose($output);
        exit;
    } catch (Exception $e) {
        die("CSV Export Failed: " . $e->getMessage());
    }
}



if (isset($_POST['send_email'])) {
    if (!isset($_SESSION['last_email_time'])) {
        $_SESSION['last_email_time'] = time();
    }
    
    // Authentication check is already done at the top of the file
    // No additional redirect needed here
}
// // Enhanced email sending function with anti-spam measures

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
                                <?php if ($canSendMailshot): ?>
            <button type="button" 
                    id="mailshotBtn" 
                    class="btn btn-primary d-none"
                    onclick="openMailshotModal()">
                <i class="ti ti-mail"></i> Send Mailshot (<span id="selectedCount">0</span>)
            </button>
        <?php endif; ?>
    </div>
                            </div>

                         
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
                                                    <button type="submit" name="send_mailshot" class="btn btn-primary" id="sendMailshotBtn">
                                                        <i class="ti ti-send"></i> Send Professional Mailshot
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                      
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

                       
                            <div class="card-body">
                                <div class="table-responsive dt-responsive">
                                    <?php if (isset($USERID) && function_exists('IsCheckPermission') && IsCheckPermission($USERID, "VIEW_CLIENTS")) : ?>
                                        <table class="table table-bordered" id="clientsTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <?php if ($canSendMailshot): ?>
                                                        <th>
                                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
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
                                                    if (strtolower(trim($isTab)) === 'not updated') {
                                                        $query_display .= " AND (TRIM(LOWER(Status)) = 'not updated')";
                                                    } elseif (strtolower(trim($isTab)) === 'targeted') {
                                                        $query_display .= " AND (TRIM(LOWER(Status)) = 'targeted')";
                                                    }
                                                }

                                                $query_display .= " ORDER BY Name ASC";
                                                $stmt_display = $db_2->prepare($query_display);
                                                $stmt_display->execute($params_display);
                                                $n = 1;
                                                while ($row = $stmt_display->fetchObject()) { ?>
                                                    <?php
                                                    $CreatedBy = $createdByMapping[$row->CreatedBy] ?? 'Unknown';
                                                    $status_class = trim(strtolower($row->Status ?? 'not updated'));
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
                                                            <?php if (strtolower(trim($row->Status)) == "targeted") : ?>
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

           
            const selectAllCheckbox = document.getElementById('selectAll');
            const mailshotBtn = document.getElementById('mailshotBtn');
            const selectedCountSpan = document.getElementById('selectedCount');
            const filterResultsSpan = document.getElementById('filterResults');
            const mailshotSubjectField = document.getElementById('mailshot_subject');
            const mailshotMessageField = document.getElementById('mailshot_message');
            const mailshotTemplateDropdown = document.getElementById('mailshot_template');

         
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

            // // Prevent form submission during processing
            // const sendMailshotBtn = document.getElementById('sendMailshotBtn');
            // if (sendMailshotBtn) {
            //     sendMailshotBtn.addEventListener('click', function(e) {
            //         const selectedClients = document.querySelectorAll('.checkbox-item:checked');
            //         const subject = document.getElementById('mailshot_subject').value;
            //         const message = document.getElementById('mailshot_message').value;
                    
            //         console.log('Mailshot form submitted with:');
            //         console.log('Selected clients:', selectedClients.length);
            //         console.log('Subject:', subject);
            //         console.log('Message length:', message.length);
                    
            //         if (selectedClients.length === 0) {
            //             e.preventDefault();
            //             alert('Please select at least one client.');
            //             return false;
            //         }
                    
            //         if (!subject.trim()) {
            //             e.preventDefault();
            //             alert('Please enter a subject line.');
            //             return false;
            //         }
                    
            //         if (!message.trim()) {
            //             e.preventDefault();
            //             alert('Please enter an email message.');
            //             return false;
            //         }
                    
            //         const isProcessing = document.querySelector('.alert-info .spinner-border');
            //         if (isProcessing) {
            //             e.preventDefault();
            //             alert('Mailshot is currently being processed. Please wait...');
            //             return false;
            //         }
                   
                    
            //         this.disabled = true;
            //         this.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Sending Emails...';
                    
                    
            //         const alertInfo = document.querySelector('.alert-info');
            //         if (alertInfo) {
            //             alertInfo.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div><strong>Processing:</strong> Sending mailshot to ' + selectedClients.length + ' clients. Please wait...';
            //         }
                   
                
            //         setTimeout(() => {
            //             if (!this.closest('form').checkValidity()) {
            //                 this.disabled = false;
            //                 this.innerHTML = '<i class="ti ti-send"></i> Send Professional Mailshot';
            //             }
            //         }, 2000);
            //     });
            // }

            
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

            
            window.toggleSelectAll = function() {
                const selectAllCheckbox = document.getElementById('selectAll');
                const checkboxes = document.querySelectorAll('.checkbox-item');
                checkboxes.forEach(checkbox => {
                    if (checkbox.closest('tr').style.display !== 'none') {
                        checkbox.checked = selectAllCheckbox.checked;
                    }
                });
                updateSelectedCount();
            };

            
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
                    const statusMatch = statusFilter === '' || status === statusFilter || (statusFilter === 'not updated' && status === 'not updated');
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

        document.addEventListener('DOMContentLoaded', function() {

    function updateMailshotButtonVisibility() {
        const checkedBoxes = document.querySelectorAll('.checkbox-item:checked');
        const mailshotBtn = document.getElementById('mailshotBtn');
        const selectedCount = document.getElementById('selectedCount');
        
        if (mailshotBtn) {
            mailshotBtn.classList.toggle('d-none', checkedBoxes.length === 0);
        }
        if (selectedCount) {
            selectedCount.textContent = checkedBoxes.length;
        }
    }

 
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.checkbox-item');
        
        checkboxes.forEach(checkbox => {
            if (checkbox.closest('tr').style.display !== 'none') {
                checkbox.checked = selectAllCheckbox.checked;
            }
        });
        updateMailshotButtonVisibility();
    }


    const checkboxes = document.querySelectorAll('.checkbox-item');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateMailshotButtonVisibility);
    });

  
    updateMailshotButtonVisibility();
});
    </script>
    
    <?php
    
    if (isset($_SESSION['mailshot_processing'])) {
        error_log("WARNING: mailshot_processing session still set at end of script - clearing it");
        unset($_SESSION['mailshot_processing']);
    }
    ?>
</body>
</html>
