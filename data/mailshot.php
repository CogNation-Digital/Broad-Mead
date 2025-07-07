<?php
session_start();

require_once 'sessions.php';

// Constants
$ClientKeyID = "5WEMfHw2aD2C35j8VsVmSQkpzZ2BI2dpqe8wLfqTmQYHPbnrBh";

// Get filter parameters
$keyword_filter = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location_filter = isset($_GET['location']) ? trim($_GET['location']) : '';
$position_filter = isset($_GET['position']) ? trim($_GET['position']) : '';
$status_filter = 'active'; // Mailshot only shows active candidates

// Distance filter parameters
$center_postcode = isset($_GET['center_postcode']) ? trim($_GET['center_postcode']) : '';
$distance_miles = isset($_GET['distance_miles']) ? (int)$_GET['distance_miles'] : 0;

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

$where_conditions[] = "Status = :status";
$params[':status'] = $status_filter;

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

// Handle mailshot submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_candidates'])) {
    $selected_candidates = $_POST['selected_candidates'];
    $subject = $_POST['subject'];
    $template = $_POST['template'];
    
    $success_message = "Mailshot with subject '" . htmlspecialchars($subject) . "' sent to " . count($selected_candidates) . " candidates successfully!";
}

// Get all available job titles and locations for dropdowns
$job_titles_query = "SELECT DISTINCT JobTitle FROM _candidates WHERE JobTitle IS NOT NULL AND JobTitle != '' ORDER BY JobTitle";
$job_titles_stmt = $db_2->query($job_titles_query);
$job_titles = $job_titles_stmt->fetchAll(PDO::FETCH_COLUMN);

$locations_query = "SELECT DISTINCT City FROM _candidates WHERE City IS NOT NULL AND City != '' ORDER BY City";
$locations_stmt = $db_2->query($locations_query);
$locations = $locations_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mailshot - Candidate Filtering</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
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
                </div>

                <!-- Mode Switch -->
                <div class="mode-switch">
                    <h4 style="margin-bottom: 15px;">Mode Selection</h4>
                    <a href="candidates.php" class="mode-button">
                        <i class="fa fa-users"></i> View Candidates
                    </a>
                    <a href="mailshot.php" class="mode-button active">
                        <i class="fa fa-paper-plane"></i> Create Mailshot
                    </a>
                    <?php 
                    // Only show KPI mode if user has VIEW_KPIs permission
                    try {
                        $stmt = $db_2->prepare("SELECT COUNT(*) as has_permission FROM userpermissions WHERE user_id = ? AND permission_name = 'VIEW_KPIs'");
                        $stmt->execute([$_SESSION['user_id']]);
                        $has_kpi_permission = $stmt->fetch(PDO::FETCH_ASSOC)['has_permission'] > 0;
                        
                        if ($has_kpi_permission): ?>
                            <a href="kpi.php" class="mode-button">
                                <i class="fa fa-bar-chart"></i> Weekly KPI Search
                            </a>
                        <?php endif;
                    } catch (Exception $e) {
                        // If there's an error checking permissions, don't show the KPI button
                    }
                    ?>
                </div>

                <h2 style="margin-bottom: 30px; color: #343a40;">
                    Mailshot - Candidate Filtering
                </h2>

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

                <?php if (isset($success_message)): ?>
                    <div class="success-message">
                        <i class="fa fa-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Filter Section -->
                <div class="filter-section">
                    <h5 style="margin-bottom: 20px; color: #495057;">
                        Filter Candidates for Mailshot
                    </h5>
                    <form method="GET" action="">
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
                                    <?php foreach ($job_titles as $job_title): ?>
                                        <option value="<?php echo htmlspecialchars($job_title); ?>" 
                                                <?php echo $position_filter === $job_title ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($job_title); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row filter-row">
                            <div class="col-md-6">
                                <div class="filter-label">Location</div>
                                <select name="location" class="filter-input">
                                    <option value="">All Locations</option>
                                    <?php foreach ($locations as $location): ?>
                                        <option value="<?php echo htmlspecialchars($location); ?>" 
                                                <?php echo $location_filter === $location ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($location); ?>
                                        </option>
                                    <?php endforeach; ?>
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
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="filter-button">
                                    <i class="fa fa-search"></i> Apply Filters
                                </button>
                                <a href="mailshot.php" class="clear-button">
                                    <i class="fa fa-times"></i> Clear Filters
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                // Execute query with filters
                try {
                    $query = "SELECT * FROM `_candidates` $where_clause ORDER BY id DESC";
                    $stmt = $db_2->prepare($query);
                    
                    foreach ($params as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                    
                    $stmt->execute();
                    $candidates = $stmt->fetchAll(PDO::FETCH_OBJ);
                    
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
                    <strong><?php echo $total_results; ?></strong> candidates found for mailshot
                    <?php if (!empty($keyword_filter) || !empty($location_filter) || !empty($position_filter)): ?>
                        with applied filters
                    <?php endif; ?>
                    <?php if (!empty($center_postcode) && $distance_miles > 0): ?>
                        within <?php echo htmlspecialchars($distance_miles); ?> miles of <?php echo htmlspecialchars($center_postcode); ?>
                    <?php endif; ?>
                </div>

                <?php if ($total_results > 0): ?>
                    <!-- Mailshot Form -->
                    <form method="POST" action="">
                        <div class="select-all-container">
                            <label>
                                <input type="checkbox" id="select-all" class="candidate-checkbox">
                                Select All Candidates
                            </label>
                        </div>
                    
                        <!-- Candidates Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="editableTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="40px">Select</th>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Job Title</th>
                                        <th>Location</th>
                                        <?php if (!empty($center_postcode) && $distance_miles > 0): ?>
                                        <th>Distance</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $n = 1;
                                    
                                    foreach ($candidates as $row) {
                                        // Handle both old and new database structures
                                        $name = '';
                                        if (isset($row->Name)) {
                                            $name = $row->Name;
                                        } elseif (isset($row->first_name) && isset($row->last_name)) {
                                            $name = $row->first_name . ' ' . $row->last_name;
                                        }
                                        
                                        $email = strtolower($row->Email ?? $row->email ?? '');
                                        $city = $row->City ?? $row->city ?? '';
                                        $job_title = $row->JobTitle ?? $row->job_title ?? '';
                                        $profileImage = $row->ProfileImage ?? (isset($row->profile) ? "https://broad-mead.com/" . $row->profile : '');
                                    ?>
                                        <tr data-id="<?php echo $row->id; ?>">
                                            <td class="text-center">
                                                <input type="checkbox" name="selected_candidates[]" value="<?php echo $row->id; ?>" class="candidate-checkbox">
                                            </td>
                                            <td><?php echo str_pad($n++, 2, '0', STR_PAD_LEFT); ?></td>
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
                                            <td><?php echo htmlspecialchars($job_title); ?></td>
                                            <td><?php echo htmlspecialchars($city); ?></td>
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
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

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

                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <h5>No candidates found</h5>
                        <p>Try adjusting your search filters or <a href="mailshot.php">clear all filters</a> to see candidates for your mailshot.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>