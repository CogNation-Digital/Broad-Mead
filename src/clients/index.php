<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    header("location: $LINK/login ");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $db_1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_2 = new PDO($dsn2, $user, $password);
    $db_2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<b>Database Connection Error: </b> " . $e->getMessage();
    exit;
}

// Process mailshot when form is submitted
if (isset($_POST['send_mailshot'])) {
    $_SESSION['mailshot_initiated'] = true;

    $selected_clients = json_decode($_POST['selected_clients'], true) ?? [];
    $mailshot_subject = $_POST['mailshot_subject'] ?? '';
    $mailshot_message = $_POST['mailshot_message'] ?? '';
    
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

        $success_count = 0;
        $error_count = 0;
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

                        $personalized_message = str_replace('[CLIENT_NAME]', $client->Name, $mailshot_message);
                        
                        $mail->setFrom($from_email, $from_name);
                        $mail->addAddress($client->Email, $client->Name);
                        $mail->addReplyTo($from_email, $from_name);
                        $mail->isHTML(true);
                        $mail->Subject = $mailshot_subject;
                        $mail->Body = nl2br(htmlspecialchars($personalized_message));
                        $mail->AltBody = strip_tags($personalized_message);

                         if ($mail->send()) {
       $success_count++;
       
       // Log the mailshot using ClientKeyID
       if ($mail->send()) {
    $success_count++;
    
    // Log the mailshot using the correct column names
    $log_query = $db_2->prepare("INSERT INTO mailshot_log (ClientKeyID, Subject, Template, SentBy, SentDate) 
                                  VALUES (:client_key_id, :subject, :template, :sent_by, NOW())");
    $log_query->bindParam(':client_key_id', $clientKeyID); // Make sure you have this variable set
    $log_query->bindParam(':subject', $mailshot_subject);
    $log_query->bindParam(':template', $mailshot_template); // Use the appropriate variable for the template
    $log_query->bindParam(':sent_by', $USERID);
    
    // if ($log_query->execute()) {
    //     // Log successful
    // } else {
    //     echo "Error logging mailshot: " . implode(", ", $log_query->errorInfo());
    // }
} else {
    $error_count++;
    $error_details[] = "Failed to send to: {$client->Email} - " . $mail->ErrorInfo;
}
if ($mail->send()) {
    $success_count++;
    
    // Check if $clientKeyID is set
    if (empty($clientKeyID)) {
        // echo "Error: ClientKeyID is not set.";
        // $error_count++;
        // $error_details[] = "ClientKeyID is not available for logging.";
    } else {
        // Log the mailshot using the correct column names
        $log_query = $db_2->prepare("INSERT INTO mailshot_log (ClientKeyID, Subject, Template, SentBy, SentDate) 
                                      VALUES (:client_key_id, :subject, :template, :sent_by, NOW())");
        $log_query->bindParam(':client_key_id', $clientKeyID);
        $log_query->bindParam(':subject', $mailshot_subject);
        $log_query->bindParam(':template', $mailshot_template);
        $log_query->bindParam(':sent_by', $USERID);
        
        if ($log_query->execute()) {
            // Log successful
        } else {
            echo "Error logging mailshot: " . implode(", ", $log_query->errorInfo());
        }
    }
} else {
    $error_count++;
    $error_details[] = "Failed to send to: {$client->Email} - " . $mail->ErrorInfo;
}

$mail->clearAddresses();
usleep(100000); // Small delay between emails
                        } else {
                            $error_count++;
                            $error_details[] = "Failed to send to: {$client->Email} - " . $mail->ErrorInfo;
                        }
                    } else {
                        $error_count++;
                        $error_details[] = "Invalid email for client ID: $client_id";
                    }
                } catch (Exception $e) {
                    $error_count++;
                    $error_details[] = "Error processing client ID: $client_id - " . $e->getMessage();
                }
            }

            if ($success_count > 0) {
                $success_message = "Successfully sent mailshot to $success_count clients.";
                $NOTIFICATION = "$NAME has successfully sent mailshot to $success_count clients.";
                Notify($USERID, $ClientKeyID, $NOTIFICATION);
            }
            
            if ($error_count > 0) {
                $error_message = "Mailshot completed with $error_count errors. " . implode("\n", array_slice($error_details, 0, 5));
                if (count($error_details) > 5) {
                    $error_message .= "\n... plus " . (count($error_details) - 5) . " more errors.";
                }
            }
        }
    }
}

// Handle search separately
if (isset($_POST['Search'])) {
    $Name = $_POST['Name'] ?? '';
    $ClientType = $_POST['ClientType'] ?? '';
    $_client_id = $_POST['_client_id'] ?? '';
    $EmailAddress = $_POST['Email'] ?? '';
    $PhoneNumber = $_POST['Number'] ?? '';
    $Address = $_POST['Address'] ?? '';
    $Postcode = $_POST['Postcode'] ?? '';
    $City = $_POST['City'] ?? '';
    
    if (!empty($SearchID)) {
        $query = $db_2->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
                  VALUES (:SearchID, :column, :value)");

        foreach ($_POST as $key => $value) {
            if (!empty($value) && $key !== 'Search') {
                $query->bindParam(':SearchID', $SearchID);
                $query->bindParam(':column', $key);
                $query->bindParam(':value', $value);
                $query->execute();
            }
        }

        header("location: $LINK/clients/?q=$SearchID");
        exit();
    }
}

if (isset($_POST['delete'])) {
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("DELETE FROM `_clients` WHERE ClientID = :ID");
    $stmt->bindParam(':ID', $ID);

    if ($stmt->execute()) {
        $NOTIFICATION = "$NAME has successfully deleted the client named '$name'. Reason for deletion: $reason.";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    } else {
        echo "Error deleting record";
    }
}

if (isset($_POST['send_mailshot'])) {
    $selected_clients = $_POST['selected_clients'];
    $mailshot_subject = $_POST['mailshot_subject'];
    $mailshot_message = $_POST['mailshot_message'];
    
    if (!empty($selected_clients) && !empty($mailshot_subject) && !empty($mailshot_message)) {
        $success_count = 0;
        foreach ($selected_clients as $client_id) {
            // Get client details
            $client_query = $conn->prepare("SELECT Name, Email FROM `_clients` WHERE ClientID = :client_id");
            $client_query->bindParam(':client_id', $client_id);
            $client_query->execute();
            $client = $client_query->fetchObject();
            
            if ($client && !empty($client->Email)) {
                // Send email (replace with your email sending function)
                $to = $client->Email;
                $subject = $mailshot_subject;
                $message = str_replace('[CLIENT_NAME]', $client->Name, $mailshot_message);
                
                // Log mailshot
                $log_query = $conn->prepare("INSERT INTO `mailshot_log` (ClientID, Subject, Message, SentBy, SentDate) VALUES (:client_id, :subject, :message, :sent_by, NOW())");
                $log_query->bindParam(':client_id', $client_id);
                $log_query->bindParam(':subject', $subject);
                $log_query->bindParam(':message', $message);
                $log_query->bindParam(':sent_by', $USERID);
                $log_query->execute();
                
                $success_count++;
            }
        }
        
        $NOTIFICATION = "$NAME has successfully sent mailshot to $success_count clients.";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    }
}

if (isset($_POST['Search'])) {
    $Name = $_POST['Name'];
    $ClientType = $_POST['ClientType'];
    $_client_id = $_POST['_client_id'];
    $EmailAddress = $_POST['Email'];
    $PhoneNumber = $_POST['Number'];
    $Address = $_POST['Address'];
    $Postcode = $_POST['Postcode'];
    $City = $_POST['City'];
    
    if (!empty($SearchID)) {
        $query = $conn->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
                  VALUES (:SearchID, :column, :value)");

        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                $query->bindParam(':SearchID', $SearchID);
                $query->bindParam(':column', $key);
                $query->bindParam(':value', $value);
                $query->execute();
            }
        }

        header("location: $LINK/clients/?q=$SearchID");
        exit();
    }
}

$SearchID = isset($_GET['q']) ? $_GET['q'] : "";
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "all";
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
                                        <?php foreach ($clientype as $type) { ?>
                                            <option value="<?php echo strtolower($type); ?>"><?php echo $type; ?></option>
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
                                        <a href="<?php echo $LINK; ?>/clients<?php echo !empty($SearchID) ? "/?q=$SearchID" : "/?i=0"  ?>&isTab=<?php echo $tab; ?>">
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
                                            $query = "SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isBranch IS NULL ";

                                            if (isset($_GET['q'])) {
                                                $SearchID = $_GET['q'];
                                                $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                                                while ($r = $qu->fetchObject()) {
                                                    $column = $r->column;
                                                    $value = $r->value;
                                                    $query .= " AND " . $column . " LIKE '%$value%'";
                                                }
                                            }
                                            
                                            if($isTab !== "all"){
                                                 $query .= " AND Status = '$isTab'";
                                            }
                                            
                                            $query .= " ORDER BY Name ASC";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $n = 1;
                                            while ($row = $stmt->fetchObject()) { ?>
                                                <?php
                                                $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
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
                                                               data-name="<?php echo $row->Name; ?>"
                                                               data-email="<?php echo $row->Email; ?>"
                                                               onchange="updateSelectedCount()">
                                                    </td>
                                                    <td><?php echo $row->Name; ?></td>
                                                    <td><?php echo $row->_client_id; ?></td>
                                                    <td><?php echo $row->ClientType; ?></td>
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
                                                    <td><?php echo $row->Email; ?></td>
                                                    <td><?php echo $row->Number; ?></td>
                                                    <td><?php echo $row->City . ', ' . $row->Address; ?></td>
                                                    <td><?php echo $CreatedBy; ?></td>
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
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <?php if ($stmt->rowCount() == 0) : ?>
                                        <div class="alert alert-danger">
                                            No data found.
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
                            <label class="form-label">Subject:</label>
                            <input type="text" class="form-control" name="mailshot_subject" required placeholder="Enter email subject">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message:</label>
                            <textarea class="form-control" name="mailshot_message" rows="8" required placeholder="Enter your message here. Use [CLIENT_NAME] to personalize the message."></textarea>
                            <small class="text-muted">Tip: Use [CLIENT_NAME] in your message to automatically insert the client's name.</small>
                        </div>
                        <input type="hidden" name="selected_clients" id="selectedClientsInput">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="send_mailshot" class="btn btn-primary">
                            <i class="ti ti-send"></i> Send Mailshot
                        </button>
                    </div>
                </form>
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
                                    <select name="ClientType" class="select-input">
                                        <option value=""></option>
                                        <?php foreach ($clientype as $type) { ?>
                                            <option><?php echo $type; ?></option>
                                        <?php } ?>
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
                                    <input type="text" class="form-control" name="PostCode">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="City">
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
                if (checkbox.checked) {
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
            clientsHtml += `<div class="mb-1"><strong>${name}</strong> - ${email}</div>`;
        });
        
        selectedClientsList.innerHTML = clientsHtml;
        selectedClientsInput.value = JSON.stringify(clientIds);
        
        const modal = new bootstrap.Modal(document.getElementById('MailshotModal'));
        modal.show();
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateFilterResults();
        updateSelectedCount();
    });

    // Delete    // Delete functionality
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
                            delete: true
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
</script>

</html>
