<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "includes/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname1 = 'broadmead';
$dbname2 = 'broadmead_v3';
$dsn1 = 'mysql:host=' . $host . ';dbname=' . $dbname1;
$dsn2 = 'mysql:host=' . $host . ';dbname=' . $dbname2;

// Database connections
try {
    $db_1 = new PDO($dsn1, $user, $password);
    $db_1->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_2 = new PDO($dsn2, $user, $password);
    $db_2->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<b>Database Connection Error: </b> " . $e->getMessage();
    exit;
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'candidates';

// Process mailshot
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'mailshot') {
    error_log("POST data received: " . print_r($_POST, true));
    
    // Validation
    if (empty($_POST['selected_candidates'])) {
        $error_message = "Please select at least one candidate.";
    } elseif (empty($_POST['subject'])) {
        $error_message = "Email subject is required.";
    } elseif (empty($_POST['template'])) {
        $error_message = "Please select an email template.";
    } else {
        $candidate_ids = $_POST['selected_candidates'];
        $subject = $_POST['subject'];
        $template = $_POST['template'];
        
        error_log("Processing mailshot for " . count($candidate_ids) . " candidates");
        
        // Email templates
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
            ]
        ];

        $template_details = $templates[$template] ?? [
            'subject' => $subject,
            'body' => "Hello [Name],\n\nThank you for being part of our network.\n\nBest regards,\nThe Recruitment Team"
        ];

        $final_subject = empty($subject) ? $template_details['subject'] : $subject;
        $base_body = $template_details['body'];
        $from_email = "learn@natec.icu";
        $from_name = "Recruitment Team";

        $mail = new PHPMailer(true);
        $smtp_host = 'smtp.titan.email';
        $smtp_username = 'learn@natec.icu';
        $smtp_password = '@WhiteDiamond0100';
        $smtp_port = 587;
        $success_count = 0;
        $error_count = 0;
        $error_details = [];
        $console_logs = [];

        // Test email configuration
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
            $error_message = "SMTP Configuration Error: " . $e->getMessage();
            $console_logs[] = "ERROR: SMTP Configuration failed - " . $e->getMessage();
            error_log("SMTP Error: " . $e->getMessage());
        }

        // Proceed if SMTP test passed
        if (!isset($error_message)) {
            foreach ($candidate_ids as $candidate_id) {
                try {
                    error_log("Processing candidate ID: " . $candidate_id);
                    
                    $stmt = $db_2->prepare("SELECT Name, Email FROM _candidates WHERE id = ?");
                    $stmt->execute([$candidate_id]);
                    $candidate = $stmt->fetch(PDO::FETCH_OBJ);

                    if (!$candidate) {
                        $stmt = $db_1->prepare("SELECT CONCAT(first_name, ' ', last_name) as Name, email as Email FROM candidates WHERE id = ?");
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
                        $mail->addAddress($to, $name);
                        $mail->addReplyTo($from_email, $from_name);
                        $mail->isHTML(true);
                        $mail->Subject = $final_subject;
                        $mail->Body = nl2br(htmlspecialchars($personalized_body));
                        $mail->AltBody = $personalized_body;

                        if ($mail->send()) {
                            $success_count++;
                            $console_logs[] = "SUCCESS: Email sent to {$to} (Candidate ID: {$candidate_id})";
                            error_log("SUCCESS: Email sent to {$to}");
                        } else {
                            $error_count++;
                            $error_details[] = "Failed to send to: $to - " . $mail->ErrorInfo;
                            $console_logs[] = "ERROR: Failed to send to {$to} (Candidate ID: {$candidate_id}) - " . $mail->ErrorInfo;
                            error_log("ERROR: Failed to send to {$to} - " . $mail->ErrorInfo);
                        }
                        
                        $mail->clearAddresses();
                        $mail->clearAttachments();
                        usleep(100000);
                        
                    } else {
                        $candidate_email = $candidate->Email ?? 'N/A';
                        $error_count++;
                        $error_details[] = "Invalid email for candidate ID: $candidate_id (Email: $candidate_email)";
                        $console_logs[] = "ERROR: Invalid email for candidate ID {$candidate_id} (Email: {$candidate_email})";
                        error_log("ERROR: Invalid email for candidate ID {$candidate_id}");
                    }
                } catch (Exception $e) {
                    $candidate_email = isset($candidate) && isset($candidate->Email) ? $candidate->Email : 'N/A';
                    $error_count++;
                    $error_details[] = "Error processing candidate ID: $candidate_id - " . $e->getMessage();
                    $console_logs[] = "ERROR: Exception for candidate ID {$candidate_id} - " . $e->getMessage();
                    error_log("ERROR: Exception for candidate ID {$candidate_id} - " . $e->getMessage());
                }
            }

            if ($error_count === 0) {
                $success_message = "Mailshot processing completed. Successfully sent to $success_count candidates.";
            } else {
                $error_message = "Mailshot processing completed with issues: $success_count succeeded, $error_count failed.";
                if (!empty($error_details)) {
                    $error_message .= "\n\nFirst 5 errors:\n" . implode("\n", array_slice($error_details, 0, 5));
                    if (count($error_details) > 5) {
                        $error_message .= "\n... plus " . (count($error_details) - 5) . " more errors.";
                    }
                }
            }
        }
    }
}

// Output JavaScript console logs
if (!empty($console_logs)) {
    echo "<script>";
    foreach ($console_logs as $log) {
        echo "console.log('" . addslashes($log) . "');";
    }
    echo "</script>";
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

function getPostcodeCoordinates($postcode) {
    static $postcodeCache = [];
    
    if (isset($postcodeCache[$postcode])) {
        return $postcodeCache[$postcode];
    }
    
    $coordinates = [
        'latitude' => 51.5 + (rand(-100, 100) / 1000),
        'longitude' => -0.1 + (rand(-100, 100) / 1000)
    ];
    
    $postcodeCache[$postcode] = $coordinates;
    return $coordinates;
}

function calculateDistanceBetweenPostcodes($postcode1, $postcode2) {
    $coords1 = getPostcodeCoordinates($postcode1);
    $coords2 = getPostcodeCoordinates($postcode2);
    
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

function calculateKPIs($db, $period, $start_date = null, $end_date = null) {
    $kpis = [];
    
    try {
        // Validate and use custom dates if provided and period is 'custom'
        if ($period === 'custom' && $start_date && $end_date) {
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            
            // Validate date range
            if ($start > $end) {
                throw new Exception("Start date cannot be after end date.");
            }
            if ($start > new DateTime()) {
                throw new Exception("Start date cannot be in the future.");
            }
            
            $dateRange = [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d')
            ];
        } else {
            $dateRange = getDateRangeForPeriod($period);
        }
        
        // Fetch all candidates for the detailed table within the period
        $stmt_all_candidates = $db->prepare("SELECT id, Name, Email, JobTitle, Status, CreatedBy, Date FROM _candidates WHERE Date BETWEEN ? AND ? ORDER BY Date DESC");
        $stmt_all_candidates->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['detailed_candidates'] = $stmt_all_candidates->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT COUNT(*) as total FROM _candidates WHERE Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['total_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $stmt = $db->prepare("SELECT COUNT(*) as new_candidates FROM _candidates WHERE Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['new_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['new_candidates'];
        
        $stmt = $db->prepare("SELECT COUNT(*) as active FROM _candidates WHERE Status = 'active' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['active_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
        
        $stmt = $db->prepare("SELECT COUNT(*) as inactive FROM _candidates WHERE Status = 'inactive' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['inactive_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['inactive'];
        
        $stmt = $db->prepare("SELECT COUNT(*) as archived FROM _candidates WHERE Status = 'archived' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['archived_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['archived'];
        
        $stmt = $db->prepare("SELECT COUNT(*) as pending FROM _candidates WHERE Status = 'pending' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['pending_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending'];
        
        // Job Title Stats
        $stmt = $db->prepare("SELECT JobTitle, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? AND JobTitle IS NOT NULL AND JobTitle != '' GROUP BY JobTitle ORDER BY count DESC");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['job_title_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // City Stats
        $stmt = $db->prepare("SELECT City, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? AND City IS NOT NULL AND City != '' GROUP BY City ORDER BY count DESC");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['city_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // CreatedBy Stats
        $stmt = $db->prepare("SELECT CreatedBy, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? GROUP BY CreatedBy ORDER BY count DESC");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['created_by_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $db->prepare("SELECT DATE(Date) as date, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? GROUP BY DATE(Date) ORDER BY date");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['daily_trend'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $previousPeriod = getPreviousPeriodRange($period, $dateRange);
        $stmt = $db->prepare("SELECT COUNT(*) as previous_total FROM _candidates WHERE Date BETWEEN ? AND ?");
        $stmt->execute([$previousPeriod['start'] . ' 00:00:00', $previousPeriod['end'] . ' 23:59:59']);
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

if ($mode === 'mailshot') {
    $job_titles_query = "SELECT DISTINCT JobTitle FROM _candidates WHERE JobTitle IS NOT NULL AND JobTitle != '' ORDER BY JobTitle";
    $job_titles_stmt = $db_2->query($job_titles_query);
    $job_titles = $job_titles_stmt->fetchAll(PDO::FETCH_COLUMN);

    $locations_query = "SELECT DISTINCT City FROM _candidates WHERE City IS NOT NULL AND City != '' ORDER BY City";
    $locations_stmt = $db_2->query($locations_query);
    $locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);
}

$kpi_data = [];
if ($mode === 'kpi') {
    $kpi_data = calculateKPIs($db_2, $kpi_period, $kpi_start_date, $kpi_end_date);
}

// Mapping for CreatedBy IDs to Names
$createdByMapping = [
    "1" => "Chax Shamwana",
    "10" => "Millie Brown",
    "11" => "Jay Fuller",
    "13" => "Jack Dowler",
    "15" => "Alex Lapompe",
    "2" => "Alex Lapompe",
    "9" => "Jack Dowler"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <?php include "../../includes/head.php"; ?>
    <style>
        .kpi-filter-section input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: white;
            cursor: text;
        }
        .kpi-filter-section input[type="date"]:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        .kpi-filter-section input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
        }
    </style>
</head>
<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php"; ?>
    <div class="pc-container" style="margin-left: 280px;">
        <div class="pc-content">
            <?php include "../../includes/breadcrumb.php"; ?>

            <div class="mode-switch">
                <h4 style="margin-bottom: 15px;">Mode Selection</h4>
                <a href="?mode=candidates" class="mode-button <?php echo $mode === 'candidates' ? 'active' : ''; ?>">
                    <i class="fa fa-users"></i> View Candidates
                </a>
                <a href="?mode=mailshot" class="mode-button <?php echo $mode === 'mailshot' ? 'active' : ''; ?>">
                    <i class="fa fa-paper-plane"></i> Create Mailshot
                </a>
                <?php
                $has_kpi_permission = true;
                if ($has_kpi_permission):
                ?>
                    <a href="?mode=kpi" class="mode-button <?php echo strtolower($mode) === 'kpi' ? 'active' : ''; ?>">
                        <i class="fa fa-bar-chart"></i> Weekly KPI Search
                    </a>
                <?php endif; ?>
            </div>

            <h2 style="margin-bottom: 30px; color: #343a40;">
                <?php
                if ($mode === 'kpi') {
                    echo 'KPI Reporting - Candidate Performance';
                } elseif ($mode === 'mailshot') {
                    echo 'Mailshot - Candidate Filtering';
                } else {
                    echo 'Candidates - Email Filtering System';
                }
                ?>
            </h2>

            <?php if ($mode === 'kpi'): ?>
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

            <?php if (isset($success_message)): ?>
                <div class="success-message">
                    <i class="fa fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($mode !== 'kpi'): ?>
                <div class="filter-section">
                    <h5 style="margin-bottom: 20px; color: #495057;">
                        <?php echo $mode === 'mailshot' ? 'Filter Candidates for Mailshot' : 'Email Filtering System'; ?>
                    </h5>
                    <form method="GET" action="">
                        <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode); ?>">
                        
                        <?php if ($mode === 'candidates'): ?>
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
                                <div class="col-md-3">
                                    <div class="filter-label">Distance Filter</div>
                                    <div class="distance-filter">
                                        <input type="text" name="center_postcode" class="filter-input"
                                            placeholder="Postcode"
                                            value="<?php echo htmlspecialchars($center_postcode); ?>">
                                        <input type="number" name="distance_miles" class="filter-input distance-input"
                                            placeholder="Miles" min="1" max="100"
                                            value="<?php echo $distance_miles > 0 ? htmlspecialchars($distance_miles) : ''; ?>">
                                        <span>miles</span>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row filter-row">
                                <div class="col-md-6">
                                    <div class="filter-label">Keywords (Name, Email, Job Title)</div>
                                    <input type="text" name="keyword" class="filter-input"
                                        placeholder="Search by keywords..."
                                        value="<?php echo htmlspecialchars($keyword_filter); ?>">
                                </div>
                                <div class="col-md-6">
                                    <div class="filter-label">Position Title</div>
                                    <select name="position" class="filter-input">
                                        <option value="">All Positions</option>
                                        <?php if (isset($job_titles)): ?>
                                            <?php foreach ($job_titles as $job_title): ?>
                                                <option value="<?php echo htmlspecialchars($job_title); ?>"
                                                        <?php echo $position_filter === $job_title ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($job_title); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row filter-row">
                                <div class="col-md-6">
                                    <div class="filter-label">Location</div>
                                    <select name="location" class="filter-input">
                                        <option value="">All Locations</option>
                                        <?php if (isset($locations)): ?>
                                            <?php foreach ($locations as $location): ?>
                                                <option value="<?php echo htmlspecialchars($location); ?>"
                                                        <?php echo $location_filter === $location ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($location); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="filter-label">Distance Filter</div>
                                    <div class="distance-filter">
                                        <input type="text" name="center_postcode" class="filter-input"
                                            placeholder="Postcode"
                                            value="<?php echo htmlspecialchars($center_postcode); ?>">
                                        <input type="number" name="distance_miles" class="filter-input distance-input"
                                            placeholder="Miles" min="1" max="100"
                                            value="<?php echo $distance_miles > 0 ? htmlspecialchars($distance_miles) : ''; ?>">
                                        <span>miles</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="filter-button">
                                    <i class="fa fa-search"></i> Apply Filters
                                </button>
                                <a href="?mode=<?php echo htmlspecialchars($mode); ?>" class="clear-button">
                                    <i class="fa fa-times"></i> Clear Filters
                                </a>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>">
                    </form>
                </div>

                <?php if ($mode === 'candidates'): ?>
                    <div class="status-tabs">
                        <a href="?mode=candidates&status=all&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>¢er_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>"
                           class="status-tab <?php echo $status_filter === 'all' ? 'active' : ''; ?>">All Candidates</a>
                        <a href="?mode=candidates&status=active&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>¢er_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>"
                           class="status-tab <?php echo $status_filter === 'active' ? 'active' : ''; ?>">Active</a>
                        <a href="?mode=candidates&status=archived&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>¢er_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>"
                           class="status-tab <?php echo $status_filter === 'archived' ? 'active' : ''; ?>">Archived</a>
                        <a href="?mode=candidates&status=inactive&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>¢er_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>"
                           class="status-tab <?php echo $status_filter === 'inactive' ? 'active' : ''; ?>">Inactive</a>
                        <a href="?mode=candidates&status=pending&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>¢er_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>"
                           class="status-tab <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">Pending Compliance</a>
                    </div>
                <?php endif; ?>

                <?php
                if ($mode !== 'kpi') {
                    try {
                        $query = "SELECT * FROM `_candidates` $where_clause ORDER BY id DESC";
                        $stmt = $db_2->prepare($query);
                        
                        foreach ($params as $key => $value) {
                            $stmt->bindValue($key, $value);
                        }
                        
                        $stmt->execute();
                        $candidates_new = $stmt->fetchAll(PDO::FETCH_OBJ);
                        
                        $candidates_old = [];
                        try {
                            $q = $db_1->query("SELECT * FROM `_candidates` ORDER BY id DESC");
                            $candidates_old = $q->fetchAll(PDO::FETCH_OBJ);
                        } catch (Exception $e) {}
                        
                        $candidates = !empty($candidates_new) ? $candidates_new : $candidates_old;
                        
                    } catch (Exception $e) {
                        $candidates = [];
                        echo "<div class='alert alert-warning'>Database query error: " . $e->getMessage() . "</div>";
                    }
                    
                    $distance_filtered_candidates = [];
                    if (!empty($center_postcode) && $distance_miles > 0) {
                        foreach ($candidates as $candidate) {
                            $postcode_field = isset($candidate->Postcode) ? $candidate->Postcode : (isset($candidate->postcode) ? $candidate->postcode : '');
                            if (!empty($postcode_field)) {
                                try {
                                    $distance = calculateDistanceBetweenPostcodes($center_postcode, $postcode_field);
                                    if ($distance <= $distance_miles) {
                                        $candidate->distance = round($distance, 1);
                                        $distance_filtered_candidates[] = $candidate;
                                    }
                                } catch (Exception $e) {
                                    continue;
                                }
                            }
                        }
                        $candidates = $distance_filtered_candidates;
                    }
                    $total_results = count($candidates);
                }
                ?>

                <?php if ($mode !== 'kpi'): ?>
                    <div class="results-info">
                        <strong><?php echo $total_results; ?></strong> candidates found
                        <?php if ($mode === 'mailshot'): ?>
                            for mailshot
                        <?php endif; ?>
                        <?php if (!empty($keyword_filter) || !empty($location_filter) || !empty($position_filter)): ?>
                            with applied filters
                        <?php endif; ?>
                        <?php if (!empty($center_postcode) && $distance_miles > 0): ?>
                            within <?php echo htmlspecialchars($distance_miles); ?> miles of <?php echo htmlspecialchars($center_postcode); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($mode !== 'kpi' && $total_results > 0): ?>
                    <?php if ($mode === 'mailshot'): ?>
                        <form method="POST" action="">
                            <div class="select-all-container">
                                <label>
                                    <input type="checkbox" id="select-all" class="candidate-checkbox">
                                    Select All Candidates
                                </label>
                            </div>
                        <?php endif; ?>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="editableTable">
                                <thead class="thead-light">
                                    <tr>
                                        <?php if ($mode === 'mailshot'): ?>
                                            <th width="40px">Select</th>
                                        <?php endif; ?>
                                        <th>#</th>
                                        <?php if ($mode === 'candidates'): ?>
                                            <th>Candidate ID</th>
                                            <th>Status</th>
                                        <?php endif; ?>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <?php if ($mode === 'candidates'): ?>
                                            <th>Phone Number</th>
                                        <?php endif; ?>
                                        <th>Job Title</th>
                                        <?php if ($mode === 'candidates'): ?>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Postcode</th>
                                        <?php else: ?>
                                            <th>Location</th>
                                        <?php endif; ?>
                                        <?php if (!empty($center_postcode) && $distance_miles > 0): ?>
                                            <th>Distance</th>
                                        <?php endif; ?>
                                        <th>Created By</th>
                                        <th>Date</th>
                                        <?php if ($mode === 'candidates'): ?>
                                            <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $n = 1;
                                    foreach ($candidates as $row) {
                                        $name = '';
                                        if (isset($row->Name)) {
                                            $name = $row->Name;
                                        } elseif (isset($row->first_name) && isset($row->last_name)) {
                                            $name = $row->first_name . ' ' . $row->last_name;
                                        }
                                        $status = $row->Status ?? $row->status ?? 'active';
                                        $email = strtolower($row->Email ?? $row->email ?? '');
                                        $phonenumber = $row->Number ?? $row->mobilenumber ?? '';
                                        $Address = $row->Address ?? $row->address ?? '';
                                        $city = $row->City ?? $row->city ?? '';
                                        $postcode = $row->Postcode ?? $row->postcode ?? '';
                                        $job_title = $row->JobTitle ?? $row->job_title ?? '';
                                        $CandidateID = $row->CandidateID ?? '';
                                        $profileImage = $row->ProfileImage ?? (isset($row->profile) ? "https://broad-mead.com/" . $row->profile : '');
                                        $CreatedBy = "Unknown";
                                        $createdByField = $row->CreatedBy ?? $row->createdBy ?? '';
                                        if (array_key_exists($createdByField, $createdByMapping)) {
                                            $CreatedBy = $createdByMapping[$createdByField];
                                        }
                                        $status_class = 'status-' . strtolower($status);
                                        $date = 'N/A';
                                        if (isset($row->Date)) {
                                            $date = date('M d, Y', strtotime($row->Date));
                                        } elseif (isset($row->created_at)) {
                                            $date = date('M d, Y', strtotime($row->created_at));
                                        }
                                    ?>
                                        <tr data-id="<?php echo $row->id; ?>">
                                            <?php if ($mode === 'mailshot'): ?>
                                                <td class="text-center">
                                                    <input type="checkbox" name="selected_candidates[]" value="<?php echo $row->id; ?>" class="candidate-checkbox">
                                                </td>
                                            <?php endif; ?>
                                            <td><?php echo str_pad($n++, 2, '0', STR_PAD_LEFT); ?></td>
                                            <?php if ($mode === 'candidates'): ?>
                                                <td>
                                                    <span class="candidate-id"><?php echo substr($CandidateID ?? '', 0, 5); ?></span>
                                                </td>
                                                <td>
                                                    <span class="status-badge <?php echo $status_class; ?>">
                                                        <?php echo ucfirst($status); ?>
                                                    </span>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <div class="candidate-name-cell">
                                                    <?php if (!empty($profileImage)): ?>
                                                        <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile" class="candidate-avatar">
                                                    <?php else: ?>
                                                        <div class="candidate-avatar-placeholder">
                                                            <?php echo strtoupper(substr($name, 0, 1)); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="candidate-name"><?php echo htmlspecialchars($name); ?></div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($email); ?></td>
                                            <?php if ($mode === 'candidates'): ?>
                                                <td><?php echo htmlspecialchars($phonenumber); ?></td>
                                            <?php endif; ?>
                                            <td><?php echo htmlspecialchars($job_title); ?></td>
                                            <?php if ($mode === 'candidates'): ?>
                                                <td><?php echo htmlspecialchars($Address); ?></td>
                                                <td><?php echo htmlspecialchars($city); ?></td>
                                                <td><?php echo htmlspecialchars($postcode); ?></td>
                                            <?php else: ?>
                                                <td><?php echo htmlspecialchars($city); ?></td>
                                            <?php endif; ?>
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
                                            <td><?php echo htmlspecialchars($CreatedBy); ?></td>
                                            <td><?php echo htmlspecialchars($date); ?></td>
                                            <?php if ($mode === 'candidates'): ?>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn btn-sm btn-outline-primary" title="View Candidate">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary" title="Edit Candidate">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" title="Delete Candidate">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($mode === 'mailshot'): ?>
                            <div class="mailshot-actions">
                                <h5>Mailshot Actions</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mailshot-subject">Email Subject</label>
                                            <input type="text" name="subject" id="mailshot-subject" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mailshot-template">Email Template</label>
                                            <select name="template" id="mailshot-template" class="form-control" required>
                                                <option value="">Select a template</option>
                                                <option value="job_alert">Job Alert</option>
                                                <option value="newsletter">Newsletter</option>
                                                <option value="event_invitation">Event Invitation</option>
                                                <option value="follow_up">Follow Up</option>
                                                <option value="welcome">Welcome Email</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-paper-plane"></i> Send Mail
                                    </button>
                                    <span class="text-muted ml-3">
                                        <i class="fa fa-info-circle"></i>
                                        This will send emails to all selected candidates
                                    </span>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>

                <?php elseif ($mode !== 'kpi'): ?>
                    <div class="alert alert-info text-center">
                        <h5>No candidates found</h5>
                        <p>Try adjusting your search filters or <a href="?mode=<?php echo htmlspecialchars($mode); ?>">clear all filters</a> to see <?php echo $mode === 'mailshot' ? 'candidates for your mailshot' : 'all candidates'; ?>.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </html>
    <?php endif; ?>

    <?php if ($mode === 'kpi'): ?>
        <!-- KPI Filter Form -->
        <div class="kpi-filter-section">
            <h5 style="margin-bottom: 20px; color: #495057;">Select KPI Period</h5>
            <form method="GET" action="">
                <input type="hidden" name="mode" value="kpi">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-3">
                        <label for="kpi_period">KPI Period</label>
                        <select name="kpi_period" id="kpi_period" class="form-control">
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
                        <label for="kpi_start_date">Start Date</label>
                        <input type="date" name="kpi_start_date" id="kpi_start_date" class="form-control" value="<?php echo htmlspecialchars($kpi_start_date); ?>" <?php echo $kpi_period !== 'custom' ? 'disabled' : ''; ?>>
                    </div>
                    <div class="col-md-3">
                        <label for="kpi_end_date">End Date</label>
                        <input type="date" name="kpi_end_date" id="kpi_end_date" class="form-control" value="<?php echo htmlspecialchars($kpi_end_date); ?>" <?php echo $kpi_period !== 'custom' ? 'disabled' : ''; ?>>
                    </div>
                    <!-- <div class="col-md-3" style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-bar-chart"></i> Generate Report</button>
                        <a href="?mode=kpi" class="btn btn-secondary"><i class="fa fa-times"></i> Clear</a>
                    </div> -->

                     <div class="col-md-3">
                <label for="job_title_filter">Job Title</label>
                <select name="job_title_filter" id="job_title_filter" class="form-control">
                    <option value="">All Job Titles</option>
                    <?php
                    // Fetch distinct job titles from database
                    $job_titles_query = "SELECT DISTINCT JobTitle FROM _candidates WHERE JobTitle IS NOT NULL AND JobTitle != '' ORDER BY JobTitle";
                    $job_titles_stmt = $db_2->query($job_titles_query);
                    $job_titles = $job_titles_stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    foreach ($job_titles as $title) {
                        $selected = isset($_GET['job_title_filter']) && $_GET['job_title_filter'] === $title ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($title) . "\" $selected>" . htmlspecialchars($title) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="row">
            <!-- Location Filter -->
            <div class="col-md-3">
                <label for="location_filter">Location</label>
                <select name="location_filter" id="location_filter" class="form-control">
                    <option value="">All Locations</option>
                    <?php
                    // Fetch distinct locations from database
                    $locations_query = "SELECT DISTINCT City FROM _candidates WHERE City IS NOT NULL AND City != '' ORDER BY City";
                    $locations_stmt = $db_2->query($locations_query);
                    $locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    foreach ($locations as $location) {
                        $selected = isset($_GET['location_filter']) && $_GET['location_filter'] === $location ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($location) . "\" $selected>" . htmlspecialchars($location) . "</option>";
                    }
                    ?>
                </select>
            </div>
            
            <!-- Status Filter -->
            <div class="col-md-3">
                <label for="status_filter">Status</label>
                <select name="status_filter" id="status_filter" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="archived" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    <option value="pending" <?php echo isset($_GET['status_filter']) && $_GET['status_filter'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                </select>
            </div>
            
            <!-- Recruiter Filter -->
            <div class="col-md-3">
                <label for="recruiter_filter">Recruiter</label>
                <select name="recruiter_filter" id="recruiter_filter" class="form-control">
                    <option value="">All Recruiters</option>
                    <?php
                    // Fetch distinct recruiters from database
                    $recruiters_query = "SELECT DISTINCT CreatedBy FROM _candidates WHERE CreatedBy IS NOT NULL AND CreatedBy != ''";
                    $recruiters_stmt = $db_2->query($recruiters_query);
                    $recruiters = $recruiters_stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    foreach ($recruiters as $recruiter_id) {
                        $recruiter_name = $createdByMapping[$recruiter_id] ?? 'Unknown User';
                        $selected = isset($_GET['recruiter_filter']) && $_GET['recruiter_filter'] === $recruiter_id ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($recruiter_id) . "\" $selected>" . htmlspecialchars($recruiter_name) . "</option>";
                    }
                    ?>
                </select>
            </div>
            
            <!-- Action Buttons -->
            <div class="col-md-3" style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary"><i class="fa fa-bar-chart"></i> Generate Report</button>
                <a href="?mode=kpi" class="btn btn-secondary"><i class="fa fa-times"></i> Clear Filters</a>
                <?php if (!empty($kpi_data)): ?>
                    <button type="button" class="btn btn-success" onclick="exportKpiData()" style="margin-top: 5px;">
                        <i class="fa fa-file-excel"></i> Export
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </form>
                </div>
            </form>
        </div>

        <?php if (!empty($kpi_data) && !isset($kpi_data['error'])): ?>
            <!-- KPI Summary Table -->
            <div class="kpi-summary card mb-4">
                <div class="card-header">
                    <h5>KPI Summary (<?php echo $kpi_data['date_range']['start']; ?> to <?php echo $kpi_data['date_range']['end']; ?>)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr><th>Total Candidates Registered</th><td><?php echo $kpi_data['total_candidates']; ?></td></tr>
                            <tr><th>New Candidates (in period)</th><td><?php echo $kpi_data['new_candidates']; ?></td></tr>
                            <tr><th>Active Candidates</th><td><?php echo $kpi_data['active_candidates']; ?></td></tr>
                            <tr><th>Inactive Candidates</th><td><?php echo $kpi_data['inactive_candidates']; ?></td></tr>
                            <tr><th>Archived Candidates</th><td><?php echo $kpi_data['archived_candidates']; ?></td></tr>
                            <tr><th>Pending Candidates</th><td><?php echo $kpi_data['pending_candidates']; ?></td></tr>
                            <tr><th>Growth Rate (vs. previous period)</th><td><span class="badge <?php echo $kpi_data['growth_rate'] >= 0 ? 'bg-success' : 'bg-danger'; ?>"><?php echo $kpi_data['growth_rate']; ?>%</span></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KPI Charts -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Candidate Status Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="statusPieChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Daily Candidate Registration Trend</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="trendLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Candidate KPI Table -->
            <div class="detailed-kpi-table card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Detailed Candidate Report (<?php echo $kpi_data['date_range']['start']; ?> to <?php echo $kpi_data['date_range']['end']; ?>)</h5>
                    <button class="btn btn-success" onclick="exportTableToCSV('kpi_detailed_report_<?php echo date('Ymd'); ?>.csv')">
                        <i class="fa fa-file-excel-o"></i> Export to CSV
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($kpi_data['detailed_candidates'])): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="detailedKpiTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Candidate Name</th>
                                        <th>Job Title</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>% of Total (in period)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $n_detailed = 1;
                                    $total_candidates_in_period = $kpi_data['total_candidates'];
                                    $status_counts = [
                                        'active' => 0,
                                        'inactive' => 0,
                                        'archived' => 0,
                                        'pending' => 0
                                    ];

                                    foreach ($kpi_data['detailed_candidates'] as $candidate) {
                                        $name = $candidate['Name'] ?? 'N/A';
                                        $job_title = $candidate['JobTitle'] ?? 'N/A';
                                        $status = $candidate['Status'] ?? 'N/A';
                                        $createdBy = $createdByMapping[$candidate['CreatedBy']] ?? 'Unknown';
                                        $createdDate = date('M d, Y', strtotime($candidate['Date']));
                                        $percentage = ($total_candidates_in_period > 0) ? round((1 / $total_candidates_in_period) * 100, 2) : 0;
                                        
                                        if (isset($status_counts[strtolower($status)])) {
                                            $status_counts[strtolower($status)]++;
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $n_detailed++; ?></td>
                                            <td><?php echo htmlspecialchars($name); ?></td>
                                            <td><?php echo htmlspecialchars($job_title); ?></td>
                                            <td><span class="status-badge status-<?php echo strtolower($status); ?>"><?php echo ucfirst($status); ?></span></td>
                                            <td><?php echo htmlspecialchars($createdBy); ?></td>
                                            <td><?php echo htmlspecialchars($createdDate); ?></td>
                                            <td><?php echo $percentage; ?>%</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <td colspan="3" class="text-end"><strong>Total Candidates:</strong></td>
                                        <td><strong><?php echo $total_candidates_in_period; ?></strong></td>
                                        <td colspan="2" class="text-end"><strong>Status Percentages:</strong></td>
                                        <td>
                                            <?php
                                            foreach ($status_counts as $status_key => $count) {
                                                $percent = ($total_candidates_in_period > 0) ? round(($count / $total_candidates_in_period) * 100, 2) : 0;
                                                echo ucfirst($status_key) . ': ' . $percent . '%<br>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            No detailed candidate data found for the selected period.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Job Titles and Locations KPI Table -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Candidates by Job Title</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($kpi_data['job_title_stats'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Count</th>
                                                <th>% of Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kpi_data['job_title_stats'] as $item): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($item['JobTitle']); ?></td>
                                                    <td><?php echo $item['count']; ?></td>
                                                    <td><?php echo ($total_candidates_in_period > 0) ? round(($item['count'] / $total_candidates_in_period) * 100, 2) : 0; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info text-center">No job title data for this period.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Candidates by Location (City)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($kpi_data['city_stats'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>City</th>
                                                <th>Count</th>
                                                <th>% of Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kpi_data['city_stats'] as $item): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($item['City']); ?></td>
                                                    <td><?php echo $item['count']; ?></td>
                                                    <td><?php echo ($total_candidates_in_period > 0) ? round(($item['count'] / $total_candidates_in_period) * 100, 2) : 0; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info text-center">No location data for this period.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Created By KPI Table -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Candidates Created By User</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($kpi_data['created_by_stats'])): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Created By</th>
                                        <th>Count</th>
                                        <th>% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kpi_data['created_by_stats'] as $item):
                                        $createdByUserName = $createdByMapping[$item['CreatedBy']] ?? 'Unknown';
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($createdByUserName); ?></td>
                                            <td><?php echo $item['count']; ?></td>
                                            <td><?php echo ($total_candidates_in_period > 0) ? round(($item['count'] / $total_candidates_in_period) * 100, 2) : 0; ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">No 'created by' data for this period.</div>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif (isset($kpi_data['error'])): ?>
            <div class="alert alert-danger text-center">
                <h5>Error fetching KPI data:</h5>
                <p><?php echo $kpi_data['error']; ?></p>
                <p>Please check your database connection and table schema.</p>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <h5>No KPI data to display</h5>
                <p>Select a period and click 'Generate Report' to view Key Performance Indicators.</p>
            </div>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Enable/disable and validate date inputs based on KPI period selection
            document.addEventListener('DOMContentLoaded', function() {
                const kpiPeriodSelect = document.getElementById('kpi_period');
                const startDateInput = document.getElementById('kpi_start_date');
                const endDateInput = document.getElementById('kpi_end_date');
                const form = document.querySelector('form[action="?mode=kpi"]');

                function toggleDateInputs() {
                    const isCustom = kpiPeriodSelect.value === 'custom';
                    console.log('KPI Period changed to:', kpiPeriodSelect.value, 'Is Custom:', isCustom);
                    
                    // Enable/disable date inputs
                    startDateInput.disabled = !isCustom;
                    endDateInput.disabled = !isCustom;
                    startDateInput.readOnly = !isCustom;
                    endDateInput.readOnly = !isCustom;
                    
                    // Remove any CSS that might interfere
                    startDateInput.style.pointerEvents = isCustom ? 'auto' : 'none';
                    endDateInput.style.pointerEvents = isCustom ? 'auto' : 'none';
                    
                    // Ensure inputs are interactive when enabled
                    startDateInput.style.cursor = isCustom ? 'pointer' : 'not-allowed';
                    endDateInput.style.cursor = isCustom ? 'pointer' : 'not-allowed';

                    // Clear values if not custom to avoid confusion
                    if (!isCustom) {
                        startDateInput.value = '';
                        endDateInput.value = '';
                    }

                    console.log('Start Date Input Disabled:', startDateInput.disabled);
                    console.log('End Date Input Disabled:', endDateInput.disabled);
                }

                function validateDates() {
                    if (kpiPeriodSelect.value === 'custom') {
                        const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
                        const endDate = endDateInput.value ? new Date(endDateInput.value) : null;
                        const today = new Date();

                        if (!startDate || !endDate) {
                            alert('Please select both start and end dates.');
                            return false;
                        }

                        if (startDate > endDate) {
                            alert('Start date cannot be after end date.');
                            return false;
                        }
                        if (startDate > today) {
                            alert('Start date cannot be in the future.');
                            return false;
                        }
                    }
                    return true;
                }

                // Initialize date inputs
                if (kpiPeriodSelect) {
                    kpiPeriodSelect.addEventListener('change', toggleDateInputs);
                    toggleDateInputs();
                } else {
                    console.error("kpi_period select element not found.");
                }

                // Form submission validation
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (!validateDates()) {
                            e.preventDefault();
                            console.log('Form submission prevented due to invalid dates.');
                        } else {
                            console.log('Form submitted with Start Date:', startDateInput.value, 'End Date:', endDateInput.value);
                        }
                    });
                }

                // Debug input interaction
                startDateInput.addEventListener('click', function() {
                    console.log('Start Date Input Clicked');
                });
                endDateInput.addEventListener('click', function() {
                    console.log('End Date Input Clicked');
                });
                startDateInput.addEventListener('change', function() {
                    console.log('Start Date Changed to:', startDateInput.value);
                });
                endDateInput.addEventListener('change', function() {
                    console.log('End Date Changed to:', endDateInput.value);
                });
            });

            <?php if (!empty($kpi_data) && !isset($kpi_data['error'])): ?>
                const statusData = {
                    labels: ['Active', 'Inactive', 'Archived', 'Pending'],
                    datasets: [{
                        data: [
                            <?php echo $kpi_data['active_candidates']; ?>,
                            <?php echo $kpi_data['inactive_candidates']; ?>,
                            <?php echo $kpi_data['archived_candidates']; ?>,
                            <?php echo $kpi_data['pending_candidates']; ?>
                        ],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d'],
                        hoverOffset: 4
                    }]
                };
                new Chart(document.getElementById('statusPieChart'), {
                    type: 'pie',
                    data: statusData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed !== null) {
                                            label += context.parsed + ' (' + (context.parsed / <?php echo $kpi_data['total_candidates']; ?> * 100).toFixed(2) + '%)';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });

                const trendLabels = <?php echo json_encode(array_column($kpi_data['daily_trend'], 'date')); ?>;
                const trendCounts = <?php echo json_encode(array_column($kpi_data['daily_trend'], 'count')); ?>;
                new Chart(document.getElementById('trendLineChart'), {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [{
                            label: 'Candidates Registered',
                            data: trendCounts,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.2)',
                            fill: true,
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Candidates'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            <?php endif; ?>

            function exportTableToCSV(filename) {
                const table = document.getElementById('detailedKpiTable');
                if (!table) {
                    console.error("Table with ID 'detailedKpiTable' not found.");
                    return;
                }

                let csv = [];
                let headers = [];
                table.querySelectorAll('thead th').forEach(th => {
                    headers.push(th.innerText.trim());
                });
                csv.push(headers.join(','));

                table.querySelectorAll('tbody tr').forEach(row => {
                    let rowData = [];
                    row.querySelectorAll('td').forEach(cell => {
                        let cellText = cell.innerText.trim().replace(/"/g, '""');
                        if (cellText.includes(',') || cellText.includes('\n')) {
                            cellText = `"${cellText}"`;
                        }
                        rowData.push(cellText);
                    });
                    csv.push(rowData.join(','));
                });

                const footerRow = table.querySelector('tfoot tr');
                if (footerRow) {
                    let footerData = [];
                    footerRow.querySelectorAll('td').forEach(cell => {
                        let cellText = cell.innerText.trim().replace(/"/g, '""');
                        if (cellText.includes(',') || cellText.includes('\n')) {
                            cellText = `"${cellText}"`;
                        }
                        footerData.push(cellText);
                    });
                    csv.push(footerData.join(','));
                }

                const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
                const downloadLink = document.createElement('a');
                downloadLink.download = filename;
                downloadLink.href = window.URL.createObjectURL(csvFile);
                downloadLink.style.display = 'none';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }
        </script>
    <?php endif; ?>
</body>
</html>