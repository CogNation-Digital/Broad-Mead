<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$VacancyID = $_GET['VacancyID'];
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "Details";
$data = $conn->query("SELECT * FROM vacancies WHERE VacancyID = '$VacancyID'")->fetchObject();
if (!$data) {
    header("location: $LINK/vacancies ");
}
$ClientName = $conn->query("SELECT Name FROM _clients WHERE ClientID = '{$data->hasClientID}' ")->fetchColumn();
$BranchName = $conn->query("SELECT Name FROM _clients WHERE ClientID = '{$data->hasBranchID}' ")->fetchColumn();

if (isset($_POST['AddCandidate'])) {
    $candidate = $_POST['candidate'];
    if (empty($candidate)) {
        $response = "Please enter a candidate";
        $error = 1;
    } else {
        try {
            // Check if the candidate already exists
            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM __vacancy_candidates WHERE ClientKeyID = :ClientKeyID AND VacancyID = :VacancyID AND CandidateID = :CandidateID");
            $checkStmt->bindParam(':ClientKeyID', $ClientKeyID);
            $checkStmt->bindParam(':VacancyID', $VacancyID);
            $checkStmt->bindParam(':CandidateID', $candidate);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                $response = "Candidate already exists in this vacancy";
                $error = 1;
            } else {
                // Insert the new candidate
                $insertStmt = $conn->prepare("INSERT INTO __vacancy_candidates (ClientKeyID, VacancyID, CandidateID, CreatedBy, Date) VALUES (:ClientKeyID, :VacancyID, :CandidateID, :CreatedBy, NOW())");
                $insertStmt->bindParam(':ClientKeyID', $ClientKeyID);
                $insertStmt->bindParam(':VacancyID', $VacancyID);
                $insertStmt->bindParam(':CandidateID', $candidate);
                $insertStmt->bindParam(':CreatedBy', $USERID);
                $insertStmt->execute();

                // Get the candidate's name
                $candidateNameStmt = $conn->prepare("SELECT Name FROM `_candidates` WHERE ClientKeyID = :ClientKeyID AND CandidateID = :CandidateID");
                $candidateNameStmt->bindParam(':ClientKeyID', $ClientKeyID);
                $candidateNameStmt->bindParam(':CandidateID', $candidate);
                $candidateNameStmt->execute();
                $candidateName = $candidateNameStmt->fetchColumn();

                $response = "Candidate added successfully";
                $error = 0;

                $Modification = "Added $candidateName to vacancy";
                LastModified($VacancyID, $USERID, $Modification);
                $Notification = "$NAME successfully added $candidateName to vacancy";

                Notify($USERID, $ClientKeyID, $Notification);
            }
        } catch (PDOException $e) {
            $response = "Error: " . $e->getMessage();
            $error = 1;
        }
    }
}

if (isset($_POST['RemoveCandidate'])) {
    $ID = $_POST['ID'];
    $candidateNameStmt = $conn->prepare("SELECT Name FROM `_candidates` WHERE ClientKeyID = :ClientKeyID AND CandidateID = :CandidateID");
    $candidateNameStmt->bindParam(':ClientKeyID', $ClientKeyID);
    $candidateNameStmt->bindParam(':CandidateID', $ID);
    $candidateNameStmt->execute();
    $candidateName = $candidateNameStmt->fetchColumn();


    $Modification = "Removed $candidateName fom vacancy";
    LastModified($VacancyID, $USERID, $Modification);
    $Notification = "$NAME successfully removed $candidateName from vacancy";

    Notify($USERID, $ClientKeyID, $Notification);

    $query = "DELETE FROM __vacancy_candidates WHERE VacancyID = '$VacancyID' AND CandidateID = '$ID'";
    $conn->exec($query);
}

if (isset($_POST['AddShift'])) {
    function generateUniqueID($conn)
    {
        do {
            global $RandomID; // Generate a unique ID
            $stmt = $conn->prepare("SELECT COUNT(*) FROM `_shifts` WHERE ShiftID = :ShiftID");
            $stmt->bindParam(':ShiftID', $RandomID);
            $stmt->execute();
            $count = $stmt->fetchColumn();
        } while ($count > 0); // Repeat until a unique ID is found

        return $RandomID;
    }

    // Get a unique ShiftID
    $RandomID = generateUniqueID($conn);

    // Get hasClientID and hasBranchID from vacancies using VacancyID
    $stmt = $conn->prepare("SELECT hasClientID, hasBranchID FROM vacancies WHERE VacancyID = :VacancyID");
    $stmt->bindParam(':VacancyID', $VacancyID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $hasClientID = $row['hasClientID'];
    $hasBranchID = $row['hasBranchID'];

    // Insert new shift
    $query = "INSERT INTO `_shifts` (`ClientKeyID`, `ShiftID`, `hasClientID`, `hasBranchID`, `VacancyID`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :ShiftID, :hasClientID, :hasBranchID, :VacancyID, :CreatedBy, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':ShiftID', $RandomID);
    $stmt->bindParam(':hasClientID', $hasClientID);
    $stmt->bindParam(':hasBranchID', $hasBranchID);
    $stmt->bindParam(':VacancyID', $VacancyID);
    $stmt->bindParam(':CreatedBy', $USERID);

    $stmt->execute();

    $Modification = "Added a new shift";
    LastModified($VacancyID, $USERID, $Modification);
    $Notification = "$NAME successfully added a new shift";

    Notify($USERID, $ClientKeyID, $Notification);
    $response = "Shift successfully added";
}

if (isset($_POST['Delete_Shift'])) {
    $shiftID = $_POST['shiftID'];
    $shiftID = $_POST['ID'];
    $query = "DELETE FROM `_shifts` WHERE ShiftID = '$shiftID'";
    $conn->exec($query);
    $Modification = "Deleted a shift";
    LastModified($VacancyID, $USERID, $Modification);
    $Notification = "$NAME successfully deleted a shift";

    Notify($USERID, $ClientKeyID, $Notification);
}

$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true); // Decode JSON data into associative array

// Check if the required data is received
if (isset($inputData['UpdateShift'])) {
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
    $candidate = $inputData['Candidate'];
    $shiftDate = $inputData['ShiftDate'];

    // Update query with Margin and TotalMargin fields
    $query = "UPDATE `_shifts` SET 
                Type = :shiftType,
                StartTime = :startTime,
                EndTime = :endTime,
                Hours = :hours,
                PayRate = :payRate,
                SupplyRate = :supplyRate,
                Margin = :margin,
                TotalMargin = :totalMargin,
                CandidateID = :candidate,
                ShiftDate = :shiftDate
              WHERE ShiftID = :id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':shiftType', $shiftType);
    $stmt->bindParam(':startTime', $startTime);
    $stmt->bindParam(':endTime', $endTime);
    $stmt->bindParam(':hours', $hours);
    $stmt->bindParam(':payRate', $payRate);
    $stmt->bindParam(':supplyRate', $supplyRate);
    $stmt->bindParam(':margin', $margin);
    $stmt->bindParam(':totalMargin', $totalMargin);
    $stmt->bindParam(':candidate', $candidate);
    $stmt->bindParam(':shiftDate', $shiftDate);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(array('status' => 'success', 'message' => 'Shift updated successfully'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to update shift'));
    }
}

if (isset($_POST['GenerateTimesheet'])) {
    // Get the period from the POST request
    $Period = $_POST['Period']; // example 15 July 2024 to 21 July 2024

    // Get the candidate from the POST request
    $CandidateID = $_POST['candidate'];
    $TimesheetNo = $_POST['TimesheetID'];

    $TimesheetID = $RandomID;

    // Prepare and execute the SELECT query
    $query = "SELECT * FROM `vacancies` WHERE VacancyID = :VacancyID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':VacancyID', $VacancyID);
    $stmt->execute();
    $row = $stmt->fetchObject();
    $hasClientID = $row->hasClientID;
    $hasBranchID = $row->hasBranchID;

    // Prepare the INSERT query
    $query = "INSERT INTO `_timesheet`(`ClientKeyID`, `TimesheetID`, `TimesheetNo`, `hasClientID`, `hasBranchID`, `CandidateID`, `VacancyID`, `CreatedBy`, `Date`)  VALUES (:ClientKeyID, :TimesheetID, :TimesheetNo, :hasClientID, :hasBranchID, :CandidateID, :VacancyID, :CreatedBy, :Date)";

    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':TimesheetID', $TimesheetID);
    $stmt->bindParam(':TimesheetNo', $TimesheetNo);
    $stmt->bindParam(':hasClientID', $hasClientID);
    $stmt->bindParam(':hasBranchID', $hasBranchID);
    $stmt->bindParam(':CandidateID', $CandidateID);
    $stmt->bindParam(':VacancyID', $VacancyID);
    $stmt->bindParam(':CreatedBy', $USERID);
    $stmt->bindParam(':Date', $date);

    // Execute the INSERT query
    $stmt->execute();


    // Separate the date range into from and to dates
    $dates = explode(" to ", $Period);
    $date_from = DateTime::createFromFormat('d F Y', trim($dates[0]));
    $date_to = DateTime::createFromFormat('d F Y', trim($dates[1]));

    $date_from_formatted = $date_from->format('Y-m-d');
    $date_to_formatted = $date_to->format('Y-m-d');



    //Check for shifts 
    $query = "SELECT * FROM `_shifts` WHERE CandidateID = '$CandidateID' AND VacancyID = '$VacancyID' AND ShiftDate BETWEEN '$date_from_formatted' AND '$date_to_formatted'";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    while ($row = $stmt->fetchObject()) {
        // Fetch shift details
        $ShiftDate = $row->ShiftDate;
        $shiftID = $row->ShiftID;
        $shiftType = $row->Type;
        $startTime = $row->StartTime;
        $endTime = $row->EndTime;
        $hours = $row->Hours;
        $payRate = $row->PayRate;
        $supplyRate = $row->SupplyRate;
        $margin = $row->Margin;

        // Prepare the insert query
        $insertQuery = $conn->prepare("INSERT INTO `__time_sheets`(`TimesheetID`, `TimesheetDate`, `ShiftType`, `StartTime`, `EndTime`, `Hours`, `Margin`, `PayRate`, `SupplyRate`, `CreatedBy`, `Date`) VALUES (:TimesheetID, :TimesheetDate, :ShiftType, :StartTime, :EndTime, :Hours, :Margin, :PayRate, :SupplyRate, :CreatedBy, :Date)");

        // Bind the parameters
        $insertQuery->bindParam(':TimesheetID', $TimesheetID);
        $insertQuery->bindParam(':TimesheetDate', $ShiftDate);
        $insertQuery->bindParam(':ShiftType', $shiftType);
        $insertQuery->bindParam(':StartTime', $startTime);
        $insertQuery->bindParam(':EndTime', $endTime);
        $insertQuery->bindParam(':Hours', $hours);
        $insertQuery->bindParam(':Margin', $margin);
        $insertQuery->bindParam(':PayRate', $payRate);
        $insertQuery->bindParam(':SupplyRate', $supplyRate);
        $insertQuery->bindParam(':CreatedBy', $USERID);
        $insertQuery->bindParam(':Date', $date);


        // Execute the insert query
        $insertQuery->execute();

        $candidateNameStmt = $conn->prepare("SELECT Name FROM `_candidates` WHERE  CandidateID = :CandidateID");
        $candidateNameStmt->bindParam(':CandidateID', $CandidateID);
        $candidateNameStmt->execute();
        $candidateName = $candidateNameStmt->fetchColumn();

        $Modification = "Created timesheet";
        LastModified($TimesheetID, $USERID, $Modification);
        $Notification = "$NAME successfully created  a new timesheet for $candidateName Timesheet No. $TimesheetNo";

        Notify($USERID, $ClientKeyID, $Notification);
    }

    header("location: $LINK/generate_timesheet/?ID=$TimesheetID");
}

if (isset($_POST['DeleteTimesheet'])) {

    $TimesheetID = $_POST['ID'];
    $reason = $_POST['reason'];
    $TimesheetNo = $conn->query("SELECT TimesheetNo FROM `_timesheet` WHERE TimesheetID = '$TimesheetID' ")->fetchColumn();

    $del = $conn->query("DELETE FROM `_timesheet` WHERE TimesheetID = '$TimesheetID' ");
    $del2 = $conn->query("DELETE FROM `__time_sheets` WHERE TimesheetID = '$TimesheetID' ");

    $Modification = "Delete Timesheet No $TimesheetNo";
    LastModified($VacancyID, $USERID, $Modification);
    $Notification = "$NAME successfully deleted Timesheet No. $TimesheetNo. Reason: $reason";

    Notify($USERID, $ClientKeyID, $Notification);
}

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
                                <h5 class="mb-0">Vacancy</h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "VIEW_VACANCY")) : ?>
                                <ul class="nav nav-tabs analytics-tab" style="margin-bottom: 10px;">
                                    <li class="nav-item" role="presentation">
                                        <a href="<?php echo $LINK ?>/view_vacancy/?VacancyID=<?php echo $VacancyID ?>&isTab=Details" class="nav-link <?php echo ($isTab == 'Details') ? 'active' : ''; ?>">Details</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="<?php echo $LINK ?>/view_vacancy/?VacancyID=<?php echo $VacancyID ?>&isTab=ShiftTypes" class="nav-link <?php echo ($isTab == 'ShiftTypes') ? 'active' : ''; ?>">Shifts Types</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="<?php echo $LINK ?>/view_vacancy/?VacancyID=<?php echo $VacancyID ?>&isTab=Candidates" class="nav-link <?php echo ($isTab == 'Candidates') ? 'active' : ''; ?>">Candidates</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="<?php echo $LINK ?>/view_vacancy/?VacancyID=<?php echo $VacancyID ?>&isTab=Shifts" class="nav-link <?php echo ($isTab == 'Shifts') ? 'active' : ''; ?>">Shifts</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="<?php echo $LINK ?>/view_vacancy/?VacancyID=<?php echo $VacancyID ?>&isTab=Timesheet" class="nav-link <?php echo ($isTab == 'Timesheet') ? 'active' : ''; ?>">Timesheet</a>
                                    </li>
                                </ul>
                                <?php include "i/details.php" ?>
                                <?php include "i/shifttypes.php" ?>
                                <?php include "i/candidates.php" ?>
                                <?php include "i/shifts.php" ?>
                                <?php include "i/timesheet.php" ?>


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


</html>