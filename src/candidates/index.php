<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once '../../includes/config.php';

// Ensure PHPMailer is loaded
require_once '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/landing/login.php");
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname1 = 'broadmead';
$dbname2 = 'broadmead_v3';

try {
    $db_1 = new PDO('mysql:host=' . $host . ';dbname=' . $dbname1, $user, $password);
    $db_1->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
    $db_2 = new PDO('mysql:host=' . $host . ';dbname=' . $dbname2, $user, $password);
    $db_2->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<b>Database Connection Error: </b> " . $e->getMessage();
    exit;
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

$canExport = in_array($loggedInUserEmail, array_map('strtolower', $allowedExportEmails));

// Enhanced email footer function
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
            BroadMead 3.0 &copy; 2025 - Powered by <a href="https://www.cog-nation.com" target="_blank" style="color: #E1AD01; text-decoration: none; font-weight: bold;">CogNation Digital</a>
        </div>
    </div>';
}

function handleFileUploads() {
    $uploadedFiles = [];
    $uploadDir = '../../uploads/candidate_mailshot_attachments/';
   
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
   
    if (isset($_FILES['mailshot_attachments'])) {
        $files = $_FILES['mailshot_attachments'];
        $fileCount = count($files['name']);
       
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
               
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
               
                // Validate file
                if (in_array($fileType, $allowedMimeTypes) &&
                    in_array($fileExtension, $allowedExtensions) &&
                    $fileSize <= 10485760) {
                   
                    $uniqueFileName = uniqid('candidate_mailshot_', true) . '.' . $fileExtension;
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

$allowedMailshotEmails = array_keys($consultantMapping);
$canSendMailshot = in_array($loggedInUserEmail, array_map('strtolower', $allowedMailshotEmails));

// Get the current mode from URL parameter
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'candidates';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST REQUEST DETECTED on candidates page");
    error_log("POST keys: " . implode(', ', array_keys($_POST)));
    error_log("send_mailshot isset: " . (isset($_POST['send_mailshot']) ? 'YES' : 'NO'));
   
    $success_message = $_SESSION['success_message'] ?? null;
    $error_message = $_SESSION['error_message'] ?? null;
    unset($_SESSION['success_message']);
    unset($_SESSION['error_message']);
   
    if ($mode === 'mailshot' && isset($_POST['send_mailshot'])) {
        $_SESSION['debug_info'] = [
            'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
            'POST_DATA' => $_POST,
            'FILES' => $_FILES,
            'USER_EMAIL' => $loggedInUserEmail,
            'CAN_SEND' => $canSendMailshot,
            'MODE' => $mode,
            'FORM_TOKEN' => isset($_POST['send_mailshot']) ? 'YES' : 'NO'
        ];
       
        error_log("=== Candidate Mailshot Debug Info ===");
        error_log(print_r($_SESSION['debug_info'], true));
       
        if (!$canSendMailshot) {
            error_log("REJECTED: User not authorized to send mailshots");
            $_SESSION['error_message'] = "Access Denied: You are not authorized to send mailshots.";
            header("Location: " . $_SERVER['PHP_SELF'] . "?mode=mailshot");
            exit;
        }
       
        $selected_candidates = json_decode($_POST['selected_candidates'], true) ?? [];
        $mailshot_subject = trim($_POST['mailshot_subject'] ?? '');
        $mailshot_message = trim($_POST['mailshot_message'] ?? '');
        $mailshot_template_selected = $_POST['mailshot_template'] ?? 'Custom Mailshot';
       
        error_log("Selected candidates: " . print_r($selected_candidates, true));
        error_log("Subject: " . $mailshot_subject);
        error_log("Message length: " . strlen($mailshot_message));
       
        if (empty($selected_candidates)) {
            $_SESSION['error_message'] = "Please select at least one candidate.";
        } elseif (empty($mailshot_subject)) {
            $_SESSION['error_message'] = "Email subject is required.";
        } elseif (empty($mailshot_message)) {
            $_SESSION['error_message'] = "Email message is required.";
        } else {
            error_log("Validation passed, proceeding with mailshot");
           
            $uploadedFiles = handleFileUploads();
            $consultant_name = $consultantMapping[$loggedInUserEmail] ?? $loggedInUserName;
           
            try {
                $db_2->exec("
                    CREATE TABLE IF NOT EXISTS `candidate_email_tracking` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `mailshot_id` int(11) DEFAULT NULL,
                        `candidate_id` int(11) NOT NULL,
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
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
            } catch (Exception $e) {
                error_log("Database setup error: " . $e->getMessage());
            }
           
            $successful_sends = 0;
            $failed_sends = 0;
            $error_details = [];
           
            foreach ($selected_candidates as $candidate_id) {
                try {
                    $stmt = $db_2->prepare("SELECT id, Name, Email FROM _candidates WHERE id = ?");
                    $stmt->execute([$candidate_id]);
                    $candidate = $stmt->fetch(PDO::FETCH_OBJ);
                   
                    if ($candidate && filter_var($candidate->Email, FILTER_VALIDATE_EMAIL)) {
                        $personalized_message = str_replace(['[CANDIDATE_NAME]', '[Name]'], $candidate->Name, $mailshot_message);
                       
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host = 'smtp.titan.email';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'learn@natec.icu';
                        $mail->Password = '@WhiteDiamond0100';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                       
                        $mail->setFrom('learn@natec.icu', $consultant_name . ' - Nocturnal Recruitment');
                        $mail->addReplyTo($loggedInUserEmail, $consultant_name);
                        $mail->addAddress($candidate->Email, $candidate->Name);
                       
                        $mail->isHTML(true);
                        $mail->Subject = $mailshot_subject;
                        $mail->Body = '
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
                       
                        $mail->AltBody = $personalized_message . "\n\n" .
                            "---\n" .
                            "Best regards,\n" .
                            $consultant_name . "\n" .
                            "Nocturnal Recruitment\n" .
                            "Email: " . $loggedInUserEmail . "\n" .
                            "Phone: 0208 050 2708";
                       
                        // Add attachments
                        foreach ($uploadedFiles as $attachment) {
                            try {
                                $mail->addAttachment($attachment['path'], $attachment['name']);
                                error_log("Added attachment: " . $attachment['name']);
                            } catch (Exception $e) {
                                error_log("Attachment error: " . $e->getMessage());
                                continue;
                            }
                        }
                       
                        // Send the email
                        if ($mail->send()) {
                            $successful_sends++;
                            error_log("SUCCESS: Email sent to " . $candidate->Email);
                           
                            // Log individual email tracking
                            try {
                                $tracking_stmt = $db_2->prepare("
                                    INSERT INTO candidate_email_tracking
                                    (mailshot_id, candidate_id, consultant_email, consultant_name, subject, sent_date, delivery_status)
                                    VALUES (?, ?, ?, ?, ?, NOW(), 'sent')
                                ");
                                $tracking_stmt->execute([
                                    null, // mailshot_id
                                    $candidate_id,
                                    $loggedInUserEmail,
                                    $consultant_name,
                                    $mailshot_subject
                                ]);
                               
                                error_log("Email tracking logged successfully for candidate ID: " . $candidate_id);
                               
                            } catch (Exception $log_e) {
                                error_log("Error logging email tracking: " . $log_e->getMessage() . "\nSQL State: " . $log_e->getCode() . "\nTrace: " . $log_e->getTraceAsString());
                            }
                        } else {
                            $failed_sends++;
                            $error_msg = "Failed to send to: {$candidate->Email} - " . $mail->ErrorInfo;
                            $error_details[] = $error_msg;
                            error_log("ERROR: " . $error_msg);
                        }
                       
                        // Clear addresses and attachments for next iteration
                        $mail->clearAddresses();
                        $mail->clearAttachments();
                       
                    } else {
                        $failed_sends++;
                        $error_msg = "Invalid email for candidate ID: $candidate_id - Email: " . ($candidate->Email ?? 'No email found');
                        $error_details[] = $error_msg;
                        error_log($error_msg);
                    }
                } catch (Exception $e) {
                    $failed_sends++;
                    $error_msg = "Error processing candidate ID: $candidate_id - " . $e->getMessage();
                    $error_details[] = $error_msg;
                    error_log($error_msg);
                }
            }
           
            error_log("Mailshot completed. Success: $successful_sends, Failed: $failed_sends");
           
            // Clean up uploaded files
            foreach ($uploadedFiles as $file) {
                if (file_exists($file['path'])) {
                    unlink($file['path']);
                }
            }
           
            // Set result messages
            if ($successful_sends > 0 && $failed_sends === 0) {
                $_SESSION['success_message'] = "Mailshot successfully sent to $successful_sends candidates from $consultant_name ($loggedInUserEmail).";
            } elseif ($successful_sends > 0 && $failed_sends > 0) {
                $_SESSION['error_message'] = "Mailshot completed with some issues: $successful_sends succeeded, $failed_sends failed.";
                if (!empty($error_details)) {
                    $_SESSION['error_message'] .= "\n\nFirst 5 errors:\n" . implode("\n", array_slice($error_details, 0, 5));
                    if (count($error_details) > 5) {
                        $_SESSION['error_message'] .= "\n... plus " . (count($error_details) - 5) . " more errors.";
                    }
                }
            } else {
                $_SESSION['error_message'] = "Mailshot failed for all selected candidates ($failed_sends failures).";
                if (!empty($error_details)) {
                    $_SESSION['error_message'] .= "\n\nFirst 5 errors:\n" . implode("\n", array_slice($error_details, 0, 5));
                }
            }
           
            $final_result = $_SESSION['success_message'] ?? $_SESSION['error_message'] ?? 'Unknown result';
            error_log("Final result: " . $final_result);
           
            // Store debug info for the next page load
            $_SESSION['debug_info'] = [
                'PROCESS_COMPLETED' => true,
                'SUCCESSFUL_SENDS' => $successful_sends,
                'FAILED_SENDS' => $failed_sends,
                'SELECTED_COUNT' => count($selected_candidates),
                'FINAL_RESULT' => $final_result,
                'ERROR_DETAILS' => $error_details
            ];
        }
    }
   
    // Always redirect back to mailshot mode
    header("Location: " . $_SERVER['PHP_SELF'] . "?mode=mailshot");
    exit;
} else {
    error_log("No mailshot POST request detected, skipping mailshot processing");
}

// Export functionality
if (isset($_GET['export'])) {
   
    if (!$canExport) {
        die("Access Denied: You do not have permission to export data. Only authorized users (alex@nocturnalrecruitment.co.uk, j.dowler@nocturnalrecruitment.co.uk, chax@nocturnalrecruitment.co.uk) can export data.");
    }
   
    try {
       
        if (!isset($db_2)) {
            throw new Exception('Database not connected for export.');
        }
       
        $exportType = $_GET['export'];
        $exportMode = $_GET['mode'] ?? 'candidates';
       
        if (!in_array($exportType, ['excel', 'csv'])) {
            throw new Exception('Invalid export type.');
        }
       
        $filename = "";
        $data_to_export = [];
        $headers = [];
       
        if ($exportMode === 'candidates') {
           
            $status_filter_export = $_GET['status'] ?? 'all';
            $keyword_filter_export = $_GET['keyword'] ?? '';
            $location_filter_export = $_GET['location'] ?? '';
            $position_filter_export = $_GET['position'] ?? '';
            $center_postcode_export = $_GET['center_postcode'] ?? '';
            $distance_miles_export = (int)($_GET['distance_miles'] ?? 0);
           
            $export_where_conditions = [];
            $export_params = [];
           
            if ($status_filter_export !== 'all') {
                $export_where_conditions[] = "Status = :status";
                $export_params[':status'] = $status_filter_export;
            }
           
            if (!empty($keyword_filter_export)) {
                $export_where_conditions[] = "(Name LIKE :keyword OR Email LIKE :keyword OR JobTitle LIKE :keyword)";
                $export_params[':keyword'] = '%' . $keyword_filter_export . '%';
            }
           
            if (!empty($location_filter_export)) {
                $export_where_conditions[] = "(City LIKE :location OR Address LIKE :location OR Postcode LIKE :location)";
                $export_params[':location'] = '%' . $location_filter_export . '%';
            }
           
            if (!empty($position_filter_export)) {
                $export_where_conditions[] = "JobTitle LIKE :position";
                $export_params[':position'] = '%' . $position_filter_export . '%';
            }
           
            $export_where_clause = '';
            if (!empty($export_where_conditions)) {
                $export_where_clause = 'WHERE ' . implode(' AND ', $export_where_conditions);
            }
           
            $stmt = $db_2->prepare("SELECT id, Name, Email, JobTitle, Status, City, Postcode, Date, CreatedBy, ProfileImage FROM _candidates $export_where_clause ORDER BY Date DESC");
            $stmt->execute($export_params);
            $raw_data_to_export = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
            if (!empty($center_postcode_export) && $distance_miles_export > 0) {
                foreach ($raw_data_to_export as $candidate) {
                    if (!empty($candidate['Postcode'])) {
                        $distance = calculateDistanceBetweenPostcodes($center_postcode_export, $candidate['Postcode']);
                        if ($distance <= $distance_miles_export) {
                            $data_to_export[] = $candidate;
                        }
                    }
                }
            } else {
                $data_to_export = $raw_data_to_export;
            }
           
            $filename = "candidates_".date('Y-m-d')."_".$status_filter_export;
           
            if (!empty($data_to_export)) {
               
                $headers = ['ID', 'Name', 'Email', 'Job Title', 'Status', 'City', 'Postcode', 'Date Added', 'Added By', 'Profile Picture URL'];
               
                $temp_data = [];
                foreach ($data_to_export as $row) {
                    $row['CreatedBy'] = $createdByMapping[$row['CreatedBy']] ?? 'Unknown';
                    $temp_data[] = $row;
                }
                $data_to_export = $temp_data;
            }
        } elseif ($exportMode === 'kpi') {
           
            $kpi_period_export = $_GET['kpi_period'] ?? 'current_week';
            $kpi_start_date_export = $_GET['kpi_start_date'] ?? '';
            $kpi_end_date_export = $_GET['kpi_end_date'] ?? '';
            $kpi_status_filter_export = $_GET['kpi_status_filter'] ?? 'all';
            $kpi_location_filter_export = $_GET['kpi_location_filter'] ?? '';
           
            $kpi_data_for_export = calculateKPIs($db_2, $kpi_period_export, $kpi_start_date_export, $kpi_end_date_export, $kpi_status_filter_export, $kpi_location_filter_export);
            $data_to_export = $kpi_data_for_export['detailed_candidates'] ?? [];
           
            $filename = "kpi_report_detailed_".date('Y-m-d')."_".$kpi_period_export;
           
            if (!empty($data_to_export)) {
               
                $headers = ['ID', 'Name', 'Email', 'Job Title', 'Status', 'City', 'Postcode', 'Date Added', 'Added By', 'Profile Picture URL'];
               
                $temp_data = [];
                foreach ($data_to_export as $row) {
                    $row['CreatedBy'] = $createdByMapping[$row['CreatedBy']] ?? 'Unknown';
                    $temp_data[] = $row;
                }
                $data_to_export = $temp_data;
            }
        }
       
        if (empty($data_to_export)) {
            die("No records found for export with the applied filters.");
        }
       
        if (ob_get_level()) {
            ob_end_clean();
        }
       
        if ($exportType === 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename.xls\"");
           
            echo implode("\t", $headers) . "\r\n";
           
            foreach ($data_to_export as $row) {
               
                echo implode("\t", array_values($row)) . "\r\n";
            }
            exit;
        }
       
        if ($exportType === 'csv') {
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=\"$filename.csv\"");
            $output = fopen('php://output', 'w');
           
            fputcsv($output, $headers);
           
            foreach ($data_to_export as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
            exit;
        }
       
    } catch (Exception $e) {
        die("Export failed: " . $e->getMessage());
    }
}

$keyword_filter = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location_filter = isset($_GET['location']) ? trim($_GET['location']) : '';
$position_filter = isset($_GET['position']) ? trim($_GET['position']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : ($mode === 'mailshot' ? 'active' : 'all');
$center_postcode = isset($_GET['center_postcode']) ? trim($_GET['center_postcode']) : '';
$distance_miles = isset($_GET['distance_miles']) ? (int)$_GET['distance_miles'] : 0;

$kpi_period = isset($_GET['kpi_period']) ? $_GET['kpi_period'] : 'current_week';
$kpi_start_date = isset($_GET['kpi_start_date']) ? $_GET['kpi_start_date'] : '';
$kpi_end_date = isset($_GET['kpi_end_date']) ? $_GET['kpi_end_date'] : '';
$kpi_status_filter = isset($_GET['kpi_status_filter']) ? $_GET['kpi_status_filter'] : 'all';
$kpi_location_filter = isset($_GET['kpi_location_filter']) ? trim($_GET['kpi_location_filter']) : '';

$candidates_for_display = [];
$where_conditions = [];
$params = [];

if (!empty($keyword_filter)) {
    $where_conditions[] = "(Name LIKE :keyword OR Email LIKE :keyword OR JobTitle LIKE :keyword)";
    $params[':keyword'] = '%' . $keyword_filter . '%';
}

if (!empty($location_filter)) {
    $where_conditions[] = "(City LIKE :location OR Address LIKE :location OR Postcode LIKE :location)";
    $params[':location'] = '%' . $location_filter . '%';
}

if (!empty($position_filter)) {
    $where_conditions[] = "JobTitle LIKE :position";
    $params[':position'] = '%' . $position_filter . '%';
}

if ($status_filter !== 'all') {
    $where_conditions[] = "Status = :status";
    $params[':status'] = $status_filter;
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
}

$query_db2 = "SELECT id, Name, Email, JobTitle, Status, City, Postcode, Date, CreatedBy, ProfileImage FROM _candidates $where_clause ORDER BY Date DESC";
$stmt_db2 = $db_2->prepare($query_db2);
$stmt_db2->execute($params);
$raw_candidates_for_display = $stmt_db2->fetchAll(PDO::FETCH_ASSOC);

if (!empty($center_postcode) && $distance_miles > 0) {
    foreach ($raw_candidates_for_display as $candidate) {
        if (!empty($candidate['Postcode'])) {
            try {
                $distance = calculateDistanceBetweenPostcodes($center_postcode, $candidate['Postcode']);
                if ($distance <= $distance_miles) {
                    $candidates_for_display[] = $candidate;
                }
            } catch (Exception $e) {
                error_log("Postcode distance calculation error for display: " . $e->getMessage());
               
            }
        }
    }
} else {
    $candidates_for_display = $raw_candidates_for_display;
}

function getPostcodeCoordinates($postcode) {
    static $postcodeCache = [];
   
    if (isset($postcodeCache[$postcode])) {
        return $postcodeCache[$postcode];
    }
   
    // IMPORTANT: For a real-world application, you would integrate with a postcode API (e.g., Postcodes.io, Google Geocoding API).
    // The current implementation uses random coordinates for demonstration purposes.
    // Example of a real API call (requires an API key and handling rate limits):
    /*
    $api_url = "https://api.postcodes.io/postcodes/" . urlencode($postcode);
    $response = @file_get_contents($api_url);
    $data = $response ? json_decode($response, true) : null;
   
    if ($data && $data['status'] == 200 && isset($data['result']['latitude']) && isset($data['result']['longitude'])) {
        $coordinates = ['latitude' => $data['result']['latitude'], 'longitude' => $data['result']['longitude']];
    } else {
        // Fallback to a default or throw an error if postcode not found
        throw new Exception("Could not get coordinates for postcode: " . $postcode);
    }
    */
   
    // Mock coordinates as per original code, for demonstration
    $coordinates = [
        'latitude' => 51.5 + (rand(-100, 100) / 1000),
        'longitude' => -0.1 + (rand(-100, 100) / 1000)
    ];
   
    $postcodeCache[$postcode] = $coordinates;
    return $coordinates;
}

function calculateDistanceBetweenPostcodes($postcode1, $postcode2) {
    try {
        $coords1 = getPostcodeCoordinates($postcode1);
        $coords2 = getPostcodeCoordinates($postcode2);
    } catch (Exception $e) {
       
        return PHP_FLOAT_MAX;
    }
   
    $earthRadius = 3959;
   
    $lat1 = deg2rad($coords1['latitude']);
    $lon1 = deg2rad($coords1['longitude']);
    $lat2 = deg2rad($coords2['latitude']);
    $lon2 = deg2rad($coords2['longitude']);
   
    $latDelta = $lat2 - $lat1;
    $lonDelta = $lon2 - $lon1;
   
    $a = sin($latDelta/2) * sin($latDelta/2) +
         cos($lat1) * cos($lat2) * sin($lonDelta/2) * sin($lonDelta/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
   
    $distance = $earthRadius * $c;
   
    return $distance;
}

function getDateRangeForPeriod($period) {
    $today = new DateTime();
   
    switch ($period) {
        case 'current_week':
            $start = clone $today;
            $start->modify('monday this week');
            $end = clone $start;
            $end->modify('+6 days');
            break;
        case 'last_week':
            $start = new DateTime('monday last week');
            $end = clone $start;
            $end->modify('+6 days');
            break;
        case 'current_month':
            $start = new DateTime('first day of this month');
            $end = new DateTime('last day of this month');
            break;
        case 'current_quarter':
            $quarter = ceil($today->format('n') / 3);
            $start = new DateTime($today->format('Y') . '-' . (($quarter - 1) * 3 + 1) . '-01');
            $end = clone $start;
            $end->modify('+2 months')->modify('last day of this month');
            break;
        case 'current_year':
            $start = new DateTime($today->format('Y') . '-01-01');
            $end = new DateTime($today->format('Y') . '-12-31');
            break;
        default:
            $start = clone $today;
            $start->modify('monday this week');
            $end = clone $start;
            $end->modify('+6 days');
    }
   
    return [
        'start' => $start->format('Y-m-d'),
        'end' => $end->format('Y-m-d')
    ];
}

function getPreviousPeriodRange($period, $currentRange) {
    $start = new DateTime($currentRange['start']);
    $end = new DateTime($currentRange['end']);
    $diff = $start->diff($end)->days + 1;
   
    $prevStart = clone $start;
    $prevStart->modify("-{$diff} days");
    $prevEnd = clone $end;
    $prevEnd->modify("-{$diff} days");
   
    return [
        'start' => $prevStart->format('Y-m-d'),
        'end' => $prevEnd->format('Y-m-d')
    ];
}

function calculateKPIs($db, $period, $start_date = null, $end_date = null, $status_filter = 'all', $location_filter = '') {
    $kpis = [];
   
    try {
       
        if ($period === 'custom' && $start_date && $end_date) {
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            if ($start > $end) {
                throw new Exception("Start date cannot be after end date.");
            }
           
            $dateRange = [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d')
            ];
        } else {
            $dateRange = getDateRangeForPeriod($period);
        }
       
        $kpis['date_range'] = $dateRange;
       
        $base_where_conditions = ["Date BETWEEN :start_date AND :end_date"];
        $base_params = [
            ':start_date' => $dateRange['start'] . ' 00:00:00',
            ':end_date' => $dateRange['end'] . ' 23:59:59'
        ];
       
        if ($status_filter !== 'all') {
            $base_where_conditions[] = "Status = :status_filter";
            $base_params[':status_filter'] = $status_filter;
        }
       
        if (!empty($location_filter)) {
            $base_where_conditions[] = "(City LIKE :location_filter OR Address LIKE :location_filter OR Postcode LIKE :location_filter)";
            $base_params[':location_filter'] = '%' . $location_filter . '%';
        }
       
        $base_where_clause = 'WHERE ' . implode(' AND ', $base_where_conditions);
       
        $stmt_all_candidates = $db->prepare("SELECT id, Name, Email, JobTitle, Status, City, Postcode, Date, CreatedBy, ProfileImage FROM _candidates $base_where_clause ORDER BY Date DESC");
        $stmt_all_candidates->execute($base_params);
        $kpis['detailed_candidates'] = $stmt_all_candidates->fetchAll(PDO::FETCH_ASSOC);
       
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM _candidates $base_where_clause");
        $stmt->execute($base_params);
        $kpis['total_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
       
        $stmt = $db->prepare("SELECT COUNT(*) as new_candidates FROM _candidates $base_where_clause");
        $stmt->execute($base_params);
        $kpis['new_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['new_candidates'];
       
        $status_count_params = [
            ':start_date' => $base_params[':start_date'],
            ':end_date' => $base_params[':end_date']
        ];
       
        $stmt = $db->prepare("SELECT COUNT(*) as active FROM _candidates WHERE Status = 'active' AND Date BETWEEN :start_date AND :end_date");
        $stmt->execute($status_count_params);
        $kpis['active_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
       
        $stmt = $db->prepare("SELECT COUNT(*) as inactive FROM _candidates WHERE Status = 'inactive' AND Date BETWEEN :start_date AND :end_date");
        $stmt->execute($status_count_params);
        $kpis['inactive_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['inactive'];
       
        $stmt = $db->prepare("SELECT COUNT(*) as archived FROM _candidates WHERE Status = 'archived' AND Date BETWEEN :start_date AND :end_date");
        $stmt->execute($status_count_params);
        $kpis['archived_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['archived'];
       
        $stmt = $db->prepare("SELECT COUNT(*) as pending FROM _candidates WHERE Status = 'pending' AND Date BETWEEN :start_date AND :end_date");
        $stmt->execute($status_count_params);
        $kpis['pending_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending'];
       
        // Job Title Stats (not affected by overall status/location filter for distribution)
        $stmt = $db->prepare("SELECT JobTitle, COUNT(*) as count FROM _candidates WHERE Date BETWEEN :start_date AND :end_date AND JobTitle IS NOT NULL AND JobTitle != '' GROUP BY JobTitle ORDER BY count DESC");
        $stmt->execute($status_count_params);
        $kpis['job_title_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        // City Stats (not affected by overall status/location filter for distribution)
        $stmt = $db->prepare("SELECT City, COUNT(*) as count FROM _candidates WHERE Date BETWEEN :start_date AND :end_date AND City IS NOT NULL AND City != '' GROUP BY City ORDER BY count DESC");
        $stmt->execute($status_count_params);
        $kpis['city_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        // CreatedBy Stats (not affected by overall status/location filter for distribution)
        $stmt = $db->prepare("SELECT CreatedBy, COUNT(*) as count FROM _candidates WHERE Date BETWEEN :start_date AND :end_date GROUP BY CreatedBy ORDER BY count DESC");
        $stmt->execute($status_count_params);
        $kpis['created_by_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        // Daily Trend (not affected by overall status/location filter for distribution)
        $stmt = $db->prepare("SELECT DATE(Date) as date, COUNT(*) as count FROM _candidates WHERE Date BETWEEN :start_date AND :end_date GROUP BY DATE(Date) ORDER BY date");
        $stmt->execute($status_count_params);
        $kpis['daily_trend'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        // Growth Rate calculation (uses the same filters as 'new_candidates' for consistency)
        $previousPeriod = getPreviousPeriodRange($period, $dateRange);
        $prev_period_params = [
            ':start_date' => $previousPeriod['start'] . ' 00:00:00',
            ':end_date' => $previousPeriod['end'] . ' 23:59:59'
        ];
       
        // Apply the same status/location filters to the previous period count for fair comparison
        $prev_where_conditions = ["Date BETWEEN :start_date AND :end_date"];
        if ($status_filter !== 'all') {
            $prev_where_conditions[] = "Status = :status_filter";
            $prev_period_params[':status_filter'] = $status_filter;
        }
        if (!empty($location_filter)) {
            $prev_where_conditions[] = "(City LIKE :location_filter OR Address LIKE :location_filter OR Postcode LIKE :location_filter)";
            $prev_period_params[':location_filter'] = '%' . $location_filter . '%';
        }
        $prev_where_clause = 'WHERE ' . implode(' AND ', $prev_where_conditions);
       
        // Get previous period count
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM _candidates $prev_where_clause");
        $stmt->execute($prev_period_params);
        $prev_period_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
       
        // Calculate growth rate
        $curr_period_count = $kpis['new_candidates'];
        $kpis['growth_rate'] = $prev_period_count > 0
            ? round((($curr_period_count - $prev_period_count) / $prev_period_count) * 100, 2)
            : ($curr_period_count > 0 ? 100 : 0);
       
        return $kpis;
       
    } catch (Exception $e) {
        error_log("Error calculating KPIs: " . $e->getMessage());
        return ['error' => "Failed to calculate KPIs: " . $e->getMessage()];
    }
}

// --- Data for Mailshot Filter Dropdowns (Job Titles, Locations) ---
if ($mode === 'mailshot') {
    $job_titles_query = "SELECT DISTINCT JobTitle FROM _candidates WHERE JobTitle IS NOT NULL AND JobTitle != '' ORDER BY JobTitle";
    $job_titles_stmt = $db_2->query($job_titles_query);
    $job_titles = $job_titles_stmt->fetchAll(PDO::FETCH_COLUMN);
   
    $locations_query = "SELECT DISTINCT City FROM _candidates WHERE City IS NOT NULL AND City != '' ORDER BY City";
    $locations_stmt = $db_2->query($locations_query);
    $locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);
}

// --- KPI Data Calculation for Display (only if mode is 'kpi') ---
$kpi_data = [];
if ($mode === 'kpi') {
    $kpi_data = calculateKPIs($db_2, $kpi_period, $kpi_start_date, $kpi_end_date, $kpi_status_filter, $kpi_location_filter);
}

// Mapping for CreatedBy IDs to Names (for display in tables)
$createdByMapping = [
    "1" => "Chax Shamwana",
    "10" => "Millie Brown",
    "11" => "Jay Fuller",
    "13" => "Jack Dowler",
    "15" => "Alex Lapompe",
    "2" => "Alex Lapompe",
    "9" => "Jack Dowler"
];

// Debug output to check if user is authorized (remove this in production)
error_log("Debug - Logged in user email: " . $loggedInUserEmail);
error_log("Debug - Can export: " . ($canExport ? 'YES' : 'NO'));

// Get success/error messages from session
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Management</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Inter font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN (for utility classes, though custom styles are also used) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Your custom stylesheet (if you have one, otherwise styles are inline below) -->
    <link rel="stylesheet" href="style.css">
    <?php include "../../includes/head.php"; // Assuming this includes meta tags, etc. ?>
   
    <style>
       
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6; /* Light background */
            color: #333; /* Dark grey text */
        }
       
        .pc-container {
            margin-left: 280px; /* Adjust based on sidebar width from includes/sidebar.php */
            padding: 20px;
            background-color: #ffffff; /* White content background */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* Soft shadow */
        }
       
        .pc-content {
            padding: 20px;
        }
       
        /* Navigation Buttons (View Candidates, Mailshot, KPI) */
        .nav-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }
       
        .nav-buttons a {
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
       
        .nav-buttons a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
       
        .nav-buttons a.active {
            background: #007bff; /* Blue for active tab */
            color: white;
        }
       
        .nav-buttons a:not(.active) {
            background: #e0e0e0; /* Light grey for inactive tabs */
            color: #333;
        }
       
        /* Status Filter Buttons (Active, Inactive, etc.) */
        .status-filter-buttons {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
       
        .status-filter-buttons a {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
       
        /* KPI Summary Cards */
        .kpi-summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
       
        .kpi-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
       
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
       
        .kpi-card.growth {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
        }
       
        .kpi-card.decline {
            border-color: #dc3545;
            background: linear-gradient(135deg, #f8d7da 0%, #ffffff 100%);
        }
       
        .kpi-card .value {
            font-size: 2.5em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
       
        .kpi-card.growth .value {
            color: #28a745;
        }
       
        .kpi-card.decline .value {
            color: #dc3545;
        }
       
        .kpi-card .label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
       
        /* KPI Detail Tables */
        .kpi-detail-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
       
        .kpi-detail-table th,
        .kpi-detail-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
       
        .kpi-detail-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
       
        .kpi-detail-table tbody tr:hover {
            background: #f8f9fa;
        }
       
        /* Info boxes for each mode */
        .candidates-info,
        .mailshot-info,
        .kpi-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
       
        .candidates-info h5,
        .mailshot-info h5,
        .kpi-info h5 {
            color: #007bff;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
       
        .candidates-info ul,
        .mailshot-info ul,
        .kpi-info ul {
            margin: 10px 0 0 20px;
            padding: 0;
        }
       
        .candidates-info li,
        .mailshot-info li,
        .kpi-info li {
            margin-bottom: 8px;
        }
       
        .status-filter-buttons a:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
       
        .status-filter-buttons a.active {
            background: #28a745;
            color: white;
        }
       
        .status-filter-buttons a:not(.active) {
            background: #f5f5f5;
            color: #555;
        }
       
        .export-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
       
        .export-btn {
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
       
        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
       
        .export-btn.excel {
            background-color: #21a366;
            color: white;
        }
       
        .export-btn.csv {
            background-color: #6c757d;
            color: white;
        }
       
        .success-message, .error-message {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
       
        .success-message {
            background-color: #d4edda; /* Light green */
            color: #155724; /* Dark green text */
            border: 1px solid #c3e6cb;
        }
       
        .error-message {
            background-color: #f8d7da; /* Light red */
            color: #721c24; /* Dark red text */
            border: 1px solid #f5c6cb;
        }
       
        .filter-section, .kpi-filter-section {
            background-color: #f8f9fa; /* Very light grey background */
            border: 1px solid #e9ecef; /* Light border */
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
       
        .filter-section h5, .kpi-filter-section h5 {
            color: #343a40;
            margin-bottom: 20px;
            font-size: 1.25rem;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6; /* Separator line */
            padding-bottom: 10px;
        }
       
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px; /* Spacing between filter inputs */
            margin-bottom: 15px;
        }
       
        .filter-row .col-md-3 {
            flex: 1 1 calc(25% - 15px); /* Four columns on larger screens, adjusting for gap */
            min-width: 200px; /* Minimum width for filter inputs to prevent squishing */
        }
       
        .filter-label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #495057;
        }
       
        .filter-input, .filter-select, .mailshot-textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da; /* Standard input border */
            border-radius: 6px;
            font-size: 1rem;
            color: #495057;
            background-color: white;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
       
        .filter-input:focus, .filter-select:focus, .mailshot-textarea:focus {
            border-color: #80bdff; /* Blue border on focus */
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Blue glow on focus */
        }
       
        .filter-select[multiple] {
            height: auto;
            min-height: 120px; /* Minimum height for multi-select dropdowns */
        }
       
        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
       
        .filter-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
       
        .filter-buttons button:hover {
            transform: translateY(-1px);
        }
       
        .filter-buttons button[type="submit"] {
            background-color: #007bff; /* Blue submit button */
            color: white;
        }
       
        .filter-buttons button[type="submit"]:hover {
            background-color: #0056b3;
        }
       
        .filter-buttons button[type="reset"] {
            background-color: #6c757d; /* Grey reset button */
            color: white;
        }
       
        .filter-buttons button[type="reset"]:hover {
            background-color: #5a6268;
        }
       
        /* Mailshot Specific Styles */
        .mailshot-form .form-group {
            margin-bottom: 15px;
        }
       
        .mailshot-form label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
            color: #495057;
        }
       
        .mailshot-form .btn-send {
            background-color: #28a745; /* Green send button */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
       
        .mailshot-form .btn-send:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }
       
        /* File upload and attachment styles */
        .file-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
       
        .file-item .btn-danger {
            padding: 2px 6px;
            font-size: 12px;
        }
       
        /* Candidate selection styles */
        .candidate-checkbox, input[name="selected_candidates[]"] {
            transform: scale(1.2);
            margin-right: 8px;
        }
       
        /* Enhanced card styles for mailshot sidebar */
        .card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
       
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 16px;
        }
       
        .card-header h6 {
            margin: 0;
            font-weight: 600;
            color: #495057;
        }
       
        .card-body {
            padding: 16px;
        }
       
        /* Badge styles for recipients */
        .badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
        }
       
        .bg-light {
            background-color: #f8f9fa !important;
        }
       
        .text-dark {
            color: #343a40 !important;
        }
       
        /* Alert styles */
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
            border-radius: 6px;
            padding: 12px 16px;
        }
       
        .alert-info i {
            margin-right: 8px;
        }
       
        /* Processing spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.1em;
        }
       
        /* Button enhancements */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
       
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
       
        .btn-lg {
            padding: 12px 30px;
            font-size: 1.1rem;
        }
       
        /* KPI Report Information Section */
        .kpi-info {
            background-color: #e6f7ff; /* Light blue background */
            border: 1px solid #91d5ff; /* Blue border */
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            color: #0050b3; /* Darker blue text */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
       
        .kpi-info h5 {
            color: #003a8c; /* Even darker blue heading */
            margin-bottom: 10px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
       
        .kpi-info ul {
            list-style: disc;
            margin-left: 20px;
            padding: 0;
            font-size: 0.95rem;
            line-height: 1.6;
        }
       
        /* KPI Summary Cards */
        .kpi-summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Responsive grid */
            gap: 20px;
            margin-bottom: 30px;
        }
       
        .kpi-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
       
        .kpi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
       
        .kpi-card .value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #007bff; /* Blue value */
            margin-bottom: 5px;
        }
       
        .kpi-card .label {
            font-size: 1rem;
            color: #555;
            font-weight: 500;
        }
       
        .kpi-card.growth .value {
            color: #28a745; /* Green for positive growth */
        }
       
        .kpi-card.decline .value {
            color: #dc3545; /* Red for negative growth */
        }
       
        /* Tables (Candidates List and KPI Detail Tables) */
        .table-responsive {
            overflow-x: auto; /* Enable horizontal scrolling for small screens */
            margin-top: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
       
        .candidates-table, .kpi-detail-table {
            width: 100%;
            border-collapse: collapse; /* Collapse borders for clean look */
            font-size: 0.9rem;
            color: #343a40;
        }
       
        .candidates-table th, .kpi-detail-table th {
            background-color: #f2f2f2; /* Light grey header */
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6; /* Stronger bottom border for header */
            font-weight: 600;
            color: #495057;
        }
       
        .candidates-table td, .kpi-detail-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #e9ecef; /* Light grey line for rows */
            /* border-right: 1px solid #e9ecef; /* Column borders */
        }
       
        /* Remove right border from last column and left from first for cleaner edges */
        .candidates-table td:last-child, .kpi-detail-table td:last-child {
            border-right: none;
        }
       
        .candidates-table td:first-child, .kpi-detail-table td:first-child {
            border-left: none;
        }
       
        /* Alternating row colors */
        .candidates-table tbody tr:nth-child(odd) {
            background-color: #fcfcfc; /* Very light grey for odd rows */
        }
       
        .candidates-table tbody tr:nth-child(even) {
            background-color: #ffffff; /* White for even rows */
        }
       
        .candidates-table tbody tr:hover {
            background-color: #eaf6ff; /* Light blue on hover */
        }
       
        /* Profile Picture in tables */
        .candidates-table .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%; /* Circular image */
            object-fit: cover; /* Ensure image covers the area */
            border: 1px solid #ddd;
            vertical-align: middle;
        }
       
        /* Status Badges in tables */
        .candidates-table .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: capitalize;
        }
       
        .status-badge.active { background-color: #d4edda; color: #155724; } /* Green */
        .status-badge.inactive { background-color: #f8d7da; color: #721c24; } /* Red */
        .status-badge.pending { background-color: #fff3cd; color: #856404; } /* Yellow */
        .status-badge.archived { background-color: #e2e3e5; color: #383d41; } /* Grey */
       
        /* No Candidates/Data Message */
        .no-candidates-message {
            text-align: center;
            padding: 30px;
            background-color: #fefefe;
            border: 1px dashed #ced4da; /* Dashed border */
            border-radius: 8px;
            margin-top: 20px;
            color: #6c757d;
            font-size: 1.1rem;
        }
       
        /* Hide sections based on mode */
        .mode-section {
            display: none;
        }
       
        .mode-section.active {
            display: block;
        }
       
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .pc-container {
                margin-left: 0; /* Sidebar might collapse or become overlay on smaller screens */
            }
            .filter-row .col-md-3 {
                flex: 1 1 calc(50% - 15px); /* Two columns on medium screens */
            }
        }
       
        @media (max-width: 768px) {
            .filter-row .col-md-3 {
                flex: 1 1 100%; /* Single column on small screens */
            }
            .nav-buttons, .status-filter-buttons, .export-buttons, .filter-buttons {
                flex-direction: column;
                align-items: stretch; /* Stack buttons vertically */
            }
            .kpi-summary-cards {
                grid-template-columns: 1fr; /* Single column for KPI cards */
            }
            .candidates-table, .kpi-detail-table {
                font-size: 0.85rem; /* Slightly smaller font for tables */
            }
            .candidates-table th, .candidates-table td,
            .kpi-detail-table th, .kpi-detail-table td {
                padding: 8px 10px; /* Reduced padding */
            }
        }
    </style>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php"; ?>
   
    <div class="pc-container">
        <div class="pc-content">
            <?php include "../../includes/breadcrumb.php"; ?>
           
            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="?mode=candidates" class="<?= ($mode === 'candidates') ? 'active' : '' ?>">
                    <i class="fa fa-users"></i> View Candidates
                </a>
                <a href="?mode=mailshot" class="<?= ($mode === 'mailshot') ? 'active' : '' ?>">
                    <i class="fa fa-paper-plane"></i> Create Mailshot
                </a>
                <a href="?mode=kpi" class="<?= ($mode === 'kpi') ? 'active' : '' ?>">
                    <i class="fa fa-chart-bar"></i> KPI Reports
                </a>
            </div>
           
            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
                <div class="success-message">
                    <i class="fa fa-check-circle"></i> <?php echo nl2br(htmlspecialchars($success_message)); ?>
                </div>
            <?php endif; ?>
           
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <i class="fa fa-exclamation-triangle"></i> <?php echo nl2br(htmlspecialchars($error_message)); ?>
                </div>
            <?php endif; ?>
           
            <!-- =============================================== -->
            <!-- VIEW CANDIDATES SECTION -->
            <!-- =============================================== -->
            <div id="candidates-section" class="mode-section <?= ($mode === 'candidates') ? 'active' : '' ?>">
                <!-- Candidates Information Box -->
                <div class="candidates-info">
                    <h5><i class="fa fa-users"></i> Candidate Management System</h5>
                    <p><strong>Comprehensive candidate database management:</strong></p>
                    <ul>
                        <li>View and search through all registered candidates</li>
                        <li>Filter by status, location, job title, and date range</li>
                        <li>Export candidate data to Excel or CSV formats</li>
                        <li>Select candidates for mailshot campaigns</li>
                        <li>Track candidate status and consultant assignments</li>
                    </ul>
                </div>
               
                <!-- Status Filter Buttons for Candidates View -->
                <div class="status-filter-buttons">
                    <a href="?mode=candidates&status=all" class="<?= ($status_filter === 'all') ? 'active' : '' ?>">
                        <i class="fa fa-list-alt"></i> All
                    </a>
                    <a href="?mode=candidates&status=active" class="<?= ($status_filter === 'active') ? 'active' : '' ?>">
                        <i class="fa fa-check-circle"></i> Active
                    </a>
                    <a href="?mode=candidates&status=inactive" class="<?= ($status_filter === 'inactive') ? 'active' : '' ?>">
                        <i class="fa fa-times-circle"></i> Inactive
                    </a>
                    <a href="?mode=candidates&status=pending" class="<?= ($status_filter === 'pending') ? 'active' : '' ?>">
                        <i class="fa fa-hourglass-half"></i> Pending
                    </a>
                    <a href="?mode=candidates&status=archived" class="<?= ($status_filter === 'archived') ? 'active' : '' ?>">
                        <i class="fa fa-archive"></i> Archived
                    </a>
                </div>
               
                <!-- Candidate Filtering Section -->
                <div class="filter-section">
                    <h5><i class="fa fa-filter"></i> Candidate Filtering System</h5>
                    <form method="GET" action="">
                        <input type="hidden" name="mode" value="candidates">
                        <div class="row filter-row">
                            <div class="col-md-3">
                                <div class="filter-label">Keywords (Name, Email, Job Title)</div>
                                <input type="text" name="keyword" class="filter-input" placeholder="Search by keywords..." value="<?php echo htmlspecialchars($keyword_filter); ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Location (City, Address, Postcode)</div>
                                <input type="text" name="location" class="filter-input" placeholder="Search by location..." value="<?php echo htmlspecialchars($location_filter); ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Job Title/Position</div>
                                <input type="text" name="position" class="filter-input" placeholder="Search by position..." value="<?php echo htmlspecialchars($position_filter); ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Status</div>
                                <select name="status" class="filter-select">
                                    <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All</option>
                                    <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="archived" <?= $status_filter === 'archived' ? 'selected' : '' ?>>Archived</option>
                                </select>
                            </div>
                        </div>
                        <div class="filter-buttons">
                            <button type="submit"><i class="fa fa-filter"></i> Apply Filters</button>
                            <button type="reset" onclick="window.location.href='?mode=candidates'"><i class="fa fa-sync-alt"></i> Reset Filters</button>
                        </div>
                    </form>
                </div>
               
                <!-- Export Buttons for Candidates -->
                <?php if ($canExport): ?>
                <div class="export-buttons">
                    <a href="?mode=candidates&export=excel&status=<?= htmlspecialchars($status_filter) ?>&keyword=<?= htmlspecialchars($keyword_filter) ?>&location=<?= htmlspecialchars($location_filter) ?>&position=<?= htmlspecialchars($position_filter) ?>" class="export-btn excel" onclick="return confirm('Export filtered candidates to Excel?')">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </a>
                    <a href="?mode=candidates&export=csv&status=<?= htmlspecialchars($status_filter) ?>&keyword=<?= htmlspecialchars($keyword_filter) ?>&location=<?= htmlspecialchars($location_filter) ?>&position=<?= htmlspecialchars($position_filter) ?>" class="export-btn csv" onclick="return confirm('Export filtered candidates to CSV?')">
                        <i class="fa fa-file-csv"></i> Export CSV
                    </a>
                </div>
                <?php endif; ?>
               
                <!-- Candidates Table -->
                <div class="card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="mb-0"><i class="fa fa-users"></i> Candidates Database (<?= count($candidates_for_display) ?> records)</h4>
                        <?php if ($canSendMailshot): ?>
                        <div class="badge bg-info">
                            <i class="fa fa-paper-plane"></i> Mailshot Available
                        </div>
                        <?php endif; ?>
                    </div>
                   
                    <?php if ($canSendMailshot): ?>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <button type="button" id="candidateMailshotBtn" class="btn btn-primary btn-lg" style="display: none;" onclick="window.location.href='?mode=mailshot'">
                                            <i class="fa fa-paper-plane me-2"></i>Create Mailshot for Selected (<span id="selectedCandidateCount">0</span>)
                                        </button>
                                    </div>
                                    <div class="text-muted">
                                        <small><i class="fa fa-info-circle me-1"></i>Select candidates to send professional mailshots</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                   
                    <div class="table-responsive">
                        <table class="candidates-table" id="candidatesTable">
                            <thead>
                                <tr>
                                    <?php if ($canSendMailshot): ?>
                                        <th><input type="checkbox" id="selectAll" onchange="toggleSelectAllCandidates()"> Select All</th>
                                    <?php endif; ?>
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Date Added</th>
                                    <th>Added By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($candidates_for_display)): ?>
                                    <?php foreach ($candidates_for_display as $candidate): ?>
                                        <tr>
                                            <?php if ($canSendMailshot): ?>
                                                <td>
                                                    <input type="checkbox" class="candidate-checkbox" value="<?= htmlspecialchars($candidate['id']) ?>" data-name="<?= htmlspecialchars($candidate['Name'] ?? '') ?>" onchange="updateSelectedCandidateCount()">
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <?php $profile_pic_url = !empty($candidate['ProfileImage']) ? htmlspecialchars($candidate['ProfileImage']) : 'https://placehold.co/40x40/cccccc/333333?text=N/A'; ?>
                                                <img src="<?= $profile_pic_url ?>" alt="Profile" class="profile-pic" onerror="this.onerror=null;this.src='https://placehold.co/40x40/cccccc/333333?text=N/A';">
                                            </td>
                                            <td><?= htmlspecialchars($candidate['Name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($candidate['Email'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($candidate['JobTitle'] ?? 'N/A') ?></td>
                                            <td><span class="status-badge <?= strtolower(htmlspecialchars($candidate['Status'] ?? '')) ?>"><?= htmlspecialchars($candidate['Status'] ?? 'N/A') ?></span></td>
                                            <td><?= htmlspecialchars($candidate['City'] ?? 'N/A') ?> (<?= htmlspecialchars($candidate['Postcode'] ?? 'N/A') ?>)</td>
                                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($candidate['Date'] ?? ''))) ?></td>
                                            <td><?= htmlspecialchars($createdByMapping[$candidate['CreatedBy']] ?? 'Unknown') ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-info" title="View Details"><i class="fa fa-eye"></i></a>
                                                <a href="#" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="<?= $canSendMailshot ? '10' : '9' ?>" class="no-candidates-message">
                                            No candidates found matching your criteria. Try adjusting your filters.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
           
            <!-- =============================================== -->
            <!-- CREATE MAILSHOT SECTION -->
            <!-- =============================================== -->
            <div id="mailshot-section" class="mode-section <?= ($mode === 'mailshot') ? 'active' : '' ?>">
                <?php if (!$canSendMailshot): ?>
                    <div class="error-message">
                        <i class="fa fa-exclamation-triangle"></i> Access Denied: You do not have permission to send mailshots. Only authorized consultants can send mailshots.
                    </div>
                <?php else: ?>
                    <!-- Mailshot Information Box -->
                    <div class="mailshot-info">
                        <h5><i class="fa fa-paper-plane"></i> Professional Mailshot System</h5>
                        <p><strong>Send targeted email campaigns to selected candidates:</strong></p>
                        <ul>
                            <li>Filter candidates by status, location, job title, and date range</li>
                            <li>Select individual candidates or use bulk selection</li>
                            <li>Choose from pre-built email templates or create custom messages</li>
                            <li>Attach files and documents to your emails</li>
                            <li>Professional anti-spam delivery system ensures inbox delivery</li>
                        </ul>
                    </div>
                   
                    <!-- Mailshot Filtering Section -->
                    <div class="filter-section">
                        <h5><i class="fa fa-filter"></i> Filter Candidates for Mailshot</h5>
                        <form method="GET" action="">
                            <input type="hidden" name="mode" value="mailshot">
                            <div class="row filter-row">
                                <div class="col-md-3">
                                    <div class="filter-label">Keywords (Name, Email, Job Title)</div>
                                    <input type="text" name="keyword" class="filter-input" placeholder="Search by keywords..." value="<?php echo htmlspecialchars($keyword_filter); ?>">
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">Location (City, Address, Postcode)</div>
                                    <input type="text" name="location" class="filter-input" placeholder="Search by location..." value="<?php echo htmlspecialchars($location_filter); ?>">
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">Job Title/Position</div>
                                    <input type="text" name="position" class="filter-input" placeholder="Search by position..." value="<?php echo htmlspecialchars($position_filter); ?>">
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">Status</div>
                                    <select name="status" class="filter-select">
                                        <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All</option>
                                        <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="archived" <?= $status_filter === 'archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                </div>
                            </div>
                            <div class="filter-buttons">
                                <button type="submit"><i class="fa fa-filter"></i> Apply Filters</button>
                                <button type="reset" onclick="window.location.href='?mode=mailshot'"><i class="fa fa-sync-alt"></i> Reset Filters</button>
                            </div>
                        </form>
                    </div>
                   
                    <!-- Enhanced Mailshot Form -->
                    <div class="card p-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="mb-0"><i class="fa fa-paper-plane"></i> Create Professional Mailshot</h4>
                            <div class="badge bg-success">
                                <i class="fa fa-envelope"></i> Consultant: <?php echo htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName); ?>
                            </div>
                        </div>
                       
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?mode=mailshot" enctype="multipart/form-data" class="mailshot-form" id="candidateMailshotForm">
                            <input type="hidden" name="send_mailshot" value="1">
                            <input type="hidden" name="selected_candidates" id="mailshotSelectedCandidates">
                           
                            <!-- Anti-Spam Notice -->
                            <div class="alert alert-info mb-4">
                                <i class="fa fa-shield-alt"></i>
                                <strong>Professional Email Delivery:</strong> This system uses anti-spam measures to ensure your emails reach candidates' inboxes. All replies will be forwarded to your email (<?php echo htmlspecialchars($loggedInUserEmail); ?>).
                            </div>
                           
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="mailshot_template"><i class="fa fa-file-alt"></i> Email Template</label>
                                        <select id="mailshot_template" name="mailshot_template" class="form-control">
                                            <option value="custom">Custom Message</option>
                                            <option value="welcome">Welcome to Network</option>
                                            <option value="job_opportunity">Job Opportunity</option>
                                            <option value="followup">Follow Up</option>
                                        </select>
                                    </div>
                                   
                                    <div class="form-group mb-3">
                                        <label for="mailshot_subject"><i class="fa fa-tag"></i> Subject Line</label>
                                        <input type="text" id="mailshot_subject" name="mailshot_subject" class="form-control" placeholder="Enter email subject..." required>
                                    </div>
                                   
                                    <div class="form-group mb-3">
                                        <label for="mailshot_message"><i class="fa fa-envelope"></i> Email Message</label>
                                        <textarea id="mailshot_message" name="mailshot_message" class="form-control" rows="12" placeholder="Enter your professional message here..." required></textarea>
                                        <small class="text-muted">Use [CANDIDATE_NAME] as placeholder for personalization</small>
                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="mailshot_attachments"><i class="fa fa-paperclip"></i> File Attachments</label>
                                        <input type="file" id="mailshot_attachments" name="mailshot_attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xlsx,.xls,.png,.jpg,.jpeg">
                                        <small class="text-muted">Max 5MB per file. PDF, DOC, Excel, Images allowed.</small>
                                    </div>
                                   
                                    <div id="filePreview" class="mb-3"></div>
                                   
                                    <div class="form-group mb-3">
                                        <label><i class="fa fa-users"></i> Selected Candidates (<span id="mailshotCandidateCount">0</span>)</label>
                                        <div id="mailshotCandidateList" class="border rounded p-2 bg-light" style="min-height: 60px; max-height: 150px; overflow-y: auto;">
                                            <small class="text-muted">Select candidates from the table below...</small>
                                        </div>
                                    </div>
                                   
                                    <button type="submit" id="sendCandidateMailshotBtn" class="btn btn-success btn-lg w-100">
                                        <i class="fa fa-paper-plane me-2"></i>Send Mailshot
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                   
                    <!-- Candidate Selection Table for Mailshot -->
                    <div class="card p-4">
                        <h5><i class="fa fa-list"></i> Select Candidates for Mailshot (<?= count($candidates_for_display) ?> found)</h5>
                        <div class="table-responsive">
                            <table class="candidates-table" id="candidatesTable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAllCandidates" onchange="toggleSelectAllCandidates()"> Select All</th>
                                        <th>Picture</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Job Title</th>
                                        <th>Status</th>
                                        <th>Location</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($candidates_for_display)): ?>
                                        <?php foreach ($candidates_for_display as $candidate): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_candidates[]" class="candidate-checkbox" value="<?= htmlspecialchars($candidate['id']) ?>" data-name="<?= htmlspecialchars($candidate['Name'] ?? '') ?>" onchange="updateSelectedCandidateCount()">
                                                </td>
                                                <td>
                                                    <?php $profile_pic_url = !empty($candidate['ProfileImage']) ? htmlspecialchars($candidate['ProfileImage']) : 'https://placehold.co/40x40/cccccc/333333?text=N/A'; ?>
                                                    <img src="<?= $profile_pic_url ?>" alt="Profile" class="profile-pic" onerror="this.onerror=null;this.src='https://placehold.co/40x40/cccccc/333333?text=N/A';">
                                                </td>
                                                <td><?= htmlspecialchars($candidate['Name'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($candidate['Email'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($candidate['JobTitle'] ?? 'N/A') ?></td>
                                                <td><span class="status-badge <?= strtolower(htmlspecialchars($candidate['Status'] ?? '')) ?>"><?= htmlspecialchars($candidate['Status'] ?? 'N/A') ?></span></td>
                                                <td><?= htmlspecialchars($candidate['City'] ?? 'N/A') ?> (<?= htmlspecialchars($candidate['Postcode'] ?? 'N/A') ?>)</td>
                                                <td><?= htmlspecialchars(date('Y-m-d', strtotime($candidate['Date'] ?? ''))) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="no-candidates-message">
                                                No candidates found matching your criteria for mailshot.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
           
            <!-- =============================================== -->
            <!-- KPI REPORTING SECTION -->
            <!-- =============================================== -->
            <div id="kpi-section" class="mode-section <?= ($mode === 'kpi') ? 'active' : '' ?>">
                <!-- KPI Information Box -->
                <div class="kpi-info">
                    <h5><i class="fa fa-bar-chart"></i> KPI Reporting Dashboard</h5>
                    <p><strong>Track and analyze your candidate metrics:</strong></p>
                    <ul>
                        <li>Monitor weekly, monthly, and quarterly candidate registration trends</li>
                        <li>Analyze candidate status distributions and conversion rates</li>
                        <li>Track top performing job titles and locations</li>
                        <li>View team performance and candidate creation statistics</li>
                        <li>Generate custom reports for specific date ranges</li>
                    </ul>
                </div>
               
                <!-- KPI Filter Section -->
                <div class="kpi-filter-section">
                    <h5>Filter KPI Report</h5>
                    <form method="GET" action="">
                        <input type="hidden" name="mode" value="kpi">
                        <div class="row filter-row">
                            <div class="col-md-3">
                                <div class="filter-label">Time Period</div>
                                <select name="kpi_period" class="filter-select" id="kpi_period" onchange="toggleCustomDateInputs()">
                                    <option value="current_week" <?= $kpi_period === 'current_week' ? 'selected' : '' ?>>Current Week</option>
                                    <option value="last_week" <?= $kpi_period === 'last_week' ? 'selected' : '' ?>>Last Week</option>
                                    <option value="current_month" <?= $kpi_period === 'current_month' ? 'selected' : '' ?>>Current Month</option>
                                    <option value="current_quarter" <?= $kpi_period === 'current_quarter' ? 'selected' : '' ?>>Current Quarter</option>
                                    <option value="current_year" <?= $kpi_period === 'current_year' ? 'selected' : '' ?>>Current Year</option>
                                    <option value="custom" <?= $kpi_period === 'custom' ? 'selected' : '' ?>>Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Start Date</div>
                                <input type="date" name="kpi_start_date" class="filter-input" id="kpi_start_date" value="<?= htmlspecialchars($kpi_start_date) ?>" <?= $kpi_period !== 'custom' ? 'disabled' : '' ?>>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">End Date</div>
                                <input type="date" name="kpi_end_date" class="filter-input" id="kpi_end_date" value="<?= htmlspecialchars($kpi_end_date) ?>" <?= $kpi_period !== 'custom' ? 'disabled' : '' ?>>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Status Filter</div>
                                <select name="kpi_status_filter" class="filter-select">
                                    <option value="all" <?= $kpi_status_filter === 'all' ? 'selected' : '' ?>>All</option>
                                    <option value="active" <?= $kpi_status_filter === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $kpi_status_filter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="pending" <?= $kpi_status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="archived" <?= $kpi_status_filter === 'archived' ? 'selected' : '' ?>>Archived</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Location Filter</div>
                                <input type="text" name="kpi_location_filter" class="filter-input" placeholder="Search by location..." value="<?php echo htmlspecialchars($kpi_location_filter); ?>">
                            </div>
                        </div>
                        <div class="filter-buttons">
                            <button type="submit"><i class="fa fa-filter"></i> Apply KPI Filters</button>
                            <button type="reset" onclick="window.location.href='?mode=kpi'"><i class="fa fa-sync-alt"></i> Reset KPI Filters</button>
                        </div>
                    </form>
                </div>
               
                <!-- Export Buttons for KPI -->
                <?php if ($canExport): ?>
                <div class="export-buttons">
                    <a href="?mode=kpi&export=excel&kpi_period=<?= htmlspecialchars($kpi_period) ?>&kpi_start_date=<?= htmlspecialchars($kpi_start_date) ?>&kpi_end_date=<?= htmlspecialchars($kpi_end_date) ?>&kpi_status_filter=<?= htmlspecialchars($kpi_status_filter) ?>&kpi_location_filter=<?= htmlspecialchars($kpi_location_filter) ?>" class="export-btn excel" onclick="return confirm('Export KPI detailed report to Excel?')">
                        <i class="fa fa-file-excel"></i> Export KPI Excel
                    </a>
                    <a href="?mode=kpi&export=csv&kpi_period=<?= htmlspecialchars($kpi_period) ?>&kpi_start_date=<?= htmlspecialchars($kpi_start_date) ?>&kpi_end_date=<?= htmlspecialchars($kpi_end_date) ?>&kpi_status_filter=<?= htmlspecialchars($kpi_status_filter) ?>&kpi_location_filter=<?= htmlspecialchars($kpi_location_filter) ?>" class="export-btn csv" onclick="return confirm('Export KPI detailed report to CSV?')">
                        <i class="fa fa-file-csv"></i> Export KPI CSV
                    </a>
                </div>
                <?php endif; ?>
               
                <?php if (isset($kpi_data['error'])): ?>
                    <div class="error-message">
                        <i class="fa fa-exclamation-triangle"></i> KPI Calculation Error: <?= htmlspecialchars($kpi_data['error']) ?>
                    </div>
                <?php else: ?>
                    <!-- KPI Summary Cards -->
                    <div class="kpi-summary-cards">
                        <div class="kpi-card">
                            <div class="value"><?= $kpi_data['total_candidates'] ?? 0 ?></div>
                            <div class="label">Total Candidates</div>
                        </div>
                        <div class="kpi-card">
                            <div class="value"><?= $kpi_data['new_candidates'] ?? 0 ?></div>
                            <div class="label">New Candidates (Period)</div>
                        </div>
                        <div class="kpi-card <?= ($kpi_data['growth_rate'] ?? 0) >= 0 ? 'growth' : 'decline' ?>">
                            <div class="value"><?= ($kpi_data['growth_rate'] ?? 0) ?>%</div>
                            <div class="label">Growth Rate (vs. Prev. Period)</div>
                        </div>
                        <div class="kpi-card">
                            <div class="value"><?= $kpi_data['active_candidates'] ?? 0 ?></div>
                            <div class="label">Active Candidates</div>
                        </div>
                        <div class="kpi-card">
                            <div class="value"><?= $kpi_data['pending_candidates'] ?? 0 ?></div>
                            <div class="label">Pending Candidates</div>
                        </div>
                        <div class="kpi-card">
                            <div class="value"><?= $kpi_data['inactive_candidates'] ?? 0 ?></div>
                            <div class="label">Inactive Candidates</div>
                        </div>
                        <div class="kpi-card">
                            <div class="value"><?= $kpi_data['archived_candidates'] ?? 0 ?></div>
                            <div class="label">Archived Candidates</div>
                        </div>
                    </div>
                   
                    <!-- KPI Detail Tables -->
                    <h5 class="mt-4 mb-3">Candidate Status Distribution (Current Period)</h5>
                    <div class="table-responsive mb-4">
                        <table class="kpi-detail-table">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Active</td><td><?= $kpi_data['active_candidates'] ?? 0 ?></td></tr>
                                <tr><td>Inactive</td><td><?= $kpi_data['inactive_candidates'] ?? 0 ?></td></tr>
                                <tr><td>Pending</td><td><?= $kpi_data['pending_candidates'] ?? 0 ?></td></tr>
                                <tr><td>Archived</td><td><?= $kpi_data['archived_candidates'] ?? 0 ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                   
                    <h5 class="mt-4 mb-3">Top Job Titles (Current Period)</h5>
                    <div class="table-responsive mb-4">
                        <table class="kpi-detail-table">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['job_title_stats'])): ?>
                                    <?php foreach (array_slice($kpi_data['job_title_stats'], 0, 10) as $job_stat): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($job_stat['JobTitle']) ?></td>
                                            <td><?= $job_stat['count'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2">No job title data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                   
                    <h5 class="mt-4 mb-3">Detailed Candidates (Filtered by KPI Criteria)</h5>
                    <div class="table-responsive">
                        <table class="candidates-table">
                            <thead>
                                <tr>
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Date Added</th>
                                    <th>Added By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['detailed_candidates'])): ?>
                                    <?php foreach ($kpi_data['detailed_candidates'] as $candidate): ?>
                                        <tr>
                                            <td>
                                                <?php $profile_pic_url = !empty($candidate['ProfileImage']) ? htmlspecialchars($candidate['ProfileImage']) : 'https://placehold.co/40x40/cccccc/333333?text=N/A'; ?>
                                                <img src="<?= $profile_pic_url ?>" alt="Profile" class="profile-pic" onerror="this.onerror=null;this.src='https://placehold.co/40x40/cccccc/333333?text=N/A';">
                                            </td>
                                            <td><?= htmlspecialchars($candidate['Name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($candidate['Email'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($candidate['JobTitle'] ?? 'N/A') ?></td>
                                            <td><span class="status-badge <?= strtolower(htmlspecialchars($candidate['Status'] ?? '')) ?>"><?= htmlspecialchars($candidate['Status'] ?? 'N/A') ?></span></td>
                                            <td><?= htmlspecialchars($candidate['City'] ?? 'N/A') ?> (<?= htmlspecialchars($candidate['Postcode'] ?? 'N/A') ?>)</td>
                                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($candidate['Date'] ?? ''))) ?></td>
                                            <td><?= htmlspecialchars($createdByMapping[$candidate['CreatedBy']] ?? 'Unknown') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="no-candidates-message">
                                            No candidates found matching your KPI criteria.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File input handling for mailshot attachments
            const fileInput = document.getElementById('mailshot_attachments');
            const filePreview = document.getElementById('filePreview');
           
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    filePreview.innerHTML = '';
                    Array.from(this.files).forEach((file, index) => {
                        const fileDiv = document.createElement('div');
                        fileDiv.className = 'file-item d-flex justify-content-between align-items-center mb-2 p-2 border rounded';
                        fileDiv.innerHTML = `
                            <span class="text-truncate">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeFile(${index})">
                                <i class="fa fa-times"></i>
                            </button>
                        `;
                        filePreview.appendChild(fileDiv);
                    });
                });
            }
           
            // Email template handling
            const mailshotTemplateDropdown = document.getElementById('mailshot_template');
            const mailshotSubjectField = document.getElementById('mailshot_subject');
            const mailshotMessageField = document.getElementById('mailshot_message');
           
            const emailTemplates = {
                'welcome': {
                    subject: 'Welcome to Nocturnal Recruitment Network!',
                    message: 'Dear [CANDIDATE_NAME],\\n\\nWelcome to Nocturnal Recruitment! We are excited to have you as part of our professional network.\\n\\nBest regards,\\n<?php echo addslashes(htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName)); ?>'
                },
                'job_opportunity': {
                    subject: 'Exciting Job Opportunity - Perfect Match for Your Profile!',
                    message: 'Hi [CANDIDATE_NAME],\\n\\nI hope this email finds you well. I wanted to reach out personally to share some exciting job opportunities.\\n\\nBest regards,\\n<?php echo addslashes(htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName)); ?>'
                },
                'followup': {
                    subject: 'Following Up on Your Career Opportunities',
                    message: 'Hello [CANDIDATE_NAME],\\n\\nI hope you are doing well. I wanted to follow up on our recent discussion about your career aspirations.\\n\\nBest regards,\\n<?php echo addslashes(htmlspecialchars($consultantMapping[$loggedInUserEmail] ?? $loggedInUserName)); ?>'
                }
            };
           
            if (mailshotTemplateDropdown) {
                mailshotTemplateDropdown.addEventListener('change', function() {
                    const selectedTemplateId = this.value;
                    if (selectedTemplateId && emailTemplates[selectedTemplateId]) {
                        const template = emailTemplates[selectedTemplateId];
                        if (mailshotSubjectField) mailshotSubjectField.value = template.subject;
                        if (mailshotMessageField) mailshotMessageField.value = template.message;
                    } else if (selectedTemplateId !== 'custom') {
                        if (mailshotSubjectField) mailshotSubjectField.value = '';
                        if (mailshotMessageField) mailshotMessageField.value = '';
                    }
                });
            }
           
            // Mailshot form handling
            const candidateMailshotForm = document.getElementById('candidateMailshotForm');
            const sendCandidateMailshotBtn = document.getElementById('sendCandidateMailshotBtn');
           
            if (candidateMailshotForm && sendCandidateMailshotBtn) {
                candidateMailshotForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                   
                    const selectedCandidates = document.querySelectorAll('input[name="selected_candidates[]"]:checked');
                    const subject = document.getElementById('mailshot_subject').value;
                    const message = document.getElementById('mailshot_message').value;
                   
                    if (selectedCandidates.length === 0) {
                        alert('Please select at least one candidate.');
                        return false;
                    }
                   
                    if (!subject.trim()) {
                        alert('Please enter a subject line.');
                        return false;
                    }
                   
                    if (!message.trim()) {
                        alert('Please enter an email message.');
                        return false;
                    }
                   
                    const selectedIds = Array.from(selectedCandidates).map(cb => cb.value);
                    document.getElementById('mailshotSelectedCandidates').value = JSON.stringify(selectedIds);
                   
                    sendCandidateMailshotBtn.disabled = true;
                    sendCandidateMailshotBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Sending Emails...';
                   
                    this.submit();
                });
            }
           
            updateSelectedCandidateCount();
        });
       
        // Function to toggle custom date inputs for KPI mode
        function toggleCustomDateInputs() {
            const period = document.getElementById('kpi_period');
            const startDate = document.getElementById('kpi_start_date');
            const endDate = document.getElementById('kpi_end_date');
           
            if (period && startDate && endDate) {
                if (period.value === 'custom') {
                    startDate.disabled = false;
                    endDate.disabled = false;
                } else {
                    startDate.disabled = true;
                    endDate.disabled = true;
                }
            }
        }
       
        // Function to toggle select all candidates
        window.toggleSelectAllCandidates = function() {
            const selectAllCheckbox = document.getElementById('selectAll') || document.getElementById('selectAllCandidates');
            const checkboxes = document.querySelectorAll('.candidate-checkbox, input[name="selected_candidates[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedCandidateCount();
        };
       
        // Function to update selected candidate count
        window.updateSelectedCandidateCount = function() {
            const checkedCheckboxes = document.querySelectorAll('.candidate-checkbox:checked, input[name="selected_candidates[]"]:checked');
            const selectedCandidateCountSpan = document.getElementById('selectedCandidateCount') || document.getElementById('mailshotCandidateCount');
            const candidateMailshotBtn = document.getElementById('candidateMailshotBtn');
           
            if (selectedCandidateCountSpan) {
                selectedCandidateCountSpan.textContent = checkedCheckboxes.length;
            }
           
            if (candidateMailshotBtn) {
                if (checkedCheckboxes.length > 0) {
                    candidateMailshotBtn.style.display = 'inline-block';
                } else {
                    candidateMailshotBtn.style.display = 'none';
                }
            }
           
            const mailshotCandidateList = document.getElementById('mailshotCandidateList');
            if (mailshotCandidateList) {
                mailshotCandidateList.innerHTML = '';
                checkedCheckboxes.forEach(checkbox => {
                    const candidateName = checkbox.dataset.name || 'Unknown';
                    const candidateItem = document.createElement('div');
                    candidateItem.className = 'badge bg-light text-dark me-1 mb-1';
                    candidateItem.textContent = candidateName;
                    mailshotCandidateList.appendChild(candidateItem);
                });
            }
        };
       
        // Function to remove file
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
    </script>
   
    <?php include "../../includes/js.php"; ?>
</body>
</html>
