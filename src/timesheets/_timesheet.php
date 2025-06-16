<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$TimesheetID = $_GET['ID'];
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "Timesheet";

// Prepare and execute the query to fetch timesheet data
$stmt = $conn->prepare("SELECT * FROM _timesheet WHERE TimesheetID = :TimesheetID");
$stmt->bindParam(':TimesheetID', $TimesheetID, PDO::PARAM_STR);
$stmt->execute();
$data = $stmt->fetchObject();

if (!$data) {
    header("Location: $LINK/timesheets");
    exit;
}

$VacancyID = $data->VacancyID;
$TimesheetNo = $data->TimesheetNo;
$hasClientID = $data->hasClientID;
$hasBranchID = empty($data->hasBranchID) ? '' : $data->hasBranchID;
$CandidateID = empty($data->CandidateID) ? '' : $data->CandidateID;

$ClientID  = empty($data->hasBranchID) ? $hasClientID : $hasBranchID;



// Prepare and execute the query to fetch candidate data
$stmt = $conn->prepare("SELECT * FROM _candidates WHERE CandidateID = :CandidateID");
$stmt->bindParam(':CandidateID', $data->CandidateID, PDO::PARAM_STR);
$stmt->execute();
$CandidateData = $stmt->fetchObject();


$fetch_vacancy_stmt = $conn->prepare("SELECT * FROM vacancies WHERE VacancyID = :VacancyID");
$fetch_vacancy_stmt->bindParam(':VacancyID', $data->VacancyID, PDO::PARAM_STR);
$fetch_vacancy_stmt->execute();
$VacancyData = $fetch_vacancy_stmt->fetchObject();



$fetch_client_stmt = $conn->prepare("SELECT * FROM _clients WHERE ClientID = :ClientID");
$fetch_client_stmt->bindParam(':ClientID', $ClientID, PDO::PARAM_STR);
$fetch_client_stmt->execute();
$ClientData = $fetch_client_stmt->fetchObject();

$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true); // Decode JSON data into associative array

// Check if the required data is received
if (isset($inputData['UpdateTimesheet'])) {
    // Extract data from the received JSON
    $id = $inputData['id'];
    $shiftType = $inputData['ShiftType'];
    $startTime = $inputData['StartTime'];
    $endTime = $inputData['EndTime'];
    $hours = $inputData['Hours'];
    $payRate = $inputData['PayRate'];
    $supplyRate = $inputData['SupplyRate'];
    $margin = $inputData['Margin']; // Include Margin from input data
    $totalMargin = $inputData['TotalMargin']; // Include TotalMargin from input data
    $shiftDate = $inputData['ShiftDate'];

    // Update query with Margin and TotalMargin fields
    $query = "UPDATE `__time_sheets` SET 
                ShiftType = :shiftType,
                StartTime = :startTime,
                EndTime = :endTime,
                Hours = :hours,
                PayRate = :payRate,
                SupplyRate = :supplyRate,
                Margin = :margin,
                TimesheetDate = :shiftDate
                WHERE id = :id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':shiftType', $shiftType);
    $stmt->bindParam(':startTime', $startTime);
    $stmt->bindParam(':endTime', $endTime);
    $stmt->bindParam(':hours', $hours);
    $stmt->bindParam(':payRate', $payRate);
    $stmt->bindParam(':supplyRate', $supplyRate);
    $stmt->bindParam(':margin', $margin);
    $stmt->bindParam(':shiftDate', $shiftDate);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(array('status' => 'success', 'message' => 'Timesheet updated successfully'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to update Timesheet'));
    }
}

if (isset($_POST['AddShift'])) {
    // Define the SQL query with placeholders
    $query = "INSERT INTO `__time_sheets` (`TimesheetID`, `CreatedBy`, `Date`) VALUES (:TimesheetID, :CreatedBy, :Date)";
    $stmt = $conn->prepare($query);

    // Bind the parameters to the SQL query
    $stmt->bindParam(':TimesheetID', $TimesheetID); // Already defined from GET parameter
    $stmt->bindParam(':CreatedBy', $USERID); // Already defined from SESSION
    $stmt->bindParam(':Date', $date); // Use the current date

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $response = 'Timesheet added successfully';
        $Modification = "Added timesheet row";
        LastModified($TimesheetID, $USERID, $Modification);
    } else {
        $response = 'Failed to add Shift';
    }
}

if (isset($_POST['DeleteTimesheet'])) {
    $ID = $_POST['ID'];
    $del = "DELETE FROM `__time_sheets` WHERE id = '$ID'";
    $stmt = $conn->prepare($del);
    $stmt->execute();
    $Modification = "$NAME deleted Time Sheet Row ID $ID";
    $Notification = "$NAME successfully deleted Time Sheet Row ID $ID from Timesheet No  $TimesheetNo";
    LastModified($TimesheetID, $USERID, $Modification);
    Notify($USERID, $ClientKeyID, $Notification);
}


if (isset($_POST['ApproveTimesheet'])) {


    $update = "UPDATE `_timesheet` SET `isApproved` = 'Approved' WHERE `TimesheetID`='$TimesheetID'";
    $stmt = $conn->prepare($update);
    $stmt->execute();
    $response = 'Timesheet successfully approved';

    $Modification = "$NAME approved Timesheet";
    $Notification = "$NAME successfully approved Timesheet No  $TimesheetNo";
    LastModified($TimesheetID, $USERID, $Modification);
    Notify($USERID, $ClientKeyID, $Notification);
}



if (isset($_POST['DisapproveTimesheet'])) {


    $update = "UPDATE `_timesheet` SET `isApproved` = '' WHERE `TimesheetID`='$TimesheetID'";
    $stmt = $conn->prepare($update);
    $stmt->execute();
    $response = 'Timesheet successfully disapproved';

    $Modification = "$NAME disapproved Timesheet";
    $Notification = "$NAME successfully disapproved Timesheet No  $TimesheetNo";
    LastModified($TimesheetID, $USERID, $Modification);
    Notify($USERID, $ClientKeyID, $Notification);
}


$CheckInvoice = "SELECT * FROM `_invoices` WHERE TimesheetID = '$TimesheetID'";
$CheckInvoiceResult = $conn->query($CheckInvoice);
$NumInvoice = $CheckInvoiceResult->rowCount();


$InvoiceQuery = "SELECT * FROM `_invoices` WHERE ClientKeyID = :ClientKeyID AND TimesheetID = :TimesheetID";
$stmt = $conn->prepare($InvoiceQuery);
$stmt->bindParam(':ClientKeyID', $ClientKeyID);
$stmt->bindParam(':TimesheetID', $TimesheetID);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $InvoiceData = $stmt->fetchObject();
    $InvoiceID = $InvoiceData->InvoiceID;
    $InvoiceNo = $InvoiceData->Number;
}






if (isset($_POST['CreateInvoice'])) {
    $Number = $_POST['Number'];
    $Status = $_POST['Status'];
    $InvoiceDate = $_POST['InvoiceDate'];
    $DueDate = $_POST['DueDate'];
    $Notes = $_POST['Notes'];
    $InvoiceID = $RandomID; // Assuming this will be auto-generated, hence passing NULL

    $query = $conn->prepare("INSERT INTO `_invoices`(`ClientKeyID`, `hasClientID`, `hasBranchID`, `InvoiceID`, `TimesheetID`, `CandidateID`, `Number`, `Status`, `DueDate`, `InvoiceDate`, `Note`, `CreatedBy`, `Date`) 
    VALUES (:ClientKeyID, :hasClientID, :hasBranchID, :InvoiceID, :TimesheetID, :CandidateID, :Number, :Status, :DueDate, :InvoiceDate, :Notes, :CreatedBy, :Date)");

    $query->bindParam(':ClientKeyID', $ClientKeyID);
    $query->bindParam(':hasClientID', $hasClientID);
    $query->bindParam(':hasBranchID', $hasBranchID);
    $query->bindParam(':InvoiceID', $InvoiceID);
    $query->bindParam(':TimesheetID', $TimesheetID);
    $query->bindParam(':CandidateID', $CandidateID);
    $query->bindParam(':Number', $Number);
    $query->bindParam(':Status', $Status);
    $query->bindParam(':DueDate', $DueDate);
    $query->bindParam(':InvoiceDate', $InvoiceDate);
    $query->bindParam(':Notes', $Notes);
    $query->bindParam(':CreatedBy', $USERID);
    $query->bindParam(':Date', $date);

    if ($query->execute()) {
        $Modification = "$NAME created Invoice";
        $Notification = "$NAME successfully created an invoice for Timesheet No $TimesheetNo";
        LastModified($TimesheetID, $USERID, $Modification);
        Notify($USERID, $ClientKeyID, $Notification);

        $query = "SELECT * FROM `__time_sheets` WHERE TimesheetID = :TimesheetID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':TimesheetID', $TimesheetID);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $Name = $CandidateData->Name;
            $Date = $row->TimesheetDate;
            $Description = "$row->ShiftType shift from $row->StartTime to $row->EndTime";
            $Hours = $row->Hours;
            $Rate = $row->SupplyRate;

            if (!empty($row->TimesheetDate) || !empty($row->ShiftType) || !empty($row->SupplyRate)) {
                $save_items = $conn->prepare("INSERT INTO `_invoice_`(`id`, `InvoiceID`, `Name`, `Date`, `Description`, `Rate`, `Hours`) VALUES (NULL, :InvoiceID, :Name, :Date, :Description, :Rate, :Hours)");
                $save_items->bindParam(':InvoiceID', $InvoiceID);
                $save_items->bindParam(':Name', $Name);
                $save_items->bindParam(':Date', $Date);
                $save_items->bindParam(':Description', $Description);
                $save_items->bindParam(':Rate', $Rate);
                $save_items->bindParam(':Hours', $Hours);
                $save_items->execute();
            }
        }
        $response = "Invoice created successfully.";
    } else {
        $response = "Error: " . $query->errorInfo()[2];
        $error = 1;
    }
}

if (isset($_POST['SendEmail'])) {
    $Emails = $_POST['Emails'];
    $Subject = $_POST['Subject'];
    $Message = $_POST['Message'];

    $template = $LINK . "/templates/invoice?ID=$InvoiceID";


    $emailArray = explode(',', $Emails);

    $emailArray = array_map('trim', $emailArray);

    $invalidEmails = [];
    foreach ($emailArray as $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $invalidEmails[] = $email;
        }
    }

    if (count($invalidEmails) > 0) {
        $response =  "Something went wrong. Please try again.";
        $error = 1;
    } else {
        foreach ($emailArray as $email) {
            if (SendEmail($email, $Subject, $Message, true, $template)) {
                $response = "The email was successfully sent";
                $Description = "The invoice email was successfully sent to the client.";
                SaveEmailLogs($email, $Description, "Success");
                $Modification = "An invoice email was sent to $email.";
                LastModified($TimesheetID, $USERID, $Modification);
                $Notification = "$NAME successfully sent an invoice email to " . $email . ". Invoice Number: $InvoiceNo.";
                Notify($USERID, $ClientKeyID, $Notification);
            } else {
                $response = "Unfortunately, the email could not be sent to " . $email;
                $error = 1;
                $Description = "Failed to send the invoice email to the client.";
                SaveEmailLogs($email, $Description, "Fail");
                $Modification = "Attempted to send an invoice email to $email, but it failed.";
                LastModified($TimesheetID, $USERID, $Modification);
                $Notification = "$NAME attempted to send an invoice email to " . $email . " but failed. Invoice Number: $InvoiceNo.";
                Notify($USERID, $ClientKeyID, $Notification);
            }
        }
    }
}


if (isset($_POST['DeleteInvoice'])) {
    $reason = $_POST['reason'];
    $query = "SELECT * FROM `_invoices` WHERE TimesheetID = :TimesheetID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':TimesheetID', $TimesheetID);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_OBJ);
    $InvoiceID = $data->InvoiceID;

    $del = $conn->query("DELETE FROM `_invoices` WHERE `TimesheetID`='$TimesheetID'");
    $del = $conn->query("DELETE FROM `_invoice_` WHERE `InvoiceID`='$InvoiceID'");
    $Modification = "Deleted Invoice";
    LastModified($TimesheetID, $USERID, $Modification);

    $Notification = "$NAME deleted an invoice. Reason: $reason";
    Notify($USERID, $ClientKeyID, $Notification);

    $response = "Invoices deleted successfully";
}

if (isset($_POST['UpdateInvoiceStatus'])) {
    $status = $_POST['Status'];
    $InvoiceNumber = $_POST['InvoiceNumber'];
    $query = "UPDATE `_invoices` SET `Status`='$status' WHERE `TimesheetID`='$TimesheetID'";
    $conn->exec($query);
    $Modification = "Updated Invoice Status";
    LastModified($TimesheetID, $USERID, $Modification);

    $Notification = "$NAME updated  invoice INV/$InvoiceNumber status to $status ";
    Notify($USERID, $ClientKeyID, $Notification);
    $response = "Invoice updated successfully";
}



?>

<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>
<style>
    @media print {
        /* @page {
            size: landscape;
        } */

        .card,
        .card-body,
        .border,
        .table th,
        .table td {
            border: none !important;
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
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Timesheet</h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "CREATE_TIMESHEET")) : ?>

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1 ">
                                        <ul class="nav nav-tabs analytics-tab" style="margin-bottom: 10px;">
                                            <li class="nav-item" role="presentation">
                                                <a href="<?php echo $LINK ?>/generate_timesheet/?ID=<?php echo $TimesheetID ?>&isTab=Details" class="nav-link <?php echo ($isTab == 'Details') ? 'active' : ''; ?>">Details</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="<?php echo $LINK ?>/generate_timesheet/?ID=<?php echo $TimesheetID ?>&isTab=Timesheet" class="nav-link <?php echo ($isTab == 'Timesheet') ? 'active' : ''; ?>">Update Timesheet</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="<?php echo $LINK ?>/generate_timesheet/?ID=<?php echo $TimesheetID ?>&isTab=Invoice" class="nav-link <?php echo ($isTab == 'Invoice') ? 'active' : ''; ?>">Invoice</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a href="<?php echo $LINK ?>/generate_timesheet/?ID=<?php echo $TimesheetID ?>&isTab=Modifications" class="nav-link <?php echo ($isTab == 'Modifications') ? 'active' : ''; ?>">Modifications</a>
                                            </li>

                                        </ul>
                                    </div>
                                    <?php if ($isTab == 'Timesheet') : ?>
                                        <div class="flex-shrink-0">
                                            <form method="POST">
                                                <button name="AddShift" class="btn  btn-primary"><i class="ti ti-plus" style="margin-top: 10px;"></i> Add</button>

                                            </form>

                                        </div>
                                    <?php endif; ?>

                                    <?php if ($isTab == 'Details') : ?>
                                        <?php if (IsCheckPermission($USERID, "APPROVE_DISAPPROVE_TIMESHEET")) : ?>
                                            <div class="flex-shrink-0">
                                                <?php if (empty($data->isApproved)) : ?>
                                                    <span data-bs-toggle="modal" data-bs-target="#ApproveDisapproveModal" class="btn btn-success"><i class="ti ti-check"></i> Approve</span>
                                                <?php else : ?>
                                                    <span data-bs-toggle="modal" data-bs-target="#ApproveDisapproveModal" class="btn btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m-1-4h2V7h-2zm1 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8" />
                                                        </svg>
                                                        Disapprove
                                                    </span>

                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                    <?php endif; ?>

                                    <?php if ($isTab == 'Invoice') : ?>
                                        <?php if (IsCheckPermission($USERID, "CREATE_INVOICE")) : ?>
                                            <div class="flex-shrink-0">
                                                <?php if ($NumInvoice == 0) : ?>
                                                    <span data-bs-toggle="modal" data-bs-target="#CreateInvoice" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="m17 21l-2.75-3l1.16-1.16L17 18.43l3.59-3.59l1.16 1.41M12.8 21H5c-1.11 0-2-.89-2-2V5c0-1.11.89-2 2-2h14c1.11 0 2 .89 2 2v7.8c-.61-.35-1.28-.6-2-.72V5H5v14h7.08c.12.72.37 1.39.72 2m-.8-4H7v-2h5m2.68-2H7v-2h10v1.08c-.85.14-1.63.46-2.32.92M17 9H7V7h10" />
                                                        </svg> Create Invoice</span>
                                                <?php else : ?>
                                                    <span data-bs-toggle="modal" data-bs-target="#DeleteInvoice" class="btn btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m-1-4h2V7h-2zm1 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8" />
                                                        </svg>
                                                        Delete Invoice
                                                    </span>

                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                    <?php endif; ?>

                                </div>

                                <?php if ($isTab == "Details") : ?>
                                    <div class="card-body">
                                        <div class="card shadow-none border">
                                            <div class="card-header">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 mx-3">
                                                        <div style="display: flex;">
                                                            <div class="flex-shrink-0">
                                                                <div>
                                                                    <img class="" width="100" height="100" style="border-radius: 50%; object-fit: cover; border: 2px solid #48f542; padding: 2px" src="<?php echo $CandidateData->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'">
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 mx-3" style="margin-top: 20px;">
                                                                <h6 class="mb-0"><?php echo $CandidateData->Name; ?></h6>
                                                                <p class="mb-0">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                        <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2zm-2 0l-8 5l-8-5zm0 12H4V8l8 5l8-5z" />
                                                                    </svg> <?php echo $CandidateData->Email; ?>
                                                                    <br>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                        <path fill="currentColor" d="M19.95 21q-3.125 0-6.175-1.362t-5.55-3.863t-3.862-5.55T3 4.05q0-.45.3-.75t.75-.3H8.1q.35 0 .625.238t.325.562l.65 3.5q.05.4-.025.675T9.4 8.45L6.975 10.9q.5.925 1.187 1.787t1.513 1.663q.775.775 1.625 1.438T13.1 17l2.35-2.35q.225-.225.588-.337t.712-.063l3.45.7q.35.1.575.363T21 15.9v4.05q0 .45-.3.75t-.75.3M6.025 9l1.65-1.65L7.25 5H5.025q.125 1.025.35 2.025T6.025 9m8.95 8.95q.975.425 1.988.675T19 18.95v-2.2l-2.35-.475zm0 0" />
                                                                    </svg> <?php echo $CandidateData->Number; ?>
                                                                </p>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="flex-shrink-0"><a href="<?php echo $LINK; ?>/view_candidate/?ID=<?php echo $CandidateData->CandidateID; ?>" class="btn btn-sm btn-light-secondary"><i class="ti ti-eye"></i> View Candidate</a></div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <div class="card shadow-none border mb-0 row ">
                                                            <div class="card-body">
                                                                <h6 class="mb-2">Vacancy details</h6>
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="mb-3"><label class="form-label">Client</label>
                                                                            <p><?php echo $ClientData->Name; ?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="mb-3"><label class="form-label">Title</label>
                                                                            <p><?php echo $VacancyData->Title; ?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <div class="mb-3"><label class="form-label">Period</label>
                                                                            <p>
                                                                                From <?php echo FormatDate($VacancyData->StartDate); ?> to <?php echo FormatDate($VacancyData->EndDate); ?>

                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>


                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <div class="card shadow-none mb-0">
                                                            <div class="card-body">
                                                                <div>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" id="InvoiceTable">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th width="15%">Shift Type</th>
                                                                                    <th>Date</th>
                                                                                    <th>Start Time</th>
                                                                                    <th>End Time</th>
                                                                                    <th>Hours</th>
                                                                                    <th width="12%">Pay Rate</th>
                                                                                    <th width="12%">Supplier Rate</th>
                                                                                    <th width="12%">Margin</th>
                                                                                    <th width="12%">Total Margin</th>
                                                                                    <th width="12%">Total Pay Rate</th>
                                                                                    <th width="12%">Total Supply Rate</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $TOTAL_HOURS = 0;
                                                                                $TOTAL_MARGINS = 0;
                                                                                $TOTAL_PAYRATE = 0;
                                                                                $TOTAL_SUPPLY = 0;
                                                                                $n = 1;
                                                                                $__shifts__query = "SELECT * FROM `__time_sheets` WHERE TimesheetID = '$TimesheetID' ORDER BY TimesheetDate ASC ";
                                                                                $__shifts__stmt = $conn->prepare($__shifts__query);
                                                                                $__shifts__stmt->execute();
                                                                                while ($row = $__shifts__stmt->fetchObject()) { ?>
                                                                                    <?php if (!empty($row->ShiftType)) : ?>
                                                                                        <tr data-id="<?php echo $row->id; ?>">
                                                                                            <td><?php echo $n++; ?></td>
                                                                                            <td><?php echo $row->ShiftType; ?></td>
                                                                                            <td><?php echo FormatDate($row->TimesheetDate); ?></td>
                                                                                            <td><?php echo $row->StartTime; ?></td>
                                                                                            <td><?php echo $row->EndTime; ?></td>
                                                                                            <td><?php echo number_format(!empty($row->Hours) ? (float)$row->Hours : 0, 2); ?></td>
                                                                                            <td><?php echo $Currency; ?> <?php echo number_format(!empty($row->PayRate) ? (float)$row->PayRate : 0, 2); ?></td>
                                                                                            <td><?php echo $Currency; ?> <?php echo number_format(!empty($row->SupplyRate) ? (float)$row->SupplyRate : 0, 2); ?></td>
                                                                                            <td width="12%"><?php echo $Currency; ?> <?php
                                                                                                                                        $Margin = (!empty($row->SupplyRate) ? (float)$row->SupplyRate : 0) - (!empty($row->PayRate) ? (float)$row->PayRate : 0);
                                                                                                                                        echo number_format($Margin, 2);
                                                                                                                                        ?></td>
                                                                                            <td width="12%"><?php echo $Currency; ?> <?php
                                                                                                                                        $TotalMargin = (!empty($row->Hours) ? (float)$row->Hours : 0) * $Margin;
                                                                                                                                        echo number_format($TotalMargin, 2);
                                                                                                                                        ?></td>
                                                                                            <td width="12%"><?php echo $Currency; ?> <?php
                                                                                                                                        $TotalPayRate = (!empty($row->Hours) ? (float)$row->Hours : 0) * (!empty($row->PayRate) ? (float)$row->PayRate : 0);
                                                                                                                                        echo number_format($TotalPayRate, 2);
                                                                                                                                        ?></td>
                                                                                            <td width="12%"><?php echo $Currency; ?> <?php
                                                                                                                                        $TotalSupplyRate = (!empty($row->Hours) ? (float)$row->Hours : 0) * (!empty($row->SupplyRate) ? (float)$row->SupplyRate : 0);
                                                                                                                                        echo number_format($TotalSupplyRate, 2);
                                                                                                                                        ?></td>
                                                                                        </tr>

                                                                                        <?php
                                                                                        $TOTAL_HOURS += !empty($row->Hours) ? (float)$row->Hours : 0;
                                                                                        $TOTAL_MARGINS += $TotalMargin;
                                                                                        $TOTAL_PAYRATE += $TotalPayRate;
                                                                                        $TOTAL_SUPPLY += $TotalSupplyRate;
                                                                                        ?>


                                                                                    <?php endif; ?>

                                                                                <?php } ?>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-12">
                                                        <div class="card shadow-none mb-0">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <div class="mb-3"><label class="form-label">Total hours</label>
                                                                            <p><b><?php echo number_format($TOTAL_HOURS, 2); ?></b> <?php echo ($TOTAL_HOURS == 1) ? ' hour ' : ' hours '; ?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="mb-3"><label class="form-label">Total Margin</label>
                                                                            <p><b><?php echo $Currency; ?> <?php echo number_format($TOTAL_MARGINS, 2); ?></b></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="mb-3"><label class="form-label">Total Pay Rate</label>
                                                                            <p><b><?php echo $Currency; ?> <?php echo number_format($TOTAL_PAYRATE, 2); ?></b></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="mb-3"><label class="form-label">Total Supply</label>
                                                                            <p><b><?php echo $Currency; ?> <?php echo number_format($TOTAL_SUPPLY, 2); ?></b></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>





                                            </div>
                                        </div>
                                    </div>

                                    <div id="ApproveDisapproveModal" class="modal fade" tabindex="-1" aria-labelledby="ApproveDisapproveModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog" role="document">
                                            <form method="POST">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ApproveDisapproveModalLiveLabel">Confirm</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php if (empty($data->isApproved)) : ?>
                                                            <p>Are you sure you want to approve this timesheet?</p>
                                                        <?php else : ?>
                                                            <p>Are you sure you want to disapprove this timesheet?</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <?php if (empty($data->isApproved)) : ?>
                                                            <button type="submit" class="btn btn-success" name="ApproveTimesheet">Confirm</button>
                                                        <?php else : ?>
                                                            <button type="submit" class="btn btn-danger" name="DisapproveTimesheet">Confirm</button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                <?php endif; ?>
                                <?php if ($isTab == "Timesheet") : ?>
                                    <div class="card-body">
                                        <div class="table-responsive dt-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th style="display: none;">
                                                            <span id="selectAll" style="cursor: pointer;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                    <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                                </svg>
                                                            </span>
                                                        </th>
                                                        <th width="15%">Shift Type</th>
                                                        <th>Date</th>
                                                        <th>Start Time</th>
                                                        <th>End Time</th>
                                                        <th>Hours</th>
                                                        <th width="12%">Pay Rate</th>
                                                        <th width="12%">Supplier Rate</th>
                                                        <th width="12%">Margin</th>
                                                        <th width="12%">Total Margin</th>
                                                        <th width="12%">Total Pay Rate</th>
                                                        <th width="12%">Total Supply Rate</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $n = 1;
                                                    $__shifts__query = "SELECT * FROM `__time_sheets` WHERE TimesheetID = '$TimesheetID'";
                                                    $__shifts__stmt = $conn->prepare($__shifts__query);
                                                    $__shifts__stmt->execute();
                                                    while ($row = $__shifts__stmt->fetchObject()) { ?>
                                                        <tr data-id="<?php echo $row->id; ?>">
                                                            <td><?php echo $n++; ?></td>
                                                            <td style="display: none;"><input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->id; ?>" id="flexCheckDefault<?php echo $row->id; ?>"></td>

                                                            <td width="15%">
                                                                <select style="width: 150px;" class="form-control timesheet-type" autocomplete="off">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    $__shifts__ = $conn->query("SELECT * FROM `__shifts__` WHERE VacancyID = '$VacancyID' GROUP BY `ShiftType`");
                                                                    while ($__shifts__row = $__shifts__->fetchObject()) { ?>
                                                                        <option <?php echo ($__shifts__row->ShiftType == $row->ShiftType) ? 'selected' : ''; ?> data-starttime="<?php echo $row->StartTime; ?>" data-endtime="<?php echo $row->EndTime; ?>" data-payrate="<?php echo $row->PayRate; ?>" data-supplyrate="<?php echo $row->SupplyRate; ?>" data-hours="<?php echo ($__shifts__row->ShiftType == $row->ShiftType) ? $row->Hours : GetHours($__shifts__row->StartTime, $__shifts__row->EndTime); ?>">
                                                                            <?php echo $__shifts__row->ShiftType; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="date" class="form-control timesheet-date" value="<?php echo $row->TimesheetDate; ?>" autocomplete="off">
                                                            </td>
                                                            <td><input type="time" class="form-control timesheet-starttime" value="<?php echo $row->StartTime; ?>" autocomplete="off"></td>
                                                            <td><input type="time" class="form-control timesheet-endtime" value="<?php echo $row->EndTime; ?>" autocomplete="off"></td>
                                                            <td><input type="text" class="form-control timesheet-hours" style="width: 80px;" value="<?php echo $row->Hours; ?>" autocomplete="off"></td>
                                                            <td width="12%"><input type="text" class="form-control timesheet-payrate" value="<?php echo $row->PayRate; ?>" autocomplete="off"></td>
                                                            <td width="12%"><input type="text" class="form-control timesheet-supplyrate" value="<?php echo $row->SupplyRate; ?>" autocomplete="off"></td>
                                                            <td width="12%"><input style="width: 100px;" readonly type="text" class="form-control timesheet-margin" value="<?php echo  (float)$row->SupplyRate - (float)$row->PayRate; ?>" autocomplete="off"></td>
                                                            <td width="12%"><input style="width: 100px;" readonly type="text" class="form-control timesheet-totalmargin" value="<?php echo (float)$row->Hours * ((float)$row->SupplyRate - (float)$row->PayRate); ?>" autocomplete="off"></td>
                                                            <td width="12%"><input style="width: 100px;" readonly type="text" class="form-control timesheet-totalpayrate" value="<?php echo (float)$row->Hours * ((float)$row->PayRate); ?>" autocomplete="off"></td>
                                                            <td width="12%"><input style="width: 100px;" readonly type="text" class="form-control timesheet-totalsupplyrate" value="<?php echo (float)$row->Hours * ((float)$row->SupplyRate); ?>" autocomplete="off"></td>
                                                            <td width="12%">
                                                                <button type="button" data-bs-toggle="modal" data-bs-target="#DeleteModal" class="btn btn-danger select-entry-row"><i class="ti ti-trash"></i> Delete</button>

                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>

                                            </table>

                                        </div>


                                        <?php if ($__shifts__stmt->rowCount() == 0) : ?>
                                            <div class="alert alert-danger">
                                                No data found.
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div id="DeleteModal" class="modal fade" tabindex="-1" aria-labelledby="DeleteModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLiveLabel">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete? This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-danger" id="ConfirmDelete">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($isTab == "Modifications") : ?>
                                    <div class="card-body">
                                        <div class="table-responsive dt-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>User</th>
                                                        <th>Modication</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $n = 1;
                                                    $query = "SELECT * FROM `datamodifications` WHERE KeyID = '$TimesheetID' ORDER BY id DESC ";
                                                    $result = $conn->query($query);
                                                    while ($row = $result->fetchObject()) { ?>
                                                        <?php
                                                        $userdata =  $conn->query("SELECT * FROM `users` WHERE UserID = '{$row->UserID}'")->fetchObject();
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $n++; ?></td>
                                                            <td>


                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0"><img src="<?php echo $userdata->ProfileImage; ?>" alt="user image" class="img-radius wid-40"></div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h6 class="mb-0"><?php echo $userdata->Name; ?></h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><?php echo $row->Modification; ?></td>
                                                            <td><?php echo FormatDate($row->Date); ?></td>
                                                        </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                <?php endif; ?>

                                <?php if ($isTab == "Invoice") : ?>
                                    <div class="card-body">
                                        <?php if (IsCheckPermission($USERID, "CREATE_INVOICE")) : ?>
                                            <?php if ($NumInvoice == 0) : ?>
                                                <div class="alert alert-danger">
                                                    No invoice found for this timesheet.
                                                </div>

                                            <?php else : ?>

                                                <div class="card-body" id="Invoice">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="row align-items-center g-3">
                                                                <div class="col-sm-6">
                                                                    <div class="d-flex align-items-center mb-2"><img src="<?php echo empty($_CLIENT_DATA->ProfileImage) ? $ICON : $_CLIENT_DATA->ProfileImage; ?>" width="70" class="img-fluid" alt="images"> <span class="badge bg-<?php echo ($InvoiceData->Status == "Paid") ? 'success' : 'danger'; ?> rounded-pill ms-2"><?php echo $InvoiceData->Status; ?></span></div>
                                                                    <p class="mb-0">INV - <?php echo $InvoiceData->Number; ?></p>
                                                                </div>
                                                                <div class="col-sm-6 text-sm-end">
                                                                    <h6>Date <span class="text-muted f-w-400"><?php echo FormatDate($InvoiceData->InvoiceDate); ?></span></h6>
                                                                    <h6>Due Date <span class="text-muted f-w-400"><?php echo FormatDate($InvoiceData->DueDate); ?></span></h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="border rounded p-3">
                                                                <h6 class="mb-0">From:</h6>
                                                                <h5><?php echo $_CLIENT_DATA->Name; ?></h5>
                                                                <p class="mb-0"><?php echo $_CLIENT_DATA->Address; ?>, <?php echo $_CLIENT_DATA->Postcode; ?>, <?php echo $_CLIENT_DATA->City; ?></p>
                                                                <p class="mb-0"><?php echo $_CLIENT_DATA->Number; ?></p>
                                                                <p class="mb-0"><?php echo $_CLIENT_DATA->Email; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="border rounded p-3">
                                                                <h6 class="mb-0">To:</h6>
                                                                <h5><?php echo $ClientData->Name; ?></h5>
                                                                <p class="mb-0"><?php echo $ClientData->Address; ?>, <?php echo $ClientData->Postcode; ?>, <?php echo $ClientData->City; ?></p>
                                                                <p class="mb-0"><?php echo $ClientData->Number; ?></p>
                                                                <p class="mb-0"><?php echo $ClientData->Email; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Name</th>
                                                                            <th>Description</th>
                                                                            <th class="text-end">RATE</th>
                                                                            <th class="text-end">Hours</th>
                                                                            <th class="text-end">Total Amount</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $n = 1;
                                                                        $subtotal = 0;
                                                                        $_invoice_query = "SELECT * FROM `_invoice_` WHERE InvoiceID = :InvoiceID";
                                                                        $_invoice_stmt = $conn->prepare($_invoice_query);
                                                                        $_invoice_stmt->bindParam(':InvoiceID', $InvoiceID);
                                                                        $_invoice_stmt->execute();
                                                                        while ($row = $_invoice_stmt->fetchObject()) {
                                                                            $total_amount = $row->Hours * $row->Rate;
                                                                            $subtotal += $total_amount;
                                                                        ?>
                                                                            <tr>
                                                                                <td><?php echo $n++; ?></td>
                                                                                <td><?php echo htmlspecialchars($row->Name); ?></td>
                                                                                <td><?php echo htmlspecialchars($row->Description); ?></td>
                                                                                <td class="text-end"><?php echo $Currency; ?><?php echo number_format($row->Rate, 2); ?>/hr</td>
                                                                                <td class="text-end"><?php echo $row->Hours; ?></td>
                                                                                <td class="text-end"><?php echo $Currency; ?><?php echo number_format($total_amount, 2); ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="text-start">
                                                                <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                                            </div>
                                                        </div>

                                                        <?php
                                                        // Assuming values for VAT rate
                                                        $vat_rate = $BANKDATA->VatPercent / 100;
                                                        $vat = $subtotal * $vat_rate;
                                                        $grand_total = $subtotal + $vat;
                                                        ?>

                                                        <div class="col-12">
                                                            <div class="invoice-total ms-auto">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <p class="text-muted mb-1 text-start">Sub Total :</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p class="mb-1 text-end"><?php echo $Currency; ?><?php echo number_format($subtotal, 2); ?></p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p class="text-muted mb-1 text-start">VAT :</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p class="mb-1 text-end"><?php echo number_format($vat_rate * 100, 2); ?>%</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p class="text-muted mb-1 text-start">Taxes :</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p class="mb-1 text-end"><?php echo $Currency; ?><?php echo number_format($vat, 2); ?></p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p class="f-w-600 mb-1 text-end"><?php echo $Currency; ?><?php echo number_format($grand_total, 2); ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12"><label class="form-label">Bank Details</label>
                                                            <p class="mb-0"><?php echo $BANKDATA->Name; ?> <br> <?php echo $BANKDATA->Number; ?> <br> <?php echo $BANKDATA->InvoiceEmail; ?> <br> <?php echo $BANKDATA->VatNo; ?></p>
                                                        </div>
                                                        <div class="col-12"><label class="form-label">Note</label>
                                                            <p class="mb-0"><?php echo $InvoiceData->Note; ?></p>
                                                        </div>
                                                        <div class="col-12 text-end d-print-none">
                                                            <button data-bs-toggle="modal" data-bs-target="#SendEmail" class="btn btn-success">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                    <path fill="currentColor" d="M13 19c0-3.31 2.69-6 6-6c1.1 0 2.12.3 3 .81V6a2 2 0 0 0-2-2H4c-1.11 0-2 .89-2 2v12a2 2 0 0 0 2 2h9.09c-.05-.33-.09-.66-.09-1M4 8V6l8 5l8-5v2l-8 5zm16 14v-2h-4v-2h4v-2l3 3z" />
                                                                </svg>
                                                                Send</button>
                                                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#EditStatusModal">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                    <path fill="currentColor" d="M5 19h1.425L16.2 9.225L14.775 7.8L5 17.575zm-2 2v-4.25L16.2 3.575q.3-.275.663-.425t.762-.15t.775.15t.65.45L20.425 5q.3.275.438.65T21 6.4q0 .4-.137.763t-.438.662L7.25 21zM19 6.4L17.6 5zm-3.525 2.125l-.7-.725L16.2 9.225z" />
                                                                </svg>
                                                                Edit Status
                                                            </button>
                                                            <button class="btn btn-outline-secondary btn-print-invoice">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                    <path fill="currentColor" d="m12 16l-5-5l1.4-1.45l2.6 2.6V4h2v8.15l2.6-2.6L17 11zm-6 4q-.825 0-1.412-.587T4 18v-3h2v3h12v-3h2v3q0 .825-.587 1.413T18 20z" />
                                                                </svg>
                                                                Download
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="EditStatusModal" class="modal fade" tabindex="-1" aria-labelledby="EditStatusModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="EditStatusLabel">Edit Status</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" value="<?php echo $InvoiceData->Number; ?>" name="InvoiceNumber">
                                                                    <label for="status">Invoice Status</label>
                                                                    <select name="Status" class="form-control" id="">
                                                                        <option>Paid</option>
                                                                        <option>Overdue</option>
                                                                        <option>Cancelled</option>
                                                                        <option>Pending</option>
                                                                    </select>
                                                                </div>
                                                                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button name="UpdateInvoiceStatus" type="submit" class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>



                                            <div id="CreateInvoice" class="modal fade" tabindex="-1" aria-labelledby="CreateInvoiceLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLiveLabel">Create Invoice</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Please ensure your timesheet is accurate before proceeding, as the invoice will be automatically generated based on the timesheet information. </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ConfirmCreateInvoice">Confirm</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="DeleteInvoice" class="modal fade" tabindex="-1" aria-labelledby="DeleteInvoiceLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog" role="document">
                                                    <form method="POST">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLiveLabel">Confirm Delete</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete? This action cannot be undone.</p>
                                                                <div class="row" style="padding: 10px;">
                                                                    <input required type="text" class="form-control" name="reason" placeholder="Give a reason">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="DeleteInvoice" class="btn btn-danger">Confirm</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>


                                            <div id="SendEmail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="SendEmailLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="SendEmailLabel">Send Invoice</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="Subject">Subject</label>
                                                                            <input required type="text" name="Subject" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="Email">Email address</label>
                                                                            <input type="text" id="Email" value="" name="Emails" required>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label" for="BoMessagedy">Message</label>
                                                                    <input type="text" id="Message" name="Message" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="SendEmail" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="ConfirmCreateInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ConfirmCreateInvoiceLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ConfirmCreateInvoiceLabel">Create Invoice</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="Number">Invoice Number</label>
                                                                            <input required type="text" value="<?php echo $data->TimesheetNo ?>" name="Number" class="form-control" placeholder="Enter Number" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">

                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="status">Status</label>
                                                                            <select name="Status" class="form-control" id="">
                                                                                <option>Paid</option>
                                                                                <option>Pending</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="date">Invoice Date</label>
                                                                            <input type="date" id="date" name="InvoiceDate" value="<?php echo date("Y-m-d"); ?>" class="form-control" required>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="duedate">Due Date</label>
                                                                            <input type="date" id="duedate" name="DueDate" value="<?php echo date("Y-m-d"); ?>" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label" for="Notes">Note</label>
                                                                    <input type="text" id="Notes" name="Notes" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="CreateInvoice" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <?php DeniedAccess(); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>


                            <?php else : ?>
                                <?php DeniedAccess(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include "../../includes/js.php"; ?>

<script>
    <?php if ($isTab == "Timesheet") : ?>

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('selectAll').addEventListener('click', function() {
                let checkboxes = document.querySelectorAll('.checkbox-item');
                let allChecked = this.classList.toggle('checked');

                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = allChecked;
                });
            });



            document.querySelectorAll('.timesheet-type').forEach(function(select) {
                select.addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    var row = this.closest('tr');

                    var startTime = selectedOption.getAttribute('data-starttime');
                    var endTime = selectedOption.getAttribute('data-endtime');
                    var payRate = selectedOption.getAttribute('data-payrate');
                    var supplyRate = selectedOption.getAttribute('data-supplyrate');
                    var hours = selectedOption.getAttribute('data-hours');
                    row.querySelector('.timesheet-starttime').value = startTime;
                    row.querySelector('.timesheet-endtime').value = endTime;
                    row.querySelector('.timesheet-payrate').value = payRate;
                    row.querySelector('.timesheet-supplyrate').value = supplyRate;
                    row.querySelector('.timesheet-hours').value = hours;
                    row.querySelector('.timesheet-margin').value = (parseFloat(supplyRate) - parseFloat(payRate)).toFixed(2);
                    row.querySelector('.timesheet-totalmargin').value = (parseFloat(hours) * (parseFloat(supplyRate) - parseFloat(payRate))).toFixed(2);
                    row.querySelector('.timesheet-totalpayrate').value = (parseFloat(hours) * (parseFloat(payRate))).toFixed(2);
                    row.querySelector('.timesheet-totalsupplyrate').value = (parseFloat(hours) * (parseFloat(supplyRate))).toFixed(2);

                    setTimeout(() => {
                        updateShiftData(row);

                    }, 1000);


                });
            });

            var rows = document.querySelectorAll('tbody tr');
            rows.forEach(function(row) {
                var shiftTypeSelect = row.querySelector('.timesheet-type');
                var startTimeInput = row.querySelector('.timesheet-starttime');
                var endTimeInput = row.querySelector('.timesheet-endtime');
                var hoursInput = row.querySelector('.timesheet-hours');
                var payRateInput = row.querySelector('.timesheet-payrate');
                var supplyRateInput = row.querySelector('.timesheet-supplyrate');
                var marginInput = row.querySelector('.timesheet-margin');
                var totalMarginInput = row.querySelector('.timesheet-totalmargin');
                var shiftDateInput = row.querySelector('.timesheet-date');
                var totalPayRateInput = row.querySelector('.timesheet-totalpayrate');
                var totalSupplyRateInput = row.querySelector('.timesheet-totalsupplyrate');


                [shiftTypeSelect, startTimeInput, endTimeInput, hoursInput, payRateInput, supplyRateInput, shiftDateInput, totalPayRateInput, totalSupplyRateInput].forEach(function(element) {
                    element.addEventListener('change', function() {
                        updateShiftData(row);
                    });
                });

                hoursInput.addEventListener('keyup', function() {
                    if (startTimeInput.value !== '' && /^\d*\.?\d*$/.test(this.value)) {
                        var start = new Date('1970-01-01T' + startTimeInput.value);
                        var hoursNum = parseFloat(this.value);
                        var endTimeDate = new Date(start.getTime() + hoursNum * 60 * 60 * 1000);
                        endTimeInput.value = endTimeDate.toTimeString().slice(0, 5);
                    } else {
                        this.value = '';
                    }
                    updateShiftData(row);
                });

                function updateShiftData(row) {
                    var dataId = row.getAttribute('data-id');
                    var shiftType = shiftTypeSelect.value;
                    var startTime = startTimeInput.value;
                    var endTime = endTimeInput.value;
                    var hours = hoursInput.value.trim();
                    var payRate = payRateInput.value;
                    var supplyRate = supplyRateInput.value;

                    if (startTime !== '' && endTime !== '') {
                        var startDateTime = new Date('1970-01-01T' + startTime);
                        var endDateTime = new Date('1970-01-01T' + endTime);
                        var timeDifference = (endDateTime - startDateTime) / (1000 * 60 * 60); // Difference in hours
                        hoursInput.value = timeDifference.toFixed(2); // Display hours with two decimals
                        hours = timeDifference.toFixed(2); // Update hours variable
                    }

                    // Validate and calculate margin
                    var margin = parseFloat(supplyRate) - parseFloat(payRate);
                    marginInput.value = margin.toFixed(2); // Display margin with two decimals

                    // Validate and calculate total margin
                    var totalMargin = parseFloat(hours) * parseFloat(margin);
                    totalMarginInput.value = totalMargin.toFixed(2); // Display total margin with two decimals


                    var totalPayRate = parseFloat(hours) * parseFloat(payRate);
                    var totalSupplyRate = parseFloat(hours) * parseFloat(supplyRate);


                    totalPayRateInput.value = totalPayRate.toFixed(2);
                    totalSupplyRateInput.value = totalSupplyRate.toFixed(2);

                    var postData = {
                        UpdateTimesheet: true,
                        id: dataId,
                        ShiftType: shiftType,
                        StartTime: startTime,
                        EndTime: endTime,
                        Hours: hours,
                        PayRate: payRate,
                        SupplyRate: supplyRate,
                        Margin: margin,
                        TotalMargin: totalMargin,
                        ShiftDate: shiftDateInput.value
                    };
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', window.location.href);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            setTimeout(() => {
                                ShowToast('Shift updated successfully');
                            }, 2000);
                        } else {
                            ShowToast('Failed to update shift');
                            // Optionally handle error response
                        }
                    };
                    xhr.onerror = function() {
                        ShowToast('Error occurred while updating shift');
                        // Optionally handle XHR error
                    };
                    xhr.send(JSON.stringify(postData));

                }
            });
        });


        document.getElementById('ConfirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;

            checkboxes.forEach(function(checkbox) {
                ids.push({
                    id: checkbox.value,
                    name: checkbox.getAttribute('data-name')
                });
            });

            if (ids.length > 0) {
                $("#ConfirmDelete").text("Deleting...");

                ids.forEach(function(item) {
                    $.ajax({
                        url: window.location.href,
                        type: 'POST',
                        data: {
                            ID: item.id,
                            DeleteTimesheet: true
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
                            // Optionally, handle the error case if needed
                        }
                    });
                });

            } else {
                ShowToast('Error 102: Something went wrong.');
            }
        });

    <?php endif; ?>
    <?php if ($isTab == "Invoice") : ?>
        new TomSelect("#Email", {
            persist: false,
            createOnBlur: true,
            create: true
        });

        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelector('.btn-print-invoice').addEventListener('click', () => {
                printInvoice();
            });
        });

        function printInvoice() {
            const printContents = document.getElementById('Invoice').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

            location.reload();
        }

    <?php endif; ?>
</script>

</html>