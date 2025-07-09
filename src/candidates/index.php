<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/login ");
}


//todo: Enabble PRG Pattern
//todo: Send to DB
//todo: Send emails


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

// Email Configuration Class
function sendTitanMail($to, $subject, $body, $fromEmail, $fromName = 'Recruitment Team') {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'learn@natec.icu';
        $mail->Password = '@WhiteDiamond0100'; //TODO: Replace with your actual password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);
        $mail->addReplyTo('info@nocturnalrecruitment.co.uk', 'Information');
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
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


    }} else {   }

        

       foreach ($candidate_ids as $candidate_id) {
    try {
        // 1. First fetch candidate data
        $stmt = $db_2->prepare("SELECT Name, Email FROM _candidates WHERE id = ?");
        $stmt->execute([$candidate_id]);
        $candidate = $stmt->fetch(PDO::FETCH_OBJ);


        if (!$candidate) {
            $stmt = $db_1->prepare("SELECT CONCAT(first_name, ' ', last_name) as Name, email as Email FROM candidates WHERE id = ?");
            $stmt->execute([$candidate_id]);
            $candidate = $stmt->fetch(PDO::FETCH_OBJ);
        }

        // 2. Validate candidate and email
        if ($candidate && filter_var($candidate->Email, FILTER_VALIDATE_EMAIL)) {
            $to = $candidate->Email;
            $name = $candidate->Name ?: 'Candidate';

            // 3. Personalize email content
            $personalized_body = str_replace(
                ['[Name]', '[LoginLink]', '[NewsletterLink]', '[EventLink]'],
                [$name, 'https://broad-mead.com/login', 'https://broad-mead.com/newsletter', 'https://broad-mead.com/events'],
                $base_body
            );

            // 4. FIRST log to database with 'processing' status
            $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs 
                (candidate_id, email, subject, template, body, status, error, sent_at, smtp_response) 
                VALUES (?, ?, ?, ?, ?, 'processing', NULL, NOW(), 'Pending dispatch')");
            $log_stmt->execute([
                $candidate_id, 
                $to, 
                $final_subject, 
                $template, 
                $personalized_body
            ]);
            $mailshot_log_id = $db_2->lastInsertId();

            // 5. NOW attempt to send the email
            $result = sendTitanMail($to, $final_subject, $personalized_body, $from_email);

            // 6. Update log based on send result
            if ($result === true) {
                $update_stmt = $db_2->prepare("UPDATE mailshot_logs 
                    SET status = 'sent', smtp_response = 'SMTP accepted for delivery'
                    WHERE id = ?");
                $update_stmt->execute([$mailshot_log_id]);
                $success_count++;
            } else {
                $error_message = is_string($result) ? $result : 'Unknown error';
                $update_stmt = $db_2->prepare("UPDATE mailshot_logs 
                    SET status = 'failed', error = ?, smtp_response = ?
                    WHERE id = ?");
                $update_stmt->execute([$error_message, $error_message, $mailshot_log_id]);
                $error_count++;
                $error_details[] = "Failed to send to: $to - $error_message";
            }
        } else {
            // Invalid email case - still log it
            $candidate_email = $candidate->Email ?? 'N/A';
            $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs 
                (candidate_id, email, subject, template, body, status, error, sent_at, smtp_response) 
                VALUES (?, ?, ?, ?, ?, 'invalid', 'Invalid email address', NOW(), 'No send attempted')");
            $log_stmt->execute([
                $candidate_id, 
                $candidate_email, 
                $final_subject, 
                $template, 
                'Email validation failed - no message sent'
            ]);
            $error_count++;
            $error_details[] = "Invalid email for candidate ID: $candidate_id (Email: $candidate_email)";
        }
    } catch (Exception $e) {
        // Log exceptions with candidate data if available
        $candidate_email = isset($candidate) && isset($candidate->Email) ? $candidate->Email : 'N/A';
        $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs 
            (candidate_id, email, subject, template, body, status, error, sent_at, smtp_response) 
            VALUES (?, ?, ?, ?, ?, 'error', ?, NOW(), 'Exception occurred')");
        $log_stmt->execute([
            $candidate_id, 
            $candidate_email, 
            $final_subject, 
            $template, 
            'Exception occurred - no message sent', 
            $e->getMessage()
        ]);
        $error_count++;
        $error_details[] = "Error processing candidate ID: $candidate_id - " . $e->getMessage();
    }
}

// Final status message
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

function checkUserPermission($required_permission) {
    // Check if user is logged in
    // if (!isset($_SESSION['user_id'])) {
    //     header('Location: login.php');
    //     exit();
    // }


    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'broadmead_v3'; 
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as has_permission 
            FROM userpermissions 
            WHERE user_id = ? AND permission_name = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $required_permission]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['has_permission'] == 0) {
            header('HTTP/1.0 403 Forbidden');
            echo "<div style='text-align: center; margin-top: 50px;'>
                    <h2>Access Denied</h2>
                    <p>You don't have permission to view this page.</p>
                    <p>Required permission: $required_permission</p>
                    <a href='dashboard.php'>Return to Dashboard</a>
                  </div>";
            exit();
        }
        
        return true;
        
    } catch (PDOException $e) {
        // error_log("Permission check error: " . $e->getMessage());
        // header('HTTP/1.0 500 Internal Server Error');
        // echo "<div style='text-align: center; margin-top: 50px;'>
        //         <h2>System Error</h2>
        //         <p>Unable to verify permissions. Please try again later.</p>
        //       </div>";
        // exit();
    }
}

checkUserPermission('VIEW_CLIENTS');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname1 = 'broadmead';
$dbname2 = 'broadmead_v3';
$dsn1 = 'mysql:host=' . $host . ';dbname=' . $dbname1;
$dsn2 = 'mysql:host=' . $host . ';dbname=' . $dbname2;

try {
    $db_1 = new PDO($dsn1, $user, $password);
    $db_1->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_2 = new PDO($dsn2, $user, $password);
    $db_2->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<b>Error: </b> " . $e->getMessage();
    exit;
}

$ClientKeyID = "5WEMfHw2aD2C35j8VsVmSQkpzZ2BI2dpqe8wLfqTmQYHPbnrBh";

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'candidates';

if ($mode === 'kpi') {
    checkUserPermission('VIEW_KPIs');
}

$keyword_filter = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location_filter = isset($_GET['location']) ? trim($_GET['location']) : '';
$position_filter = isset($_GET['position']) ? trim($_GET['position']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : ($mode === 'mailshot' ? 'active' : 'all');

$center_postcode = isset($_GET['center_postcode']) ? trim($_GET['center_postcode']) : '';
$distance_miles = isset($_GET['distance_miles']) ? (int)$_GET['distance_miles'] : 0;

$kpi_period = isset($_GET['kpi_period']) ? $_GET['kpi_period'] : 'current_week';
$kpi_metric = isset($_GET['kpi_metric']) ? $_GET['kpi_metric'] : 'overview';
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

// function generateUUID($length = 50) {
//     $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
//     $charactersLength = strlen($charset);
//     $randomString = '';
//     for ($i = 0; $i < $length; $i++) {
//         $randomString .= $charset[random_int(0, $charactersLength - 1)];
//     }
//     return $randomString;
// }

// function generateUUIDFromID($id) {
//     $hash = md5($id);
//     $uuid = sprintf(
//         '%08s-%04s-%04s-%04s-%12s',
//         substr($hash, 0, 8),
//         substr($hash, 8, 4),
//         substr($hash, 12, 4),
//         substr($hash, 16, 4),
//         substr($hash, 20, 12)
//     );
//     return $uuid;
// }

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
    
    $earthRadius = 3959; // in miles
    
    $lat1 = deg2rad($coords1['latitude']);
    $lon1 = deg2rad($coords1['longitude']);
    $lat2 = deg2rad($coords2['latitude']);
    $lon2 = deg2rad($coords2['longitude']);
    
    $latDelta = $lat2 - $lat1;
    $lonDelta = $lon2 - $lon1;
    
    $a = sin($latDelta/2) * sin($latDelta/2) +
         cos($lat1) * cos($lat2) * 
         sin($lonDelta/2) * sin($lonDelta/2);
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
    if ($start_date && $end_date) {
        $dateRange = ['start' => $start_date, 'end' => $end_date];
    } else {
        $dateRange = getDateRangeForPeriod($period);
    }
    
    $kpis = [];

    
    
    try {
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
        
        $stmt = $db->prepare("SELECT JobTitle, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? AND JobTitle IS NOT NULL GROUP BY JobTitle ORDER BY count DESC LIMIT 5");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['top_job_titles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $db->prepare("SELECT City, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? AND City IS NOT NULL GROUP BY City ORDER BY count DESC LIMIT 5");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['top_cities'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_candidates'])) {
//     $selected_candidates = $_POST['selected_candidates'];
//     $subject = $_POST['subject'];
//     $template = $_POST['template'];
    
//     $success_message = "Mailshot with subject '" . htmlspecialchars($subject) . "' sent to " . count($selected_candidates) . " candidates successfully!";
// }

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
    $stmt = $db_2->prepare("SELECT COUNT(*) as has_permission FROM userpermissions WHERE UserID = ? AND permission = 'VIEW_KPIs'");
    $stmt->execute([$_SESSION['UserID']]);
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

</html>