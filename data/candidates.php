<?php
session_start();

require_once 'sessions.php';

// Constants
$ClientKeyID = "5WEMfHw2aD2C35j8VsVmSQkpzZ2BI2dpqe8wLfqTmQYHPbnrBh";

// Determine the mode (candidates view, mailshot mode, or KPI reporting)
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'candidates';

// Additional permission checks for specific modes
if ($mode === 'kpi') {
    checkUserPermission('VIEW_KPIs');
}

// Get filter parameters
$keyword_filter = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location_filter = isset($_GET['location']) ? trim($_GET['location']) : '';
$position_filter = isset($_GET['position']) ? trim($_GET['position']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : ($mode === 'mailshot' ? 'active' : 'all');

// Distance filter parameters
$center_postcode = isset($_GET['center_postcode']) ? trim($_GET['center_postcode']) : '';
$distance_miles = isset($_GET['distance_miles']) ? (int)$_GET['distance_miles'] : 0;

// KPI filter parameters
$kpi_period = isset($_GET['kpi_period']) ? $_GET['kpi_period'] : 'current_week';
$kpi_metric = isset($_GET['kpi_metric']) ? $_GET['kpi_metric'] : 'overview';
$kpi_start_date = isset($_GET['kpi_start_date']) ? $_GET['kpi_start_date'] : '';
$kpi_end_date = isset($_GET['kpi_end_date']) ? $_GET['kpi_end_date'] : '';

// Build WHERE clause based on filters
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

// Utility Functions
function generateUUID($length = 50) {
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($charset);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $charset[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateUUIDFromID($id) {
    $hash = md5($id);
    $uuid = sprintf(
        '%08s-%04s-%04s-%04s-%12s',
        substr($hash, 0, 8),
        substr($hash, 8, 4),
        substr($hash, 12, 4),
        substr($hash, 16, 4),
        substr($hash, 20, 12)
    );
    return $uuid;
}

function getPostcodeCoordinates($postcode) {
    static $postcodeCache = [];
    
    if (isset($postcodeCache[$postcode])) {
        return $postcodeCache[$postcode];
    }
    
    // Simplified coordinates - replace with real API in production
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

// KPI Functions
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
        // Total Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM _candidates WHERE Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['total_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // New Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as new_candidates FROM _candidates WHERE Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['new_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['new_candidates'];
        
        // Active Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as active FROM _candidates WHERE Status = 'active' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['active_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
        
        // Inactive Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as inactive FROM _candidates WHERE Status = 'inactive' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['inactive_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['inactive'];
        
        // Archived Candidates
        $stmt = $db->prepare("SELECT COUNT(*) as archived FROM _candidates WHERE Status = 'archived' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['archived_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['archived'];
        
        // Pending Compliance
        $stmt = $db->prepare("SELECT COUNT(*) as pending FROM _candidates WHERE Status = 'pending' AND Date BETWEEN ? AND ?");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['pending_candidates'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending'];
        
        // Top Job Titles
        $stmt = $db->prepare("SELECT JobTitle, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? AND JobTitle IS NOT NULL GROUP BY JobTitle ORDER BY count DESC LIMIT 5");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['top_job_titles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Top Cities
        $stmt = $db->prepare("SELECT City, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? AND City IS NOT NULL GROUP BY City ORDER BY count DESC LIMIT 5");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['top_cities'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Created By Stats
        $stmt = $db->prepare("SELECT CreatedBy, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? GROUP BY CreatedBy ORDER BY count DESC");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['created_by_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Daily Registration Trend
        $stmt = $db->prepare("SELECT DATE(Date) as date, COUNT(*) as count FROM _candidates WHERE Date BETWEEN ? AND ? GROUP BY DATE(Date) ORDER BY date");
        $stmt->execute([$dateRange['start'] . ' 00:00:00', $dateRange['end'] . ' 23:59:59']);
        $kpis['daily_trend'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate growth rate
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

// Handle mailshot submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_candidates'])) {
    $selected_candidates = $_POST['selected_candidates'];
    $subject = $_POST['subject'];
    $template = $_POST['template'];
    
    $success_message = "Mailshot with subject '" . htmlspecialchars($subject) . "' sent to " . count($selected_candidates) . " candidates successfully!";
}

// Get all available job titles and locations for dropdowns (for mailshot mode)
if ($mode === 'mailshot') {
    $job_titles_query = "SELECT DISTINCT JobTitle FROM _candidates WHERE JobTitle IS NOT NULL AND JobTitle != '' ORDER BY JobTitle";
    $job_titles_stmt = $db_2->query($job_titles_query);
    $job_titles = $job_titles_stmt->fetchAll(PDO::FETCH_COLUMN);

    $locations_query = "SELECT DISTINCT City FROM _candidates WHERE City IS NOT NULL AND City != '' ORDER BY City";
    $locations_stmt = $db_2->query($locations_query);
    $locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Calculate KPIs if in KPI mode
$kpi_data = [];
if ($mode === 'kpi') {
    $kpi_data = calculateKPIs($db_2, $kpi_period, $kpi_start_date, $kpi_end_date);
}
?>
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
                <!-- Permission Indicator -->
                <div class="permission-indicator">
                    <i class="fa fa-shield"></i>
                    <strong>Access Granted:</strong> You have VIEW_CLIENTS permission and can access this candidates page.
                    <?php if ($mode === 'kpi'): ?>
                        You also have VIEW_KPIs permission for analytics.
                    <?php endif; ?>
                </div>

                <!-- Mode Switch -->
                <div class="mode-switch">
                    <h4 style="margin-bottom: 15px;">Mode Selection</h4>
                    <a href="?mode=candidates" class="mode-button <?php echo $mode === 'candidates' ? 'active' : ''; ?>">
                        <i class="fa fa-users"></i> View Candidates
                    </a>
                    <a href="?mode=mailshot" class="mode-button <?php echo $mode === 'mailshot' ? 'active' : ''; ?>">
                        <i class="fa fa-paper-plane"></i> Create Mailshot
                    </a>
                    <?php 
                    // Only show KPI mode if user has VIEW_KPIs permission
                    try {
                        $stmt = $db_2->prepare("SELECT COUNT(*) as has_permission FROM userpermissions WHERE user_id = ? AND permission_name = 'VIEW_KPIs'");
                        $stmt->execute([$_SESSION['user_id']]);
                        $has_kpi_permission = $stmt->fetch(PDO::FETCH_ASSOC)['has_permission'] > 0;
                        
                        if ($has_kpi_permission): ?>
                            <a href="?mode=kpi" class="mode-button <?php echo $mode === 'kpi' ? 'active' : ''; ?>">
                                <i class="fa fa-bar-chart"></i> Weekly KPI Search
                            </a>
                        <?php endif;
                    } catch (Exception $e) {
                        // If there's an error checking permissions, don't show the KPI button
                    }
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

                <!-- KPI Filter Section -->
                <div class="filter-section">
                    <h5 style="margin-bottom: 20px; color: #495057;">
                        <i class="fa fa-filter"></i> KPI Filters & Date Range
                    </h5>
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
                                    <i class="fa fa-search"></i> Generate KPI Report
                                </button>
                                <a href="?mode=kpi" class="clear-button">
                                    <i class="fa fa-refresh"></i> Reset Filters
                                </a>
                                <button type="button" class="filter-button" onclick="exportKPIReport()" style="background-color: #28a745;">
                                    <i class="fa fa-download"></i> Export Report
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
                            <i class="fa fa-<?php echo $kpi_data['growth_rate'] >= 0 ? 'arrow-up' : 'arrow-down'; ?>"></i>
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
                        <h4><i class="fa fa-line-chart"></i> Daily Registration Trend</h4>
                        <canvas id="dailyTrendChart" width="400" height="200"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <h4><i class="fa fa-pie-chart"></i> Status Distribution</h4>
                        <canvas id="statusChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Tables Section -->
                <div class="kpi-tables">
                    <div class="kpi-table-container">
                        <h4><i class="fa fa-briefcase"></i> Top Job Titles</h4>
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
                        <h4><i class="fa fa-map-marker"></i> Top Cities</h4>
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
                        <h4><i class="fa fa-users"></i> Team Performance</h4>
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
                                $createdByMapping = [
                                    "1" => "Chax Shamwana",
                                    "10" => "Millie Brown", 
                                    "11" => "Jay Fuller",
                                    "13" => "Jack Dowler",
                                    "15" => "Alex Lapompe",
                                    "2" => "Alex Lapompe",
                                    "9" => "Jack Dowler"
                                ];
                                
                                foreach ($kpi_data['created_by_stats'] as $creator): 
                                    $creatorName = isset($createdByMapping[$creator['CreatedBy']]) ? $createdByMapping[$creator['CreatedBy']] : 'Unknown';
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

                <script>
                // Daily Trend Chart
                const dailyTrendCtx = document.getElementById('dailyTrendChart').getContext('2d');
                const dailyTrendChart = new Chart(dailyTrendCtx, {
                    type: 'line',
                    data: {
                        labels: [<?php echo !empty($kpi_data['daily_trend']) ? "'" . implode("','", array_column($kpi_data['daily_trend'], 'date')) . "'" : ''; ?>],
                        datasets: [{
                            label: 'Daily Registrations',
                            data: [<?php echo !empty($kpi_data['daily_trend']) ? implode(',', array_column($kpi_data['daily_trend'], 'count')) : ''; ?>],
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
                                <?php echo isset($kpi_data['active_candidates']) ? $kpi_data['active_candidates'] : 0; ?>,
                                <?php echo isset($kpi_data['inactive_candidates']) ? $kpi_data['inactive_candidates'] : 0; ?>,
                                <?php echo isset($kpi_data['archived_candidates']) ? $kpi_data['archived_candidates'] : 0; ?>,
                                <?php echo isset($kpi_data['pending_candidates']) ? $kpi_data['pending_candidates'] : 0; ?>
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
                    let csvContent = "KPI Report - <?php echo isset($kpi_data['date_range']['start']) ? $kpi_data['date_range']['start'] : 'N/A'; ?> to <?php echo isset($kpi_data['date_range']['end']) ? $kpi_data['date_range']['end'] : 'N/A'; ?>\n\n";
                    csvContent += "Metric,Value\n";
                    csvContent += "Total Candidates,<?php echo isset($kpi_data['total_candidates']) ? $kpi_data['total_candidates'] : 0; ?>\n";
                    csvContent += "New Candidates,<?php echo isset($kpi_data['new_candidates']) ? $kpi_data['new_candidates'] : 0; ?>\n";
                    csvContent += "Active Candidates,<?php echo isset($kpi_data['active_candidates']) ? $kpi_data['active_candidates'] : 0; ?>\n";
                    csvContent += "Inactive Candidates,<?php echo isset($kpi_data['inactive_candidates']) ? $kpi_data['inactive_candidates'] : 0; ?>\n";
                    csvContent += "Archived Candidates,<?php echo isset($kpi_data['archived_candidates']) ? $kpi_data['archived_candidates'] : 0; ?>\n";
                    csvContent += "Pending Candidates,<?php echo isset($kpi_data['pending_candidates']) ? $kpi_data['pending_candidates'] : 0; ?>\n";
                    csvContent += "Growth Rate,<?php echo isset($kpi_data['growth_rate']) ? $kpi_data['growth_rate'] : 0; ?>%\n";
                    
                    const blob = new Blob([csvContent], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'kpi-report-<?php echo date('Y-m-d'); ?>.csv';
                    a.click();
                    window.URL.revokeObjectURL(url);
                }
                </script>

                <?php elseif (isset($kpi_data['error'])): ?>
                    <div class="alert alert-danger">
                        <h5>Error generating KPI report</h5>
                        <p><?php echo htmlspecialchars($kpi_data['error']); ?></p>
                    </div>
                <?php endif; ?>

                <?php elseif ($mode === 'mailshot'): ?>
                <div class="mailshot-info">
                    <h5><i class="fa fa-info-circle"></i> Mailshot Mode</h5>
                    <p><strong>What happens here:</strong></p>
                    <ul>
                        <li>Filter candidates using location, distance, position, and keywords</li>
                        <li>Select individual candidates or use "Select All" for bulk selection</li>
                        <li>Choose an email template and customize the subject line</li>
                        <li>Send targeted email campaigns to selected candidates</li>
                        <li>Only active candidates are shown by default for mailshots</li>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if (isset($success_message)): ?>
                    <div class="success-message">
                        <i class="fa fa-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($mode !== 'kpi'): ?>
                <!-- Filter Section (for candidates and mailshot modes) -->
                <div class="filter-section">
                    <h5 style="margin-bottom: 20px; color: #495057;">
                        <?php echo $mode === 'mailshot' ? 'Filter Candidates for Mailshot' : 'Email Filtering System'; ?>
                    </h5>
                    <form method="GET" action="">
                        <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode); ?>">
                        
                        <?php if ($mode === 'candidates'): ?>
                        <!-- Standard candidate filtering layout -->
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
                        <!-- Mailshot filtering layout -->
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
                <!-- Status Tabs (only for candidates mode) -->
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
                // Execute query with filters - using both databases for comprehensive data
                try {
                    // First get from _candidates table (new structure)
                    $query = "SELECT * FROM `_candidates` $where_clause ORDER BY id DESC";
                    $stmt = $db_2->prepare($query);
                    
                    foreach ($params as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                    
                    $stmt->execute();
                    $candidates_new = $stmt->fetchAll(PDO::FETCH_OBJ);
                    
                    // Also get from old candidates table for migration/comparison
                    $candidates_old = [];
                    try {
                        $q = $db_1->query("SELECT * FROM `candidates` ORDER BY id DESC");
                        $candidates_old = $q->fetchAll(PDO::FETCH_OBJ);
                    } catch (Exception $e) {
                        // Old table might not exist or be accessible
                    }
                    
                    // Use new candidates primarily, fall back to old if needed
                    $candidates = !empty($candidates_new) ? $candidates_new : $candidates_old;
                    
                } catch (Exception $e) {
                    $candidates = [];
                    echo "<div class='alert alert-warning'>Database query error: " . $e->getMessage() . "</div>";
                }
                
                // Apply distance filter if both postcode and distance are provided
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

                <!-- Results Info -->
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
                    <!-- Mailshot Form -->
                    <form method="POST" action="">
                        <div class="select-all-container">
                            <label>
                                <input type="checkbox" id="select-all" class="candidate-checkbox">
                                Select All Candidates
                            </label>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Candidates Table -->
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
                                
                                // Created By mapping
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
                                    // Handle both old and new database structures
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
                                    
                                    // Status badge class
                                    $status_class = 'status-' . strtolower($status);
                                    
                                    // Format date
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
                    <!-- Mailshot Actions -->
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
                <?php endif; // End of non-KPI mode ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>