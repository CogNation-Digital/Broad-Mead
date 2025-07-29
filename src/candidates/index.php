<?php
session_start(); 
error_reporting(E_ALL);
ini_set("display_errors", 1);
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
$USERID = $_COOKIE['USERID'] ?? null;
if ($USERID) {
    try {
        $stmt = $db_2->prepare("SELECT Email FROM users WHERE UserID = :userid");
        $stmt->bindParam(':userid', $USERID);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if ($user && filter_var($user->Email, FILTER_VALIDATE_EMAIL)) {
            $loggedInUserEmail = strtolower($user->Email); 
        } else {
            error_log("Logged-in user's email not found or invalid for UserID: " . $USERID);
            
            $loggedInUserEmail = "default_sender@yourdomain.com"; 
        }
    } catch (PDOException $e) {
        error_log("Error fetching user email: " . $e->getMessage());
        $loggedInUserEmail = "default_sender@yourdomain.com";
    }
} else {
    $loggedInUserEmail = "default_sender@yourdomain.com"; 
}



$allowedExportEmails = [
    'alex@nocturnalrecruitment.co.uk',
    'j.dowler@nocturnalrecruitment.co.uk',
    'chax@nocturnalrecruitment.co.uk'
];

$email_footer_html = '
<br><br>
<div style="font-family: Arial, sans-serif; font-size: 12px; color: #333333; line-height: 1.5; background-color: #1a1a1a; padding: 20px; color: #ffffff;">
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="https://i.ibb.co/L5w2t8J/nocturnal-recruitment-logo.png" alt="Nocturnal Recruitment Solutions" style="max-width: 200px; height: auto; display: block; margin: 0 auto;">
    </div>
    <div style="text-align: center; margin-bottom: 15px;">
        <p style="margin: 0; padding: 0; font-size: 12px; color: #ffffff;">
            <img src="https://i.ibb.co/h14251P/location-icon.png" alt="Location" style="vertical-align: middle; width: 14px; height: 14px; margin-right: 5px;">
            <span style="color: #6daffb;">Nocturnal Recruitment, Office 16, 321 High Road, RM6 6AX</span>
        </p>
        <p style="margin: 5px 0 0 0; padding: 0; font-size: 12px; color: #ffffff;">
            <img src="https://i.ibb.co/yY1h976/phone-icon.png" alt="Phone" style="vertical-align: middle; width: 14px; height: 14px; margin-right: 5px;">
            <span style="color: #6daffb;">0208 050 2708</span> &nbsp;
            <img src="https://i.ibb.co/N710N5M/mobile-icon.png" alt="Mobile" style="vertical-align: middle; width: 14px; height: 14px; margin-right: 5px;">
            <span style="color: #6daffb;">0755 357 0871</span> &nbsp;
            <img src="https://i.ibb.co/Jk1n6rK/email-icon.png" alt="Email" style="vertical-align: middle; width: 14px; height: 14px; margin-right: 5px;">
            <a href="mailto:chax@nocturnalrecruitment.co.uk" style="color: #6daffb; text-decoration: none;">chax@nocturnalrecruitment.co.uk</a>
        </p>
        <p style="margin: 5px 0 0 0; padding: 0; font-size: 12px; color: #ffffff;">
            <img src="https://i.ibb.co/M9d5NnL/website-icon.png" alt="Website" style="vertical-align: middle; width: 14px; height: 14px; margin-right: 5px;">
            <a href="https://www.nocturnalrecruitment.co.uk" target="_blank" style="color: #6daffb; text-decoration: none;">www.nocturnalrecruitment.co.uk</a>
        </p>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <!-- LinkedIn Icon - Already correct -->
        <a href="https://www.linkedin.com/company/nocturnalrecruitment" target="_blank" style="margin: 0 5px; display: inline-block;">
            <img src="https://i.ibb.co/zQJ6x0n/linkedin-icon.png" alt="LinkedIn" style="width: 30px; height: 30px;">
        </a>
        <!-- Instagram Icon - Already correct -->
        <a href="https://www.instagram.com/nocturnalrecruitment" target="_blank" style="margin: 0 5px; display: inline-block;">
            <img src="https://i.ibb.co/gST1V5g/instagram-icon.png" alt="Instagram" style="width: 30px; height: 30px;">
        </a>
        <!-- Facebook Icon - Already correct -->
        <a href="https://www.facebook.com/nocturnalrecruitment" target="_blank" style="margin: 0 5px; display: inline-block;">
            <img src="https://i.ibb.co/g3139V7/facebook-icon.png" alt="Facebook" style="width: 30px; height: 30px;">
        </a>
        <!-- REC Corporate Member - Fixed the duplicate URL issue -->
        <img src="https://i.ibb.co/mXp8qKJ/rec-corporate-member.png" alt="REC Corporate Member" style="vertical-align: middle; height: 30px; margin-left: 10px;">
    </div>

    <p style="text-align: center; margin: 0 0 10px 0; font-size: 12px; font-weight: bold; color: #ffffff;">
        Company Registration – 11817091
    </p>
    <p style="margin: 0; font-style: italic; color: #aaaaaa; text-align: center; font-size: 10px;">
        Disclaimer* This email is intended only for the use of the addressee named above and may be confidential or legally privileged. If you are not the addressee, you must not read it and must not use any information contained in nor copy it nor inform any person other than Nocturnal Recruitment or the addressee of its existence or contents. If you have received this email in error, please delete it and notify our team at <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #6daffb; text-decoration: none;">info@nocturnalrecruitment.co.uk</a>
    </p>
    <div style="text-align: center; margin-top: 15px; font-size: 11px; color: #888888;">
        BroadMead 3.0 &copy; 2025 - a product of
        <a href="https://www.cog-nation.com" target="_blank" style="color: #E1AD01; text-decoration: none; font-weight: bold;">
            CogNation Digital
        </a>.
    </div>
</div>';



$canExport = in_array($loggedInUserEmail, array_map('strtolower', $allowedExportEmails));

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'candidates';


$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'mailshot') {
    error_log("POST data received: " . print_r($_POST, true));
    
    if (empty($_POST['selected_candidates'])) {
        $_SESSION['error_message'] = "Please select at least one candidate.";
    } elseif (empty($_POST['subject'])) {
        $_SESSION['error_message'] = "Email subject is required.";
    } elseif (empty($_POST['template']) && empty($_POST['custom_template_content'])) {
        $_SESSION['error_message'] = "Please select an email template or provide custom content.";
    } else {
        $candidate_ids = $_POST['selected_candidates'];
        $subject = $_POST['subject'];
        $template_key = $_POST['template'];
        $custom_template_content = $_POST['custom_template_content'] ?? '';

        error_log("Processing mailshot for " . count($candidate_ids) . " candidates");

        $templates = [
            'job_alert' => [
                'subject' => 'New Job Opportunities Matching Your Profile',
                'body' => "Hello [Name],\n\nWe have new job opportunities that match your profile. Log in to your account to view them:\n\n[LoginLink]\n\nBest regards,\nThe Recruitment Team"
            ],
            'newsletter' => [
                'subject' => 'Our Latest Industry Insights',
                'body' => "Hello [Name],\n\nCheck out our latest newsletter with industry insights and job tips:\n\n[NewsletterLink]\n\nBest regards,\nThe Recruitment Team"
            ],
            'event_invitation' => [
                'subject' => 'Invitation to Recruitment Event',
                'body' => "Hello [Name],\n\nYou are invited to our upcoming recruitment event. Please RSVP here:\n\n[EventLink]\n\nBest regards,\nThe Recruitment Team"
            ],
            'follow_up' => [
                'subject' => 'Following Up on Your Application',
                'body' => "Hello [Name],\n\nFollowing up on your recent application. Any updates?\n\nBest regards,\nThe Recruitment Team"
            ],
            'welcome' => [
                'subject' => 'Welcome to Our Candidate Network',
                'body' => "Hello [Name],\n\nWelcome to our candidate database! We will contact you when we find a match.\n\nBest regards,\nThe Recruitment Team"
            ],
            'custom' => [ 
                'subject' => $subject,
                'body' => $custom_template_content
            ]
        ];

    
        if ($template_key === 'custom') {
            $template_details = [
                'subject' => $subject, 
                'body' => $custom_template_content 
            ];
        } else {
            
            $template_details = $templates[$template_key] ?? [
                'subject' => $subject, 
                'body' => "Hello [Name],\n\nThank you for being part of our network.\n\nBest regards,\nThe Recruitment Team"
            ];
        }

        $final_subject = empty($subject) ? $template_details['subject'] : $subject;
        $base_body = $template_details['body'];
    
        $from_email = $loggedInUserEmail;
        $from_name = "Recruitment Team"; 
        $success_count = 0;
        $error_count = 0;
        $temp_error_details = []; 
        $console_logs = []; 


        $from_email = "info@nocturnalrecruitment.co.uk";
        $from_name = "Recruitment Team";
        $smtp_host = 'smtp.nocturnalrecruitment.co.uk';
        $smtp_username = 'info@nocturnalrecruitment.co.uk';
        $smtp_password = '@Bludiamond0100';
        $smtp_port = 587;



       
        try {
            $test_mail = new PHPMailer(true);
            $test_mail->isSMTP();
            $test_mail->Host = $smtp_host;
            $test_mail->SMTPAuth = true;
            $test_mail->Username = $smtp_username;
            $test_mail->Password = $smtp_password;
            $test_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $test_mail->Port = $smtp_port;
            $test_mail->SMTPDebug = 0; 
            $test_mail->Debugoutput = function($str, $level) use (&$console_logs) {
                $console_logs[] = "SMTP DEBUG: " . trim($str);
            };
            if (!$test_mail->smtpConnect()) {
                throw new Exception("SMTP connection failed");
            }
            $test_mail->smtpClose();
            $console_logs[] = "SMTP connection test successful";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "SMTP Configuration Error: " . $e->getMessage();
            $console_logs[] = "ERROR: SMTP Configuration failed - " . $e->getMessage();
            error_log("SMTP Error: " . $e->getMessage());
            header("Location: ?mode=mailshot"); 
            exit;
        }

    
        if (!isset($_SESSION['error_message'])) { 
            foreach ($candidate_ids as $candidate_id) {
                $log_status = ''; 
                $log_error = ''; 
                try {
                    error_log("Processing candidate ID: " . $candidate_id);
                    
                    $stmt = $db_2->prepare("SELECT id, Name, Email, ProfileImage FROM _candidates WHERE id = ?");
                    $stmt->execute([$candidate_id]);
                    $candidate = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    if (!$candidate) {
                        
                        $stmt = $db_1->prepare("SELECT id, first_name as Name, email as Email FROM candidates WHERE id = ?");
                        $stmt->execute([$candidate_id]);
                        $candidate = $stmt->fetch(PDO::FETCH_OBJ);
                    }

                    
                    if ($candidate && filter_var($candidate->Email, FILTER_VALIDATE_EMAIL)) {
                        $to = $candidate->Email;
                        $name = $candidate->Name ?: 'Candidate'; 
                        error_log("Sending email to: " . $to . " (" . $name . ")");

                        
                        $personalized_body = str_replace(
                            ['[Name]', '[LoginLink]', '[NewsletterLink]', '[EventLink]'],
                            [$name, 'https://broad-mead.com/login', 'https://broad-mead.com/newsletter', 'https://broad-mead.com/events'],
                            $base_body
                        );

$personalized_body_with_footer = $personalized_body . $email_footer_html;
                        
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

                        
                        $mail->setFrom($from_email, $from_name);
                        
                        $mail->addReplyTo($loggedInUserEmail, $from_name);
                        $mail->addAddress($to, $name);
                        $mail->isHTML(true); 
                        $mail->Subject = $final_subject;
                      $mail->Body = $personalized_body_with_footer; 
                        $mail->AltBody = $personalized_body; 

                        if ($mail->send()) {
                            $success_count++;
                            $log_status = 'sent';
                            $log_error = '';
                            $console_logs[] = "SUCCESS: Email sent to {$to} (Candidate ID: {$candidate_id})";
                            error_log("SUCCESS: Email sent to {$to}");
                        } else {
                            $error_count++;
                            $log_status = 'failed';
                            $log_error = $mail->ErrorInfo;
                            $temp_error_details[] = "Failed to send to: $to - " . $mail->ErrorInfo;
                            $console_logs[] = "ERROR: Failed to send to {$to} (Candidate ID: {$candidate_id}) - " . $mail->ErrorInfo;
                            error_log("ERROR: Failed to send to {$to} - " . $mail->ErrorInfo);
                        }

                        
                        try {
                            $log_stmt = $db_2->prepare(
                                "INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at)
                                 VALUES (:candidate_id, :email, :subject, :template, :body, :status, :error, NOW())"
                            );
                            $log_stmt->execute([
                                ':candidate_id' => $candidate_id,
                                ':email' => $to,
                                ':subject' => $final_subject,
                                ':template' => $template_key,
                                ':body' => $personalized_body,
                                ':status' => $log_status,
                                ':error' => $log_error
                            ]);
                            error_log("Mailshot log saved for candidate ID: {$candidate_id}, Status: {$log_status}");
                        } catch (PDOException $e) {
                            error_log("DATABASE ERROR: Failed to log mailshot for candidate ID {$candidate_id}: " . $e->getMessage());
                           
                        }
                        $mail->clearAddresses(); 
                        $mail->clearAttachments(); 
                        usleep(100000); 
                    } else { 
                        $candidate_email = $candidate->Email ?? 'N/A';
                        $error_count++;
                        $log_status = 'failed';
                        $log_error = "Invalid email or candidate not found for ID: $candidate_id (Email: $candidate_email)";
                        $temp_error_details[] = $log_error;
                        $console_logs[] = "ERROR: Invalid email or candidate not found for ID {$candidate_id} (Email: {$candidate_email})";
                        error_log("ERROR: Invalid email or candidate not found for ID {$candidate_id}");

                        
                        try {
                            $log_stmt = $db_2->prepare(
                                "INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at)
                                 VALUES (:candidate_id, :email, :subject, :template, :body, :status, :error, NOW())"
                            );
                            $log_stmt->execute([
                                ':candidate_id' => $candidate_id,
                                ':email' => $candidate_email,
                                ':subject' => $final_subject,
                                ':template' => $template_key,
                                ':body' => $personalized_body ?? $base_body, 
                                ':status' => $log_status,
                                ':error' => $log_error
                            ]);
                            error_log("Mailshot log saved for candidate ID: {$candidate_id}, Status: {$log_status} (Invalid Candidate/Email)");
                        } catch (PDOException $e) {
                            error_log("DATABASE ERROR: Failed to log mailshot (invalid candidate/email) for ID {$candidate_id}: " . $e->getMessage());
                        }
                    }
                } catch (Exception $e) { 
                    $candidate_email = isset($candidate) && isset($candidate->Email) ? $candidate->Email : 'N/A';
                    $error_count++;
                    $log_status = 'failed';
                    $log_error = "Error processing candidate ID: $candidate_id - " . $e->getMessage();
                    $temp_error_details[] = $log_error;
                    $console_logs[] = "ERROR: Exception for candidate ID {$candidate_id} - " . $e->getMessage();
                    error_log("ERROR: Exception for candidate ID {$candidate_id} - " . $e->getMessage());
                    
                    try {
                        $log_stmt = $db_2->prepare(
                            "INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at)
                             VALUES (:candidate_id, :email, :subject, :template, :body, :status, :error, NOW())"
                        );
                        $log_stmt->execute([
                            ':candidate_id' => $candidate_id,
                            ':email' => $candidate_email,
                            ':subject' => $final_subject,
                            ':template' => $template_key,
                            ':body' => $personalized_body_with_footer ?? ($personalized_body ?? $base_body), 
                            ':status' => $log_status,
                            ':error' => $log_error
                        ]);
                        error_log("Mailshot log saved for candidate ID: {$candidate_id}, Status: {$log_status} (Exception)");
                    } catch (PDOException $e) {
                        error_log("DATABASE ERROR: Failed to log mailshot (exception) for ID {$candidate_id}: " . $e->getMessage());
                    }
                }
            } 

            if ($error_count === 0) {
                $_SESSION['success_message'] = "Mailshot processing completed. Successfully sent to $success_count candidates.";
            } else {
                $message = "Mailshot processing completed with issues: $success_count succeeded, $error_count failed.";
                if (!empty($temp_error_details)) {
                    $message .= "\n\nFirst 5 errors:\n" . implode("\n", array_slice($temp_error_details, 0, 5));
                    if (count($temp_error_details) > 5) {
                        $message .= "\n... plus " . (count($temp_error_details) - 5) . " more errors.";
                    }
                }
                $_SESSION['error_message'] = $message;
            }
        }
    }
    
    header("Location: ?mode=mailshot");
    exit;
}



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
    static $postcodeCache = []; // Cache to avoid repeated API calls for the same postcode
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
        'latitude' => 51.5 + (rand(-100, 100) / 1000), // Random latitude around London
        'longitude' => -0.1 + (rand(-100, 100) / 1000) // Random longitude around London
    ];

    $postcodeCache[$postcode] = $coordinates;
    return $coordinates;
}

function calculateDistanceBetweenPostcodes($postcode1, $postcode2) {
    try {
        $coords1 = getPostcodeCoordinates($postcode1);
        $coords2 = getPostcodeCoordinates($postcode2);
    } catch (Exception $e) {
        // If coordinates cannot be retrieved, treat distance as infinite or very large
        return PHP_FLOAT_MAX;
    }

    $earthRadius = 3959; // Earth's radius in miles

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

// --- Date Range Helper for KPI ---
function getDateRangeForPeriod($period) {
    $today = new DateTime();
    switch ($period) {
        case 'current_week':
            $start = clone $today;
            $start->modify('monday this week');
            $end = clone $start;
            $end->modify('+6 days'); // End of Sunday
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
        default: // Default to current week if an invalid period is passed
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

// --- Previous Period Range Helper for Growth Rate ---
function getPreviousPeriodRange($period, $currentRange) {
    $start = new DateTime($currentRange['start']);
    $end = new DateTime($currentRange['end']);
    $diff = $start->diff($end)->days + 1; // Calculate duration of the current period

    $prevStart = clone $start;
    $prevStart->modify("-{$diff} days"); // Move start date back by the duration
    $prevEnd = clone $end;
    $prevEnd->modify("-{$diff} days"); // Move end date back by the duration

    return [
        'start' => $prevStart->format('Y-m-d'),
        'end' => $prevEnd->format('Y-m-d')
    ];
}

// --- KPI Calculation Function (updated to include status and location filters) ---
function calculateKPIs($db, $period, $start_date = null, $end_date = null, $status_filter = 'all', $location_filter = '') {
    $kpis = [];
    try {
        // Determine the date range for the current KPI period
        if ($period === 'custom' && $start_date && $end_date) {
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            if ($start > $end) {
                throw new Exception("Start date cannot be after end date.");
            }
            // Optional: Add check for future start dates if you only want past data
            // if ($start > new DateTime()) { /* handle error or warning */ }
            $dateRange = [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d')
            ];
        } else {
            $dateRange = getDateRangeForPeriod($period);
        }

        $kpis['date_range'] = $dateRange; // Store the actual date range used

        // Build base WHERE clause and parameters for KPI queries
        // These filters apply to the 'detailed_candidates' table and 'new_candidates' count
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

        // --- Fetch Detailed Candidates for KPI Table ---
        $stmt_all_candidates = $db->prepare("SELECT id, Name, Email, JobTitle, Status, City, Postcode, Date, CreatedBy, ProfileImage FROM _candidates $base_where_clause ORDER BY Date DESC");
        $stmt_all_candidates->execute($base_params);
        $kpis['detailed_candidates'] = $stmt_all_candidates->fetchAll(PDO::FETCH_ASSOC);

        // --- Calculate Key Performance Indicators ---
        // Total candidates in the period (filtered by status/location)
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM _candidates $base_where_clause");
        $stmt->execute($base_params);
        $kpis['total_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // New candidates in the period (filtered by status/location)
        $stmt = $db->prepare("SELECT COUNT(*) as new_candidates FROM _candidates $base_where_clause");
        $stmt->execute($base_params);
        $kpis['new_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['new_candidates'];

        // Individual status counts (these counts are for the selected date range, but *not* filtered by the overall $status_filter or $location_filter,
        // as they represent the distribution of ALL candidates within the date range by status)
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

        $stmt = $db->prepare("SELECT COUNT(*) as previous_total FROM _candidates $prev_where_clause");
        $stmt->execute($prev_period_params);
        $previous_total = $stmt->fetch(PDO::FETCH_ASSOC)['previous_total'];

        if ($previous_total > 0) {
            $kpis['growth_rate'] = round((($kpis['new_candidates'] - $previous_total) / $previous_total) * 100, 2);
        } else {
            $kpis['growth_rate'] = 0; // Or handle as 'N/A' if previous count is zero
        }

    } catch (Exception $e) {
        $kpis['error'] = $e->getMessage();
    }

    return $kpis;
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
        /* General Body and Container Styles */
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

        .status-filter-buttons a:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .status-filter-buttons a.active {
            background: #28a745; /* Green for active status */
            color: white;
        }

        .status-filter-buttons a:not(.active) {
            background: #f5f5f5; /* Very light grey for inactive status filters */
            color: #555;
        }

        /* Export Buttons (Excel, CSV) */
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
            background-color: #21a366; /* Green for Excel */
            color: white;
        }

        .export-btn.csv {
            background-color: #6c757d; /* Grey for CSV */
            color: white;
        }

        /* Messages (Success/Error) */
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

        /* Filter Sections (General and KPI) */
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
                <a href="?mode=candidates"
                   class="<?= ($mode ?? '') === 'candidates' ? 'active' : '' ?>">
                    <i class="fa fa-users"></i> View Candidates
                </a>
                <a href="?mode=mailshot"
                   class="<?= ($mode ?? '') === 'mailshot' ? 'active' : '' ?>">
                    <i class="fa fa-paper-plane"></i> Create Mailshot
                </a>
                <a href="?mode=kpi"
                   class="<?= ($mode ?? '') === 'kpi' ? 'active' : '' ?>">
                    <i class="fa fa-chart-bar"></i> Weekly KPI Report
                </a>
            </div>

            <?php if (($mode ?? '') === 'candidates'): ?>
            <!-- Status Filter Buttons for Candidates View -->
            <div class="status-filter-buttons">
                <a href="?mode=candidates&status=all"
                   class="<?= ($status_filter ?? 'all') === 'all' ? 'active' : '' ?>">
                    <i class="fa fa-list-alt"></i> All
                </a>
                <a href="?mode=candidates&status=active"
                   class="<?= ($status_filter ?? '') === 'active' ? 'active' : '' ?>">
                    <i class="fa fa-check-circle"></i> Active
                </a>
                <a href="?mode=candidates&status=inactive"
                   class="<?= ($status_filter ?? '') === 'inactive' ? 'active' : '' ?>">
                    <i class="fa fa-times-circle"></i> Inactive
                </a>
                <a href="?mode=candidates&status=pending"
                   class="<?= ($status_filter ?? '') === 'pending' ? 'active' : '' ?>">
                    <i class="fa fa-hourglass-half"></i> Pending
                </a>
                <a href="?mode=candidates&status=archived"
                   class="<?= ($status_filter ?? '') === 'archived' ? 'active' : '' ?>">
                    <i class="fa fa-archive"></i> Archived
                </a>
            </div>

            <!-- Export Buttons for Candidates List -->
            <?php if ($canExport): // Only show export buttons if user is authorized ?>
            <div class="export-buttons">
                <a href="?mode=candidates&export=excel&status=<?= htmlspecialchars($status_filter) ?>&keyword=<?= htmlspecialchars($keyword_filter) ?>&location=<?= htmlspecialchars($location_filter) ?>&position=<?= htmlspecialchars($position_filter) ?>&center_postcode=<?= htmlspecialchars($center_postcode) ?>&distance_miles=<?= htmlspecialchars($distance_miles) ?>"
                   class="export-btn excel"
                   onclick="return confirm('Export filtered candidates to Excel?')">
                    <i class="fa fa-file-excel"></i> Export Excel
                </a>
                <a href="?mode=candidates&export=csv&status=<?= htmlspecialchars($status_filter) ?>&keyword=<?= htmlspecialchars($keyword_filter) ?>&location=<?= htmlspecialchars($location_filter) ?>&position=<?= htmlspecialchars($position_filter) ?>&center_postcode=<?= htmlspecialchars($center_postcode) ?>&distance_miles=<?= htmlspecialchars($distance_miles) ?>"
                   class="export-btn csv"
                   onclick="return confirm('Export filtered candidates to CSV?')">
                    <i class="fa fa-file-csv"></i> Export CSV
                </a>
            </div>
            <?php else: ?>
            <!-- Debug message for unauthorized users (remove in production) -->
            <div class="alert alert-info">
                <small>Export functionality is restricted to authorized users only. Current user: <?= htmlspecialchars($loggedInUserEmail) ?></small>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($mode === 'kpi'): ?>
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
            <?php endif; ?>

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

            <?php if ($mode !== 'kpi'): ?>
                <!-- Candidate Filtering Section (for 'candidates' and 'mailshot' modes) -->
                <div class="filter-section">
                    <h5>
                        <?php echo $mode === 'mailshot' ? 'Filter Candidates for Mailshot' : 'Candidate Filtering System'; ?>
                    </h5>
                    <form method="GET" action="">
                        <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode); ?>">
                        <div class="row filter-row">
                            <div class="col-md-3">
                                <div class="filter-label">Keywords (Name, Email, Job Title)</div>
                                <input type="text" name="keyword" class="filter-input"
                                    placeholder="Search by keywords..."
                                    value="<?php echo htmlspecialchars($keyword_filter); ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Location (City, Address, Postcode)</div>
                                <input type="text" name="location" class="filter-input"
                                    placeholder="Search by location..."
                                    value="<?php echo htmlspecialchars($location_filter); ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Position Title</div>
                                <input type="text" name="position" class="filter-input"
                                    placeholder="Search by position..."
                                    value="<?php echo htmlspecialchars($position_filter); ?>">
                            </div>
                            <?php if ($mode === 'candidates'): ?>
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
                            <?php endif; ?>
                            <div class="col-md-3">
                                <div class="filter-label">Center Postcode (for distance)</div>
                                <input type="text" name="center_postcode" class="filter-input"
                                    placeholder="e.g., SW1A 0AA"
                                    value="<?php echo htmlspecialchars($center_postcode); ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Distance (miles)</div>
                                <input type="number" name="distance_miles" class="filter-input"
                                    placeholder="e.g., 10"
                                    value="<?php echo htmlspecialchars($distance_miles); ?>">
                            </div>
                        </div>
                        <div class="filter-buttons">
                            <button type="submit"><i class="fa fa-filter"></i> Apply Filters</button>
                            <button type="reset" onclick="window.location.href='?mode=<?php echo htmlspecialchars($mode); ?>'"><i class="fa fa-sync-alt"></i> Reset Filters</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($mode === 'candidates' || $mode === 'mailshot'): ?>
                <!-- Candidate List Table Section -->
                <div class="card p-4">
                    <h4 class="mb-4">
                        <?php echo $mode === 'mailshot' ? 'Select Candidates for Mailshot' : 'Candidate List'; ?>
                    </h4>

                    <?php if ($mode === 'mailshot'): ?>
                        <!-- Mailshot Form -->
                        <form method="POST" action="?mode=mailshot" class="mailshot-form">
                            <div class="form-group">
                                <label for="subject">Email Subject:</label>
                                <input type="text" id="subject" name="subject" class="form-control filter-input" required placeholder="Enter email subject">
                            </div>
                            <div class="form-group">
                                <label for="template">Select Template:</label>
                                <select id="template" name="template" class="form-control filter-select" onchange="toggleCustomTemplate()">
                                    <option value="">-- Select --</option>
                                    <option value="job_alert">Job Alert</option>
                                    <option value="newsletter">Newsletter</option>
                                    <option value="event_invitation">Event Invitation</option>
                                    <option value="follow_up">Follow Up</option>
                                    <option value="welcome">Welcome</option>
                                    <option value="custom">Custom Template</option>
                                </select>
                            </div>
                            <div class="form-group" id="customTemplateContentDiv" style="display: none;">
                                <label for="custom_template_content">Custom Template Content:</label>
                                <textarea id="custom_template_content" name="custom_template_content" class="form-control mailshot-textarea" placeholder="Enter your custom email content here. Use [Name] for candidate's name, [LoginLink] for login URL, etc."></textarea>
                            </div>
                            <p class="text-muted mb-3 text-sm">
                                You can use placeholders like <code>[Name]</code>, <code>[LoginLink]</code>, <code>[NewsletterLink]</code>, <code>[EventLink]</code> in your email body.
                            </p>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="candidates-table">
                            <thead>
                                <tr>
                                    <?php if ($mode === 'mailshot'): ?>
                                        <th><input type="checkbox" id="selectAllCandidates"></th>
                                    <?php endif; ?>
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Date Added</th>
                                    <th>Added By</th>
                                    <?php if ($mode === 'candidates'): ?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($candidates_for_display)): ?>
                                    <?php foreach ($candidates_for_display as $candidate): ?>
                                        <tr>
                                            <?php if ($mode === 'mailshot'): ?>
                                                <td><input type="checkbox" name="selected_candidates[]" value="<?= htmlspecialchars($candidate['id']) ?>"></td>
                                            <?php endif; ?>
                                            <td>
                                                <?php
                                                // Use ProfilePictureURL if available, otherwise fallback to a generic placeholder
                                                $profile_pic_url = !empty($candidate['ProfileImage']) ? htmlspecialchars($candidate['ProfileImage']) : 'https://placehold.co/40x40/cccccc/333333?text=N/A';
                                                ?>
                                                <img src="<?= $profile_pic_url ?>" alt="Profile" class="profile-pic" onerror="this.onerror=null;this.src='https://placehold.co/40x40/cccccc/333333?text=N/A';">
                                            </td>
                                            <td><?= htmlspecialchars($candidate['Name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($candidate['Email'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($candidate['JobTitle'] ?? 'N/A') ?></td>
                                            <td><span class="status-badge <?= strtolower(htmlspecialchars($candidate['Status'] ?? '')) ?>"><?= htmlspecialchars($candidate['Status'] ?? 'N/A') ?></span></td>
                                            <td><?= htmlspecialchars($candidate['City'] ?? 'N/A') ?> (<?= htmlspecialchars($candidate['Postcode'] ?? 'N/A') ?>)</td>
                                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($candidate['Date'] ?? ''))) ?></td>
                                            <td><?= htmlspecialchars($createdByMapping[$candidate['CreatedBy']] ?? 'Unknown') ?></td>
                                            <?php if ($mode === 'candidates'): ?>
                                                <td>
                                                    <!-- Action buttons (placeholders for now) -->
                                                    <a href="#" class="btn btn-sm btn-info" title="View Details" style="background-color: #17a2b8; color: white; padding: 5px 8px; border-radius: 4px; text-decoration: none;"><i class="fa fa-eye"></i></a>
                                                    <a href="#" class="btn btn-sm btn-warning" title="Edit" style="background-color: #ffc107; color: white; padding: 5px 8px; border-radius: 4px; text-decoration: none;"><i class="fa fa-edit"></i></a>
                                                    <a href="#" class="btn btn-sm btn-danger" title="Delete" style="background-color: #dc3545; color: white; padding: 5px 8px; border-radius: 4px; text-decoration: none;"><i class="fa fa-trash"></i></a>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="<?= $mode === 'mailshot' ? '10' : '9' ?>" class="no-candidates-message">
                                            No candidates found matching your criteria.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($mode === 'mailshot'): ?>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn-send"><i class="fa fa-paper-plane"></i> Send Mailshot</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($mode === 'kpi'): ?>
                <!-- KPI Report Filtering Section -->
                <div class="kpi-filter-section">
                    <h5>Filter KPI Report</h5>
                    <form method="GET" action="">
                        <input type="hidden" name="mode" value="kpi">
                        <div class="row filter-row">
                            <div class="col-md-3">
                                <div class="filter-label">Reporting Period</div>
                                <select name="kpi_period" id="kpi_period" class="filter-select" onchange="toggleCustomDateInputs()">
                                    <option value="current_week" <?= $kpi_period === 'current_week' ? 'selected' : '' ?>>Current Week</option>
                                    <option value="last_week" <?= $kpi_period === 'last_week' ? 'selected' : '' ?>>Last Week</option>
                                    <option value="current_month" <?= $kpi_period === 'current_month' ? 'selected' : '' ?>>Current Month</option>
                                    <option value="last_month" <?= $kpi_period === 'last_month' ? 'selected' : '' ?>>Last Month</option>
                                    <option value="current_quarter" <?= $kpi_period === 'current_quarter' ? 'selected' : '' ?>>Current Quarter</option>
                                    <option value="current_year" <?= $kpi_period === 'current_year' ? 'selected' : '' ?>>Current Year</option>
                                    <option value="custom" <?= $kpi_period === 'custom' ? 'selected' : '' ?>>Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">Start Date</div>
                                <input type="date" name="kpi_start_date" id="kpi_start_date" class="filter-input"
                                    value="<?= htmlspecialchars($kpi_start_date) ?>"
                                    <?= $kpi_period !== 'custom' ? 'disabled' : '' ?>>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-label">End Date</div>
                                <input type="date" name="kpi_end_date" id="kpi_end_date" class="filter-input"
                                    value="<?= htmlspecialchars($kpi_end_date) ?>"
                                    <?= $kpi_period !== 'custom' ? 'disabled' : '' ?>>
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
                                <input type="text" name="kpi_location_filter" class="filter-input"
                                    placeholder="Filter by location..."
                                    value="<?= htmlspecialchars($kpi_location_filter) ?>">
                            </div>
                        </div>
                        <div class="filter-buttons">
                            <button type="submit"><i class="fa fa-filter"></i> Apply KPI Filters</button>
                            <button type="reset" onclick="window.location.href='?mode=kpi'"><i class="fa fa-sync-alt"></i> Reset KPI Filters</button>
                        </div>
                    </form>
                </div>

                <!-- Export Buttons for KPI Report -->
                <?php if ($canExport): // Only show export buttons if user is authorized ?>
                <div class="export-buttons">
                    <a href="?mode=kpi&export=excel&kpi_period=<?= htmlspecialchars($kpi_period) ?>&kpi_start_date=<?= htmlspecialchars($kpi_start_date) ?>&kpi_end_date=<?= htmlspecialchars($kpi_end_date) ?>&kpi_status_filter=<?= htmlspecialchars($kpi_status_filter) ?>&kpi_location_filter=<?= htmlspecialchars($kpi_location_filter) ?>"
                       class="export-btn excel"
                       onclick="return confirm('Export KPI detailed report to Excel?')">
                        <i class="fa fa-file-excel"></i> Export KPI Excel
                    </a>
                    <a href="?mode=kpi&export=csv&kpi_period=<?= htmlspecialchars($kpi_period) ?>&kpi_start_date=<?= htmlspecialchars($kpi_start_date) ?>&kpi_end_date=<?= htmlspecialchars($kpi_end_date) ?>&kpi_status_filter=<?= htmlspecialchars($kpi_status_filter) ?>&kpi_location_filter=<?= htmlspecialchars($kpi_location_filter) ?>"
                       class="export-btn csv"
                       onclick="return confirm('Export KPI detailed report to CSV?')">
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
                                    <?php foreach ($kpi_data['job_title_stats'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['JobTitle']) ?></td>
                                            <td><?= htmlspecialchars($item['count']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2">No job title data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-4 mb-3">Top Locations (Current Period)</h5>
                    <div class="table-responsive mb-4">
                        <table class="kpi-detail-table">
                            <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['city_stats'])): ?>
                                    <?php foreach ($kpi_data['city_stats'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['City']) ?></td>
                                            <td><?= htmlspecialchars($item['count']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2">No location data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-4 mb-3">Candidates Added By (Current Period)</h5>
                    <div class="table-responsive mb-4">
                        <table class="kpi-detail-table">
                            <thead>
                                <tr>
                                    <th>Recruiter</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['created_by_stats'])): ?>
                                    <?php foreach ($kpi_data['created_by_stats'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($createdByMapping[$item['CreatedBy']] ?? 'Unknown') ?></td>
                                            <td><?= htmlspecialchars($item['count']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2">No 'Created By' data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-4 mb-3">Daily Candidate Registration Trend (Current Period)</h5>
                    <div class="table-responsive mb-4">
                        <table class="kpi-detail-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>New Candidates</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['daily_trend'])): ?>
                                    <?php foreach ($kpi_data['daily_trend'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['date']) ?></td>
                                            <td><?= htmlspecialchars($item['count']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2">No daily trend data for this period.</td></tr>
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
                                                <?php
                                                $profile_pic_url = !empty($candidate['ProfileImage']) ? htmlspecialchars($candidate['ProfileImage']) : 'https://placehold.co/40x40/cccccc/333333?text=N/A';
                                                ?>
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
                                            No detailed candidate data found for the selected KPI filters and period.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // JavaScript for Mailshot: Toggle Custom Template Textarea visibility
        function toggleCustomTemplate() {
            const templateSelect = document.getElementById('template');
            const customContentDiv = document.getElementById('customTemplateContentDiv');
            if (templateSelect.value === 'custom') {
                customContentDiv.style.display = 'block';
            } else {
                customContentDiv.style.display = 'none';
                // Clear custom content if template is changed from custom
                document.getElementById('custom_template_content').value = '';
            }
        }

        // JavaScript for KPI Report: Toggle Custom Date Inputs enabled/disabled state
        function toggleCustomDateInputs() {
            const periodSelect = document.getElementById('kpi_period');
            const startDateInput = document.getElementById('kpi_start_date');
            const endDateInput = document.getElementById('kpi_end_date');
            if (periodSelect.value === 'custom') {
                startDateInput.removeAttribute('disabled');
                endDateInput.removeAttribute('disabled');
            } else {
                startDateInput.setAttribute('disabled', 'disabled');
                endDateInput.setAttribute('disabled', 'disabled');
                // Optionally clear values when disabled to avoid confusion
                // startDateInput.value = '';
                // endDateInput.value = '';
            }
        }

        // JavaScript for "Select All" checkbox in Mailshot mode
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Mailshot custom template state on page load
            toggleCustomTemplate();
            // Initialize KPI custom date inputs state on page load
            toggleCustomDateInputs();

            // Event listener for "Select All Candidates" checkbox
            const selectAllCheckbox = document.getElementById('selectAllCandidates');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('input[name="selected_candidates[]"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }
        });
    </script>

    <!-- Include other JS files from your includes folder if necessary -->
    <?php // include "../../includes/footer_scripts.php"; ?>
</body>
</html>
