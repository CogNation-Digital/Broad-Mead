<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/login ");
}

// 1. USE COMPOSER AUTOLOADER INSTEAD OF MANUAL PATHS
require_once __DIR__ . '/../../vendor/autoload.php'; // Adjust path as needed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Email Configuration Class - UPDATED FOR GMAIL AND REPLY-TO
class EmailConfig {
    // Titan Mail SMTP Settings
    public static $SMTP_HOST = 'smtp.titan.email';
    public static $SMTP_PORT = 587;
    public static $SMTP_SECURE = PHPMailer::ENCRYPTION_STARTTLS;
    public static $SMTP_USERNAME = 'learn@natec.icu';
    public static $SMTP_PASSWORD = '@WhiteDiamond0100';
    public static $FROM_EMAIL = 'learn@natec.icu';
    public static $FROM_NAME = 'Recruitment Team';
    public static $REPLY_TO_EMAIL = 'info@nocturnalrecruitment.co.uk';
    public static $REPLY_TO_NAME = 'Nocturnal Recruitment';
    public static $USE_SMTP = true;


    
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'mailshot') {
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

        $success_count = 0;
        $error_count = 0;
        $error_details = [];

        foreach ($candidate_ids as $candidate_id) {
            try {
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

                    $personalized_body = str_replace(
                        ['[Name]', '[LoginLink]', '[NewsletterLink]', '[EventLink]'],
                        [$name, 'https://broad-mead.com/login', 'https://broad-mead.com/newsletter', 'https://broad-mead.com/events'],
                        $base_body
                    );

                    $result = sendTitanMail($to, $final_subject, $personalized_body, $from_email);

                    if ($result === true) {
                        $success_count++;
                        $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at) VALUES (?, ?, ?, ?, ?, 'sent', NULL, NOW())");
                        $log_stmt->execute([$candidate_id, $to, $final_subject, $template, $personalized_body]);
                    } else {
                        $error_count++;
                        $error_details[] = "Failed to send to: $to - $result";
                        $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at) VALUES (?, ?, ?, ?, ?, 'failed', ?, NOW())");
                        $log_stmt->execute([$candidate_id, $to, $final_subject, $template, $personalized_body, $result]);
                    }
                } else {
                    $error_count++;
                    $candidate_email = $candidate->Email ?? 'N/A';
                    $error_details[] = "Invalid email for candidate ID: $candidate_id (Email: $candidate_email)";
                    $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at) VALUES (?, ?, ?, ?, ?, 'invalid', NULL, NOW())");
                    $log_stmt->execute([$candidate_id, $candidate_email, $final_subject, $template, 'Email validation failed - no message sent']);
                }
            } catch (Exception $e) {
                $error_count++;
                $error_details[] = "Error processing candidate ID: $candidate_id - " . $e->getMessage();
                $candidate_email = isset($candidate) && isset($candidate->Email) ? $candidate->Email : 'N/A';
                $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at) VALUES (?, ?, ?, ?, ?, 'error', ?, NOW())");
                $log_stmt->execute([$candidate_id, $candidate_email, $final_subject, $template, 'Exception occurred - no message sent', $e->getMessage()]);
            }
        }

        if ($error_count === 0) {
            $success_message = "Mailshot sent successfully to $success_count candidates.";
        } else {
            $error_message = "Mailshot partially sent: $success_count succeeded, $error_count failed.";
            if (!empty($error_details)) {
                $error_message .= "\n\nError details:\n" . implode("\n", array_slice($error_details, 0, 5));
                if (count($error_details) > 5) {
                    $error_message .= "\n... and " . (count($error_details) - 5) . " more errors.";
                }
            }
        }
    }
}


function getMailshotOptions($db) {
    $job_titles_query = "SELECT DISTINCT JobTitle FROM _candidates WHERE JobTitle IS NOT NULL AND JobTitle != '' ORDER BY JobTitle";
    $job_titles_stmt = $db->query($job_titles_query);
    $job_titles = $job_titles_stmt->fetchAll(PDO::FETCH_COLUMN);

    $locations_query = "SELECT DISTINCT City FROM _candidates WHERE City IS NOT NULL AND City != '' ORDER BY City";
    $locations_stmt = $db->query($locations_query);
    $locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);

    return [$job_titles, $locations];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_candidates'])) {
    $selected_candidates = $_POST['selected_candidates'];
    $subject = $_POST['subject'];
    $template = $_POST['template'];
    
    $success_message = "Mailshot with subject '" . htmlspecialchars($subject) . "' sent to " . count($selected_candidates) . " candidates successfully!";
}
?>





<?php include "../../includes/head.php"; ?>
<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php"; ?>

    <div class="pc-container" style="margin-left: 280px;">
        <div class="pc-content">
            <?php include "../../includes/breadcrumb.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $mode === 'kpi' ? 'KPI Reporting - Weekly Analytics' : ($mode === 'mailshot' ? 'Mailshot - Candidate Filtering' : 'Candidates - Email Filtering System'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="permission-indicator">
                    <i class="fa fa-shield"></i>
                    <strong>Access Granted:</strong> You have VIEW_CLIENTS permission and can access this candidates page.
                    <?php if ($mode === 'kpi'): ?>
                        You also have VIEW_KPIs permission for analytics.
                    <?php endif; ?>
                </div>

                <div class="mode-switch">
                    <h4 style="margin-bottom: 15px;">Mode Selection</h4>
                    <a href="?mode=candidates" class="mode-button <?php echo $mode === 'candidates' ? 'active' : ''; ?>">
                        <i class="fa fa-users"></i> View Candidates
                    </a>
                    <a href="?mode=mailshot" class="mode-button <?php echo $mode === 'mailshot' ? 'active' : ''; ?>">
                        <i class="fa fa-paper-plane"></i> Create Mailshot
                    </a>
                    <?php 
                    try {
                        $stmt = $db_2->prepare("SELECT COUNT(*) as has_permission FROM userpermissions WHERE user_id = ? AND permission_name = 'VIEW_KPIs'");
                        $stmt->execute([$_SESSION['user_id']]);
                        $has_kpi_permission = $stmt->fetch(PDO::FETCH_ASSOC)['has_permission'] > 0;
                        
                        if ($has_kpi_permission): ?>
                            <a href="?mode=kpi" class="mode-button <?php echo $mode === 'kpi' ? 'active' : ''; ?>">
                                <i class="fa fa-bar-chart"></i> Weekly KPI Search
                            </a>
                        <?php endif;
                    } catch (Exception $e) {}
                    ?>
                </div>

                <h2 style="margin-bottom: 30px; color: #343a40;">
                    <?php 
                    if ($mode === 'kpi') {
                        echo 'KPI Reporting - Weekly Analytics & Tracking';
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
                
                <?php require_once 'kpi_reporting.php'; ?>

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
                    <a href="?mode=candidates&status=all&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>&center_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>" 
                       class="status-tab <?php echo $status_filter === 'all' ? 'active' : ''; ?>">All Candidates</a>
                    <a href="?mode=candidates&status=active&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>&center_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>" 
                       class="status-tab <?php echo $status_filter === 'active' ? 'active' : ''; ?>">Active</a>
                    <a href="?mode=candidates&status=archived&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>&center_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>" 
                       class="status-tab <?php echo $status_filter === 'archived' ? 'active' : ''; ?>">Archived</a>
                    <a href="?mode=candidates&status=inactive&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>&center_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>" 
                       class="status-tab <?php echo $status_filter === 'inactive' ? 'active' : ''; ?>">Inactive</a>
                    <a href="?mode=candidates&status=pending&keyword=<?php echo urlencode($keyword_filter); ?>&location=<?php echo urlencode($location_filter); ?>&position=<?php echo urlencode($position_filter); ?>&center_postcode=<?php echo urlencode($center_postcode); ?>&distance_miles=<?php echo urlencode($distance_miles); ?>" 
                       class="status-tab <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">Pending Compliance</a>
                </div>
                <?php endif; ?>

                <?php
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
                        $q = $db_1->query("SELECT * FROM `candidates` ORDER BY id DESC");
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
                ?>
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

                <?php if ($total_results > 0): ?>
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
                                    <?php if ($mode === 'candidates'): ?>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $n = 1;
                                
                                $createdByMapping = [
                                    "1" => "Chax Shamwana",
                                    "10" => "Millie Brown", 
                                    "11" => "Jay Fuller",
                                    "13" => "Jack Dowler",
                                    "15" => "Alex Lapompe",
                                    "2" => "Alex Lapompe",
                                    "9" => "Jack Dowler"
                                ];
                                
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
                                    $CandidateID = generateUUIDFromID($row->id);
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
                                            <span class="candidate-id"><?php echo substr($CandidateID, 0, 5); ?></span>
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
                                        <?php if ($mode === 'candidates'): ?>
                                        <td><?php echo htmlspecialchars($CreatedBy); ?></td>
                                        <td><?php echo htmlspecialchars($date); ?></td>
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
                                <i class="fa fa-paper-plane"></i> Send Mailshot
                            </button>
                            <span class="text-muted ml-3">
                                <i class="fa fa-info-circle"></i> 
                                This will send emails to all selected candidates
                            </span>
                        </div>
                    </div>
                    </form>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <h5>No candidates found</h5>
                        <p>Try adjusting your search filters or <a href="?mode=<?php echo htmlspecialchars($mode); ?>">clear all filters</a> to see <?php echo $mode === 'mailshot' ? 'candidates for your mailshot' : 'all candidates'; ?>.</p>
                    </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script>
        document.querySelectorAll('.filter-input').forEach(function(input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.form.submit();
                }
            });
        });
        
        <?php if ($mode === 'mailshot'): ?>
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="selected_candidates[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
            
            document.querySelectorAll('input[name="selected_candidates[]"]').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const allCheckboxes = document.querySelectorAll('input[name="selected_candidates[]"]');
                    const checkedCheckboxes = document.querySelectorAll('input[name="selected_candidates[]"]:checked');
                    selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
                });
            });
        }
        <?php endif; ?>
        
        function highlightSearchTerms() {
            const keyword = '<?php echo addslashes($keyword_filter); ?>';
            const location = '<?php echo addslashes($location_filter); ?>';
            const position = '<?php echo addslashes($position_filter); ?>';
            
            if (keyword || location || position) {
                const terms = [keyword, location, position].filter(term => term.length > 0);
                
                terms.forEach(term => {
                    if (term.length > 2) {
                        const regex = new RegExp(`(${term})`, 'gi');
                        document.querySelectorAll('tbody td').forEach(cell => {
                            if (cell.innerHTML.match(regex)) {
                                cell.innerHTML = cell.innerHTML.replace(regex, '<mark>$1</mark>');
                            }
                        });
                    }
                });
            }
        }
        
        document.addEventListener('DOMContentLoaded', highlightSearchTerms);
    </script>
</body>
</html>
<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php"; ?>

    <div class="pc-container" style="margin-left: 280px;">
        <div class="pc-content">
            <?php include "../../includes/breadcrumb.php"; ?>

     

            <!-- <div class="pc-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="filter-section">
                                <form method="GET" action="">
                                    <div class="row filter-row">
                                        <div class="col-md-3">
                                            <label class="filter-label" for="keyword">Keyword</label>
                                            <input type="text" id="keyword" name="keyword" class="filter-input filter-input-sm filter-input-lg filter-input-md filter-input-xs filter-input-xl filter-input-xxl filter-input-xxxl" value="<?php echo htmlspecialchars($keyword_filter); ?>" placeholder="Search by name, email, etc.">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="filter-label" for="location">Location</label>
                                            <input type="text" id="location" name="location" class="filter-input filter-input-sm filter-input-lg filter-input-md filter-input-xs filter-input-xl filter-input-xxl filter-input-xxxl" value="<?php echo htmlspecialchars($location_filter); ?>" placeholder="City or postcode">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="filter-label" for="position">Position</label>
                                            <select id="position" name="position" class="filter-input filter-input-sm filter-input-lg filter-input-md filter-input-xs filter-input-xl filter-input-xxl filter-input-xxxl">
                                                <option value="">All Positions</option>
                                                <?php foreach ($job_titles as $title): ?>
                                                    <option value="<?php echo htmlspecialchars($title); ?>" <?php echo ($title === $position_filter) ? 'selected' : ''; ?>><?php echo htmlspecialchars($title); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 distance-filter">
                                            <label class="filter-label" for="center_postcode">Center Postcode</label>
                                            <input type="text" id="center_postcode" name="center_postcode" class="filter-input distance-input" value="<?php echo htmlspecialchars($center_postcode); ?>" placeholder="Postcode">
                                            <input type="number" id="distance_miles" name="distance_miles" class="filter-input distance-input" value="<?php echo $distance_miles; ?>" placeholder="Miles">
                                            <?php if ($distance_miles > 0): ?>
                                                <span class="distance-badge"><?php echo $distance_miles; ?> miles</span>
                                            <?php endif; ?>
                                        </div> -->

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
   