<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "includes/config.php"; // Ensure this path is correct and config.php defines necessary globals like $LINK, $USERID, $NAME, Notify(), IsCheckPermission(), FormatDate(), DeniedAccess(), $theme

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Added SMTP class for debug output

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname1 = 'broadmead'; // Assuming this is for old candidate data or other related data
$dbname2 = 'broadmead_v3'; // Assuming this is the primary database for current candidates
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

// Get the current mode from GET parameter, default to 'candidates'
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'candidates';

// Initialize messages and logs
$success_message = '';
$error_message = '';
$console_logs = [];

// Process mailshot if mode is 'mailshot' and form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'mailshot') {
    error_log("POST data received for mailshot: " . print_r($_POST, true));

    // Validation
    if (empty($_POST['selected_candidates'])) {
        $error_message = "Please select at least one candidate.";
    } elseif (empty($_POST['subject'])) {
        $error_message = "Email subject is required.";
    } elseif (empty($_POST['template'])) {
        $error_message = "Please select an email template.";
    } else {
        // Decode the JSON string of candidate IDs
        $candidate_ids_json = $_POST['selected_candidates'];
        $candidate_ids = json_decode($candidate_ids_json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_message = "Invalid candidate selection data.";
            error_log("JSON Decode Error: " . json_last_error_msg());
        } else {
            $subject = $_POST['subject'];
            $template = $_POST['template'];

            error_log("Processing mailshot for " . count($candidate_ids) . " candidates");

            // Email templates (ensure these are well-defined)
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

            // Get template details, fallback to default if template not found
            $template_details = $templates[$template] ?? [
                'subject' => $subject, // Use provided subject if template not found
                'body' => "Hello [Name],\n\nThank you for being part of our network.\n\nBest regards,\nThe Recruitment Team"
            ];

            $final_subject = empty($subject) ? $template_details['subject'] : $subject;
            $base_body = $template_details['body'];
            $from_email = "learn@natec.icu";
            $from_name = "Recruitment Team";

            $smtp_host = 'smtp.titan.email';
            $smtp_username = 'learn@natec.icu';
            $smtp_password = '@WhiteDiamond0100';
            $smtp_port = 587;
            $success_count = 0;
            $error_count = 0;
            $error_details = [];

            // Test email configuration (PHPMailer instance for testing connection)
            try {
                $test_mail = new PHPMailer(true);
                $test_mail->isSMTP();
                $test_mail->Host = $smtp_host;
                $test_mail->SMTPAuth = true;
                $test_mail->Username = $smtp_username;
                $test_mail->Password = $smtp_password;
                $test_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $test_mail->Port = $smtp_port;
                $test_mail->SMTPDebug = 0; // Turn off debug for connection test, but log output to console_logs if needed
                $test_mail->Debugoutput = function($str, $level) use (&$console_logs) {
                    $console_logs[] = "SMTP DEBUG (Test): " . trim($str);
                };

                if (!$test_mail->smtpConnect()) {
                    throw new Exception("SMTP connection failed: " . $test_mail->ErrorInfo);
                }
                $test_mail->smtpClose();
                $console_logs[] = "SMTP connection test successful.";

            } catch (Exception $e) {
                $error_message = "SMTP Configuration Error: " . $e->getMessage();
                $console_logs[] = "ERROR: SMTP Configuration failed - " . $e->getMessage();
                error_log("SMTP Error: " . $e->getMessage());
            }

            // Proceed if SMTP test passed and no prior error message
            if (!isset($error_message) || $error_message === '') {
                foreach ($candidate_ids as $candidate_id) {
                    try {
                        error_log("Attempting to send email to candidate ID: " . $candidate_id);

                        // Fetch candidate details from either database
                        $stmt = $db_2->prepare("SELECT Name, Email FROM _candidates WHERE id = ?");
                        $stmt->execute([$candidate_id]);
                        $candidate = $stmt->fetch(PDO::FETCH_OBJ);

                        if (!$candidate) {
                            $stmt = $db_1->prepare("SELECT CONCAT(first_name, ' ', last_name) as Name, email as Email FROM candidates WHERE id = ?");
                            $stmt->execute([$candidate_id]);
                            $candidate = $stmt->fetch(PDO::FETCH_OBJ);
                        }

                        if ($candidate && filter_var($candidate->Email, FILTER_VALIDATE_EMAIL)) {
                            $to_email = $candidate->Email;
                            $to_name = $candidate->Name ?: 'Candidate';

                            error_log("Preparing email for: " . $to_email . " (" . $to_name . ")");

                            $personalized_body = str_replace(
                                ['[Name]', '[LoginLink]', '[NewsletterLink]', '[EventLink]'],
                                [htmlspecialchars($to_name), 'https://broad-mead.com/login', 'https://broad-mead.com/newsletter', 'https://broad-mead.com/events'],
                                $base_body
                            );

                            // Create a new PHPMailer instance for each email to avoid issues with previous recipients
                            $mail = new PHPMailer(true);
                            $mail->isSMTP();
                            $mail->Host = $smtp_host;
                            $mail->SMTPAuth = true;
                            $mail->Username = $smtp_username;
                            $mail->Password = $smtp_password;
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = $smtp_port;
                            $mail->Timeout = 30; // Timeout in seconds
                            // Disable SSL verification for self-signed or invalid certs (use with caution in production)
                            $mail->SMTPOptions = array(
                                'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                )
                            );
                            $mail->setFrom($from_email, $from_name);
                            $mail->addAddress($to_email, $to_name);
                            $mail->addReplyTo($from_email, $from_name);
                            $mail->isHTML(true);
                            $mail->Subject = $final_subject;
                            $mail->Body = nl2br(htmlspecialchars($personalized_body)); // Convert newlines to <br> and HTML escape
                            $mail->AltBody = $personalized_body; // Plain text version

                            if ($mail->send()) {
                                $success_count++;
                                $console_logs[] = "SUCCESS: Email sent to {$to_email} (Candidate ID: {$candidate_id})";
                                error_log("SUCCESS: Email sent to {$to_email}");
                            } else {
                                $error_count++;
                                $error_details[] = "Failed to send to: $to_email - " . $mail->ErrorInfo;
                                $console_logs[] = "ERROR: Failed to send to {$to_email} (Candidate ID: {$candidate_id}) - " . $mail->ErrorInfo;
                                error_log("ERROR: Failed to send to {$to_email} - " . $mail->ErrorInfo);
                            }

                            // Small delay to avoid hitting SMTP rate limits
                            usleep(100000); // 100 milliseconds
                        } else {
                            $candidate_email = $candidate->Email ?? 'N/A';
                            $error_count++;
                            $error_details[] = "Invalid or missing email for candidate ID: $candidate_id (Email: $candidate_email)";
                            $console_logs[] = "ERROR: Invalid or missing email for candidate ID {$candidate_id} (Email: {$candidate_email})";
                            error_log("ERROR: Invalid or missing email for candidate ID {$candidate_id}");
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
}

// Output JavaScript console logs
if (!empty($console_logs)) {
    echo "<script>";
    foreach ($console_logs as $log) {
        echo "console.log('" . addslashes($log) . "');";
    }
    echo "</script>";
}

// Initialize filter variables for candidates/mailshot modes
$keyword_filter = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location_filter = isset($_GET['location']) ? trim($_GET['location']) : '';
$position_filter = isset($_GET['position']) ? trim($_GET['position']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : ($mode === 'mailshot' ? 'active' : 'all');
$center_postcode = isset($_GET['center_postcode']) ? trim($_GET['center_postcode']) : '';
$distance_miles = isset($_GET['distance_miles']) ? (int)$_GET['distance_miles'] : 0;

// Initialize KPI specific variables
$kpi_period = isset($_GET['kpi_period']) ? $_GET['kpi_period'] : 'current_week';
$kpi_start_date_param = isset($_GET['kpi_start_date']) ? $_GET['kpi_start_date'] : '';
$kpi_end_date_param = isset($_GET['kpi_end_date']) ? $_GET['kpi_end_date'] : '';

// Build WHERE conditions for candidate/mailshot filtering
$where_conditions = [];
$params = []; // Parameters for prepared statement

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

// Dummy function for postcode coordinates (replace with real API call)
function getPostcodeCoordinates($postcode) {
    static $postcodeCache = [];

    if (isset($postcodeCache[$postcode])) {
        return $postcodeCache[$postcode];
    }

    // Simulate API call delay and return random coordinates for demonstration
    // In a real application, you would use a postcode API (e.g., Postcodes.io, Google Geocoding API)
    $coordinates = [
        'latitude' => 51.5 + (rand(-100, 100) / 1000), // Random latitude around London
        'longitude' => -0.1 + (rand(-100, 100) / 1000) // Random longitude around London
    ];

    $postcodeCache[$postcode] = $coordinates;
    return $coordinates;
}

// Haversine formula to calculate distance between two lat/lon points
function calculateDistanceBetweenPostcodes($postcode1, $postcode2) {
    $coords1 = getPostcodeCoordinates($postcode1);
    $coords2 = getPostcodeCoordinates($postcode2);

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












// Function to get date range for predefined periods
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
            // $start = new DateTime('first day of this month');
            // $end = new DateTime('last day of this month');
            // break;
        case 'last_month':
            // $start = new DateTime('first day of last month');
            // $end = new DateTime('last day of last month');
            // break;
        case 'current_quarter':
            // $quarter = ceil($today->format('n') / 3);
            // $start = new DateTime($today->format('Y') . '-' . (($quarter - 1) * 3 + 1) . '-01');
            // $end = clone $start;
            // $end->modify('+2 months')->modify('last day of this month');
            // break;
        case 'current_year':
            // $start = new DateTime($today->format('Y') . '-01-01');
            // $end = new DateTime($today->format('Y') . '-12-31');
            // break;
        default: // Default to current week if period is invalid or not set
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

// Function to calculate KPIs based on selected period or custom dates
function calculateKPIs($db, $period, $start_date = null, $end_date = null) {
    $kpis = [];

    try {
        // Determine the date range
        if ($period === 'custom' && $start_date && $end_date) {
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);

            // Validate date range
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

        // Fetch all candidates for the detailed table
        $stmt_all_candidates = $db->prepare("
            SELECT id, Name, Email, JobTitle, Status, CreatedBy, Date 
            FROM _candidates 
            WHERE Date BETWEEN ? AND ? 
            ORDER BY Date DESC
        ");
        $stmt_all_candidates->execute([
            $dateRange['start'] . ' 00:00:00', 
            $dateRange['end'] . ' 23:59:59'
        ]);
        $kpis['detailed_candidates'] = $stmt_all_candidates->fetchAll(PDO::FETCH_ASSOC);

        // Consolidated KPI calculation
        $stmt = $db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN Status = 'active' THEN 1 ELSE 0 END) as active_candidates,
                SUM(CASE WHEN Status = 'inactive' THEN 1 ELSE 0 END) as inactive_candidates,
                SUM(CASE WHEN Status = 'archived' THEN 1 ELSE 0 END) as archived_candidates,
                SUM(CASE WHEN Status = 'pending' THEN 1 ELSE 0 END) as pending_candidates
            FROM _candidates 
            WHERE Date BETWEEN ? AND ?
        ");
        $stmt->execute([
            $dateRange['start'] . ' 00:00:00', 
            $dateRange['end'] . ' 23:59:59'
        ]);
        $counts = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $kpis['total_candidates'] = $counts['total'];
        $kpis['active_candidates'] = $counts['active_candidates'];
        $kpis['inactive_candidates'] = $counts['inactive_candidates'];
        $kpis['archived_candidates'] = $counts['archived_candidates'];
        $kpis['pending_candidates'] = $counts['pending_candidates'];
        $kpis['new_candidates'] = $counts['total']; // Backward compatibility

        // Job Title Stats
        $stmt = $db->prepare("
            SELECT JobTitle, COUNT(*) as count 
            FROM _candidates 
            WHERE Date BETWEEN ? AND ? 
            AND JobTitle IS NOT NULL 
            AND JobTitle != '' 
            GROUP BY JobTitle 
            ORDER BY count DESC
        ");
        $stmt->execute([
            $dateRange['start'] . ' 00:00:00', 
            $dateRange['end'] . ' 23:59:59'
        ]);
        $kpis['job_title_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // City Stats
        $stmt = $db->prepare("
            SELECT City, COUNT(*) as count 
            FROM _candidates 
            WHERE Date BETWEEN ? AND ? 
            AND City IS NOT NULL 
            AND City != '' 
            GROUP BY City 
            ORDER BY count DESC
        ");
        $stmt->execute([
            $dateRange['start'] . ' 00:00:00', 
            $dateRange['end'] . ' 23:59:59'
        ]);
        $kpis['city_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // CreatedBy Stats
        $stmt = $db->prepare("
            SELECT CreatedBy, COUNT(*) as count 
            FROM _candidates 
            WHERE Date BETWEEN ? AND ? 
            GROUP BY CreatedBy 
            ORDER BY count DESC
        ");
        $stmt->execute([
            $dateRange['start'] . ' 00:00:00', 
            $dateRange['end'] . ' 23:59:59'
        ]);
        $kpis['created_by_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Daily Trend
        $stmt = $db->prepare("
            SELECT DATE(Date) as date, COUNT(*) as count 
            FROM _candidates 
            WHERE Date BETWEEN ? AND ? 
            GROUP BY DATE(Date) 
            ORDER BY date
        ");
        $stmt->execute([
            $dateRange['start'] . ' 00:00:00', 
            $dateRange['end'] . ' 23:59:59'
        ]);
        $kpis['daily_trend'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate growth rate
        $previousPeriod = getPreviousPeriodRange($period, $dateRange);
        $stmt = $db->prepare("
            SELECT COUNT(*) as previous_total 
            FROM _candidates 
            WHERE Date BETWEEN ? AND ?
        ");
        $stmt->execute([
            $previousPeriod['start'] . ' 00:00:00', 
            $previousPeriod['end'] . ' 23:59:59'
        ]);
        $previous_total = $stmt->fetch(PDO::FETCH_ASSOC)['previous_total'];

        $kpis['growth_rate'] = ($previous_total > 0) 
            ? round(($kpis['total_candidates'] - $previous_total) / $previous_total * 100, 2)
            : 0;

        $kpis['date_range'] = $dateRange;
        $kpis['previous_date_range'] = $previousPeriod;

    } catch (Exception $e) {
        $kpis['error'] = $e->getMessage();
        error_log("KPI Calculation Error: " . $e->getMessage());
    }

    return $kpis;
}
// Function to get the date range for the period immediately preceding the current range
function getPreviousPeriodRange($period, $currentRange) {
    try {
        $start = new DateTime($currentRange['start']);
        $end = new DateTime($currentRange['end']);

        switch($period) {
            case 'current_week':
            case 'last_week':
                $prevStart = (clone $start)->modify('-1 week');
                $prevEnd = (clone $end)->modify('-1 week');
                break;

            case 'current_month':
            case 'last_month':
                $prevStart = (clone $start)->modify('first day of last month');
                $prevEnd = (clone $prevStart)->modify('last day of this month');
                break;

            case 'current_quarter':
                $prevStart = (clone $start)->modify('-3 months');
                $prevEnd = (clone $prevStart)->modify('+2 months')->modify('last day of this month');
                break;

            case 'current_year':
                $prevStart = (clone $start)->modify('-1 year');
                $prevEnd = (clone $end)->modify('-1 year');
                break;

            case 'custom':
                $interval = $start->diff($end);
                $prevStart = (clone $start)->sub($interval)->modify('-1 day');
                $prevEnd = (clone $end)->sub($interval)->modify('-1 day');
                break;

            default:
                $prevStart = (clone $start)->modify('-1 week');
                $prevEnd = (clone $end)->modify('-1 week');
        }

        return [
            'start' => $prevStart->format('Y-m-d'),
            'end' => $prevEnd->format('Y-m-d')
        ];

    } catch (Exception $e) {
        error_log("Date Range Error: " . $e->getMessage());
        return [
            'start' => date('Y-m-d', strtotime('-1 month')),
            'end' => date('Y-m-d')
        ];
    }
}
// Fetch distinct job titles and locations for mailshot/candidate filters
if ($mode === 'mailshot' || $mode === 'candidates') {
    $job_titles_query = "SELECT DISTINCT JobTitle FROM _candidates WHERE JobTitle IS NOT NULL AND JobTitle != '' ORDER BY JobTitle";
    $job_titles_stmt = $db_2->query($job_titles_query);
    $job_titles = $job_titles_stmt->fetchAll(PDO::FETCH_COLUMN);

    $locations_query = "SELECT DISTINCT City FROM _candidates WHERE City IS NOT NULL AND City != '' ORDER BY City";
    $locations_stmt = $db_2->query($locations_query);
    $locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Calculate KPIs only if mode is 'kpi'
$kpi_data = [];
$kpi_start_date_display = ''; // Initialize for display in input fields
$kpi_end_date_display = '';   // Initialize for display in input fields

// Prepare predefined date ranges for JavaScript
$predefined_date_ranges = [];
$periods_to_calculate = ['current_week', 'last_week', 'current_month', 'last_month', 'current_quarter', 'current_year'];
foreach ($periods_to_calculate as $p) {
    $range = getDateRangeForPeriod($p);
    $predefined_date_ranges[$p] = $range;
}

if ($mode === 'kpi') {
    // Determine the date range for KPI calculation and display
    $calculatedDateRange = getDateRangeForPeriod($kpi_period);

    if ($kpi_period === 'custom' && !empty($kpi_start_date_param) && !empty($kpi_end_date_param)) {
        // If custom period is selected and dates are provided, use them
        $kpi_start_date_display = $kpi_start_date_param;
        $kpi_end_date_display = $kpi_end_date_param;
    } else {
        // Otherwise, use the calculated dates for the selected predefined period
        $kpi_start_date_display = $calculatedDateRange['start'];
        $kpi_end_date_display = $calculatedDateRange['end'];
    }

    // Now calculate the KPIs using the determined dates
    $kpi_data = calculateKPIs($db_2, $kpi_period, $kpi_start_date_display, $kpi_end_date_display);
}

// Mapping for CreatedBy IDs to Names (example, replace with actual user fetching)
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
        /* General styling for mode buttons */
        .mode-switch {
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .mode-button {
            padding: 10px 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mode-button:hover {
            background-color: #e2e6ea;
            color: #000;
        }
        .mode-button.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .mode-button.active:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .mode-button i {
            margin-right: 5px;
        }

        /* Styles for filter sections */
        .filter-section, .kpi-filter-section {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        .filter-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
        }
        .filter-input, .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 1rem;
            margin-bottom: 15px; /* Spacing between inputs */
        }
        .filter-row {
            margin-bottom: 15px;
        }
        .distance-filter {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .distance-filter .filter-input {
            margin-bottom: 0;
        }
        .distance-filter .distance-input {
            flex-grow: 1;
        }
        .distance-filter span {
            white-space: nowrap;
        }
        .btn-primary, .btn-secondary {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: 1px solid #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }

        /* KPI specific styles */
        .kpi-info {
            background-color: #e9f7ef;
            border: 1px solid #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .kpi-info h5 {
            color: #155724;
            margin-bottom: 15px;
        }
        .kpi-info ul {
            list-style-type: disc;
            margin-left: 20px;
            padding-left: 0;
        }
        .kpi-info ul li {
            margin-bottom: 5px;
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .kpi-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            text-align: center;
        }
        .kpi-card h6 {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .kpi-card .kpi-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #007bff;
        }
        .kpi-card .kpi-change {
            font-size: 0.85rem;
            margin-top: 5px;
        }
        .kpi-card .kpi-change.positive {
            color: #28a745;
        }
        .kpi-card .kpi-change.negative {
            color: #dc3545;
        }
        .kpi-card .kpi-change.neutral {
            color: #6c757d;
        }
        .kpi-section-title {
            margin-top: 40px;
            margin-bottom: 20px;
            color: #343a40;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .kpi-table-container {
            margin-top: 20px;
            max-height: 500px; /* Limit height for scrollability */
            overflow-y: auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kpi-table th, .kpi-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
        }
        .kpi-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .kpi-table tbody tr:hover {
            background-color: #f2f2f2;
        }
        .kpi-table tbody tr:last-child td {
            border-bottom: none;
        }
        .kpi-table .badge {
            padding: 0.4em 0.6em;
            border-radius: 0.25rem;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            display: inline-block;
        }
        .kpi-table .badge.bg-success { background-color: #28a745; color: #fff; }
        .kpi-table .badge.bg-warning { background-color: #ffc107; color: #212529; }
        .kpi-table .badge.bg-danger { background-color: #dc3545; color: #fff; }
        .kpi-table .badge.bg-info { background-color: #17a2b8; color: #fff; }

        .error-message {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            white-space: pre-wrap; /* Preserve newlines */
        }
        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success-message i, .error-message i {
            margin-right: 10px;
        }
        /* Styles for date inputs */
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
        /* NEW CSS: Override browser validation styles for disabled date inputs */
        .kpi-filter-section input[type="date"]:disabled:invalid {
            border-color: #ced4da !important; /* Reset border color to default */
            box-shadow: none !important;      /* Remove any red shadow */
            background-image: none !important; /* Remove any error icons (e.g., red X) */
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
                // Assuming IsCheckPermission is defined in config.php and handles user permissions
                $has_kpi_permission = true; // Placeholder: Replace with actual permission check like IsCheckPermission($USERID, "VIEW_KPI_REPORTS");
                if ($has_kpi_permission):
                ?>
                    <a href="?mode=kpi" class="mode-button <?php echo strtolower($mode) === 'kpi' ? 'active' : ''; ?>">
                        <i class="fa fa-bar-chart"></i> Kpi Report
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

                <div class="kpi-filter-section">
                    <h5 style="margin-bottom: 20px; color: #495057;">Select KPI Report Period</h5>
                    <form method="GET" action="">
                        <input type="hidden" name="mode" value="kpi">
                        <div class="row filter-row">
                            <div class="col-md-4">
                                <div class="filter-label">Select Period</div>
                                <select name="kpi_period" id="kpiPeriodSelect" class="form-select" onchange="updateKpiDateInputs()">
                                    <option value="current_week" <?php echo $kpi_period === 'current_week' ? 'selected' : ''; ?>>Current Week</option>
                                    <option value="last_week" <?php echo $kpi_period === 'last_week' ? 'selected' : ''; ?>>Last Week</option>
                                    <option value="current_month" <?php echo $kpi_period === 'current_month' ? 'selected' : ''; ?>>Current Month</option>
                                    <option value="last_month" <?php echo $kpi_period === 'last_month' ? 'selected' : ''; ?>>Last Month</option>
                                    <option value="current_quarter" <?php echo $kpi_period === 'current_quarter' ? 'selected' : ''; ?>>Current Quarter</option>
                                    <option value="current_year" <?php echo $kpi_period === 'current_year' ? 'selected' : ''; ?>>Current Year</option>
                                    <option value="custom" <?php echo $kpi_period === 'custom' ? 'selected' : ''; ?>>Custom Range</option>
                                </select>
                            </div>
                          <div class="col-md-4">
    <div class="filter-label">Start Date</div>
    <input type="date" name="kpi_start_date" id="kpiStartDate" class="filter-input"
           value="<?php echo htmlspecialchars($kpi_start_date_display); ?>">
</div>
<div class="col-md-4">
    <div class="filter-label">End Date</div>
    <input type="date" name="kpi_end_date" id="kpiEndDate" class="filter-input"
           value="<?php echo htmlspecialchars($kpi_end_date_display); ?>">
</div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- <?php if (isset($kpi_data['error'])): ?>
                    <div class="error-message">
                        <i class="fa fa-exclamation-triangle"></i> KPI Report Error: <?php echo htmlspecialchars($kpi_data['error']); ?>
                    </div>
                <!-- <?php elseif (!empty($kpi_data)): ?>
                    <h4 class="kpi-section-title">KPI Summary
                        (<?php echo htmlspecialchars($kpi_data['date_range']['start']); ?> to
                        <?php echo htmlspecialchars($kpi_data['date_range']['end']); ?>)
                    </h4> --> -->
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <h6>Total Candidates (Period)</h6>
                            <div class="kpi-value"><?php echo $kpi_data['total_candidates']; ?></div>
                            <div class="kpi-change <?php echo $kpi_data['growth_rate'] >= 0 ? 'positive' : 'negative'; ?>">
                                <?php echo $kpi_data['growth_rate']; ?>% vs Previous Period
                            </div>
                            <small class="text-muted">(<?php echo htmlspecialchars($kpi_data['previous_date_range']['start']); ?> to <?php echo htmlspecialchars($kpi_data['previous_date_range']['end']); ?>)</small>
                        </div>
                        <div class="kpi-card">
                            <h6>Active Candidates</h6>
                            <div class="kpi-value"><?php echo $kpi_data['active_candidates']; ?></div>
                        </div>
                        <div class="kpi-card">
                            <h6>Inactive Candidates</h6>
                            <div class="kpi-value"><?php echo $kpi_data['inactive_candidates']; ?></div>
                        </div>
                        <div class="kpi-card">
                            <h6>Archived Candidates</h6>
                            <div class="kpi-value"><?php echo $kpi_data['archived_candidates']; ?></div>
                        </div>
                        <div class="kpi-card">
                            <h6>Pending Candidates</h6>
                            <div class="kpi-value"><?php echo $kpi_data['pending_candidates']; ?></div>
                        </div>
                    </div>

                    <h4 class="kpi-section-title">Candidate Status Distribution</h4>
                    <div class="kpi-grid">
                        <?php
                        $total_status_candidates = $kpi_data['active_candidates'] + $kpi_data['inactive_candidates'] + $kpi_data['archived_candidates'] + $kpi_data['pending_candidates'];
                        $status_types = [
                            'Active' => $kpi_data['active_candidates'],
                            'Inactive' => $kpi_data['inactive_candidates'],
                            'Archived' => $kpi_data['archived_candidates'],
                            'Pending' => $kpi_data['pending_candidates']
                        ];
                        foreach ($status_types as $status_name => $count):
                            $percentage = $total_status_candidates > 0 ? round(($count / $total_status_candidates) * 100, 2) : 0;
                        ?>
                            <div class="kpi-card">
                                <h6><?php echo htmlspecialchars($status_name); ?></h6>
                                <div class="kpi-value"><?php echo $count; ?></div>
                                <div class="text-muted"><?php echo $percentage; ?>%</div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h4 class="kpi-section-title">Top Job Titles</h4>
                    <div class="kpi-table-container">
                        <table class="kpi-table">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['job_title_stats'])): ?>
                                    <?php foreach ($kpi_data['job_title_stats'] as $stat): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($stat['JobTitle']); ?></td>
                                            <td><?php echo $stat['count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-center text-muted">No job title data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="kpi-section-title">Top Locations</h4>
                    <div class="kpi-table-container">
                        <table class="kpi-table">
                            <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['city_stats'])): ?>
                                    <?php foreach ($kpi_data['city_stats'] as $stat): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($stat['City']); ?></td>
                                            <td><?php echo $stat['count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-center text-muted">No location data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="kpi-section-title">Candidates Created By</h4>
                    <div class="kpi-table-container">
                        <table class="kpi-table">
                            <thead>
                                <tr>
                                    <th>Recruiter</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['created_by_stats'])): ?>
                                    <?php foreach ($kpi_data['created_by_stats'] as $stat): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($createdByMapping[$stat['CreatedBy']] ?? 'Unknown User'); ?></td>
                                            <td><?php echo $stat['count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-center text-muted">No 'Created By' data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="kpi-section-title">Daily Trend of New Candidates</h4>
                    <div class="kpi-table-container">
                        <table class="kpi-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>New Candidates</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['daily_trend'])): ?>
                                    <?php foreach ($kpi_data['daily_trend'] as $trend): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($trend['date']); ?></td>
                                            <td><?php echo $trend['count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-center text-muted">No daily trend data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="kpi-section-title">Detailed Candidate List (<?php echo htmlspecialchars($kpi_data['date_range']['start']); ?> to <?php echo htmlspecialchars($kpi_data['date_range']['end']); ?>)</h4>
                    <div class="kpi-table-container">
                        <table class="kpi-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kpi_data['detailed_candidates'])): ?>
                                    <?php foreach ($kpi_data['detailed_candidates'] as $candidate): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($candidate['id']); ?></td>
                                            <td><?php htmlspecialchars($candidate['Name']); ?></td>
                                            <td><?php htmlspecialchars($candidate['Email']); ?></td>
                                            <td><?php htmlspecialchars($candidate['JobTitle']); ?></td>
                                            <td>
                                                <?php
                                                $status_class = '';
                                                switch (strtolower($candidate['Status'])) {
                                                    case 'active': $status_class = 'bg-success'; break;
                                                    case 'archived': $status_class = 'bg-warning'; break;
                                                    case 'inactive': $status_class = 'bg-danger'; break;
                                                    case 'pending': $status_class = 'bg-info'; break;
                                                    default: $status_class = 'bg-secondary'; break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($candidate['Status']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($createdByMapping[$candidate['CreatedBy']] ?? 'Unknown User'); ?></td>
                                            <td><?php echo FormatDate($candidate['Date']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center text-muted">No detailed candidate data for this period.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            <?php elseif (isset($success_message)): ?>
                <div class="success-message">
                    <i class="fa fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php elseif (isset($error_message)): ?>
                <div class="error-message">
                    <i class="fa fa-exclamation-triangle"></i> <?php echo nl2br(htmlspecialchars($error_message)); ?>
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
                        <?php else: /* mode === 'mailshot' */ ?>
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
                                    <div class="filter-label">Status</div>
                                    <select name="status" class="filter-input">
                                        <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Statuses</option>
                                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="archived" <?php echo $status_filter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Apply Filters</button>
                                <a href="?mode=<?php echo htmlspecialchars($mode); ?>" class="btn btn-secondary"><i class="ti ti-refresh"></i> Clear Filters</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive dt-responsive">
                    <?php if (IsCheckPermission($USERID, "VIEW_CANDIDATES")): // Assuming a permission check function ?>
                        <form method="POST" id="mailshotFormCandidates">
                            <input type="hidden" name="mode" value="mailshot">
                            <table class="table table-bordered" id="candidatesTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            <input type="checkbox" id="selectAllCandidates" class="form-check-input">
                                            <label for="selectAllCandidates" class="form-check-label ms-1">Select All</label>
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Job Title</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Prepare the base query for candidates
                                    $candidates_query = "SELECT id, Name, Email, JobTitle, Status, CreatedBy, Date, Postcode FROM _candidates " . $where_clause . " ORDER BY Date DESC";
                                    $stmt = $db_2->prepare($candidates_query);

                                    // Bind parameters for candidate filters
                                    foreach ($params as $key => $value) {
                                        $stmt->bindValue($key, $value);
                                    }
                                    $stmt->execute();
                                    $n = 1;
                                    $filtered_candidates_count = 0;
                                    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                                        $display_row = true;

                                        // Apply distance filter in PHP if postcode is available
                                        if (!empty($center_postcode) && $distance_miles > 0 && !empty($row->Postcode)) {
                                            try {
                                                $distance = calculateDistanceBetweenPostcodes($center_postcode, $row->Postcode);
                                                if ($distance > $distance_miles) {
                                                    $display_row = false;
                                                }
                                            } catch (Exception $e) {
                                                // Log error but don't stop display
                                                error_log("Postcode distance calculation error: " . $e->getMessage());
                                            }
                                        }

                                        if ($display_row) {
                                            $filtered_candidates_count++;
                                            $CreatedBy = $createdByMapping[$row->CreatedBy] ?? 'Unknown User';
                                            $status_text = htmlspecialchars($row->Status);
                                            $status_class = '';
                                            switch (strtolower($row->Status)) {
                                                case 'active': $status_class = 'bg-success'; break;
                                                case 'archived': $status_class = 'bg-warning'; break;
                                                case 'inactive': $status_class = 'bg-danger'; break;
                                                case 'pending': $status_class = 'bg-info'; break;
                                                default: $status_class = 'bg-secondary'; break;
                                            }
                                    ?>
                                            <tr class="candidate-row">
                                                <td><?php echo $n++; ?></td>
                                                <td>
                                                    <input class="form-check-input candidate-checkbox"
                                                        type="checkbox"
                                                        name="selected_candidates[]"
                                                        value="<?php echo htmlspecialchars($row->id); ?>"
                                                        data-name="<?php echo htmlspecialchars($row->Name); ?>"
                                                        data-email="<?php echo htmlspecialchars($row->Email); ?>"
                                                        onchange="updateMailshotButton()">
                                                </td>
                                                <td><?php echo htmlspecialchars($row->Name); ?></td>
                                                <td><?php htmlspecialchars($row->Email); ?></td>
                                                <td><?php htmlspecialchars($row->JobTitle); ?></td>
                                                <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                                <td><?php htmlspecialchars($CreatedBy); ?></td>
                                                <td><?php echo FormatDate($row->Date); ?></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="edit_candidate.php?id=<?php echo htmlspecialchars($row->id); ?>">
                                                                <span class="text-info">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                        <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                    </svg>
                                                                </span>
                                                                Edit
                                                            </a>
                                                            <?php if (IsCheckPermission($USERID, "DELETE_CANDIDATE")): // Assuming permission check for delete ?>
                                                                <a class="dropdown-item delete-candidate-btn" href="#"
                                                                    data-id="<?php echo htmlspecialchars($row->id); ?>"
                                                                    data-name="<?php echo htmlspecialchars($row->Name); ?>"
                                                                    data-bs-toggle="modal" data-bs-target="#deleteCandidateModal">
                                                                    <span class="text-danger">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                            <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                        </svg>
                                                                    </span>
                                                                    Delete
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    if ($filtered_candidates_count === 0):
                                    ?>
                                        <tr><td colspan="9" class="text-center text-muted">No candidates found matching your criteria.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <?php if ($mode === 'mailshot'): ?>
                                <div class="mailshot-actions mt-3">
                                    <button type="button" class="btn btn-primary" id="openMailshotModalBtn" style="display: none;" data-bs-toggle="modal" data-bs-target="#mailshotModal">
                                        Send Mailshot (<span id="selectedCandidatesCount">0</span> selected)
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <?php DeniedAccess(); // Assuming DeniedAccess() function exists ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Candidate Modal (for candidates table) -->
    <div class="modal fade" id="deleteCandidateModal" tabindex="-1" aria-labelledby="deleteCandidateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCandidateModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="mode" value="candidates">
                    <input type="hidden" name="delete_candidate_id" id="deleteCandidateId">
                    <div class="modal-body">
                        <p>Are you sure you want to delete candidate <strong id="candidateNameToDelete"></strong>? This action cannot be undone.</p>
                        <div class="mb-3">
                            <label for="deleteReason" class="form-label">Reason for deletion:</label>
                            <input type="text" class="form-control" id="deleteReason" name="reason" required placeholder="e.g., Duplicate entry, requested removal">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_candidate" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Mailshot Modal -->
    <div class="modal fade" id="mailshotModal" tabindex="-1" aria-labelledby="mailshotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mailshotModalLabel">Send Mailshot to Selected Candidates</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="?mode=mailshot">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Selected Candidates (<span id="modalSelectedCandidatesCount">0</span>):</label>
                            <div id="selectedCandidatesList" class="border rounded p-2 bg-light" style="max-height: 150px; overflow-y: auto;">
                                <!-- List of selected candidates will be populated here by JS -->
                            </div>
                            <input type="hidden" name="selected_candidates" id="mailshotSelectedCandidateIds">
                        </div>
                        <div class="mb-3">
                            <label for="mailshotSubject" class="form-label">Subject:</label>
                            <input type="text" class="form-control" id="mailshotSubject" name="subject" required placeholder="Enter email subject">
                        </div>
                        <div class="mb-3">
                            <label for="mailshotTemplate" class="form-label">Select Template:</label>
                            <select class="form-select" id="mailshotTemplate" name="template" onchange="loadMailshotTemplate()">
                                <option value="">Custom Message</option>
                                <option value="job_alert">Job Alert</option>
                                <option value="newsletter">Newsletter</option>
                                <option value="event_invitation">Event Invitation</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="welcome">Welcome</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mailshotMessage" class="form-label">Message:</label>
                            <textarea class="form-control" id="mailshotMessage" name="message" rows="8" required placeholder="Enter your message here. Use [Name] to personalize the message."></textarea>
                            <small class="text-muted">Tip: Use [Name] in your message to automatically insert the candidate's name.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="send_mailshot" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send Mailshot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

<?php include "../../includes/js.php"; ?>
<script>
    // PHP-generated predefined date ranges for JavaScript
    const predefinedDateRanges = <?php echo json_encode($predefined_date_ranges); ?>;

    // JavaScript for KPI date inputs
    function updateKpiDateInputs() {
        const periodSelect = document.getElementById('kpiPeriodSelect');
        const startDateInput = document.getElementById('kpiStartDate');
        const endDateInput = document.getElementById('kpiEndDate');
        const selectedPeriod = periodSelect.value;

        if (selectedPeriod === 'custom') {
            startDateInput.removeAttribute('disabled');
            endDateInput.removeAttribute('disabled');
            // When switching to custom, keep current values or let user input.
            // No explicit clearing here, as user might have pre-filled.
        } else {
            startDateInput.setAttribute('disabled', 'disabled');
            endDateInput.setAttribute('disabled', 'disabled');

            // Populate dates for predefined periods from the PHP-generated object
            if (predefinedDateRanges[selectedPeriod]) {
                startDateInput.value = predefinedDateRanges[selectedPeriod].start;
                endDateInput.value = predefinedDateRanges[selectedPeriod].end;
            } else {
                // Fallback: This should ideally not be hit if PHP correctly generates all predefined ranges
                startDateInput.value = '';
                endDateInput.value = '';
            }
        }
    }

    // Call on page load to set initial state and populate dates
    document.addEventListener('DOMContentLoaded', function() {
        updateKpiDateInputs();
    });


    // JavaScript for Mailshot functionality
    let selectedCandidateData = []; // Stores {id, name, email} for selected candidates

    function updateMailshotButton() {
        const checkboxes = document.querySelectorAll('.candidate-checkbox:checked');
        selectedCandidateData = []; // Clear previous selections

        checkboxes.forEach(checkbox => {
            selectedCandidateData.push({
                id: checkbox.value,
                name: checkbox.dataset.name,
                email: checkbox.dataset.email
            });
        });

        const selectedCountSpan = document.getElementById('selectedCandidatesCount');
        const openMailshotModalBtn = document.getElementById('openMailshotModalBtn');
        const selectAllCheckbox = document.getElementById('selectAllCandidates');
        const allVisibleCheckboxes = document.querySelectorAll('.candidate-row .candidate-checkbox'); // Get all checkboxes, visible or not

        selectedCountSpan.textContent = selectedCandidateData.length;

        if (selectedCandidateData.length > 0) {
            openMailshotModalBtn.style.display = 'inline-block';
        } else {
            openMailshotModalBtn.style.display = 'none';
        }

        // Update "Select All" checkbox state
        let allChecked = true;
        if (allVisibleCheckboxes.length > 0) {
            allVisibleCheckboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });
        } else {
            allChecked = false; // No checkboxes to select
        }
        selectAllCheckbox.checked = allChecked;
    }

    // Toggle all checkboxes based on "Select All"
    document.getElementById('selectAllCandidates').addEventListener('change', function() {
        const isChecked = this.checked;
        const allCheckboxes = document.querySelectorAll('.candidate-checkbox');
        allCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateMailshotButton();
    });

    // Event listener for opening the mailshot modal
    document.getElementById('openMailshotModalBtn').addEventListener('click', function() {
        const modalSelectedCountSpan = document.getElementById('modalSelectedCandidatesCount');
        const selectedCandidatesListDiv = document.getElementById('selectedCandidatesList');
        const mailshotSelectedCandidateIdsInput = document.getElementById('mailshotSelectedCandidateIds');

        modalSelectedCountSpan.textContent = selectedCandidateData.length;
        selectedCandidatesListDiv.innerHTML = ''; // Clear previous list

        if (selectedCandidateData.length > 0) {
            const ul = document.createElement('ul');
            ul.classList.add('list-unstyled', 'mb-0');
            selectedCandidateData.forEach(candidate => {
                const li = document.createElement('li');
                li.textContent = `${candidate.name} (${candidate.email})`;
                ul.appendChild(li);
            });
            selectedCandidatesListDiv.appendChild(ul);
        } else {
            selectedCandidatesListDiv.innerHTML = '<p class="text-muted">No candidates selected.</p>';
        }

        // Populate the hidden input with selected candidate IDs as a JSON string
        mailshotSelectedCandidateIdsInput.value = JSON.stringify(selectedCandidateData.map(c => c.id));
    });

    // Function to load mailshot template content
    function loadMailshotTemplate() {
        const templateSelect = document.getElementById('mailshotTemplate');
        const messageTextarea = document.getElementById('mailshotMessage');
        const subjectInput = document.getElementById('mailshotSubject');

        const templates = {
            'job_alert': {
                subject: 'New Job Opportunities Matching Your Profile',
                body: "Hello [Name],\n\nWe have new job opportunities that match your profile. Log in to your account to view them:\n\nhttps://broad-mead.com/login\n\nBest regards,\nThe Recruitment Team"
            },
            'newsletter': {
                subject: 'Our Latest Industry Insights',
                body: "Hello [Name],\n\nCheck out our latest newsletter with industry insights and job tips:\n\nhttps://broad-mead.com/newsletter\n\nBest regards,\nThe Recruitment Team"
            },
            'event_invitation': {
                subject: 'Invitation to Recruitment Event',
                body: "Hello [Name],\n\nYou are invited to our upcoming recruitment event. Please RSVP here:\n\nhttps://broad-mead.com/events\n\nBest regards,\nThe Recruitment Team"
            },
            'follow_up': {
                subject: 'Following Up on Your Application',
                body: "Hello [Name],\n\nFollowing up on your recent application. Any updates?\n\nBest regards,\nThe Recruitment Team"
            },
            'welcome': {
                subject: 'Welcome to Our Candidate Network',
                body: "Hello [Name],\n\nWelcome to our candidate database! We will contact you when we find a match.\n\nBest regards,\nThe Recruitment Team"
            }
        };

        const selectedTemplate = templateSelect.value;
        if (selectedTemplate && templates[selectedTemplate]) {
            subjectInput.value = templates[selectedTemplate].subject;
            messageTextarea.value = templates[selectedTemplate].body;
        } else {
            // If "Custom Message" or no template selected, clear fields
            subjectInput.value = '';
            messageTextarea.value = '';
        }
    }

    // Initial update of mailshot button state on page load
    document.addEventListener('DOMContentLoaded', updateMailshotButton);

    // Handle delete candidate button click to populate modal
    document.querySelectorAll('.delete-candidate-btn').forEach(button => {
        button.addEventListener('click', function() {
            const candidateId = this.dataset.id;
            const candidateName = this.dataset.name;

            document.getElementById('deleteCandidateId').value = candidateId;
            document.getElementById('candidateNameToDelete').textContent = candidateName;
        });
    });

    // PHP's error_log messages are echoed as console.log in the PHP script block at the top.
    // This ensures they appear in the browser's console for debugging.
</script>
</html>
