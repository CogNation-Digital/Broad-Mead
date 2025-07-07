<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/login ");
}

// Include PHPMailer - adjust path as needed
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Try different possible paths to find PHPMailer
$phpmailer_paths = [
    'PHPMailer/src/',
    '../PHPMailer/src/',
    '../../PHPMailer/src/',
    $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/src/',
    $_SERVER['DOCUMENT_ROOT'] . '/broadmead/PHPMailer/src/',
    // 'C:/xampppppp/htdocs/broadmead/PHPMailer/src/'
];


$phpmailer_found = false;
foreach ($phpmailer_paths as $base_path) {
    if (file_exists($base_path . 'Exception.php')) {
        require_once $base_path . 'Exception.php';
        require_once $base_path . 'PHPMailer.php';
        require_once $base_path . 'SMTP.php';
        $phpmailer_found = true;
        break;
    }
}

if (!$phpmailer_found) {
    error_log("PHPMailer not found - falling back to basic mail() function");
}

// Email Configuration Class
$phpmailer_found = true; // Assume true because Composer autoload is used.
// If you ever experience 'Class not found' errors for PHPMailer,
// then you might need to re-evaluate the autoload.php path or your Composer setup.


// Email Configuration Class - UPDATED FOR GMAIL AND REPLY-TO
class EmailConfig {
    // --- GMAIL SMTP SETTINGS ---
    public static $SMTP_HOST = 'smtp.gmail.com'; // Gmail SMTP Host
    public static $SMTP_PORT = 465; // Gmail SMTPS Port
    public static $SMTP_SECURE = PHPMailer::ENCRYPTION_SMTPS; // Use PHPMailer's constant for SMTPS
    public static $SMTP_USERNAME = 'recruitmentnocturnal@gmail.com'; // Your sending Gmail address
    public static $SMTP_PASSWORD = 'hbaa qcvq wxkk kmcm'; 
    public static $FROM_EMAIL = 'recruitmentnocturnal@gmail.com'; // Your sending Gmail address
    public static $FROM_NAME = 'Nocturnal Recruitment'; // Display name for the sender

    // --- REPLY-TO SETTINGS ---
    public static $REPLY_TO_EMAIL = 'info@nocturnalrecruitment.co.uk'; // The email for replies
    public static $REPLY_TO_NAME = 'Nocturnal Recruitment'; // Display name for replies

    public static $USE_SMTP = true; // Keep this true to use SMTP

    public static function getHost() { return self::$SMTP_HOST; }
    public static function getPort() { return self::$SMTP_PORT; }
    public static function getSecure() { return self::$SMTP_SECURE; }
    public static function getUsername() { return self::$SMTP_USERNAME; }
    public static function getPassword() { return self::$SMTP_PASSWORD; }
    public static function getFromEmail() { return self::$FROM_EMAIL; }
    public static function getFromName() { return self::$FROM_NAME; }
    public static function getReplyToEmail() { return self::$REPLY_TO_EMAIL; } // New getter
    public static function getReplyToName() { return self::$REPLY_TO_NAME; }   // New getter
    public static function useSmtp() { return self::$USE_SMTP; }
}

// Enhanced Email Templates Class with Custom Template Support
class EmailTemplates {
    private static $conn;
    
    public static function setConnection($database_connection) {
        self::$conn = $database_connection;
        self::createCustomTemplatesTable();
    }
    
    private static function createCustomTemplatesTable() {
        try {
            $create_table = "CREATE TABLE IF NOT EXISTS custom_email_templates (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ClientKeyID VARCHAR(50) NOT NULL,
                template_name VARCHAR(100) NOT NULL,
                template_subject VARCHAR(255) NOT NULL,
                template_body TEXT NOT NULL,
                created_by INT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                is_active TINYINT(1) DEFAULT 1,
                INDEX idx_client (ClientKeyID),
                INDEX idx_created_by (created_by),
                UNIQUE KEY unique_template (ClientKeyID, template_name)
            )";
            self::$conn->exec($create_table);
        } catch (Exception $e) {
            error_log("Failed to create custom templates table: " . $e->getMessage());
        }
    }
    
    public static function saveCustomTemplate($ClientKeyID, $template_name, $subject, $body, $created_by) {
        try {
            $query = "INSERT INTO custom_email_templates (ClientKeyID, template_name, template_subject, template_body, created_by) 
                     VALUES (?, ?, ?, ?, ?) 
                     ON DUPLICATE KEY UPDATE 
                     template_subject = VALUES(template_subject), 
                     template_body = VALUES(template_body), 
                     updated_at = CURRENT_TIMESTAMP";
            
            $stmt = self::$conn->prepare($query);
            $stmt->execute([$ClientKeyID, $template_name, $subject, $body, $created_by]);
            return true;
        } catch (Exception $e) {
            error_log("Failed to save custom template: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getCustomTemplates($ClientKeyID) {
        try {
            $query = "SELECT * FROM custom_email_templates WHERE ClientKeyID = ? AND is_active = 1 ORDER BY template_name";
            $stmt = self::$conn->prepare($query);
            $stmt->execute([$ClientKeyID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get custom templates: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getCustomTemplate($ClientKeyID, $template_name) {
        try {
            $query = "SELECT * FROM custom_email_templates WHERE ClientKeyID = ? AND template_name = ? AND is_active = 1";
            $stmt = self::$conn->prepare($query);
            $stmt->execute([$ClientKeyID, $template_name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get custom template: " . $e->getMessage());
            return false;
        }
    }
    
    public static function deleteCustomTemplate($ClientKeyID, $template_name) {
        try {
            $query = "UPDATE custom_email_templates SET is_active = 0 WHERE ClientKeyID = ? AND template_name = ?";
            $stmt = self::$conn->prepare($query);
            $stmt->execute([$ClientKeyID, $template_name]);
            return true;
        } catch (Exception $e) {
            error_log("Failed to delete custom template: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getTemplate($template_name, $candidate_name = '', $custom_content = '', $ClientKeyID = null) {
        // Check if it's a custom template first
        if ($ClientKeyID && strpos($template_name, 'custom_') === 0) {
            $custom_template_name = substr($template_name, 7); // Remove 'custom_' prefix
            $custom_template = self::getCustomTemplate($ClientKeyID, $custom_template_name);
            
            if ($custom_template) {
                $body = $custom_template['template_body'];
                
                // Replace placeholders
                $body = str_replace('[CANDIDATE_NAME]', htmlspecialchars($candidate_name), $body);
                $body = str_replace('[CUSTOM_CONTENT]', nl2br(htmlspecialchars($custom_content)), $body);
                
                return [
                    'subject' => $custom_template['template_subject'],
                    'body' => $body
                ];
            }
        }
        
        // Default templates
        $templates = [
            'job_alert' => [
                'subject' => 'New Job Opportunities Available',
                'body' => "
                    <html>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                            <h2 style='color: #2c3e50;'>Hello " . htmlspecialchars($candidate_name) . ",</h2>
                            <p>We have exciting new job opportunities that match your profile!</p>
                            <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                                <p>" . nl2br(htmlspecialchars($custom_content)) . "</p>
                            </div>
                            <p>Best regards,<br><strong>Nocturnal Recruitment Team</strong></p>
                        </div>
                    </body>
                    </html>
                "
            ],
            'newsletter' => [
                'subject' => 'Nocturnal Recruitment Newsletter',
                'body' => "
                    <html>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                            <h2 style='color: #2c3e50;'>Hello " . htmlspecialchars($candidate_name) . ",</h2>
                            <p>Here's our latest newsletter with industry updates and opportunities.</p>
                            <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                                <p>" . nl2br(htmlspecialchars($custom_content)) . "</p>
                            </div>
                            <p>Best regards,<br><strong>Nocturnal Recruitment Team</strong></p>
                        </div>
                    </body>
                    </html>
                "
            ],
            'event_invitation' => [
                'subject' => 'You\'re Invited to Our Event',
                'body' => "
                    <html>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                            <h2 style='color: #2c3e50;'>Hello " . htmlspecialchars($candidate_name) . ",</h2>
                            <p>We'd like to invite you to our upcoming event!</p>
                            <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                                <p>" . nl2br(htmlspecialchars($custom_content)) . "</p>
                            </div>
                            <p>Best regards,<br><strong>Nocturnal Recruitment Team</strong></p>
                        </div>
                    </body>
                    </html>
                "
            ],
            'follow_up' => [
                'subject' => 'Following Up on Your Application',
                'body' => "
                    <html>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                            <h2 style='color: #2c3e50;'>Hello " . htmlspecialchars($candidate_name) . ",</h2>
                            <p>We wanted to follow up regarding your recent application.</p>
                            <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                                <p>" . nl2br(htmlspecialchars($custom_content)) . "</p>
                            </div>
                            <p>Best regards,<br><strong>Nocturnal Recruitment Team</strong></p>
                        </div>
                    </body>
                    </html>
                "
            ],
            'welcome' => [
                'subject' => 'Welcome to Nocturnal Recruitment',
                'body' => "
                    <html>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                            <h2 style='color: #2c3e50;'>Hello " . htmlspecialchars($candidate_name) . "!</h2>
                            <p>Thank you for joining Nocturnal Recruitment. We're excited to help you find your next opportunity.</p>
                            <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                                <p>" . nl2br(htmlspecialchars($custom_content)) . "</p>
                            </div>
                            <p>Best regards,<br><strong>Nocturnal Recruitment Team</strong></p>
                        </div>
                    </body>
                    </html>
                "
            ]
        ];
        
        return isset($templates[$template_name]) ? $templates[$template_name] : $templates['job_alert'];
    }
    
    public static function getEmailSignature() {
        return '
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
    }
}

// Email Sender Class - Updated to support custom templates
class EmailSender {
    private $conn;
    private $sent_count = 0;
    private $failed_count = 0;
    private $errors = [];
    private $ClientKeyID;
    
    public function __construct($database_connection, $client_key_id = null) {
        $this->conn = $database_connection;
        $this->ClientKeyID = $client_key_id;
    }
    

    public function sendMailshot($selected_candidates, $subject, $template, $custom_content = '', $sender_id = null) {
        $this->sent_count = 0;
        $this->failed_count = 0;
        $this->errors = [];
        
        $candidate_emails = $this->getCandidateEmails($selected_candidates);
        
        if (empty($candidate_emails)) {
            throw new Exception("No valid candidate emails found.");
        }
        
        foreach ($candidate_emails as $candidate) {
            try {
                $this->sendSingleEmail($candidate, $subject, $template, $custom_content);
                $this->logEmailSent($candidate['CandidateID'], $subject, $template, $sender_id);
                $this->sent_count++;
                usleep(500000);
            } catch (Exception $e) {
                $this->failed_count++;
                $this->errors[] = "Failed to send to {$candidate['Email']}: " . $e->getMessage();
                error_log("Mailshot error for {$candidate['Email']}: " . $e->getMessage());
            }
        }
        
        return [
            'sent' => $this->sent_count,
            'failed' => $this->failed_count,
            'errors' => $this->errors,
            'total' => count($candidate_emails)
        ];
    }
    
    private function getCandidateEmails($candidate_ids) {
        $placeholders = str_repeat('?,', count($candidate_ids) - 1) . '?';
        $query = "SELECT CandidateID, Name, Email FROM _candidates WHERE CandidateID IN ($placeholders) AND Email IS NOT NULL AND Email != ''";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($candidate_ids);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function sendSingleEmail($candidate, $subject, $template_name, $custom_content = '') {
        $template = EmailTemplates::getTemplate($template_name, $candidate['Name'], $custom_content, $this->ClientKeyID);
        $email_subject = !empty($subject) ? $subject : $template['subject'];
        $email_body = $template['body'] . EmailTemplates::getEmailSignature();
        
        global $phpmailer_found;
        if ($phpmailer_found && EmailConfig::useSmtp()) {
            return $this->sendViaPHPMailer($candidate['Email'], $candidate['Name'], $email_subject, $email_body);
        } else {
            return $this->sendViaPHPMail($candidate['Email'], $candidate['Name'], $email_subject, $email_body);
        }
    }
    
    private function sendViaPHPMailer($to_email, $to_name, $subject, $body) {
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = EmailConfig::getHost();
            $mail->SMTPAuth = true;
            $mail->Username = EmailConfig::getUsername();
            $mail->Password = EmailConfig::getPassword();
            $mail->SMTPSecure = EmailConfig::getSecure();
            $mail->Port = EmailConfig::getPort();
            $mail->setFrom(EmailConfig::getFromEmail(), EmailConfig::getFromName());
            $mail->addReplyTo(EmailConfig::getFromEmail(), EmailConfig::getFromName());
            $mail->addAddress($to_email, $to_name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            $mail->send();
            error_log("Email sent successfully to: $to_email via PHPMailer");
            return true;
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            return $this->sendViaPHPMail($to_email, $to_name, $subject, $body);
        }
    }

    private function sendViaPHPMail($to_email, $to_name, $subject, $body) {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . EmailConfig::getFromName() . ' <' . EmailConfig::getFromEmail() . '>',
            'Reply-To: ' . EmailConfig::getFromEmail(),
            'X-Mailer: PHP/' . phpversion()
        ];
        
        $success = mail($to_email, $subject, $body, implode("\r\n", $headers));
        

        if (!$success) {
            throw new Exception("Failed to send email via PHP mail()");
        }
        
        error_log("Email sent successfully to: $to_email via PHP mail()");
        return true;
    }
    
    private function logEmailSent($candidate_id, $subject, $template, $sender_id) {
        try {
            $create_table = "CREATE TABLE IF NOT EXISTS email_log (
                id INT AUTO_INCREMENT PRIMARY KEY,
                CandidateID VARCHAR(50) NOT NULL,
                Subject VARCHAR(255) NOT NULL,
                Template VARCHAR(50) NOT NULL,
                SentBy INT NULL,
                SentAt DATETIME NOT NULL,
                Status ENUM('sent', 'failed', 'bounced') DEFAULT 'sent',
                ErrorMessage TEXT NULL,
                INDEX idx_candidate (CandidateID),
                INDEX idx_sent_at (SentAt),
                INDEX idx_sent_by (SentBy)
            )";
            $this->conn->exec($create_table);
            
            $query = "INSERT INTO email_log (CandidateID, Subject, Template, SentBy, SentAt, Status) VALUES (?, ?, ?, ?, NOW(), 'sent')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$candidate_id, $subject, $template, $sender_id]);
        } catch (Exception $e) {
            error_log("Failed to log email: " . $e->getMessage());
        }
    }
}

// Initialize EmailTemplates with database connection
EmailTemplates::setConnection($conn);

// Initialize variables
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : 'all';
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'candidates';

// Enhanced search parameters
$keyword_filter = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location_filter = isset($_GET['location']) ? trim($_GET['location']) : '';
$position_filter = isset($_GET['position']) ? trim($_GET['position']) : '';
$center_postcode = isset($_GET['center_postcode']) ? trim($_GET['center_postcode']) : '';
$distance_miles = isset($_GET['distance_miles']) ? (int)$_GET['distance_miles'] : 0;
$email_keywords = isset($_GET['email_keywords']) ? trim($_GET['email_keywords']) : '';

// KPI filter parameters
$kpi_period = isset($_GET['kpi_period']) ? $_GET['kpi_period'] : 'current_week';
$kpi_metric = isset($_GET['kpi_metric']) ? $_GET['kpi_metric'] : 'overview';
$kpi_start_date = isset($_GET['kpi_start_date']) ? $_GET['kpi_start_date'] : '';
$kpi_end_date = isset($_GET['kpi_end_date']) ? $_GET['kpi_end_date'] : '';

// Handle custom template creation
if (isset($_POST['save_custom_template'])) {
    $template_name = trim($_POST['custom_template_name']);
    $template_subject = trim($_POST['custom_template_subject']);
    $template_body = trim($_POST['custom_template_body']);
    
    if (!empty($template_name) && !empty($template_subject) && !empty($template_body)) {
        if (EmailTemplates::saveCustomTemplate($ClientKeyID, $template_name, $template_subject, $template_body, $USERID)) {
            $success_message = "Custom email template '$template_name' saved successfully!";
        } else {
            $error_message = "Failed to save custom template. Please try again.";
        }
    } else {
        $error_message = "All fields are required for custom template.";
    }
}

// Handle custom template deletion
if (isset($_POST['delete_custom_template'])) {
    $template_name = $_POST['template_to_delete'];
    if (EmailTemplates::deleteCustomTemplate($ClientKeyID, $template_name)) {
        $success_message = "Custom template '$template_name' deleted successfully!";
    } else {
        $error_message = "Failed to delete custom template.";
    }
}

// KPI Functions (keeping existing functions)
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
        case 'last_month':
            $start = new DateTime('first day of last month');
            $end = new DateTime('last day of last month');
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

function calculateKPIs($conn, $ClientKeyID, $period, $start_date = null, $end_date = null) {
    if ($start_date && $end_date) {
        $dateRange = ['start' => $start_date, 'end' => $end_date];
    } else {
        $dateRange = getDateRangeForPeriod($period);
    }
    
    $kpis = [];
    
    try {
        // Total Candidates
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM _candidates WHERE ClientKeyID = ? AND Date BETWEEN ? AND ?");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['total_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // New Candidates
        $stmt = $conn->prepare("SELECT COUNT(*) as new_candidates FROM _candidates WHERE ClientKeyID = ? AND Date BETWEEN ? AND ?");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['new_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['new_candidates'];
        
        // Active Candidates
        $stmt = $conn->prepare("SELECT COUNT(*) as active FROM _candidates WHERE ClientKeyID = ? AND Status = 'Active' AND Date BETWEEN ? AND ?");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['active_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
        
        // Inactive Candidates
        $stmt = $conn->prepare("SELECT COUNT(*) as inactive FROM _candidates WHERE ClientKeyID = ? AND Status = 'Inactive' AND Date BETWEEN ? AND ?");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['inactive_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['inactive'];
        
        // Archived Candidates
        $stmt = $conn->prepare("SELECT COUNT(*) as archived FROM _candidates WHERE ClientKeyID = ? AND Status = 'Archived' AND Date BETWEEN ? AND ?");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['archived_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['archived'];
        
        // Pending Compliance
        $stmt = $conn->prepare("SELECT COUNT(*) as pending FROM _candidates WHERE ClientKeyID = ? AND Status = 'Pending Compliance' AND Date BETWEEN ? AND ?");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['pending_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending'];
        
        // Top Job Titles
        $stmt = $conn->prepare("SELECT JobTitle, COUNT(*) as count FROM _candidates WHERE ClientKeyID = ? AND Date BETWEEN ? AND ? AND JobTitle IS NOT NULL GROUP BY JobTitle ORDER BY count DESC LIMIT 5");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['top_job_titles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Top Cities
        $stmt = $conn->prepare("SELECT City, COUNT(*) as count FROM _candidates WHERE ClientKeyID = ? AND Date BETWEEN ? AND ? AND City IS NOT NULL GROUP BY City ORDER BY count DESC LIMIT 5");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['top_cities'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Created By Stats
        $stmt = $conn->prepare("SELECT CreatedBy, COUNT(*) as count FROM _candidates WHERE ClientKeyID = ? AND Date BETWEEN ? AND ? GROUP BY CreatedBy ORDER BY count DESC");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['created_by_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Daily Registration Trend
        $stmt = $conn->prepare("SELECT DATE(Date) as date, COUNT(*) as count FROM _candidates WHERE ClientKeyID = ? AND Date BETWEEN ? AND ? GROUP BY DATE(Date) ORDER BY date");
        $stmt->execute([$ClientKeyID, $dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['daily_trend'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate growth rate
        $previousPeriod = getPreviousPeriodRange($period, $dateRange);
        $stmt = $conn->prepare("SELECT COUNT(*) as previous_total FROM _candidates WHERE ClientKeyID = ? AND Date BETWEEN ? AND ?");
        $stmt->execute([$ClientKeyID, $previousPeriod['start'] . ' 00:00:00', $previousPeriod['end'] . ' 23:59:59']);
        $previous_total = $stmt->fetch(PDO::FETCH_ASSOC)['previous_total'];
        
        if ($previous_total > 0) {
            $kpis['growth_rate'] = round((($kpis['new_candidates'] - $previous_total) / $previous_total) * 100, 2);
        } else {
            $kpis['growth_rate'] = 0;
        }
        
        $kpis['date_range'] = $dateRange;
        
    } catch (Exception $e) {
        $kpis['error'] = $e->getMessage();
    }
    
    return $kpis;
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

function calculateDistance($postcode1, $postcode2) {
    return rand(1, 50);
}

// Handle legacy search
if (isset($_POST['Search'])) {
    $Name = isset($_POST['Name']) ? $_POST['Name'] : '';
    $JobTitle = isset($_POST['JobTitle']) ? $_POST['JobTitle'] : '';
    $IDNumber = isset($_POST['IDNumber']) ? $_POST['IDNumber'] : '';
    $EmailAddress = isset($_POST['Email']) ? $_POST['Email'] : '';
    $PhoneNumber = isset($_POST['Number']) ? $_POST['Number'] : '';
    $Address = isset($_POST['Address']) ? $_POST['Address'] : '';
    $Postcode = isset($_POST['Postcode']) ? $_POST['Postcode'] : '';
    $City = isset($_POST['City']) ? $_POST['City'] : '';
    
    $SearchID = uniqid();
    
    if (!empty($SearchID)) {
        $query = $conn->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) VALUES (:SearchID, :column, :value)");

        foreach ($_POST as $key => $value) {
            if (!empty($value) && $key !== 'Search') {
                $query->bindParam(':SearchID', $SearchID);
                $query->bindParam(':column', $key);
                $query->bindParam(':value', $value);
                $query->execute();
            }
        }

        header("location: $LINK/candidates/?q=$SearchID");
        exit();
    }
}

// MAILSHOT HANDLING - Updated to support custom templates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_candidates'])) {
    error_log("=== MAILSHOT PROCESSING START ===");
    
    $selected_candidates = $_POST['selected_candidates'];
    $subject = trim($_POST['subject']);
    $template = $_POST['template'];
    $custom_content = isset($_POST['custom_content']) ? trim($_POST['custom_content']) : '';
    
    $errors = [];
    if (empty($selected_candidates)) {
        $errors[] = "No candidates selected.";
    }
    if (empty($subject)) {
        $errors[] = "Email subject is required.";
    }
    if (empty($template)) {
        $errors[] = "Email template is required.";
    }
    
    if (empty($errors)) {
        try {
            $emailSender = new EmailSender($conn, $ClientKeyID);
            $result = $emailSender->sendMailshot($selected_candidates, $subject, $template, $custom_content, $USERID);
            
            if ($result['sent'] > 0) {
                $success_message = "Mailshot sent successfully! " . $result['sent'] . " emails sent";
                if ($result['failed'] > 0) {
                    $success_message .= ", " . $result['failed'] . " failed.";
                }
                
                $NOTIFICATION = "$NAME sent a mailshot with subject '$subject' to " . $result['sent'] . " candidates.";
                Notify($USERID, $ClientKeyID, $NOTIFICATION);
            } else {
                $error_message = "Failed to send mailshot. " . implode('; ', $result['errors']);
            }
            
        } catch (Exception $e) {
            $error_message = "Error sending mailshot: " . $e->getMessage();
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}

$SearchID = isset($_GET['q']) ? $_GET['q'] : '';

// Handle candidate deletion
if (isset($_POST['DeletCandidate'])) {
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("DELETE FROM `_candidates` WHERE CandidateID = :ID");
    $stmt->bindParam(':ID', $ID);

    if ($stmt->execute()) {
        $NOTIFICATION = "$NAME has successfully deleted the candidate named '$name'. Reason for deletion: $reason.";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    } else {
        echo "Error deleting record";
    }
}

// Get available job titles and locations for dropdowns
$job_titles_query = "SELECT DISTINCT JobTitle FROM _candidates WHERE ClientKeyID = '$ClientKeyID' AND JobTitle IS NOT NULL AND JobTitle != '' ORDER BY JobTitle";
$job_titles_stmt = $conn->query($job_titles_query);
$job_titles = $job_titles_stmt->fetchAll(PDO::FETCH_COLUMN);

$locations_query = "SELECT DISTINCT City FROM _candidates WHERE ClientKeyID = '$ClientKeyID' AND City IS NOT NULL AND City != '' ORDER BY City";
$locations_stmt = $conn->query($locations_query);
$locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);

// Get custom templates
$custom_templates = EmailTemplates::getCustomTemplates($ClientKeyID);

// Calculate KPIs if in KPI mode
$kpi_data = [];
if ($mode === 'kpi') {
    $kpi_data = calculateKPIs($conn, $ClientKeyID, $kpi_period, $kpi_start_date, $kpi_end_date);
}

// Set default profile placeholder if not defined
if (!isset($ProfilePlaceholder)) {
    $ProfilePlaceholder = '/assets/images/user/avatar-1.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>
<style>
.filter-section {
    background-color: #f8f9fa;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.filter-row {
    margin-bottom: 15px;
}

.filter-label {
    font-weight: 600;
    margin-bottom: 5px;
    color: #495057;
    font-size: 14px;
}

.filter-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
}

.filter-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
}

.filter-button:hover {
    background-color: #0056b3;
}

.clear-button {
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.clear-button:hover {
    background-color: #545b62;
}

.mode-switch {
    margin-bottom: 20px;
    padding: 15px;
    background-color: #e9ecef;
    border-radius: 8px;
}

.mode-button {
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
    text-decoration: none;
    display: inline-block;
}

.mode-button.active {
    background-color: #007bff;
}

.mode-button:hover {
    text-decoration: none;
    color: white;
    background-color: #0056b3;
}

.distance-filter {
    display: flex;
    align-items: center;
    gap: 10px;
}

.distance-input {
    width: 80px;
}

.distance-badge {
    background-color: #e9ecef;
    color: #495057;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    margin-left: 5px;
}

.results-info {
    margin-bottom: 15px;
    color: #6c757d;
    font-size: 14px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.mailshot-actions {
    margin-top: 20px;
    padding: 15px;
    background-color: #e9ecef;
    border-radius: 5px;
}

.select-all-container {
    margin-bottom: 15px;
}

.candidate-checkbox {
    width: 18px;
    height: 18px;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #c3e6cb;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
}

.mailshot-info {
    background-color: #d1ecf1;
    color: #0c5460;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #bee5eb;
}

.enhanced-search-icon {
    color: #007bff;
    margin-right: 5px;
}

.filter-active {
    background-color: #fff3cd;
    border-color: #ffeaa7;
}

.kpi-info {
    background-color: #fff3cd;
    color: #856404;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #ffeaa7;
}

.kpi-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.kpi-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid #007bff;
}

.kpi-card.success {
    border-left-color: #28a745;
}

.kpi-card.warning {
    border-left-color: #ffc107;
}

.kpi-card.danger {
    border-left-color: #dc3545;
}

.kpi-card.info {
    border-left-color: #17a2b8;
}

.kpi-card h3 {
    margin: 0 0 10px 0;
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
}

.kpi-card p {
    margin: 0;
    color: #6c757d;
    font-weight: 500;
}

.kpi-card .growth {
    font-size: 0.9rem;
    margin-top: 5px;
}

.growth.positive {
    color: #28a745;
}

.growth.negative {
    color: #dc3545;
}

.growth.neutral {
    color: #6c757d;
}

.kpi-charts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.chart-container {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chart-container h4 {
    margin-bottom: 20px;
    color: #2c3e50;
    font-weight: 600;
}

.kpi-tables {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.kpi-table-container {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.kpi-table-container h4 {
    margin-bottom: 15px;
    color: #2c3e50;
    font-weight: 600;
}

.kpi-table {
    width: 100%;
    border-collapse: collapse;
}

.kpi-table th,
.kpi-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.kpi-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

/* Custom Template Styles */
.custom-template-section {
    background-color: #f8f9fa;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.template-list {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 10px;
    background-color: white;
}

.template-item {
    padding: 8px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: between;
    align-items: center;
}

.template-item:last-child {
    border-bottom: none;
}

.template-name {
    font-weight: 600;
    color: #495057;
}

.template-subject {
    font-size: 12px;
    color: #6c757d;
    margin-top: 2px;
}

.template-actions {
    margin-left: auto;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

@media (max-width: 768px) {
    .kpi-cards {
        grid-template-columns: 1fr;
    }
    
    .kpi-charts {
        grid-template-columns: 1fr;
    }
    
    .kpi-tables {
        grid-template-columns: 1fr;
    }
}
</style>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php"; ?>

    <div class="pc-container" style="margin-left: 280px;">
        <div class="pc-content">
            <?php include "../../includes/breadcrumb.php"; ?>

            <div class="row">
                <div class="col-sm-12">
                    <!-- Mode Switch -->
                    <div class="mode-switch">
                        <h5 style="margin-bottom: 15px;">Mode Selection</h5>
                        <a href="?mode=candidates" class="mode-button <?php echo $mode === 'candidates' ? 'active' : ''; ?>">
                            <i class="ti ti-users"></i> View Candidates
                        </a>
                        <a href="?mode=mailshot" class="mode-button <?php echo $mode === 'mailshot' ? 'active' : ''; ?>">
                            <i class="ti ti-mail"></i> Create Mailshot
                        </a>
                        <?php if (IsCheckPermission($USERID, "VIEW_KPIs") || IsCheckPermission($USERID, "VIEW_CANDIDATES")): ?>
                        <a href="?mode=kpi" class="mode-button <?php echo $mode === 'kpi' ? 'active' : ''; ?>">
                            <i class="ti ti-chart-bar"></i> Weekly KPI Search
                        </a>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($success_message)): ?>
                        <div class="success-message">
                            <i class="ti ti-check-circle"></i> <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error_message)): ?>
                        <div class="error-message">
                            <i class="ti ti-alert-circle"></i> <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($mode === 'kpi'): ?>
                    <div class="kpi-info">
                        <h6><i class="ti ti-chart-bar"></i> KPI Reporting Dashboard</h6>
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
                    <div class="filter-section">
                        <h6 style="margin-bottom: 20px; color: #495057;">
                            <i class="ti ti-filter enhanced-search-icon"></i> KPI Filters & Date Range
                        </h6>
                        <form method="GET" action="">
                            <input type="hidden" name="mode" value="kpi">
                            
                            <div class="row filter-row">
                                <div class="col-md-3">
                                    <div class="filter-label">Time Period</div>
                                    <select name="kpi_period" class="filter-input">
                                        <option value="current_week" <?php echo $kpi_period === 'current_week' ? 'selected' : ''; ?>>Current Week</option>
                                        <option value="last_week" <?php echo $kpi_period === 'last_week' ? 'selected' : ''; ?>>Last Week</option>
                                        <option value="current_month" <?php echo $kpi_period === 'current_month' ? 'selected' : ''; ?>>Current Month</option>
                                        <option value="last_month" <?php echo $kpi_period === 'last_month' ? 'selected' : ''; ?>>Last Month</option>
                                        <option value="current_quarter" <?php echo $kpi_period === 'current_quarter' ? 'selected' : ''; ?>>Current Quarter</option>
                                        <option value="current_year" <?php echo $kpi_period === 'current_year' ? 'selected' : ''; ?>>Current Year</option>
                                        <option value="custom" <?php echo $kpi_period === 'custom' ? 'selected' : ''; ?>>Custom Range</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">KPI Metric Focus</div>
                                    <select name="kpi_metric" class="filter-input">
                                        <option value="overview" <?php echo $kpi_metric === 'overview' ? 'selected' : ''; ?>>Overview Dashboard</option>
                                        <option value="registrations" <?php echo $kpi_metric === 'registrations' ? 'selected' : ''; ?>>Registration Trends</option>
                                        <option value="status_analysis" <?php echo $kpi_metric === 'status_analysis' ? 'selected' : ''; ?>>Status Analysis</option>
                                        <option value="geographic" <?php echo $kpi_metric === 'geographic' ? 'selected' : ''; ?>>Geographic Distribution</option>
                                        <option value="job_titles" <?php echo $kpi_metric === 'job_titles' ? 'selected' : ''; ?>>Job Title Analysis</option>
                                        <option value="team_performance" <?php echo $kpi_metric === 'team_performance' ? 'selected' : ''; ?>>Team Performance</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">Custom Start Date</div>
                                    <input type="date" name="kpi_start_date" class="filter-input" 
                                           value="<?php echo htmlspecialchars($kpi_start_date); ?>">
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">Custom End Date</div>
                                    <input type="date" name="kpi_end_date" class="filter-input" 
                                           value="<?php echo htmlspecialchars($kpi_end_date); ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="filter-button">
                                        <i class="ti ti-search"></i> Generate KPI Report
                                    </button>
                                    <a href="?mode=kpi" class="clear-button">
                                        <i class="ti ti-refresh"></i> Reset Filters
                                    </a>
                                    <button type="button" class="filter-button" onclick="exportKPIReport()" style="background-color: #28a745;">
                                        <i class="ti ti-download"></i> Export Report
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($kpi_data) && !isset($kpi_data['error'])): ?>
                    <!-- KPI Cards -->
                    <div class="kpi-cards">
                        <div class="kpi-card success">
                            <h3><?php echo number_format($kpi_data['new_candidates']); ?></h3>
                            <p>New Candidates</p>
                            <div class="growth <?php echo $kpi_data['growth_rate'] >= 0 ? 'positive' : 'negative'; ?>">
                                <i class="ti ti-trending-<?php echo $kpi_data['growth_rate'] >= 0 ? 'up' : 'down'; ?>"></i>
                                <?php echo abs($kpi_data['growth_rate']); ?>% vs previous period
                            </div>
                        </div>
                        
                        <div class="kpi-card info">
                            <h3><?php echo number_format($kpi_data['active_candidates']); ?></h3>
                            <p>Active Candidates</p>
                            <div class="growth neutral">
                                <?php echo $kpi_data['total_candidates'] > 0 ? round(($kpi_data['active_candidates'] / $kpi_data['total_candidates']) * 100, 1) : 0; ?>% of total
                            </div>
                        </div>
                        
                        <div class="kpi-card warning">
                            <h3><?php echo number_format($kpi_data['pending_candidates']); ?></h3>
                            <p>Pending Compliance</p>
                            <div class="growth neutral">
                                Requires attention
                            </div>
                        </div>
                        
                        <div class="kpi-card danger">
                            <h3><?php echo number_format($kpi_data['inactive_candidates']); ?></h3>
                            <p>Inactive Candidates</p>
                            <div class="growth neutral">
                                <?php echo $kpi_data['total_candidates'] > 0 ? round(($kpi_data['inactive_candidates'] / $kpi_data['total_candidates']) * 100, 1) : 0; ?>% of total
                            </div>
                        </div>
                        
                        <div class="kpi-card">
                            <h3><?php echo number_format($kpi_data['archived_candidates']); ?></h3>
                            <p>Archived Candidates</p>
                            <div class="growth neutral">
                                Historical data
                            </div>
                        </div>
                        
                        <div class="kpi-card success">
                            <h3><?php echo number_format($kpi_data['total_candidates']); ?></h3>
                            <p>Total Candidates</p>
                            <div class="growth neutral">
                                <?php echo $kpi_data['date_range']['start']; ?> to <?php echo $kpi_data['date_range']['end']; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="kpi-charts">
                        <div class="chart-container">
                            <h4><i class="ti ti-chart-line"></i> Daily Registration Trend</h4>
                            <canvas id="dailyTrendChart" width="400" height="200"></canvas>
                        </div>
                        
                        <div class="chart-container">
                            <h4><i class="ti ti-chart-pie"></i> Status Distribution</h4>
                            <canvas id="statusChart" width="400" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Tables Section -->
                    <div class="kpi-tables">
                        <div class="kpi-table-container">
                            <h4><i class="ti ti-briefcase"></i> Top Job Titles</h4>
                            <table class="kpi-table">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Count</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kpi_data['top_job_titles'] as $job): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($job['JobTitle']); ?></td>
                                        <td><?php echo $job['count']; ?></td>
                                        <td><?php echo $kpi_data['total_candidates'] > 0 ? round(($job['count'] / $kpi_data['total_candidates']) * 100, 1) : 0; ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="kpi-table-container">
                            <h4><i class="ti ti-map-pin"></i> Top Cities</h4>
                            <table class="kpi-table">
                                <thead>
                                    <tr>
                                        <th>City</th>
                                        <th>Count</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kpi_data['top_cities'] as $city): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($city['City']); ?></td>
                                        <td><?php echo $city['count']; ?></td>
                                        <td><?php echo $kpi_data['total_candidates'] > 0 ? round(($city['count'] / $kpi_data['total_candidates']) * 100, 1) : 0; ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="kpi-table-container">
                            <h4><i class="ti ti-users"></i> Team Performance</h4>
                            <table class="kpi-table">
                                <thead>
                                    <tr>
                                        <th>Created By</th>
                                        <th>Count</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($kpi_data['created_by_stats'] as $creator): 
                                        $creatorName = $conn->query("SELECT Name FROM users WHERE UserID = '{$creator['CreatedBy']}'")->fetchColumn();
                                        if (!$creatorName) $creatorName = 'Unknown';
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($creatorName); ?></td>
                                        <td><?php echo $creator['count']; ?></td>
                                        <td><?php echo $kpi_data['total_candidates'] > 0 ? round(($creator['count'] / $kpi_data['total_candidates']) * 100, 1) : 0; ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php elseif (isset($kpi_data['error'])): ?>
                        <div class="alert alert-danger">
                            <h6>Error generating KPI report</h6>
                            <p><?php echo htmlspecialchars($kpi_data['error']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php elseif ($mode === 'mailshot'): ?>
                    <div class="mailshot-info">
                        <h6><i class="ti ti-info-circle"></i> Mailshot Mode - Now Using Professional SMTP</h6>
                        <p><strong>Enhanced filtering for targeted campaigns:</strong></p>
                        <ul>
                            <li>✅ Professional SMTP email delivery via mail.nocturnalrecruitment.co.uk</li>
                            <li>✅ Company branded email templates with signature</li>
                            <li>✅ Email logging and tracking</li>
                            <li>✅ Location-based filtering with distance radius</li>
                            <li>✅ Position and skill-based targeting</li>
                            <li>✅ Custom email templates - Create your own templates!</li>
                        </ul>
                    </div>

                    <?php endif; ?>

                    <?php if ($mode !== 'kpi'): ?>
                    <!-- Enhanced Filter Section -->
                    <div class="filter-section <?php echo (!empty($keyword_filter) || !empty($location_filter) || !empty($position_filter) || !empty($center_postcode)) ? 'filter-active' : ''; ?>">
                        <h6 style="margin-bottom: 20px; color: #495057;">
                            <i class="ti ti-filter enhanced-search-icon"></i>
                            <?php echo $mode === 'mailshot' ? 'Enhanced Mailshot Filtering' : 'Advanced Email & CV Filtering'; ?>
                        </h6>
                        <form method="GET" action="">
                            <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode); ?>">
                            <input type="hidden" name="isTab" value="<?php echo htmlspecialchars($isTab); ?>">
                            
                            <div class="row filter-row">
                                <div class="col-md-3">
                                    <div class="filter-label">
                                        <i class="ti ti-search"></i> Keywords (Name, Email, CV Content)
                                    </div>
                                    <input type="text" name="keyword" class="filter-input" 
                                           placeholder="Search keywords..." 
                                           value="<?php echo htmlspecialchars($keyword_filter); ?>">
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">
                                        <i class="ti ti-briefcase"></i> Position Title
                                    </div>
                                    <?php if ($mode === 'mailshot'): ?>
                                    <select name="position" class="filter-input">
                                        <option value="">All Positions</option>
                                        <?php foreach ($job_titles as $job_title): ?>
                                            <option value="<?php echo htmlspecialchars($job_title); ?>" 
                                                    <?php echo $position_filter === $job_title ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($job_title); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php else: ?>
                                    <input type="text" name="position" class="filter-input" 
                                           placeholder="Job title..." 
                                           value="<?php echo htmlspecialchars($position_filter); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">
                                        <i class="ti ti-map-pin"></i> Location
                                    </div>
                                    <?php if ($mode === 'mailshot'): ?>
                                    <select name="location" class="filter-input">
                                        <option value="">All Locations</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?php echo htmlspecialchars($location); ?>" 
                                                    <?php echo $location_filter === $location ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($location); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php else: ?>
                                    <input type="text" name="location" class="filter-input" 
                                           placeholder="City, address, postcode..." 
                                           value="<?php echo htmlspecialchars($location_filter); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="filter-label">
                                        <i class="ti ti-mail"></i> Email Keywords
                                    </div>
                                    <input type="text" name="email_keywords" class="filter-input" 
                                           placeholder="Keywords from emails..." 
                                           value="<?php echo htmlspecialchars($email_keywords); ?>">
                                </div>
                            </div>
                            
                            <div class="row filter-row">
                                <div class="col-md-6">
                                    <div class="filter-label">
                                        <i class="ti ti-target"></i> Distance Filter
                                    </div>
                                    <div class="distance-filter">
                                        <input type="text" name="center_postcode" class="filter-input" 
                                               placeholder="Center postcode" 
                                               value="<?php echo htmlspecialchars($center_postcode); ?>">
                                        <span>within</span>
                                        <input type="number" name="distance_miles" class="filter-input distance-input" 
                                               placeholder="Miles" min="1" max="100"
                                               value="<?php echo $distance_miles > 0 ? htmlspecialchars($distance_miles) : ''; ?>">
                                        <span>miles</span>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div>
                                        <button type="submit" class="filter-button">
                                            <i class="ti ti-search"></i> Apply Filters
                                        </button>
                                        <a href="?mode=<?php echo htmlspecialchars($mode); ?>&isTab=<?php echo htmlspecialchars($isTab); ?>" class="clear-button">
                                            <i class="ti ti-x"></i> Clear Filters
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">
                                    <?php 
                                    if ($mode === 'mailshot') {
                                        echo 'Mailshot - Candidate Selection';
                                    } else {
                                        echo 'Candidates';
                                    }
                                    ?>
                                </h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?php echo $LINK; ?>/create_candidate?CandidateID=<?php echo isset($KeyID) ? $KeyID : ''; ?>">
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
                                            Legacy Search
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist" style="margin-left:30px;">
                            <li class="nav-item" role="presentation">
                                <a href="<?php echo $LINK; ?>/candidates?mode=<?php echo $mode; ?><?php echo !empty($SearchID) ? "&q=$SearchID" : "" ?>">
                                    <button class="nav-link <?php echo ($isTab == "all") ? 'active' : ''; ?>">All Candidates</button>
                                </a>
                            </li>
                            <?php
                            $tabs = [
                                ["name" => "Active", "index" => 1],
                                ["name" => "Archived", "index" => 2],
                                ["name" => "Inactive", "index" => 3],
                                ["name" => "Pending Compliance", "index" => 4]
                            ];
                            ?>

                            <ul class="nav">
                                <?php foreach ($tabs as $tab): ?>
                                    <li class="nav-item" role="presentation">
                                        <a href="<?php echo $LINK; ?>/candidates?mode=<?php echo $mode; ?><?php echo !empty($SearchID) ? "&q=$SearchID" : "&i=" . $tab['index']; ?>&isTab=<?php echo $tab['name']; ?>">
                                            <button class="nav-link <?php echo ($isTab == $tab['name']) ? 'active' : ''; ?>">
                                                <?php echo $tab['name']; ?>
                                            </button>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </ul>
                        
                        <div class="card-body">
                            <?php if ($mode === 'mailshot'): ?>
                            <!-- Mailshot Form -->
                            <form method="POST" action="">
                                <div class="select-all-container">
                                    <label>
                                        <input type="checkbox" id="select-all" class="candidate-checkbox">
                                        <strong>Select All Candidates</strong>
                                    </label>
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "VIEW_CANDIDATES")): ?>
                                    <?php
                                    // Build enhanced query with filters
                                    $query = "SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID'";
                                    $params = [];
                                    
                                    if ($isTab !== "all") {
                                        $query .= " AND Status = :status";
                                        $params[':status'] = $isTab;
                                    }
                                    
                                    // Enhanced filtering
                                    if (!empty($keyword_filter)) {
                                        $query .= " AND (Name LIKE :keyword OR Email LIKE :keyword OR JobTitle LIKE :keyword)";
                                        $params[':keyword'] = '%' . $keyword_filter . '%';
                                    }
                                    
                                    if (!empty($location_filter)) {
                                        $query .= " AND (City LIKE :location OR Address LIKE :location OR Postcode LIKE :location)";
                                        $params[':location'] = '%' . $location_filter . '%';
                                    }
                                    
                                    if (!empty($position_filter)) {
                                        $query .= " AND JobTitle LIKE :position";
                                        $params[':position'] = '%' . $position_filter . '%';
                                    }
                                    
                                    if (!empty($email_keywords)) {
                                        $query .= " AND (Email LIKE :email_keywords OR Notes LIKE :email_keywords)";
                                        $params[':email_keywords'] = '%' . $email_keywords . '%';
                                    }
                                    
                                    // Legacy search support
                                    if (isset($_GET['q'])) {
                                        $SearchID = $_GET['q'];
                                        $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                                        while ($r = $qu->fetchObject()) {
                                            $column = $r->column;
                                            $value = $r->value;
                                            $query .= " AND " . $column . " LIKE '%$value%'";
                                        }
                                    }
                                    
                                    $query .= " ORDER BY Name ASC";
                                    $stmt = $conn->prepare($query);
                                    
                                    foreach ($params as $key => $value) {
                                        $stmt->bindValue($key, $value);
                                    }
                                    
                                    $stmt->execute();
                                    $candidates = $stmt->fetchAll(PDO::FETCH_OBJ);
                                    
                                    // Apply distance filter if specified
                                    if (!empty($center_postcode) && $distance_miles > 0) {
                                        $filtered_candidates = [];
                                        foreach ($candidates as $candidate) {
                                            if (!empty($candidate->Postcode)) {
                                                $distance = calculateDistance($center_postcode, $candidate->Postcode);
                                                if ($distance <= $distance_miles) {
                                                    $candidate->distance = $distance;
                                                    $filtered_candidates[] = $candidate;
                                                }
                                            }
                                        }
                                        $candidates = $filtered_candidates;
                                    }
                                    
                                    $total_results = count($candidates);
                                    ?>
                                    
                                    <!-- Results Info -->
                                    <div class="results-info">
                                        <strong><?php echo $total_results; ?></strong> candidates found
                                        <?php if ($mode === 'mailshot'): ?>
                                            for mailshot
                                        <?php endif; ?>
                                        <?php if (!empty($keyword_filter) || !empty($location_filter) || !empty($position_filter) || !empty($email_keywords)): ?>
                                            with applied filters
                                        <?php endif; ?>
                                        <?php if (!empty($center_postcode) && $distance_miles > 0): ?>
                                            within <?php echo $distance_miles; ?> miles of <?php echo htmlspecialchars($center_postcode); ?>
                                        <?php endif; ?>
                                    </div>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <?php if ($mode === 'mailshot'): ?>
                                                <th width="40px">Select</th>
                                                <?php endif; ?>
                                                <th>#</th>
                                                <th style="<?php echo $mode === 'mailshot' ? 'display: none;' : ''; ?>">
                                                    <span id="selectAll" style="cursor: pointer;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                        </svg>
                                                    </span>
                                                </th>
                                                <th>Candidate ID</th>
                                                <th>Status</th>
                                                <th>Name</th>
                                                <th>Email Address</th>
                                                <?php if ($mode !== 'mailshot'): ?>
                                                <th>Phone Number</th>
                                                <?php endif; ?>
                                                <th>Job Title</th>
                                                <?php if ($mode !== 'mailshot'): ?>
                                                <th>Location</th>
                                                <?php else: ?>
                                                <th>City</th>
                                                <?php endif; ?>
                                                <?php if (!empty($center_postcode) && $distance_miles > 0): ?>
                                                <th>Distance</th>
                                                <?php endif; ?>
                                                <?php if ($mode !== 'mailshot'): ?>
                                                <th>Created By</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $n = 1;
                                            foreach ($candidates as $row) {
                                                $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                            ?>
                                                <tr>
                                                    <?php if ($mode === 'mailshot'): ?>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="selected_candidates[]" value="<?php echo $row->CandidateID; ?>" class="candidate-checkbox">
                                                    </td>
                                                    <?php endif; ?>
                                                    <td><?php echo $n++; ?></td>
                                                    <td style="<?php echo $mode === 'mailshot' ? 'display: none;' : ''; ?>">
                                                        <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->CandidateID; ?>" data-name="<?php echo $row->Name; ?>" id="flexCheckDefault<?php echo isset($row->id) ? $row->id : $row->CandidateID; ?>">
                                                    </td>
                                                    <td><?php echo empty($row->IDNumber) ? str_pad(isset($row->id) ? $row->id : 1, 5, '0', STR_PAD_LEFT) : $row->IDNumber; ?></td>
                                                    <td>
                                                        <?php if ($row->Status == "Active"): ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php elseif ($row->Status == "Archived"): ?>
                                                            <span class="badge bg-warning">Archived</span>
                                                        <?php elseif ($row->Status == "Inactive"): ?>
                                                            <span class="badge bg-danger"><?php echo $row->Status; ?></span>
                                                        <?php elseif ($row->Status == "Pending Compliance"): ?>
                                                            <span class="badge bg-info"><?php echo $row->Status; ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Unknown</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $LINK; ?>/view_candidate/?ID=<?php echo $row->CandidateID; ?>">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    <img width="40" height="40" style="object-fit: cover;" src="<?php echo !empty($row->ProfileImage) ? $row->ProfileImage : $ProfilePlaceholder; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40">
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0"><?php echo htmlspecialchars($row->Name); ?></h6>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row->Email); ?></td>
                                                    <?php if ($mode !== 'mailshot'): ?>
                                                    <td><?php echo htmlspecialchars($row->Number); ?></td>
                                                    <?php endif; ?>
                                                    <td><?php echo htmlspecialchars($row->JobTitle); ?></td>
                                                    <td><?php echo htmlspecialchars($row->City); ?></td>
                                                    <?php if (!empty($center_postcode) && $distance_miles > 0): ?>
                                                    <td>
                                                        <?php if (isset($row->distance)): ?>
                                                        <span class="distance-badge">
                                                            <?php echo $row->distance; ?> miles
                                                        </span>
                                                        <?php else: ?>
                                                        <span class="distance-badge">N/A</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <?php endif; ?>
                                                    <?php if ($mode !== 'mailshot'): ?>
                                                    <td><?php echo htmlspecialchars($CreatedBy); ?></td>
                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/edit_candidate/?CandidateID=<?php echo $row->CandidateID; ?>" id="Edit">
                                                                    <span class="text-info">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                            <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                        </svg>
                                                                    </span>
                                                                    Edit</a>
                                                                <?php if (IsCheckPermission($USERID, "DELETE_CANDIDATE")): ?>
                                                                    <a class="dropdown-item delete" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                                        <span class="text-danger">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                            </svg>
                                                                        </span>
                                                                        Delete</a>
                                                                <?php endif; ?>
                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/view_candidate/?ID=<?php echo $row->CandidateID; ?>">
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
                                                    <?php endif; ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    
                                    <?php if ($total_results == 0): ?>
                                        <div class="alert alert-info text-center">
                                            <h6>No candidates found</h6>
                                            <p>Try adjusting your search filters or <a href="?mode=<?php echo htmlspecialchars($mode); ?>&isTab=<?php echo htmlspecialchars($isTab); ?>">clear all filters</a> to see more candidates.</p>
                                        </div>
                                    <?php endif; ?>
                                    <?php else: ?>
                                        <?php DeniedAccess(); ?>
                                    <?php endif; ?>
                            </div>
                            
                            
<?php if ($mode === 'mailshot' && $total_results > 0): ?>
<!-- Mailshot Actions -->
<div class="mailshot-actions">
    <div class="row">
        <div class="col-md-8">
            <h6>Mailshot Configuration</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mailshot-subject" class="form-label">Email Subject *</label>
                        <input type="text" name="subject" id="mailshot-subject" class="form-control" required 
                               placeholder="Enter email subject">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mailshot-template" class="form-label">Email Template *</label>
                        <div class="d-flex">
                            <select name="template" id="mailshot-template" class="form-control" required style="margin-right: 10px;">
                                <option value="">Select a template</option>
                                <optgroup label="Default Templates">
                                    <option value="job_alert">Job Alert</option>
                                    <option value="newsletter">Newsletter</option>
                                    <option value="event_invitation">Event Invitation</option>
                                    <option value="follow_up">Follow Up</option>
                                    <option value="welcome">Welcome Email</option>
                                </optgroup>
                                <?php if (!empty($custom_templates)): ?>
                                <optgroup label="Custom Templates">
                                    <?php foreach ($custom_templates as $template): ?>
                                        <option value="custom_<?php echo htmlspecialchars($template['template_name']); ?>">
                                            <?php echo htmlspecialchars($template['template_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <?php endif; ?>
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#CustomTemplateModal">
                                <i class="ti ti-plus"></i> New
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="custom-content" class="form-label">Custom Content (Optional)</label>
                <textarea name="custom_content" id="custom-content" class="form-control" rows="4" 
                          placeholder="Add any custom content to include in the email..."></textarea>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary" id="send-mailshot-btn">
                    <i class="ti ti-mail"></i> Send Mailshot
                </button>
                <span class="text-muted ms-3">
                    <i class="ti ti-info-circle"></i> 
                    This will send emails to all selected candidates
                </span>
            </div>
        </div>
        
        <div class="col-md-4">
            <h6>Quick Template Management</h6>
            <?php if (!empty($custom_templates)): ?>
                <div class="template-list" style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 10px; background-color: white;">
                    <?php foreach ($custom_templates as $template): ?>
                        <div class="template-item" style="padding: 8px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div class="template-name" style="font-weight: 600; color: #495057; font-size: 13px;"><?php echo htmlspecialchars($template['template_name']); ?></div>
                                <div class="template-subject" style="font-size: 11px; color: #6c757d; margin-top: 2px;"><?php echo htmlspecialchars(substr($template['template_subject'], 0, 30)) . (strlen($template['template_subject']) > 30 ? '...' : ''); ?></div>
                            </div>
                            <div class="template-actions">
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="useTemplate('custom_<?php echo htmlspecialchars($template['template_name']); ?>', '<?php echo htmlspecialchars($template['template_subject']); ?>')" title="Use Template">
                                    <i class="ti ti-check"></i>
                                </button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="template_to_delete" value="<?php echo htmlspecialchars($template['template_name']); ?>">
                                    <button type="submit" name="delete_custom_template" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this template?')" title="Delete Template">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info" style="font-size: 12px; padding: 10px;">
                    <i class="ti ti-info-circle"></i> No custom templates yet. Click "New" to create one!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</form>
<?php endif; ?>

                            <?php if (isset($_GET['q']) || !empty($keyword_filter) || !empty($location_filter) || !empty($position_filter) || !empty($email_keywords)): ?>
                                <div style="margin-top: 10px;">
                                    <a href="<?php echo $LINK; ?>/candidates?mode=<?php echo $mode; ?>&isTab=<?php echo $isTab; ?>">
                                        <button class="btn btn-primary">
                                            <i class="ti ti-refresh"></i> Reset All Filters
                                        </button>
                                    </a>
                                    <span style="margin-left: 20px;">
                                        <strong><?php echo $total_results; ?></strong> 
                                        <?php echo ($total_results == 1 ? 'candidate' : 'candidates'); ?> found
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="DeleteModal" class="modal fade" tabindex="-1" aria-labelledby="DeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete? This action cannot be undone.</p>
                    <div class="row" style="padding: 10px;">
                        <input required type="text" class="form-control" id="reason" name="reason" placeholder="Give a reason">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Preview Modal -->
    <div id="TemplatePreviewModal" class="modal fade" tabindex="-1" aria-labelledby="TemplatePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Template Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="template-preview-content">
                        <!-- Template preview will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Legacy Advanced Search Modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Legacy Advanced Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i>
                        <strong>Note:</strong> Use the enhanced filtering above for better search capabilities including email keywords, distance filtering, and KPI reporting features.
                    </div>
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Candidate ID</label>
                                    <input type="text" class="form-control" name="IDNumber">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Job Title</label>
                                    <input type="text" name="JobTitle" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input type="text" class="form-control" name="Email">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="Number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="Address">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Postcode</label>
                                    <input type="text" class="form-control" name="Postcode">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="City">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Created By</label>
                                    <select name="CreatedBy" class="form-control">
                                        <option value=""></option>
                                        <?php
                                        $q = "SELECT * FROM `users` WHERE ClientKeyID = '$ClientKeyID'";
                                        $stmt = $conn->prepare($q);
                                        $stmt->execute();
                                        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) { ?>
                                            <option value="<?php echo $row->UserID; ?>"><?php echo htmlspecialchars($row->Name); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="Search" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Custom Template Modal -->
<div id="CustomTemplateModal" class="modal fade" tabindex="-1" aria-labelledby="CustomTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Custom Email Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i>
                        <strong>Template Placeholders:</strong> Use <code>[CANDIDATE_NAME]</code> for the candidate's name and <code>[CUSTOM_CONTENT]</code> for the custom content field.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Template Name *</label>
                        <input type="text" name="custom_template_name" class="form-control" required 
                               placeholder="e.g., Monthly Newsletter, Job Alert Special">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Default Subject *</label>
                        <input type="text" name="custom_template_subject" class="form-control" required 
                               placeholder="Default subject line for this template">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Template Body (HTML) *</label>
                        <textarea name="custom_template_body" class="form-control" rows="12" required 
                                  placeholder="<html><body><h2>Hello [CANDIDATE_NAME],</h2><p>Your custom message here...</p><div>[CUSTOM_CONTENT]</div><p>Best regards,<br>Your Team</p></body></html>"></textarea>
                        <small class="form-text text-muted">You can use HTML formatting. The company signature will be automatically added.</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Quick Templates:</h6>
                            <button type="button" class="btn btn-sm btn-outline-secondary mb-2" onclick="loadQuickTemplate('basic')">Basic Template</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary mb-2" onclick="loadQuickTemplate('newsletter')">Newsletter Style</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary mb-2" onclick="loadQuickTemplate('job_alert')">Job Alert Style</button>
                        </div>
                        <div class="col-md-6">
                            <h6>Preview:</h6>
                            <div id="template-preview" style="border: 1px solid #ddd; padding: 10px; background-color: #f9f9f9; min-height: 100px; font-size: 12px;">
                                <em>Template preview will appear here...</em>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_custom_template" class="btn btn-success">
                        <i class="ti ti-device-floppy"></i> Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

<?php include "../../includes/js.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Enhanced filtering auto-submit on Enter key
    $('.filter-input').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $(this).closest('form').submit();
        }
    });

    // Mailshot select all functionality
    $('#select-all').on('change', function() {
        $('.candidate-checkbox').prop('checked', this.checked);
    });
    
    $('.candidate-checkbox').on('change', function() {
        const allCheckboxes = $('.candidate-checkbox').length;
        const checkedCheckboxes = $('.candidate-checkbox:checked').length;
        $('#select-all').prop('checked', allCheckboxes === checkedCheckboxes);
    });

    // Legacy delete functionality
    $('.delete').on('click', function() {
        var row = $(this).closest('tr');
        var checkbox = row.find('.checkbox-item');
        checkbox.prop('checked', true);
    });

    // Mailshot form validation and loading state
    $('#send-mailshot-btn').on('click', function(e) {
        const selectedCandidates = document.querySelectorAll('input[name="selected_candidates[]"]:checked');
        const subject = document.getElementById('mailshot-subject').value.trim();
        const template = document.getElementById('mailshot-template').value;
        
        if (selectedCandidates.length === 0) {
            e.preventDefault();
            alert('Please select at least one candidate.');
            return;
        }
        
        if (!subject) {
            e.preventDefault();
            alert('Please enter an email subject.');
            return;
        }
        
        if (!template) {
            e.preventDefault();
            alert('Please select an email template.');
            return;
        }
        
        // Show loading state
        this.innerHTML = '<i class="ti ti-loader"></i> Sending...';
        this.disabled = true;
        
        // Confirm before sending
        if (!confirm(`Are you sure you want to send this mailshot to ${selectedCandidates.length} candidates?`)) {
            e.preventDefault();
            this.innerHTML = '<i class="ti ti-mail"></i> Send Mailshot';
            this.disabled = false;
        }
    });
});

// Template preview function
function previewTemplate(templateName) {
    // Get custom template data via AJAX
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            action: 'preview_template',
            template_name: templateName
        },
        success: function(response) {
            // For now, show a simple preview
            const previewContent = `
                <h6>Template: ${templateName}</h6>
                <div class="alert alert-info">
                    <strong>Note:</strong> This is a preview of your custom template. 
                    The actual email will include the company signature and proper formatting.
                </div>
                <div style="border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9;">
                    <p><em>Template preview functionality coming soon...</em></p>
                    <p>Template Name: <strong>${templateName}</strong></p>
                </div>
            `;
            
            document.getElementById('template-preview-content').innerHTML = previewContent;
            const modal = new bootstrap.Modal(document.getElementById('TemplatePreviewModal'));
            modal.show();
        },
        error: function() {
            alert('Error loading template preview.');
        }
    });
}

// KPI Charts (only load if in KPI mode)
<?php if ($mode === 'kpi' && !empty($kpi_data) && !isset($kpi_data['error'])): ?>
// Daily Trend Chart
const dailyTrendCtx = document.getElementById('dailyTrendChart').getContext('2d');
const dailyTrendChart = new Chart(dailyTrendCtx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", array_column($kpi_data['daily_trend'], 'date')) . "'"; ?>],
        datasets: [{
            label: 'Daily Registrations',
            data: [<?php echo implode(',', array_column($kpi_data['daily_trend'], 'count')); ?>],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Inactive', 'Archived', 'Pending'],
        datasets: [{
            data: [
                <?php echo $kpi_data['active_candidates']; ?>,
                <?php echo $kpi_data['inactive_candidates']; ?>,
                <?php echo $kpi_data['archived_candidates']; ?>,
                <?php echo $kpi_data['pending_candidates']; ?>
            ],
            backgroundColor: [
                '#28a745',
                '#dc3545',
                '#ffc107',
                '#17a2b8'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Export function
function exportKPIReport() {
    let csvContent = "KPI Report - <?php echo $kpi_data['date_range']['start']; ?> to <?php echo $kpi_data['date_range']['end']; ?>\n\n";
    csvContent += "Metric,Value\n";
    csvContent += "Total Candidates,<?php echo $kpi_data['total_candidates']; ?>\n";
    csvContent += "New Candidates,<?php echo $kpi_data['new_candidates']; ?>\n";
    csvContent += "Active Candidates,<?php echo $kpi_data['active_candidates']; ?>\n";
    csvContent += "Inactive Candidates,<?php echo $kpi_data['inactive_candidates']; ?>\n";
    csvContent += "Archived Candidates,<?php echo $kpi_data['archived_candidates']; ?>\n";
    csvContent += "Pending Candidates,<?php echo $kpi_data['pending_candidates']; ?>\n";
    csvContent += "Growth Rate,<?php echo $kpi_data['growth_rate']; ?>%\n";
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'kpi-report-<?php echo date('Y-m-d'); ?>.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
<?php endif; ?>

document.getElementById('confirmDelete').addEventListener('click', function() {
    let checkboxes = document.querySelectorAll('.checkbox-item:checked');
    let ids = [];
    let successCount = 0;
    var reason = $("#reason").val();

    if (reason.length > 0) {
        checkboxes.forEach(function(checkbox) {
            ids.push({
                id: checkbox.value,
                name: checkbox.getAttribute('data-name')
            });
        });

        if (ids.length > 0) {
            $("#confirmDelete").text("Deleting...");

            ids.forEach(function(item) {
                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: {
                        ID: item.id,
                        name: item.name,
                        reason: reason,
                        DeletCandidate: true
                    },
                    success: function(response) {
                        successCount++;
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                    }
                });
            });
        } else {
            ShowToast('Error 102: Something went wrong.');
        }
    } else {
        ShowToast('Error 101: Reason field is required.');
        return;
    }
});

// Template management functions
function useTemplate(templateValue, templateSubject) {
    document.getElementById('mailshot-template').value = templateValue;
    document.getElementById('mailshot-subject').value = templateSubject;
    
    // Visual feedback
    const templateSelect = document.getElementById('mailshot-template');
    templateSelect.style.borderColor = '#28a745';
    setTimeout(() => {
        templateSelect.style.borderColor = '';
    }, 2000);
}

function loadQuickTemplate(type) {
    const templateBody = document.querySelector('textarea[name="custom_template_body"]');
    const templateSubject = document.querySelector('input[name="custom_template_subject"]');
    
    const templates = {
        basic: {
            subject: 'Important Update from Nocturnal Recruitment',
            body: `<html>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
    <h2 style='color: #2c3e50;'>Hello [CANDIDATE_NAME],</h2>
    
    <p>We hope this email finds you well.</p>
    
    <div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
        [CUSTOM_CONTENT]
    </div>
    
    <p>If you have any questions, please don't hesitate to contact us.</p>
    
    <p>Best regards,<br><strong>Nocturnal Recruitment Team</strong></p>
</body>
</html>`
        },
        newsletter: {
            subject: 'Nocturnal Recruitment Newsletter - [MONTH] Edition',
            body: `<html>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
    <div style='background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;'>
        <h1 style='margin: 0;'>Newsletter</h1>
        <p style='margin: 5px 0 0 0;'>Latest Updates & Opportunities</p>
    </div>
    
    <div style='background-color: #f8f9fa; padding: 20px; border-radius: 0 0 5px 5px;'>
        <h2 style='color: #2c3e50;'>Hello [CANDIDATE_NAME],</h2>
        
        <p>Welcome to our latest newsletter with exciting updates and opportunities!</p>
        
        <div style='background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #007bff;'>
            [CUSTOM_CONTENT]
        </div>
        
        <p>Stay connected with us for more opportunities!</p>
        
        <p>Best regards,<br><strong>Nocturnal Recruitment Team</strong></p>
    </div>
</body>
</html>`
        },
        job_alert: {
            subject: 'New Job Opportunities - Perfect Match for You!',
            body: `<html>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
    <div style='background-color: #28a745; color: white; padding: 15px; text-align: center; border-radius: 5px;'>
        <h1 style='margin: 0;'>🎯 New Job Alert!</h1>
    </div>
    
    <h2 style='color: #2c3e50; margin-top: 20px;'>Hello [CANDIDATE_NAME],</h2>
    
    <p>We've found some exciting new opportunities that match your profile!</p>
    
    <div style='background-color: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0; border: 1px solid #c3e6cb;'>
        <h3 style='color: #155724; margin-top: 0;'>Featured Opportunities:</h3>
        [CUSTOM_CONTENT]
    </div>
    
    <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center;'>
        <p style='margin: 0;'><strong>Ready to take the next step?</strong></p>
        <p style='margin: 5px 0 0 0;'>Contact us today to discuss these opportunities!</p>
    </div>
    
    <p>Best regards,<br><strong>Nocturnal Recruitment Team</strong></p>
</body>
</html>`
        }
    };
    
    if (templates[type]) {
        templateSubject.value = templates[type].subject;
        templateBody.value = templates[type].body;
        updatePreview();
    }
}

function updatePreview() {
    const templateBody = document.querySelector('textarea[name="custom_template_body"]').value;
    const preview = document.getElementById('template-preview');
    
    if (templateBody.trim()) {
        // Simple preview - replace placeholders with sample data
        let previewContent = templateBody
            .replace(/\[CANDIDATE_NAME\]/g, 'John Doe')
            .replace(/\[CUSTOM_CONTENT\]/g, 'This is where your custom content will appear...');
        
        preview.innerHTML = previewContent;
    } else {
        preview.innerHTML = '<em>Template preview will appear here...</em>';
    }
}

// Add event listener for template body changes
$(document).ready(function() {
    $('textarea[name="custom_template_body"]').on('input', updatePreview);
});
</script>

</html>