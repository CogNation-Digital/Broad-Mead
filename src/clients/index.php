<?php 
include "../../includes/config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/login");
    exit();
}

// Email configuration - UPDATE THESE WITH YOUR ACTUAL CREDENTIALS
$from_email = "your_email@yourdomain.com";
$from_name = "Your Company Name";
$smtp_host = 'smtp.yourdomain.com';
$smtp_username = 'your_smtp_username';
$smtp_password = 'your_smtp_password';
$smtp_port = 587; // Typically 587 for TLS, 465 for SSL

// Test SMTP connection
try {
    $test_mail = new PHPMailer(true);
    $test_mail->isSMTP();
    $test_mail->Host = $smtp_host;
    $test_mail->SMTPAuth = true;
    $test_mail->Username = $smtp_username;
    $test_mail->Password = $smtp_password;
    $test_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $test_mail->Port = $smtp_port;
    $test_mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable for troubleshooting
    
    if (!$test_mail->smtpConnect()) {
        throw new Exception("SMTP connection failed");
    }
    $test_mail->smtpClose();
} catch (Exception $e) {
    error_log("SMTP Error: " . $e->getMessage());
    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => "SMTP Configuration Error: " . $e->getMessage()
    ];
}

// Handle delete action
if (isset($_POST['delete'])) {
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("DELETE FROM `_clients` WHERE ClientID = :ID");
    $stmt->bindParam(':ID', $ID);

    if ($stmt->execute()) {
        $NOTIFICATION = "$NAME has successfully deleted the client named '$name'. Reason for deletion: $reason.";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Client deleted successfully'
        ];
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Error deleting client'
        ];
    }
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}

// Handle mailshot sending
if (isset($_POST['send_mailshot'])) {
    $selected_clients = json_decode($_POST['selected_clients']);
    $mailshot_subject = $_POST['mailshot_subject'];
    $mailshot_message = $_POST['mailshot_message'];
    $is_html = isset($_POST['is_html']) ? true : false;
    
    $success_count = 0;
    $error_count = 0;
    $error_details = [];
    
    if (!empty($selected_clients) && !empty($mailshot_subject) && !empty($mailshot_message)) {
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set to DEBUG_SERVER for troubleshooting
            $mail->isSMTP();
            $mail->Host       = $smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp_username;
            $mail->Password   = $smtp_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $smtp_port;
            
            // Sender info
            $mail->setFrom($from_email, $from_name);
            $mail->addReplyTo($from_email, $from_name);
            
            // Content
            $mail->isHTML($is_html);
            $mail->Subject = $mailshot_subject;
            
            foreach ($selected_clients as $client_id) {
                // Get client details
                $client_query = $conn->prepare("SELECT ClientID, Name, Email FROM `_clients` WHERE ClientID = :client_id");
                $client_query->bindParam(':client_id', $client_id);
                $client_query->execute();
                $client = $client_query->fetch(PDO::FETCH_OBJ);
                
                if ($client && filter_var($client->Email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        // Clear all recipients for new email
                        $mail->clearAddresses();
                        
                        // Add recipient
                        $mail->addAddress($client->Email, $client->Name);
                        
                        // Personalize message
                        $personalized_message = str_replace(
                            ['[CLIENT_NAME]', '[CLIENT_EMAIL]', '[CLIENT_ID]'], 
                            [$client->Name, $client->Email, $client->ClientID], 
                            $mailshot_message
                        );
                        
                        $mail->Body = $personalized_message;
                        
                        // Send email
                        if ($mail->send()) {
                            $success_count++;
                            
                            // Log mailshot
                            $log_query = $conn->prepare("INSERT INTO `mailshot_log` 
                                (ClientID, Subject, Message, SentBy, SentDate, Status) 
                                VALUES (:client_id, :subject, :message, :sent_by, NOW(), 'Sent')");
                            $log_query->bindParam(':client_id', $client_id);
                            $log_query->bindParam(':subject', $mailshot_subject);
                            $log_query->bindParam(':message', $personalized_message);
                            $log_query->bindParam(':sent_by', $USERID);
                            $log_query->execute();
                        }
                    } catch (Exception $e) {
                        $error_count++;
                        $error_details[] = "Client ID {$client_id}: " . $e->getMessage();
                        
                        // Log failed attempt
                        $log_query = $conn->prepare("INSERT INTO `mailshot_log` 
                            (ClientID, Subject, Message, SentBy, SentDate, Status, Error) 
                            VALUES (:client_id, :subject, :message, :sent_by, NOW(), 'Failed', :error)");
                        $log_query->bindParam(':client_id', $client_id);
                        $log_query->bindParam(':subject', $mailshot_subject);
                        $log_query->bindParam(':message', $personalized_message);
                        $log_query->bindParam(':sent_by', $USERID);
                        $log_query->bindParam(':error', $e->getMessage());
                        $log_query->execute();
                    }
                } else {
                    $error_count++;
                    $error_details[] = "Client ID {$client_id}: Invalid or missing email address";
                }
            }
            
            // Prepare notification
            $notification_message = "$NAME sent a mailshot to $success_count clients.";
            if ($error_count > 0) {
                $notification_message .= " Failed to send to $error_count clients.";
            }
            
            Notify($USERID, $ClientKeyID, $notification_message);
            
            // Prepare response
            $response = [
                'success' => true,
                'sent' => $success_count,
                'failed' => $error_count,
                'errors' => $error_details,
                'message' => "Mailshot completed. Success: $success_count, Failed: $error_count"
            ];
            
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                $_SESSION['toast'] = [
                    'type' => $error_count > 0 ? 'warning' : 'success',
                    'message' => $response['message']
                ];
                header("Location: ".$_SERVER['HTTP_REFERER']);
                exit();
            }
            
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => "Mailer Error: " . $e->getMessage()
            ];
            
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => $response['message']
                ];
                header("Location: ".$_SERVER['HTTP_REFERER']);
                exit();
            }
        }
        
        // For AJAX requests
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

// Handle search
if (isset($_POST['Search'])) {
    $SearchID = uniqid();
    $fields = [
        'Name', 'ClientType', '_client_id', 'Email', 'Number', 
        'Address', 'City', 'Postcode', 'Status'
    ];
    
    foreach ($fields as $field) {
        if (!empty($_POST[$field])) {
            $value = $_POST[$field];
            $stmt = $conn->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
                                  VALUES (:SearchID, :column, :value)");
            $stmt->bindParam(':SearchID', $SearchID);
            $stmt->bindParam(':column', $field);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
        }
    }

    header("location: $LINK/clients/?q=$SearchID");
    exit();
}

$SearchID = isset($_GET['q']) ? $_GET['q'] : "";
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "all";

// Get all client statuses for tabs
$statuses = $conn->query("SELECT DISTINCT Status FROM `_clients` WHERE Status IS NOT NULL AND Status != ''")->fetchAll(PDO::FETCH_COLUMN);
$client_types = $conn->query("SELECT DISTINCT ClientType FROM `_clients` WHERE ClientType IS NOT NULL AND ClientType != ''")->fetchAll(PDO::FETCH_COLUMN);
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

                        <!-- Quick Filters -->
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
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?php echo strtolower($status); ?>" <?php echo ($isTab == strtolower($status)) ? 'selected' : ''; ?>>
                                                <?php echo $status; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Filter by Client Type:</label>
                                    <select class="form-select" id="clientTypeFilter" onchange="applyFilters()">
                                        <option value="">All Types</option>
                                        <?php foreach ($client_types as $type): ?>
                                            <option value="<?php echo strtolower($type); ?>"><?php echo $type; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllFilters()">
                                        <i class="ti ti-refresh"></i> Clear All Filters
                                    </button>
                                    <span id="filterResults" class="ms-3 text-muted"></span>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="button" class="btn btn-primary btn-sm" id="mailshotBtn" onclick="openMailshotModal()" style="display: none;">
                                        <i class="ti ti-mail"></i> Send Mailshot (<span id="selectedCount">0</span>)
                                    </button>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist" style="margin-left:30px;">
                            <li class="nav-item" role="presentation">
                                <a href="<?php echo $LINK; ?>/clients<?php echo !empty($SearchID) ? "/?q=$SearchID" : "" ?>">
                                    <button class="nav-link <?php echo ($isTab == "all") ? 'active' : ''; ?>">All Clients</button>
                                </a>
                            </li>
                            <?php foreach ($statuses as $tab): ?>
                                <li class="nav-item" role="presentation">
                                    <a href="<?php echo $LINK; ?>/clients<?php echo !empty($SearchID) ? "/?q=$SearchID" : "" ?>&isTab=<?php echo urlencode(strtolower($tab)); ?>">
                                        <button class="nav-link <?php echo ($isTab == strtolower($tab)) ? 'active' : ''; ?>">
                                            <?php echo $tab; ?>
                                        </button>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "VIEW_CLIENTS")) : ?>
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
                                            $query = "SELECT c.*, u.Name as CreatedByName 
                                                     FROM `_clients` c
                                                     LEFT JOIN `users` u ON c.CreatedBy = u.UserID
                                                     WHERE c.ClientKeyID = :clientKeyID AND c.isBranch IS NULL";
                                            $params = [':clientKeyID' => $ClientKeyID];

                                            if (isset($_GET['q'])) {
                                                $SearchID = $_GET['q'];
                                                $qu = $conn->prepare("SELECT `column`, `value` FROM `search_queries` WHERE SearchID = :searchID");
                                                $qu->bindParam(':searchID', $SearchID);
                                                $qu->execute();
                                                
                                                while ($r = $qu->fetch(PDO::FETCH_OBJ)) {
                                                    $query .= " AND c.{$r->column} LIKE :{$r->column}";
                                                    $params[":{$r->column}"] = "%{$r->value}%";
                                                }
                                            }
                                            
                                            if($isTab !== "all"){
                                                $query .= " AND LOWER(c.Status) = :status";
                                                $params[':status'] = strtolower($isTab);
                                            }
                                            
                                            $query .= " ORDER BY c.Name ASC";
                                            
                                            $stmt = $conn->prepare($query);
                                            foreach ($params as $key => $value) {
                                                $stmt->bindValue($key, $value);
                                            }
                                            $stmt->execute();
                                            $n = 1;
                                            
                                            while ($row = $stmt->fetch(PDO::FETCH_OBJ)): 
                                                $status = !empty($row->Status) ? strtolower($row->Status) : "not updated";
                                            ?>
                                                <tr class="client-row" 
                                                    data-name="<?php echo strtolower($row->Name); ?>"
                                                    data-email="<?php echo strtolower($row->Email); ?>"
                                                    data-status="<?php echo $status; ?>"
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
                                                            <span class="badge bg-secondary">Not Updated</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row->Email); ?></td>
                                                    <td><?php echo htmlspecialchars($row->Number); ?></td>
                                                    <td><?php echo htmlspecialchars($row->City . ', ' . $row->Address); ?></td>
                                                    <td><?php echo htmlspecialchars($row->CreatedByName); ?></td>
                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/edit_client/?ID=<?php echo $row->ClientID; ?>">
                                                                    <span class="text-info">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                            <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                        </svg>
                                                                    </span>
                                                                    Edit</a>
                                                                <?php if (IsCheckPermission($USERID, "DELETE_CLIENT")) : ?>
                                                                    <a class="dropdown-item delete-entry" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                                        <span class="text-danger">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                            </svg>
                                                                        </span>
                                                                        Delete</a>
                                                                <?php endif; ?>
                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/view_client/?ID=<?php echo $row->ClientID; ?>">
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
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                    <?php if ($stmt->rowCount() == 0) : ?>
                                        <div class="alert alert-danger">
                                            No clients found matching your criteria.
                                        </div>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php DeniedAccess(); ?>
                                <?php endif; ?>

                                <?php if (isset($_GET['q'])) : ?>
                                    <a href="<?php echo $LINK; ?>/clients">
                                        <button class="btn btn-primary">Reset Search</button>
                                    </a>
                                    <span style="margin-left: 20px;"><?php echo $stmt->rowCount() . ' ' . ($stmt->rowCount() == 1 ? 'client' : 'clients'); ?> found</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mailshot History -->
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Mailshots</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Subject</th>
                                            <th>Recipients</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $mailshots = $conn->prepare("
                                            SELECT DATE(m.SentDate) as sent_date, m.Subject, 
                                                   COUNT(m.id) as recipients,
                                                   SUM(CASE WHEN m.Status = 'Sent' THEN 1 ELSE 0 END) as success_count,
                                                   u.Name as sent_by
                                            FROM mailshot_log m
                                            JOIN users u ON m.SentBy = u.UserID
                                            WHERE m.SentBy = :userID
                                            GROUP BY DATE(m.SentDate), m.Subject
                                            ORDER BY m.SentDate DESC
                                            LIMIT 5
                                        ");
                                        $mailshots->bindParam(':userID', $USERID);
                                        $mailshots->execute();
                                        
                                        while ($mailshot = $mailshots->fetch(PDO::FETCH_OBJ)):
                                        ?>
                                        <tr>
                                            <td><?php echo FormatDate($mailshot->sent_date); ?></td>
                                            <td><?php echo htmlspecialchars($mailshot->Subject); ?></td>
                                            <td>
                                                <?php echo $mailshot->recipients; ?>
                                                (<?php echo $mailshot->success_count; ?> sent)
                                            </td>
                                            <td>
                                                <?php echo $mailshot->success_count == $mailshot->recipients ? 
                                                    '<span class="badge bg-success">Success</span>' : 
                                                    '<span class="badge bg-warning">Partial</span>'; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="viewMailshotDetails('<?php echo $mailshot->sent_date; ?>', '<?php echo htmlspecialchars($mailshot->Subject, ENT_QUOTES); ?>')">
                                                    Details
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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

    <!-- Mailshot Modal -->
    <div id="MailshotModal" class="modal fade" tabindex="-1" aria-labelledby="MailshotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Mailshot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="mailshotForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Selected Clients (<span id="modalSelectedCount">0</span>):</label>
                            <div id="selectedClientsList" class="border rounded p-2 bg-light" style="max-height: 150px; overflow-y: auto;">
                                <!-- Selected clients will be listed here -->
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">From:</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars("$from_name <$from_email>"); ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Subject: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="mailshot_subject" required placeholder="Enter email subject">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Message: <span class="text-danger">*</span></label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_html" id="isHtmlSwitch" checked>
                                <label class="form-check-label" for="isHtmlSwitch">HTML Format</label>
                            </div>
                            <textarea class="form-control" id="mailshotMessage" name="mailshot_message" rows="10" required placeholder="Enter your message here"></textarea>
                            <small class="text-muted">
                                Placeholders: <code>[CLIENT_NAME]</code>, <code>[CLIENT_EMAIL]</code>, <code>[CLIENT_ID]</code> will be replaced with actual values.
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Preview:</label>
                            <div id="messagePreview" class="border rounded p-3 bg-light" style="min-height: 100px;">
                                Preview will appear here
                            </div>
                        </div>
                        
                        <input type="hidden" name="selected_clients" id="selectedClientsInput">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="send_mailshot" class="btn btn-primary" id="sendMailshotBtn">
                            <i class="ti ti-send"></i> Send Mailshot
                        </button>
                        <div id="sendingProgress" class="d-none">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Sending...</span>
                            </div>
                            <span class="ms-2">Sending <span id="progressCount">0</span>/<span id="totalCount">0</span></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Mailshot Details Modal -->
    <div class="modal fade" id="mailshotDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mailshotDetailsTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mailshotDetailsContent">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search Modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Advanced Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="SearchID" value="<?php echo uniqid(); ?>">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Client Type</label>
                                    <select name="ClientType" class="form-select">
                                        <option value=""></option>
                                        <?php foreach ($client_types as $type): ?>
                                            <option><?php echo htmlspecialchars($type); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Client ID</label>
                                    <input type="text" class="form-control" name="_client_id">
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
                                    <label class="form-label">Status</label>
                                    <select name="Status" class="form-select">
                                        <option value=""></option>
                                        <?php foreach ($statuses as $status): ?>
                                            <option><?php echo htmlspecialchars($status); ?></option>
                                        <?php endforeach; ?>
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
</body>

<?php include "../../includes/js.php"; ?>
<script>
    let visibleRows = 0;

    // Apply filters function
    function applyFilters() {
        const nameFilter = document.getElementById('nameFilter').value.toLowerCase();
        const emailFilter = document.getElementById('emailFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const clientTypeFilter = document.getElementById('clientTypeFilter').value.toLowerCase();
        
        const rows = document.querySelectorAll('.client-row');
        visibleRows = 0;
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const status = row.getAttribute('data-status');
            const clientType = row.getAttribute('data-clienttype');
            
            const nameMatch = !nameFilter || name.includes(nameFilter);
            const emailMatch = !emailFilter || email.includes(emailFilter);
            const statusMatch = !statusFilter || status.includes(statusFilter);
            const clientTypeMatch = !clientTypeFilter || clientType.includes(clientTypeFilter);
            
            if (nameMatch && emailMatch && statusMatch && clientTypeMatch) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
                // Uncheck hidden rows
                const checkbox = row.querySelector('.checkbox-item');
                if (checkbox && checkbox.checked) {
                    checkbox.checked = false;
                }
            }
        });
        
        updateFilterResults();
        updateSelectedCount();
    }

    // Clear all filters
    function clearAllFilters() {
        document.getElementById('nameFilter').value = '';
        document.getElementById('emailFilter').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('clientTypeFilter').value = '';
        applyFilters();
    }

    // Update filter results display
    function updateFilterResults() {
        const totalRows = document.querySelectorAll('.client-row').length;
        document.getElementById('filterResults').textContent = 
            `Showing ${visibleRows} of ${totalRows} clients`;
    }

    // Update selected count
    function updateSelectedCount() {
        const visibleCheckboxes = document.querySelectorAll('.client-row:not([style*="display: none"]) .checkbox-item');
        const selectedCheckboxes = document.querySelectorAll('.client-row:not([style*="display: none"]) .checkbox-item:checked');
        
        const count = selectedCheckboxes.length;
        document.getElementById('selectedCount').textContent = count;
        document.getElementById('modalSelectedCount').textContent = count;
        
        // Show/hide mailshot button
        const mailshotBtn = document.getElementById('mailshotBtn');
        if (count > 0) {
            mailshotBtn.style.display = 'inline-block';
        } else {
            mailshotBtn.style.display = 'none';
        }
        
        // Update select all checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        if (visibleCheckboxes.length > 0) {
            selectAllCheckbox.indeterminate = count > 0 && count < visibleCheckboxes.length;
            selectAllCheckbox.checked = count === visibleCheckboxes.length;
        }
    }

    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const visibleCheckboxes = document.querySelectorAll('.client-row:not([style*="display: none"]) .checkbox-item');
        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Mailshot preview functionality
    document.getElementById('mailshotMessage').addEventListener('input', function() {
        const preview = document.getElementById('messagePreview');
        const isHtml = document.getElementById('isHtmlSwitch').checked;
        let message = this.value;
        
        // Replace placeholders with sample data for preview
        message = message.replace(/\[CLIENT_NAME\]/g, 'John Doe')
                        .replace(/\[CLIENT_EMAIL\]/g, 'john@example.com')
                        .replace(/\[CLIENT_ID\]/g, '12345');
        
        if (isHtml) {
            preview.innerHTML = message;
        } else {
            preview.textContent = message;
        }
    });

    // Handle mailshot form submission with AJAX
    document.getElementById('mailshotForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('sendMailshotBtn');
        const progressDiv = document.getElementById('sendingProgress');
        const progressCount = document.getElementById('progressCount');
        const totalCount = document.getElementById('totalCount');
        
        // Get selected clients count
        const selectedClients = JSON.parse(document.getElementById('selectedClientsInput').value);
        totalCount.textContent = selectedClients.length;
        
        // Show progress, disable button
        submitBtn.classList.add('d-none');
        progressDiv.classList.remove('d-none');
        
        // Submit form via AJAX
        fetch(window.location.href, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update progress to show completion
                progressCount.textContent = data.sent;
                
                // Show success message
                ShowToast({
                    type: data.failed > 0 ? 'warning' : 'success',
                    message: data.message
                });
                
                // Close modal after delay
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('MailshotModal')).hide();
                    // Reload page to see updates
                    window.location.reload();
                }, 2000);
                
                // Show detailed errors if any
                if (data.failed > 0) {
                    console.error("Mailshot errors:", data.errors);
                }
            } else {
                ShowToast({
                    type: 'error',
                    message: data.message
                });
                submitBtn.classList.remove('d-none');
                progressDiv.classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            ShowToast({
                type: 'error',
                message: 'An error occurred while sending the mailshot: ' + error.message
            });
            submitBtn.classList.remove('d-none');
            progressDiv.classList.add('d-none');
        });
    });

    // Open mailshot modal
    function openMailshotModal() {
        const selectedCheckboxes = document.querySelectorAll('.client-row:not([style*="display: none"]) .checkbox-item:checked');
        const selectedClientsList = document.getElementById('selectedClientsList');
        const selectedClientsInput = document.getElementById('selectedClientsInput');
        
        let clientsHtml = '';
        let clientIds = [];
        
        selectedCheckboxes.forEach(checkbox => {
            const name = checkbox.getAttribute('data-name');
            const email = checkbox.getAttribute('data-email');
            clientIds.push(checkbox.value);
            clientsHtml += `<div class="mb-1"><strong>${name}</strong> &lt;${email}&gt;</div>`;
        });
        
        selectedClientsList.innerHTML = clientsHtml;
        selectedClientsInput.value = JSON.stringify(clientIds);
        
        // Reset form state
        document.getElementById('mailshotForm').reset();
        document.getElementById('messagePreview').innerHTML = 'Preview will appear here';
        document.getElementById('sendMailshotBtn').classList.remove('d-none');
        document.getElementById('sendingProgress').classList.add('d-none');
        
        const modal = new bootstrap.Modal(document.getElementById('MailshotModal'));
        modal.show();
    }

    // View mailshot details
    function viewMailshotDetails(date, subject) {
        const modal = new bootstrap.Modal(document.getElementById('mailshotDetailsModal'));
        document.getElementById('mailshotDetailsTitle').textContent = subject + ' - ' + date;
        
        // Fetch details via AJAX
        fetch(`ajax/get_mailshot_details.php?date=${encodeURIComponent(date)}&subject=${encodeURIComponent(subject)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById('mailshotDetailsContent').innerHTML = data;
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('mailshotDetailsContent').innerHTML = 
                    '<div class="alert alert-danger">Error loading details: ' + error.message + '</div>';
                modal.show();
            });
    }

    // Delete functionality
    document.getElementById('confirmDelete').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.checkbox-item:checked');
        let ids = [];
        let successCount = 0;
        var reason = document.getElementById('reason').value;

        if (reason.length > 0) {
            checkboxes.forEach(function(checkbox) {
                ids.push({
                    id: checkbox.value,
                    name: checkbox.getAttribute('data-name')
                });
            });

            if (ids.length > 0) {
                document.getElementById('confirmDelete').textContent = "Deleting...";
                ids.forEach(function(item) {
                    fetch(window.location.href, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            ID: item.id,
                            name: item.name,
                            reason: reason,
                            delete: true
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(() => {
                        successCount++;
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        ShowToast({
                            type: 'error',
                            message: 'Error deleting client: ' + error.message
                        });
                    });
                });
            } else {
                ShowToast({
                    type: 'error',
                    message: 'Error: No clients selected for deletion'
                });
            }
        } else {
            ShowToast({
                type: 'error',
                message: 'Error: Reason field is required'
            });
            return;
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateFilterResults();
        updateSelectedCount();
        
        // Initialize the message preview
        const messageTextarea = document.getElementById('mailshotMessage');
        if (messageTextarea) {
            messageTextarea.dispatchEvent(new Event('input'));
        }
    });
</script>

</html>