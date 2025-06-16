<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$CandidateID = $_GET['ID'];
$isTab = (isset($_GET['isTab'])) ? $_GET['isTab'] : 'Details';
$CandidateData = $conn->query("SELECT * FROM `_candidates` WHERE CandidateID = '$CandidateID' ")->fetchObject();


if (isset($_POST['CreateDocument'])) {
    // Define allowed file extensions
    $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Extract file details
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameParts = pathinfo($fileName);
        $fileExtension = strtolower($fileNameParts['extension']);

        // Validate file extension
        if (in_array($fileExtension, $allowedExtensions)) {
            // Generate a unique filename
            $newFileName = $KeyID . '.' . $fileExtension;
            $destPath = $File_Directory . $newFileName;

            // Move the file to the target directory
            if (move_uploaded_file($fileTmpPath, $destPath)) {

                // Get other form data
                $type = $_POST['Type'];
                $name = $_POST['Name'];
                $issueDate = $_POST['IssueDate'];
                $expiryDate = $_POST['ExpiryDate'];

                $FilePath = $LINK . '/assets/files/' . $newFileName;



                // Prepare SQL insertion statement
                $query = "INSERT INTO `_candidates_documents`( `ClientKeyID`, `CandidateID`, `Type`, `Name`, `Path`, `IssuedDate`, `ExpiryDate`, `CreatedBy`, `Date`) 
                          VALUES (:ClientKeyID, :CandidateID, :Type, :Name, :Path, :IssuedDate, :ExpiryDate, :CreatedBy, :Date)";

                $stmt = $conn->prepare($query);

                // Bind parameters
                $stmt->bindParam(':ClientKeyID', $ClientKeyID);
                $stmt->bindParam(':CandidateID', $CandidateID);
                $stmt->bindParam(':Type', $type);
                $stmt->bindParam(':Name', $name);
                $stmt->bindParam(':Path', $FilePath);
                $stmt->bindParam(':IssuedDate', $issueDate);
                $stmt->bindParam(':ExpiryDate', $expiryDate);
                $stmt->bindParam(':CreatedBy', $USERID);
                $stmt->bindParam(':Date', $date);

                // Execute the statement
                if ($stmt->execute()) {
                    $Modification = "Document $name uploaded for candidate $CandidateData->Name";
                    $Notification = "$NAME has uploaded a new document titled $name for candidate $CandidateData->Name.";
                    Notify($USERID, $ClientKeyID, $Notification);
                    LastModified($RandomID, $USERID, $Modification);



                    $response = 'Document details saved successfully.';
                } else {
                    $response = 'Error: ' . $stmt->errorInfo()[2];
                    $error = 1;
                }
            } else {
                $response = 'Error moving the uploaded file.';
                $error = 1;
            }
        } else {
            $response = 'Invalid file extension. Only PDF, DOC, DOCX, XLS, and XLSX are allowed.';
            $error = 1;
        }
    } else {
        $response = 'No file uploaded or there was an upload error.';
        $error = 1;
    }
}

if (isset($_POST['UpdateDocument'])) {
    // Define allowed file extensions
    $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Extract file details
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameParts = pathinfo($fileName);
        $fileExtension = strtolower($fileNameParts['extension']);

        // Validate file extension
        if (in_array($fileExtension, $allowedExtensions)) {
            // Generate a unique filename
            $newFileName = $KeyID . '.' . $fileExtension;
            $destPath = $File_Directory . $newFileName;

            // Move the file to the target directory
            if (move_uploaded_file($fileTmpPath, $destPath)) {

                // Get other form data
                $type = $_POST['Type'];
                $name = $_POST['Name'];
                $issueDate = $_POST['IssueDate'];
                $expiryDate = $_POST['ExpiryDate'];
                $ID = $_POST['ID']; // Get the ID for the document to be updated

                $FilePath = $LINK . '/assets/files/' . $newFileName;

                // Prepare SQL update statement
                $query = "UPDATE `_candidates_documents` SET 
                          `ClientKeyID` = :ClientKeyID, 
                          `CandidateID` = :CandidateID, 
                          `Type` = :Type, 
                          `Name` = :Name, 
                          `Path` = :Path, 
                          `IssuedDate` = :IssuedDate, 
                          `ExpiryDate` = :ExpiryDate 
                          WHERE `id` = :ID";

                $stmt = $conn->prepare($query);

                // Bind parameters
                $stmt->bindParam(':ClientKeyID', $ClientKeyID);
                $stmt->bindParam(':CandidateID', $CandidateID);
                $stmt->bindParam(':Type', $type);
                $stmt->bindParam(':Name', $name);
                $stmt->bindParam(':Path', $FilePath);
                $stmt->bindParam(':IssuedDate', $issueDate);
                $stmt->bindParam(':ExpiryDate', $expiryDate);
                $stmt->bindParam(':ID', $ID);

                // Execute the statement
                if ($stmt->execute()) {
                    $Modification = "Document $name updated for candidate $CandidateData->Name";
                    $Notification = "$NAME has updated the document titled $name for candidate $CandidateData->Name.";
                    Notify($USERID, $ClientKeyID, $Notification);
                    LastModified($RandomID, $USERID, $Modification);

                    $response = 'Document details updated successfully.';
                } else {
                    $response = 'Error: ' . $stmt->errorInfo()[2];
                    $error = 1;
                }
            } else {
                $response = 'Error moving the uploaded file.';
                $error = 1;
            }
        } else {
            $response = 'Invalid file extension. Only PDF, DOC, DOCX, XLS, and XLSX are allowed.';
            $error = 1;
        }
    } else {
        $response = 'No file uploaded or there was an upload error.';
        $error = 1;
    }
}


if (isset($_POST['DeleteCandidateDocument'])) {
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    // Validate input data
    if (!empty($ID) && !empty($name) && !empty($reason)) {
        try {
            // Start a transaction
            $conn->beginTransaction();

            // Perform the deletion in the database
            $query = "DELETE FROM `_candidates_documents` WHERE `id` = :ID";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':ID', $ID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Record the deletion action and reason
                $modification = "Document $name deleted document titled $name ";
                $notification = "$NAME has deleted the document titled $name for candidate $CandidateData->Name. Reason for deletion is: $reason";
                Notify($USERID, $ClientKeyID, $notification);
                LastModified($CandidateID, $USERID, $modification);

                // Commit the transaction
                $conn->commit();

                // Return a success response
                echo json_encode(['status' => 'success', 'message' => 'Document deleted successfully.']);
            } else {
                // Rollback the transaction
                $conn->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete the document.']);
            }
        } catch (Exception $e) {
            // Rollback the transaction
            $conn->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
    }
}

if (isset($_POST['CreateEmergencyContact'])) {
    $name = $_POST['Name'];
    $relationship = $_POST['Relationship'];
    $PhoneNumber = $_POST['PhoneNumber'];

    $query = "INSERT INTO `_candidates_kins`(`ClientKeyID`, `CandidateID`, `Relationship`, `Name`, `Contact`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :CandidateID, :Relationship, :Name, :PhoneNumber, :CreatedBy, :Date)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':CandidateID', $CandidateID);
    $stmt->bindParam(':Relationship', $relationship);
    $stmt->bindParam(':Name', $name);
    $stmt->bindParam(':PhoneNumber', $PhoneNumber);
    $stmt->bindParam(':CreatedBy', $USERID);
    $stmt->bindParam(':Date', $date);

    if ($stmt->execute()) {
        $response = 'Emergency contact added successfully.';
        $Notification = "$NAME has added a new emergency contact for candidate $CandidateData->Name. Name: $name, Relationship: $relationship, Phone Number: $PhoneNumber";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Emergency contact $name added for candidate $CandidateData->Name");
    } else {
        $response = 'Something went wrong. Please try again';
        $error = 1;
    }
}



if (isset($_POST['UpdateEmergencyContact'])) {
    $ID = $_POST['ID'];
    $name = $_POST['Name'];
    $relationship = $_POST['Relationship'];
    $PhoneNumber = $_POST['PhoneNumber'];

    $query = "UPDATE `_candidates_kins` 
              SET `Relationship` = :Relationship, `Name` = :Name, `Contact` = :PhoneNumber  
              WHERE `id` = :ID ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    $stmt->bindParam(':Relationship', $relationship);
    $stmt->bindParam(':Name', $name);
    $stmt->bindParam(':PhoneNumber', $PhoneNumber);

    if ($stmt->execute()) {
        $response = 'Emergency contact updated successfully.';
        $Notification = "$NAME has updated an emergency contact for candidate $CandidateData->Name. Name: $name, Relationship: $relationship, Phone Number: $PhoneNumber";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Emergency contact $name updated for candidate $CandidateData->Name");
    } else {
        $response = 'Something went wrong. Please try again';
        $error = 1;
    }
}

if (isset($_POST['DeleteEmergencyContact'])) {
    $ID = $_POST['ID'];
    $reason = $_POST['reason'];

    $query = "DELETE FROM `_candidates_kins` WHERE `id` = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $response = 'Emergency contact deleted successfully.';
        $Notification = "$NAME has deleted an emergency contact for candidate $CandidateData->Name. Reason for deletion is: $reason";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Emergency contact deleted for candidate $CandidateData->Name");
    }
}


if (isset($_POST['CreateSkill'])) {
    $Name = $_POST['Name'];

    $query = "INSERT INTO `_candidates_skills` (`ClientKeyID`, `CandidateID`, `Skill`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :CandidateID, :Skill, :CreatedBy, :Date)";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':CandidateID', $CandidateID);
    $stmt->bindParam(':Skill', $Name);
    $stmt->bindParam(':CreatedBy', $USERID);
    $stmt->bindParam(':Date', $date);

    // Execute the statement
    if ($stmt->execute()) {
        $response = 'Skill added successfully.';

        // Notify and log the action
        $Notification = "$NAME has added a new skill for candidate $CandidateData->Name. Skill: $Name";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Skill $Name added for candidate $CandidateData->Name");
    } else {
        $response = 'Something went wrong. Please try again';
        $error = 1;
    }
}

if (isset($_POST['DeleteSkill'])) {
    $ID = $_POST['ID'];
    $reason = $_POST['reason'];

    $query = "DELETE FROM `_candidates_skills` WHERE `id` = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $response = 'Skill deleted successfully.';
        $Notification = "$NAME has deleted a skill for candidate $CandidateData->Name. Reason for deletion is: $reason";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Skill deleted for candidate $CandidateData->Name");
    }
}

if (isset($_POST['UpdateSkill'])) {
    $ID = $_POST['ID'];
    $Name = $_POST['Name'];

    $query = "UPDATE `_candidates_skills` SET `Skill` = :Skill WHERE `id` = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    $stmt->bindParam(':Skill', $Name);
    if ($stmt->execute()) {
        $response = 'Skill updated successfully.';

        // Notify and log the action
        $Notification = "$NAME has updated a skill for candidate $CandidateData->Name. Skill: $Name";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Skill $Name updated for candidate $CandidateData->Name");
    } else {
        $response = 'Something went wrong. Please try again';
        $error = 1;
    }
}

if (isset($_POST['UpdateStatus'])) {
    $Status = $_POST['Status'];

    $query = "UPDATE `_candidates` SET `Status` = :Status WHERE `CandidateID` = :CandidateID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':Status', $Status);
    $stmt->bindParam(':CandidateID', $CandidateID);
    if ($stmt->execute()) {

        // Notify and log the action
        $Notification = "$NAME has updated the status for candidate $CandidateData->Name to $Status";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Update Status for candidate $CandidateData->Name to $Status");
    } else {
        $response = 'Something went wrong. Please try again';
        $error = 1;
    }
}

if (isset($_POST['DeleteInterview'])) {
    $ID = $_POST['ID'];
    $reason = $_POST['reason'];
    $query = "DELETE FROM `interviews` WHERE `id` = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    if ($stmt->execute()) {
        $response = 'Interview deleted successfully.';
        $Notification = "$NAME has deleted an interview for candidate $CandidateData->Name. Reason for deletion is: $reason";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Interview deleted for candidate $CandidateData->Name");
    }
}

if (isset($_POST['DeleteShift'])) {
    $ID = $_POST['ID'];
    $reason = $_POST['reason'];
    $query = "DELETE FROM `_shifts` WHERE `ShiftID` = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    if ($stmt->execute()) {
        $response = 'Shift deleted successfully.';
        $Notification = "$NAME has deleted a shift for candidate $CandidateData->Name. Reason for deletion is: $reason";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Shift deleted for candidate $CandidateData->Name");
    }
}

if (isset($_POST['DeleteTimesheet'])) {
    $ID = $_POST['ID'];
    $reason = $_POST['reason'];
    $query = "DELETE FROM `_timesheet` WHERE `TimesheetID` = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    if ($stmt->execute()) {
        $response = 'Timesheet deleted successfully.';
        $Notification = "$NAME has deleted a Timesheet for candidate $CandidateData->Name. Reason for deletion is: $reason";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Timesheet deleted for candidate $CandidateData->Name");
    }
}

if (isset($_POST['SaveLog'])) {
    $Type = $_POST['Type'];
    $Details = $_POST['Description'];
    $Category = "Candidate";

    $query = "INSERT INTO `_communication_logs`(`ClientKeyID`, `LogID`, `RecipientID`, `Category`, `Type`, `Details`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :LogID, :RecipientID, :Category, :Type, :Details, :CreatedBy, :Date)";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':LogID', $RandomID);
    $stmt->bindParam(':RecipientID', $CandidateID);
    $stmt->bindParam(':Category', $Category);
    $stmt->bindParam(':Type', $Type);
    $stmt->bindParam(':Details', $Details);
    $stmt->bindParam(':CreatedBy', $USERID);
    $stmt->bindParam(':Date', $date);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $dd = TruncateText($Details, 70);
        $response = 'Log saved successfully.';
        $Notification = "$NAME has saved a new log for candidate $CandidateData->Name. Type: $Type, Details: $dd";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Log saved for candidate $CandidateData->Name");
    } else {
        $response = 'Something went wrong. Please try again';
        $error = 1;
    }
}

if (isset($_POST['UpdateLog'])) {
    $ID = $_POST['ID'];
    $Type = $_POST['Type'];
    $Details = $_POST['Description'];

    $query = "UPDATE `_communication_logs` SET `Type` = :Type, `Details` = :Details WHERE `id` = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    $stmt->bindParam(':Type', $Type);
    $stmt->bindParam(':Details', $Details);
    if ($stmt->execute()) {
        $response = 'Log updated successfully.';
        $Notification = "$NAME has updated a log for candidate $CandidateData->Name. Type: $Type, Details: $Details";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Log updated for candidate $CandidateData->Name");
    }
}

if (isset($_POST['DeleteLog'])) {
    $ID = $_POST['ID'];
    $reason = $_POST['reason'];

    $query = "DELETE FROM `_communication_logs` WHERE `id` = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    if ($stmt->execute()) {
        $response = 'Log deleted successfully.';
        $Notification = "$NAME has deleted a log for candidate $CandidateData->Name. Reason for deletion is: $reason";
        Notify($USERID, $ClientKeyID, $Notification);
        LastModified($CandidateID, $USERID, "Log deleted for candidate $CandidateData->Name");
    }
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
                                <h5 class="mb-0"><?php echo $page; ?></h5>
                            </div>

                        </div>

                        <div class="card-body">
                            <ul class="nav nav-tabs analytics-tab">
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_candidate/?isTab=Details&ID=<?php echo $CandidateID; ?>" class="nav-link <?php echo ($isTab == 'Details') ? 'active' : ''; ?>">Details</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_candidate/?isTab=Documents&ID=<?php echo $CandidateID; ?>" class="nav-link <?php echo ($isTab == 'Documents') ? 'active' : ''; ?>">Compliance Checklist </a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_candidate/?isTab=Emergency&ID=<?php echo $CandidateID; ?>" class="nav-link <?php echo ($isTab == 'Emergency') ? 'active' : ''; ?>">Emergency Contacts </a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_candidate/?isTab=Skills&ID=<?php echo $CandidateID; ?>" class="nav-link <?php echo ($isTab == 'Skills') ? 'active' : ''; ?>">Skills </a></li>
                                <?php if (!isset($_GET['isBranch'])) : ?>

                                <?php endif; ?>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_candidate/?isTab=Interviews&ID=<?php echo $CandidateID; ?>" class="nav-link <?php echo ($isTab == 'Interviews') ? 'active' : ''; ?>">Interviews</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_candidate/?isTab=Shifts&ID=<?php echo $CandidateID; ?>" class="nav-link <?php echo ($isTab == 'Shifts') ? 'active' : ''; ?>">Shifts</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_candidate/?isTab=Timesheets&ID=<?php echo $CandidateID; ?>" class="nav-link <?php echo ($isTab == 'Timesheets') ? 'active' : ''; ?>">Timesheets</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_candidate/?isTab=CommunicationLog&ID=<?php echo $CandidateID; ?>" class="nav-link <?php echo ($isTab == 'CommunicationLog') ? 'active' : ''; ?>">Communication Log</a></li>
                            </ul>
                            <div class="table-responsive dt-responsive" style="margin-top: 10px;">
                                <?php if (IsCheckPermission($USERID, "VIEW_CANDIDATE")) : ?>
                                    <?php include "i/details.php"; ?>
                                    <?php include "i/documents.php"; ?>
                                    <?php include "i/emergency.php"; ?>
                                    <?php include "i/skill.php"; ?>
                                    <?php include "i/interviews.php"; ?>
                                    <?php include "i/shifts.php"; ?>
                                    <?php include "i/timesheet.php"; ?>
                                    <?php include "i/logs.php"; ?>

                                <?php else : ?>
                                    <?php
                                    DeniedAccess();
                                    ?>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<?php include "../../includes/js.php"; ?>

<script>
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.checkbox-item');
        let allChecked = this.classList.toggle('checked');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = allChecked;
        });
    });

    function ConfirmDelete(data) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: data,
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }


    <?php if ($isTab == "Documents") : ?>
        $(document).ready(function() {
            $('.edit').on('click', function() {
                var checkbox = $('.checkbox-item:checked');

                if (checkbox.length > 0) {
                    // Get the attributes from the checked checkbox
                    var id = checkbox.val();
                    var name = checkbox.data('name');
                    var type = checkbox.data('type');
                    var expiryDate = checkbox.data('expiry');
                    var issuedDate = checkbox.data('issueddate');

                    // Populate the form fields in the modal
                    $('#EditModal #id').val(id);
                    $('#EditModal select[name="Type"]').val(type);
                    $('#EditModal input[name="Name"]').val(name);
                    $('#EditModal input[name="IssueDate"]').val(issuedDate);
                    $('#EditModal input[name="ExpiryDate"]').val(expiryDate);

                    // Show the modal
                    $('#EditModal').modal('show');

                } else {

                    ShowToast('Error 102: Something went wrong.');
                }
            });
        });



        document.getElementById('ConfirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;
            let failCount = 0;

            var reason = $("#reason").val();

            if (reason.length > 0) {
                checkboxes.forEach(function(checkbox) {
                    ids.push({
                        id: checkbox.value,
                        name: checkbox.getAttribute('data-name')
                    });
                });

                if (ids.length > 0) {
                    $("#ConfirmDelete").text("Deleting...");
                    let promises = ids.map(function(item) {
                        let data = {
                            ID: item.id,
                            name: item.name,
                            reason: reason,
                            DeleteCandidateDocument: true
                        };

                        return ConfirmDelete(data)
                            .then(response => {
                                successCount++;
                            })
                            .catch(error => {
                                failCount++;
                                ShowToast("Error: " + error);
                            });
                    });

                    Promise.all(promises).then(() => {
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            ShowToast(`Error: ${failCount} deletions failed.`);
                        }
                    });
                } else {
                    ShowToast('Error 102: Something went wrong.');
                }
            } else {
                ShowToast('Error 101: Reason field is required.');
                return;
            }
        });




    <?php endif; ?>

    <?php if ($isTab == "Emergency") : ?>
        $(document).ready(function() {
            $('.edit').on('click', function() {
                var checkbox = $('.checkbox-item:checked');

                if (checkbox.length > 0) {
                    // Get the attributes from the checked checkbox
                    var id = checkbox.val();
                    $('#EditModal input[name="ID"]').val(checkbox.val());
                    $('#EditModal input[name="Name"]').val(checkbox.data('name'));
                    $('#EditModal input[name="Relationship"]').val(checkbox.data('relationship'));
                    $('#EditModal input[name="PhoneNumber"]').val(checkbox.data('contact'));

                    $('#EditModal').modal('show');

                } else {

                    ShowToast('Error 102: Something went wrong.');
                }
            });
        });

        document.getElementById('ConfirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;
            let failCount = 0;

            var reason = $("#reason").val();

            if (reason.length > 0) {
                checkboxes.forEach(function(checkbox) {
                    ids.push({
                        id: checkbox.value,
                    });
                });

                if (ids.length > 0) {
                    $("#ConfirmDelete").text("Deleting...");
                    let promises = ids.map(function(item) {
                        let data = {
                            ID: item.id,
                            reason: reason,
                            DeleteEmergencyContact: true
                        };

                        return ConfirmDelete(data)
                            .then(response => {
                                successCount++;
                            })
                            .catch(error => {
                                failCount++;
                                ShowToast("Error: " + error);
                            });
                    });

                    Promise.all(promises).then(() => {
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            ShowToast(`Error: ${failCount} deletions failed.`);
                        }
                    });
                } else {
                    ShowToast('Error 102: Something went wrong.');
                }
            } else {
                ShowToast('Error 101: Reason field is required.');
                return;
            }
        });

    <?php endif; ?>

    <?php if ($isTab == "Skills") : ?>
        $(document).ready(function() {
            $('.edit').on('click', function() {
                var checkbox = $('.checkbox-item:checked');

                if (checkbox.length > 0) {
                    // Get the attributes from the checked checkbox
                    var id = checkbox.val();
                    $('#EditModal input[name="ID"]').val(checkbox.val());
                    $('#EditModal input[name="Name"]').val(checkbox.data('name'));

                    $('#EditModal').modal('show');

                } else {

                    ShowToast('Error 102: Something went wrong.');
                }
            });
        });
        document.getElementById('ConfirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;
            let failCount = 0;

            var reason = $("#reason").val();

            if (reason.length > 0) {
                checkboxes.forEach(function(checkbox) {
                    ids.push({
                        id: checkbox.value,
                    });
                });

                if (ids.length > 0) {
                    $("#ConfirmDelete").text("Deleting...");
                    let promises = ids.map(function(item) {
                        let data = {
                            ID: item.id,
                            reason: reason,
                            DeleteSkill: true
                        };

                        return ConfirmDelete(data)
                            .then(response => {
                                successCount++;
                            })
                            .catch(error => {
                                failCount++;
                                ShowToast("Error: " + error);
                            });
                    });

                    Promise.all(promises).then(() => {
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            ShowToast(`Error: ${failCount} deletions failed.`);
                        }
                    });
                } else {
                    ShowToast('Error 102: Something went wrong.');
                }
            } else {
                ShowToast('Error 101: Reason field is required.');
                return;
            }
        });
    <?php endif; ?>


    <?php if ($isTab == "Interviews") : ?>
        $(document).ready(function() {
            $('.edit').on('click', function() {
                var checkbox = $('.checkbox-item:checked');

                if (checkbox.length > 0) {
                    var url = '<?php echo $LINK ?>/' + checkbox.val();
                    window.location.href = url;
                } else {

                    ShowToast('Error 102: Something went wrong.');
                }
            });
        });

        document.getElementById('ConfirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;
            let failCount = 0;

            var reason = $("#reason").val();

            if (reason.length > 0) {
                checkboxes.forEach(function(checkbox) {
                    ids.push({
                        id: checkbox.getAttribute("data-id")
                    });
                });

                if (ids.length > 0) {
                    $("#ConfirmDelete").text("Deleting...");
                    let promises = ids.map(function(item) {
                        let data = {
                            ID: item.id,
                            reason: reason,
                            DeleteInterview: true
                        };

                        return ConfirmDelete(data)
                            .then(response => {
                                successCount++;
                            })
                            .catch(error => {
                                failCount++;
                                ShowToast("Error: " + error);
                            });
                    });

                    Promise.all(promises).then(() => {
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            ShowToast(`Error: ${failCount} deletions failed.`);
                        }
                    });
                } else {
                    ShowToast('Error 102: Something went wrong.');
                }
            } else {
                ShowToast('Error 101: Reason field is required.');
                return;
            }
        });

    <?php endif; ?>

    <?php if ($isTab == "Shifts") : ?>
        $(document).ready(function() {
            $('.edit').on('click', function() {
                var checkbox = $('.checkbox-item:checked');

                if (checkbox.length > 0) {
                    var url = '<?php echo $LINK ?>/' + checkbox.val();
                    window.location.href = url;
                } else {

                    ShowToast('Error 102: Something went wrong.');
                }
            });
        });

        document.getElementById('ConfirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;
            let failCount = 0;

            var reason = $("#reason").val();

            if (reason.length > 0) {
                checkboxes.forEach(function(checkbox) {
                    ids.push({
                        id: checkbox.getAttribute("data-id")
                    });
                });

                if (ids.length > 0) {
                    $("#ConfirmDelete").text("Deleting...");
                    let promises = ids.map(function(item) {
                        let data = {
                            ID: item.id,
                            reason: reason,
                            DeleteShift: true
                        };

                        return ConfirmDelete(data)
                            .then(response => {
                                successCount++;
                            })
                            .catch(error => {
                                failCount++;
                                ShowToast("Error: " + error);
                            });
                    });

                    Promise.all(promises).then(() => {
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            ShowToast(`Error: ${failCount} deletions failed.`);
                        }
                    });
                } else {
                    ShowToast('Error 102: Something went wrong.');
                }
            } else {
                ShowToast('Error 101: Reason field is required.');
                return;
            }
        });
    <?php endif; ?>

    <?php if ($isTab == "Timesheets") : ?>
        $(document).ready(function() {
            $('#Edit').on('click', function() {
                var checkbox = $('.checkbox-item:checked');

                if (checkbox.length > 0) {
                    var id = checkbox.val();
                    window.location.href = "<?php echo $LINK; ?>/generate_timesheet/?isTab=Timesheet&ID=" + id;
                } else {

                    ShowToast('Error 102: Something went wrong.');
                }
            });
        });

        $(document).ready(function() {
            $('#View').on('click', function() {
                var checkbox = $('.checkbox-item:checked');

                if (checkbox.length > 0) {
                    var id = checkbox.val();
                    window.location.href = "<?php echo $LINK; ?>/generate_timesheet/?isTab=Details&ID=" + id;
                } else {

                    ShowToast('Error 102: Something went wrong.');
                }
            });
        });
        document.getElementById('ConfirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;
            let failCount = 0;

            var reason = $("#reason").val();

            if (reason.length > 0) {
                checkboxes.forEach(function(checkbox) {
                    ids.push({
                        id: checkbox.getAttribute("value")
                    });
                });

                if (ids.length > 0) {
                    $("#ConfirmDelete").text("Deleting...");
                    let promises = ids.map(function(item) {
                        let data = {
                            ID: item.id,
                            reason: reason,
                            DeleteTimesheet: true
                        };

                        return ConfirmDelete(data)
                            .then(response => {
                                successCount++;
                            })
                            .catch(error => {
                                failCount++;
                                ShowToast("Error: " + error);
                            });
                    });

                    Promise.all(promises).then(() => {
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            ShowToast(`Error: ${failCount} deletions failed.`);
                        }
                    });
                } else {
                    ShowToast('Error 102: Something went wrong.');
                }
            } else {
                ShowToast('Error 101: Reason field is required.');
                return;
            }
        });
    <?php endif; ?>

    <?php if ($isTab == "CommunicationLog") : ?>
        $("#View").click(function() {
            var checkbox = $('.checkbox-item:checked');

            if (checkbox.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let id = checkbox[0].value;
                let Details = checkbox[0].getAttribute('data-details');;
                $("#CommunicationLogModal").modal('show');
                $("#Details").text(Details);
            }
        });

        $("#Edit").click(function() {
            var checkbox = $('.checkbox-item:checked');

            if (checkbox.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                $("#EditCommunicationLogModal").modal('show');
                const id = checkbox[0].value;
                const Details = checkbox[0].getAttribute('data-details');
                const Type = checkbox[0].getAttribute('data-type');
                $("#Description").val(Details);
                $("#Type").val(Type);
                $("#ID").val(id);


            }
        });

        document.getElementById('ConfirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;
            let failCount = 0;

            var reason = $("#reason").val();

            if (reason.length > 0) {
                checkboxes.forEach(function(checkbox) {
                    ids.push({
                        id: checkbox.value,
                    });
                });

                if (ids.length > 0) {
                    $("#ConfirmDelete").text("Deleting...");
                    let promises = ids.map(function(item) {
                        let data = {
                            ID: item.id,
                            reason: reason,
                            DeleteLog: true
                        };

                        return ConfirmDelete(data)
                            .then(response => {
                                successCount++;
                            })
                            .catch(error => {
                                failCount++;
                                ShowToast("Error: " + error);
                            });
                    });

                    Promise.all(promises).then(() => {
                        if (successCount === ids.length) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            ShowToast(`Error: ${failCount} deletions failed.`);
                        }
                    });
                } else {
                    ShowToast('Error 102: Something went wrong.');
                }
            } else {
                ShowToast('Error 101: Reason field is required.');
                return;
            }
        });
    <?php endif; ?>
</script>


</html>