<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/config.php'; // Ensure this includes necessary configurations like $theme, $LINK, etc.
// Make sure you have PHPMailer loaded, e.g., via Composer:
// require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect if user is not logged in
if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/login");
    exit; // Always exit after a header redirect
}

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname1 = 'broadmead'; // Legacy database
$dbname2 = 'broadmead_v3'; // Primary, newer database

// Database connections
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

// --- CSV Export Handler (must come before ANY output) ---
if (isset($_GET['export_csv']) && $_GET['export_csv'] === 'true') {
    try {
        // Retrieve filter parameters from GET request
        $nameFilter = $_GET['nameFilter'] ?? '';
        $emailFilter = $_GET['emailFilter'] ?? '';
        $statusFilter = $_GET['statusFilter'] ?? '';
        $clientTypeFilter = $_GET['clientTypeFilter'] ?? '';
        $isTab = $_GET['isTab'] ?? 'all'; // Retrieve the active tab filter

        // Assuming $ClientKeyID is defined in config.php and represents the current user's associated client key.
        // If it's not defined, you might need to fetch it from the session or user data.
        // For now, assuming it's available.
        $export_where_conditions = ["ClientKeyID = :client_key_id", "isBranch IS NULL"];
        // Ensure $ClientKeyID is defined before using it. You might need to retrieve it from session or user data.
        // For demonstration, let's assume a dummy value if not already set.
        $ClientKeyID = $ClientKeyID ?? $_COOKIE['ClientKeyID'] ?? 1; // Example: Fetch from cookie or default
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
        $export_query = "SELECT ClientID, Name, Email, Number, Address, Postcode, City, ClientType, Status, CreatedBy, Date FROM `_clients` $export_where_clause ORDER BY Name ASC";

        $stmt = $db_2->prepare($export_query);
        $stmt->execute($export_params);
        $clients_data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array for headers

        if (empty($clients_data)) {
            die("No clients found for export with the applied filters.");
        }

        // Clear any existing output buffer to prevent header issues
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="clients_filtered_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Mapping for CreatedBy IDs to Names (for display in tables)
        // This needs to be available for the export as well.
        $createdByMapping = [
            "1" => "Chax Shamwana",
            "10" => "Millie Brown",
            "11" => "Jay Fuller",
            "13" => "Jack Dowler",
            "15" => "Alex Lapompe",
            "2" => "Alex Lapompe",
            "9" => "Jack Dowler"
        ];

        // Get headers from the first row and map CreatedBy IDs to names
        $headers = array_keys($clients_data[0]);
        // Find 'CreatedBy' index and replace with 'Created By Name' for clarity in CSV
        $createdByHeaderIndex = array_search('CreatedBy', $headers);
        if ($createdByHeaderIndex !== false) {
            $headers[$createdByHeaderIndex] = 'Created By Name';
        }

        fputcsv($output, $headers); // Write headers to CSV

        // Write data rows to CSV
        foreach ($clients_data as $row) {
            // Map CreatedBy ID to name for the current row
            if (isset($row['CreatedBy'])) {
                $row['CreatedBy'] = $createdByMapping[$row['CreatedBy']] ?? 'Unknown';
            }
            // Format Date if it exists
            if (isset($row['Date'])) {
                $row['Date'] = date('Y-m-d H:i:s', strtotime($row['Date']));
            }
            fputcsv($output, $row);
        }

        fclose($output);
        exit; // Terminate script after sending the file
    } catch (Exception $e) {
        die("CSV Export Failed: " . $e->getMessage());
    }
}


// Process mailshot when form is submitted
if (isset($_POST['send_mailshot'])) {
    $_SESSION['mailshot_initiated'] = true;

    $selected_clients = json_decode($_POST['selected_clients'], true) ?? [];
    $mailshot_subject = $_POST['mailshot_subject'] ?? '';
    $mailshot_message = $_POST['mailshot_message'] ?? '';
    $mailshot_template_selected = $_POST['mailshot_template'] ?? 'Custom Mailshot'; // Get selected template value

    // Validation
    if (empty($selected_clients)) {
        $error_message = "Please select at least one client.";
    } elseif (empty($mailshot_subject)) {
        $error_message = "Email subject is required.";
    } elseif (empty($mailshot_message)) {
        $error_message = "Email message is required.";
    } else {
        // SMTP Configuration
        $from_email = "learn@natec.icu";
        $from_name = "Recruitment Team";
        $smtp_host = 'smtp.titan.email';
        $smtp_username = 'learn@natec.icu';
        $smtp_password = '@WhiteDiamond0100';
        $smtp_port = 587;

        $total_recipients = count($selected_clients);
        $successful_sends = 0;
        $failed_sends = 0;
        $error_details = [];

        // Test SMTP connection first
        try {
            $test_mail = new PHPMailer(true);
            $test_mail->isSMTP();
            $test_mail->Host = $smtp_host;
            $test_mail->SMTPAuth = true;
            $test_mail->Username = $smtp_username;
            $test_mail->Password = $smtp_password;
            $test_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $test_mail->Port = $smtp_port;
            
            if (!$test_mail->smtpConnect()) {
                throw new Exception("SMTP connection failed");
            }
            $test_mail->smtpClose();
        } catch (Exception $e) {
            $error_message = "SMTP Configuration Error: " . $e->getMessage();
            error_log("SMTP Error: " . $e->getMessage());
        }

        // Proceed if SMTP test passed
        if (!isset($error_message)) {
            foreach ($selected_clients as $client_id) {
                try {
                    // Get client details from either database
                    $client = null;
                    
                    // Try broadmead_v3 first
                    $stmt = $db_2->prepare("SELECT Name, Email FROM _clients WHERE ClientID = ?");
                    $stmt->execute([$client_id]);
                    $client = $stmt->fetch();
                    
                    // If not found, try broadmead
                    if (!$client) {
                        $stmt = $db_1->prepare("SELECT Name, Email FROM clients WHERE id = ?");
                        $stmt->execute([$client_id]);
                        $client = $stmt->fetch();
                    }

                    if ($client && filter_var($client->Email, FILTER_VALIDATE_EMAIL)) {
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

                        // Personalize the message with the client's actual name
                        $personalized_message = str_replace('[CLIENT_NAME]', $client->Name, $mailshot_message);
                        
                        $mail->setFrom($from_email, $from_name);
                        $mail->addAddress($client->Email, $client->Name);
                        $mail->addReplyTo($from_email, $from_name);
                        $mail->isHTML(true);
                        $mail->Subject = $mailshot_subject;
                        $mail->Body = nl2br(htmlspecialchars($personalized_message));
                        $mail->AltBody = strip_tags($personalized_message);

                        if ($mail->send()) {
                            $successful_sends++;
                        } else {
                            $failed_sends++;
                            $error_details[] = "Failed to send to: {$client->Email} - " . $mail->ErrorInfo;
                        }
                        $mail->clearAddresses();
                        usleep(100000); // Small delay between emails
                    } else {
                        $failed_sends++;
                        $error_details[] = "Invalid email for client ID: $client_id";
                    }
                } catch (Exception $e) {
                    $failed_sends++;
                    $error_details[] = "Error processing client ID: $client_id - " . $e->getMessage();
                }
            }

            // --- Log Mailshot Summary AFTER all emails are processed ---
            try {
                // Use the selected template value for logging, or 'Custom Mailshot' if none selected
                $log_template_name = ($mailshot_template_selected !== '') ? $mailshot_template_selected : "Custom Mailshot";
                
                $log_query = $db_2->prepare("INSERT INTO mailshot_log (ClientKeyID, Subject, Template, RecipientsCount, SuccessCount, FailedCount, SentBy, SentDate) 
                                              VALUES (:client_key_id, :subject, :template, :recipients_count, :success_count, :failed_count, :sent_by, NOW())");
                
                // Assuming $ClientKeyID is the identifier for the current user's associated client key.
                // This is typically the ID of the account/entity that initiated the mailshot.
                // Ensure $ClientKeyID and $USERID are defined from your config or session.
                $ClientKeyID = $ClientKeyID ?? $_COOKIE['ClientKeyID'] ?? 1; // Example: Fetch from cookie or default
                $USERID = $USERID ?? $_COOKIE['USERID'] ?? 1; // Example: Fetch from cookie or default

                $log_query->bindParam(':client_key_id', $ClientKeyID); 
                $log_query->bindParam(':subject', $mailshot_subject);
                $log_query->bindParam(':template', $log_template_name); // Log the chosen template
                $log_query->bindParam(':recipients_count', $total_recipients, PDO::PARAM_INT);
                $log_query->bindParam(':success_count', $successful_sends, PDO::PARAM_INT);
                $log_query->bindParam(':failed_count', $failed_sends, PDO::PARAM_INT);
                $log_query->bindParam(':sent_by', $USERID); 
                
                if (!$log_query->execute()) {
                    error_log("Error inserting mailshot summary log: " . implode(", ", $log_query->errorInfo()));
                    // Add a user-facing error if logging fails, but don't prevent showing email send results
                    $error_message = ($error_message ?? '') . "\nError logging mailshot summary.";
                }
            } catch (Exception $e) {
                error_log("Exception during mailshot summary logging: " . $e->getMessage());
                $error_message = ($error_message ?? '') . "\nException during mailshot summary logging: " . $e->getMessage();
            }

            // Update user-facing messages based on overall success/failure
            if ($successful_sends > 0 && $failed_sends === 0) {
                $success_message = "Mailshot successfully sent to $successful_sends clients.";
                $NOTIFICATION = ($NAME ?? 'A user') . " has successfully sent mailshot to $successful_sends clients.";
                // Assuming Notify function is defined in config.php
                if (function_exists('Notify')) {
                     Notify($USERID, $ClientKeyID, $NOTIFICATION); 
                }
            } elseif ($successful_sends > 0 && $failed_sends > 0) {
                $error_message = "Mailshot completed with some issues: $successful_sends succeeded, $failed_sends failed.";
                if (!empty($error_details)) {
                    $error_message .= "\n\nFirst 5 errors:\n" . implode("\n", array_slice($error_details, 0, 5));
                    if (count($error_details) > 5) {
                        $error_message .= "\n... plus " . (count($error_details) - 5) . " more errors.";
                    }
                }
                $NOTIFICATION = ($NAME ?? 'A user') . " sent mailshot to $successful_sends clients with $failed_sends failures.";
                if (function_exists('Notify')) {
                     Notify($USERID, $ClientKeyID, $NOTIFICATION); 
                }
            } elseif ($failed_sends > 0) {
                $error_message = "Mailshot failed for all selected clients ($failed_sends failures).";
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

// Handle search form submission (this block seems to handle an older search method)
// The current quick filters are handled by JavaScript
if (isset($_POST['Search'])) {
    // This block seems to be for a different search mechanism, possibly for logging searches.
    // The current filtering is done client-side via JavaScript's applyFilters().
    // If this is still needed for server-side search logging, ensure $SearchID is correctly generated.
    $Name = $_POST['Name'] ?? '';
    $ClientType = $_POST['ClientType'] ?? '';
    $_client_id = $_POST['_client_id'] ?? '';
    $EmailAddress = $_POST['Email'] ?? '';
    $PhoneNumber = $_POST['Number'] ?? '';
    $Address = $_POST['Address'] ?? '';
    $Postcode = $_POST['Postcode'] ?? '';
    $City = $_POST['City'] ?? '';
    
    // Assuming $SearchID is generated elsewhere, e.g., uniqid()
    // if (!empty($SearchID)) {
    //     $query = $db_2->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
    //             VALUES (:SearchID, :column, :value)");

    //     foreach ($_POST as $key => $value) {
    //         if (!empty($value) && $key !== 'Search') {
    //             $query->bindParam(':SearchID', $SearchID);
    //             $query->bindParam(':column', $key);
    //             $query->bindParam(':value', $value);
    //             $query->execute();
    //         }
    //     }

    //     header("location: $LINK/clients/?q=$SearchID");
    //     exit();
    // }
}

// Handle delete operation
if (isset($_POST['delete'])) {
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    // Using $db_2 for _clients table
    $stmt = $db_2->prepare("DELETE FROM `_clients` WHERE ClientID = :ID");
    $stmt->bindParam(':ID', $ID);

    if ($stmt->execute()) {
        $NOTIFICATION = ($NAME ?? 'A user') . " has successfully deleted the client named '$name'. Reason for deletion: $reason.";
        // Assuming Notify function is defined in config.php
        if (function_exists('Notify')) {
             Notify($USERID, $ClientKeyID, $NOTIFICATION); 
        }
    } else {
        error_log("Error deleting record: " . implode(", ", $stmt->errorInfo()));
        // Optionally set an error message for display
    }
}

// Re-defining $SearchID and $isTab for page rendering
$SearchID = isset($_GET['q']) ? $_GET['q'] : "";
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "all";

// Client Statuses (assuming these are defined in config.php or similar)
// Example: $clients_status = ['targeted', 'not updated', 'active', 'inactive', 'archived'];
// If not defined, uncomment and define it here:
$clients_status = ['targeted', 'not updated', 'active', 'inactive', 'archived'];


// Mapping for CreatedBy IDs to Names (for display in tables)
// This mapping needs to be defined for the client list as well.
$createdByMapping = [
    "1" => "Chax Shamwana",
    "10" => "Millie Brown",
    "11" => "Jay Fuller",
    "13" => "Jack Dowler",
    "15" => "Alex Lapompe",
    "2" => "Alex Lapompe",
    "9" => "Jack Dowler"
];

// Ensure $ClientKeyID and $USERID are defined for rendering, if not already from config.php
$ClientKeyID = $ClientKeyID ?? $_COOKIE['ClientKeyID'] ?? 1; // Example: Fetch from cookie or default
$USERID = $USERID ?? $_COOKIE['USERID'] ?? 1; // Example: Fetch from cookie or default
$NAME = $NAME ?? 'Guest User'; // Example: Fetch user's name

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

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Clients</h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
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
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Filter by Client Type:</label>
                                    <select class="form-select" id="clientTypeFilter" onchange="applyFilters()">
                                        <option value="">All Types</option>
                                        <?php
                                        // Fetch distinct client types from _clients table
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
                                    <a href="#" id="exportCsvBtn" style="background-color: #21a366; color: white; border: 1px solid #21a366; padding: 0.25rem 0.5rem; border-radius: 0.25rem; text-decoration: none; display: inline-flex; align-items: center; margin-left: 0.5rem;" onclick="return confirm('Export filtered clients to CSV?')">
                                        <i class="ti ti-file-text"></i> Export CSV
                                    </a>
                                    <span id="filterResults" style="margin-left: 1rem; color: #6c757d;"></span>
                                </div>
                                <div style="margin-left: auto; padding-left: 15px;">
                                    <button type="button" style="background-color: #0d6efd; color: white; border: 1px solid #0d6efd; padding: 0.25rem 0.5rem; border-radius: 0.25rem; display: none;" id="mailshotBtn" onclick="openMailshotModal()">
                                        <i class="ti ti-mail"></i> Send Mailshot (<span id="selectedCount">0</span>)
                                    </button>
                                </div>
                            </div>

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
                                                <?php echo $tab; ?>
                                            </button>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </ul>

                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (isset($USERID) && function_exists('IsCheckPermission') && IsCheckPermission($USERID, "VIEW_CLIENTS")) : // Added checks for defined variables ?>
                                    <table class="table table-bordered" id="clientsTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>
                                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                                    <label for="selectAll" class="form-check-label ms-1">Select All</label>
                                                </th>
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
                                            // Start building the query for displaying clients
                                            $query_display = "SELECT * FROM `_clients` WHERE ClientKeyID = :client_key_id AND isBranch IS NULL ";
                                            $params_display = [':client_key_id' => $ClientKeyID];

                                            // Apply advanced search filters if SearchID is present
                                            if (!empty($SearchID)) {
                                                $qu = $db_2->prepare("SELECT `column`, `value` FROM `search_queries` WHERE SearchID = :search_id");
                                                $qu->bindParam(':search_id', $SearchID);
                                                $qu->execute();
                                                while ($r = $qu->fetchObject()) {
                                                    $column = $r->column;
                                                    $value = $r->value;
                                                    // Ensure valid columns to prevent SQL injection
                                                    $allowed_columns = ['Name', 'ClientType', '_client_id', 'Email', 'Number', 'Address', 'Postcode', 'City'];
                                                    if (in_array($column, $allowed_columns)) {
                                                        $query_display .= " AND " . $column . " LIKE :" . $column;
                                                        $params_display[':' . $column] = '%' . $value . '%';
                                                    }
                                                }
                                            }
                                            
                                            // Apply tab filter (e.g., "active", "inactive")
                                            if ($isTab !== "all") {
                                                $query_display .= " AND Status = :is_tab";
                                                $params_display[':is_tab'] = $isTab;
                                            }
                                            
                                            $query_display .= " ORDER BY Name ASC";
                                            
                                            $stmt_display = $db_2->prepare($query_display);
                                            $stmt_display->execute($params_display);
                                            $n = 1;
                                            while ($row = $stmt_display->fetchObject()) { ?>
                                                <?php
                                                // Fetch CreatedBy Name
                                                $CreatedBy = $createdByMapping[$row->CreatedBy] ?? 'Unknown';
                                                $status_class = strtolower($row->Status ?? 'not updated');
                                                ?>
                                                <tr class="client-row" 
                                                    data-name="<?php echo strtolower($row->Name); ?>"
                                                    data-email="<?php echo strtolower($row->Email); ?>"
                                                    data-status="<?php echo $status_class; ?>"
                                                    data-clienttype="<?php echo strtolower($row->ClientType); ?>">
                                                    <td><?php echo $n++; ?></td>
                                                    <td>
                                                        <input class="form-check-input checkbox-item" 
                                                               type="checkbox" 
                                                               value="<?php echo $row->ClientID; ?>" 
                                                               data-name="<?php echo htmlspecialchars($row->Name); ?>"
                                                               data-email="<?php echo htmlspecialchars($row->Email); ?>"
                                                               onchange="updateSelectedCount()">
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row->Name); ?></td>
                                                    <td><?php echo htmlspecialchars($row->_client_id); ?></td>
                                                    <td><?php echo htmlspecialchars($row->ClientType); ?></td>
                                                    <td>
                                                        <?php if ($row->Status == "Active") : ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php elseif ($row->Status == "Archived") : ?>
                                                            <span class="badge bg-warning">Archived</span>
                                                        <?php elseif ($row->Status == "Inactive") : ?>
                                                            <span class="badge bg-danger">Inactive</span>
                                                        <?php elseif ($row->Status == "Targeted") : ?>
                                                            <span class="badge bg-info">Targeted</span>
                                                        <?php else : ?>
                                                            <span class="badge bg-danger">Not Updated</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row->Email); ?></td>
                                                    <td><?php echo htmlspecialchars($row->Number); ?></td>
                                                    <td><?php echo htmlspecialchars($row->City . ', ' . $row->Address); ?></td>
                                                    <td><?php echo htmlspecialchars($CreatedBy); ?></td>
                                                    <td><?php echo htmlspecialchars(function_exists('FormatDate') ? FormatDate($row->Date) : $row->Date); ?></td> <td>
                                                        <div class="dropdown">
                                                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/edit_client/?ID=<?php echo htmlspecialchars($row->ClientID); ?>">
                                                                    <span class="text-info">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                            <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                        </svg>
                                                                    </span>
                                                                    Edit</a>
                                                                <?php if (isset($USERID) && function_exists('IsCheckPermission') && IsCheckPermission($USERID, "DELETE_CLIENT")) : ?>
                                                                    <a class="dropdown-item delete-entry" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal" data-id="<?php echo htmlspecialchars($row->ClientID); ?>" data-name="<?php echo htmlspecialchars($row->Name); ?>">
                                                                        <span class="text-danger">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                            </svg>
                                                                        </span>
                                                                        Delete</a>
                                                                <?php endif; ?>
                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/view_client/?ID=<?php echo htmlspecialchars($row->ClientID); ?>">
                                                                    <span class="text-warning">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                            <g fill="currentColor">
                                                                                <path d="M6.5 12a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z" />
                                                                                <path fill-rule="evenodd" d="M11.185 1H4.5A1.5 1.5 0 0 0 3 2.5v15A1.5 1.5 0 0 0 4.5 19h11a1.5 1.5 0 0 0 1.5-1.5V7.202a1.5 1.5 0 0 0-.395-1.014l-4.314-4.702A1.5 1.5 0 0 0 11.185 1M4 2.5a.5.5 0 0 1 .5-.5h6.685a.5.5 0 0 1 .369.162l4.314 4.702a.5.5 0 0 1 .132.338V17.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5z" clip-rule="evenodd" />
                                                                                <path d="M11 7h5.5a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 1 0z" />
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                    View</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <div class="alert alert-warning" role="alert">
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

   <div class="modal fade" id="mailshotModal" tabindex="-1" role="dialog" aria-labelledby="mailshotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mailshotModalLabel">Send Mailshot to Selected Clients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="selected_clients" id="mailshotSelectedClients">

                    <div class="form-group mb-3">
                        <label for="mailshot_template">Choose Template:</label>
                        <select class="form-control" id="mailshot_template" name="mailshot_template">
                            <option value="">-- Select a Template --</option>
                            <option value="welcome">Welcome Email</option>
                            <option value="promotion">New Promotion</option>
                            <option value="followup">Follow-up Reminder</option>
                            </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="mailshot_subject">Subject:</label>
                        <input type="text" class="form-control" id="mailshot_subject" name="mailshot_subject" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="mailshot_message">Message:</label>
                        <textarea class="form-control" id="mailshot_message" name="mailshot_message" rows="8" placeholder="Enter your email message here. Use [CLIENT_NAME] for the client's name." required></textarea>
                    </div>
                    <p class="text-muted">Selected Clients: <span id="mailshotClientList"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="send_mailshot" class="btn btn-primary">Send Mailshot</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <?php // include "../../includes/footer_scripts.php"; // Assuming this includes Bootstrap JS, etc. ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener for Delete Modal to populate data
            var deleteModal = document.getElementById('DeleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Button that triggered the modal
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

            // Select All / Deselect All functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const mailshotBtn = document.getElementById('mailshotBtn');
            const selectedCountSpan = document.getElementById('selectedCount');
            const filterResultsSpan = document.getElementById('filterResults');
            const mailshotSubjectField = document.getElementById('mailshot_subject');
            const mailshotMessageField = document.getElementById('mailshot_message');
            const mailshotTemplateDropdown = document.getElementById('mailshot_template');

            // Define your templates (in a real application, these might come from a server endpoint)
            const emailTemplates = {
                'welcome': {
                    subject: 'Welcome to Our Service!',
                    message: 'Dear [CLIENT_NAME],\n\nWelcome aboard! We are thrilled to have you as part of our community. Explore our features and let us know if you have any questions.\n\nBest regards,\n[Your Company Name]'
                },
                'promotion': {
                    subject: 'Exclusive Offer Just for You!',
                    message: 'Hi [CLIENT_NAME],\n\nWe\'re excited to announce a special promotion just for our valued clients! Get [Discount/Offer Details] on your next purchase. This offer is valid until [Date].\n\nDon\'t miss out!\n[Your Company Name]'
                },
                'followup': {
                    subject: 'Quick Follow-up Regarding Our Last Conversation',
                    message: 'Hello [CLIENT_NAME],\n\nHope you\'re doing well. I wanted to follow up on our recent discussion about [Topic]. Please let me know if you have any further questions or if there\'s anything else I can assist you with.\n\nLooking forward to hearing from you,\n[Your Company Name]'
                }
                // Add more templates here with unique values matching the <option> values
            };

            // Event listener for template selection
            if (mailshotTemplateDropdown) {
                mailshotTemplateDropdown.addEventListener('change', function() {
                    const selectedTemplateId = this.value;
                    if (selectedTemplateId && emailTemplates[selectedTemplateId]) {
                        const template = emailTemplates[selectedTemplateId];
                        mailshotSubjectField.value = template.subject;
                        mailshotMessageField.value = template.message;
                    } else {
                        // Clear fields if "Select a Template" or an invalid option is chosen
                        mailshotSubjectField.value = '';
                        mailshotMessageField.value = '';
                    }
                });
            }

            // Optional: Clear mailshot modal fields when it is hidden
            var mailshotModal = document.getElementById('mailshotModal');
            if (mailshotModal) {
                mailshotModal.addEventListener('hidden.bs.modal', function () {
                    mailshotTemplateDropdown.value = ''; // Reset template dropdown
                    mailshotSubjectField.value = ''; // Clear subject
                    mailshotMessageField.value = ''; // Clear message
                });
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.checkbox-item');
                    checkboxes.forEach(checkbox => {
                        // Only toggle checkboxes for visible rows
                        if (checkbox.closest('tr').style.display !== 'none') {
                            checkbox.checked = this.checked;
                        }
                    });
                    updateSelectedCount();
                });
            }

            // Update selected count and mailshot button visibility
            window.updateSelectedCount = function() {
                const checkedCheckboxes = document.querySelectorAll('.checkbox-item:checked');
                selectedCountSpan.textContent = checkedCheckboxes.length;
                if (checkedCheckboxes.length > 0) {
                    mailshotBtn.style.display = 'inline-block';
                } else {
                    mailshotBtn.style.display = 'none';
                }
            };

            // Open Mailshot Modal and populate selected clients
            window.openMailshotModal = function() {
                const checkedCheckboxes = document.querySelectorAll('.checkbox-item:checked');
                const selectedClientIds = [];
                const selectedClientNames = [];

                checkedCheckboxes.forEach(checkbox => {
                    selectedClientIds.push(checkbox.value);
                    selectedClientNames.push(checkbox.dataset.name);
                });

                document.getElementById('mailshotSelectedClients').value = JSON.stringify(selectedClientIds);
                document.getElementById('mailshotClientList').textContent = selectedClientNames.join(', ');

                var mailshotBootstrapModal = new bootstrap.Modal(document.getElementById('mailshotModal'));
                mailshotBootstrapModal.show();
            };

            // Apply Filters function
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
                    const statusMatch = statusFilter === '' || status === statusFilter;
                    const clientTypeMatch = clientTypeFilter === '' || clientType === clientTypeFilter;

                    if (nameMatch && emailMatch && statusMatch && clientTypeMatch) {
                        row.style.display = '';
                        visibleRowCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                filterResultsSpan.textContent = `Showing ${visibleRowCount} results.`;
                updateSelectedCount(); // Update mailshot button visibility based on new filtered list
                updateCsvExportLink(); // Update the CSV export link
            };

            // Clear All Filters function
            window.clearAllFilters = function() {
                document.getElementById('nameFilter').value = '';
                document.getElementById('emailFilter').value = '';
                document.getElementById('statusFilter').value = '';
                document.getElementById('clientTypeFilter').value = '';
                applyFilters(); // Re-apply filters to show all
            };

            // Function to update the CSV export link dynamically
            window.updateCsvExportLink = function() {
                const nameFilter = document.getElementById('nameFilter').value;
                const emailFilter = document.getElementById('emailFilter').value;
                const statusFilter = document.getElementById('statusFilter').value;
                const clientTypeFilter = document.getElementById('clientTypeFilter').value;
                
                // Get the current active tab status from the URL
                const urlParams = new URLSearchParams(window.location.search);
                const isTab = urlParams.get('isTab') || 'all';

                let exportUrl = `?export_csv=true`;
                exportUrl += `&nameFilter=${encodeURIComponent(nameFilter)}`;
                exportUrl += `&emailFilter=${encodeURIComponent(emailFilter)}`;
                exportUrl += `&statusFilter=${encodeURIComponent(statusFilter)}`;
                exportUrl += `&clientTypeFilter=${encodeURIComponent(clientTypeFilter)}`;
                exportUrl += `&isTab=${encodeURIComponent(isTab)}`; // Include the active tab filter

                document.getElementById('exportCsvBtn').href = exportUrl;
            };

            // Initial calls on page load
            applyFilters(); // Apply initial filters based on URL parameters or defaults
            updateSelectedCount(); // Initialize selected count
            updateCsvExportLink(); // Set the initial CSV export link
        });
    </script>
</body>
</html>