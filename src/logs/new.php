<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$hasClientID = $_GET['hasClientID'];
$hasBranchID = $_GET['hasBranchID'];
$LogID = $_GET['LogID'];
$isNew = $_GET['isNew'];
$IsFor = $_GET['IsFor'];
if (isset($_POST['submit'])) {
    // Initialize variables
    $RecipientID = "";

    // Determine the RecipientID based on $IsFor value
    if ($IsFor == "Client") {
        if (isset($_POST['BranchID']) && !empty($_POST['BranchID'])) {
            $RecipientID = $_POST['BranchID'];
        } else if (isset($_POST['ClientID']) && !empty($_POST['ClientID'])) {
            $RecipientID = $_POST['ClientID'];
        }
    } elseif ($IsFor == "Candidate" && isset($_POST['CandidateID'])) {
        $RecipientID = $_POST['CandidateID'];
    }

    // Check if it's a new entry
    if (!empty($RecipientID)) {
        // Sanitize input data
        $Type = htmlspecialchars($_POST['Type']);
        $Description = htmlspecialchars($_POST['Description']);
        $Category = $IsFor;
        $Date = date('Y-m-d H:i:s'); // Get current date and time

        // Check if LogID exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM `_communication_logs` WHERE `LogID` = :LogID");
        $stmt->bindParam(':LogID', $LogID);
        $stmt->execute();
        $logExists = $stmt->fetchColumn() > 0;

        if ($logExists) {
            // Update existing record
            $query = "UPDATE `_communication_logs` SET `ClientKeyID` = :ClientKeyID, `RecipientID` = :RecipientID, `Category` = :Category, `Type` = :Type, `Details` = :Details, `CreatedBy` = :CreatedBy, `Date` = :Date WHERE `LogID` = :LogID";
        } else {
            // Insert new record
            $query = "INSERT INTO `_communication_logs` (`ClientKeyID`, `LogID`, `RecipientID`, `Category`, `Type`, `Details`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :LogID, :RecipientID, :Category, :Type, :Details, :CreatedBy, :Date)";
        }

        // Prepare the statement
        $stmt = $conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':ClientKeyID', $ClientKeyID);
        $stmt->bindParam(':LogID', $LogID);
        $stmt->bindParam(':RecipientID', $RecipientID);
        $stmt->bindParam(':Category', $Category);
        $stmt->bindParam(':Type', $Type);
        $stmt->bindParam(':Details', $Description);
        $stmt->bindParam(':CreatedBy', $USERID);
        $stmt->bindParam(':Date', $date);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            if ($logExists) {
                $response = "Communication log updated successfully!";
            } else {
                $response = "Communication log created successfully!";
            }
            $Description = TruncateText($Description, 70);
            $Notification = "$NAME created a communication log with description '$Description'.";
            $Modification = "Created a communication log with description '$Description'";
            LastModified($LogID, $USERID, $Modification);
            Notify($USERID, $ClientKeyID, $Notification);
        } else {
            $response = "Error: " . $stmt->errorInfo()[2];
            $error = 1;
        }
    } else {
        $response = "Something went wrong. Please try again";
        $error = 1;
    }
}

if ($isNew == "true") {
    $ClientID = $hasClientID;
    $BranchID = $hasBranchID;
    $CandidateID = "";
    $InterviewDate = date("Y-m-d H:i");
    $keyPerson = "";
    $Title = "Create Log";
    $Type = "";
    $Details = "";
}
if ($isNew == "false") {
    $query = "SELECT * FROM `_communication_logs` WHERE LogID = '$LogID'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    $ClientID = $hasClientID;
    $BranchID = $hasBranchID;
    $CandidateID = $data->RecipientID;

    $Type = $data->Type;
    $Details = $data->Details;

    $Title = "Edit Log";
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
                                <h5 class="mb-0"><?php echo $Title; ?></h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "CREATE_LOG")) : ?>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="col-md-12">
                                        <div class="card shadow-none border mb-0 h-100">
                                            <div class="card-body">
                                                <?php if ($IsFor == "Client") : ?>
                                                    <h6 class="mb-2">Client's Details</h6>

                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="mb-3"><label class="form-label">Client</label>
                                                                <select name="ClientID" id="ClientID" class="select-input">
                                                                    <?php
                                                                    $clients_list = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isBranch IS NULL");
                                                                    while ($row = $clients_list->fetchObject()) { ?>
                                                                        <option <?php echo ($ClientID == $row->ClientID) ? 'selected' : ''; ?> value="<?php echo $row->ClientID; ?>"><?php echo $row->Name; ?></option>
                                                                    <?php }  ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Branch</label>
                                                                <select name="BranchID" id="BranchID" class="select-input">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    $branches_list = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isClient = '$hasClientID' AND isBranch IS NOT NULL ");
                                                                    while ($row = $branches_list->fetchObject()) { ?>
                                                                        <option <?php echo ($BranchID == $row->ClientID) ? 'selected' : ''; ?> value="<?php echo $row->ClientID; ?>"><?php echo $row->Name; ?></option>
                                                                    <?php }  ?>
                                                                    <?php if ($branches_list->rowCount() == 0) : ?>
                                                                        <option value="">No branches found</option>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="col-md-12">
                                                        <div>
                                                            <div>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-grow-1 me-3">
                                                                        <h6 class="mb-0">Candidate</h6>
                                                                    </div>
                                                                    <div class="flex-shrink-0"><a href="<?php echo $LINK; ?>/create_candidate" class="btn btn-sm btn-light-secondary"><i class="ti ti-plus"></i> Add Candidate</a></div>
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <label class="form-label">Candidate name</label>
                                                                    <select required name="CandidateID" id="RecipientID" class="select-input">
                                                                        <option value=""></option>
                                                                        <?php
                                                                        $candidates_list = $conn->query("SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID'");
                                                                        while ($row = $candidates_list->fetchObject()) { ?>
                                                                            <option <?php echo ($CandidateID == $row->CandidateID) ? "selected" : ""; ?> value="<?php echo $row->CandidateID; ?>"><?php echo $row->Name; ?></option>
                                                                        <?php }  ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                        </div>

                                        <div class="row g-3" style="margin-top: 10px;">
                                            <div class="col-md-6">
                                                <div class="card shadow-none border mb-0 h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 me-3">
                                                                <h6 class="mb-0">Type</h6>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 mt-3">
                                                            <label class="form-label">Enter Log Type</label>
                                                            <input type="text" value="<?php echo $Type; ?>" name="Type" class="form-control" style="margin-top: 10px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card shadow-none border mb-0 h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 me-3">
                                                                <h6 class="mb-0">Description</h6>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 mt-3">
                                                            <label class="form-label">Enter Description</label>
                                                            <textarea name="Description" class="form-control" ><?php echo $Details; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="d-grid gap-2 mt-2"><button class="btn btn-primary" type="submit" name="submit">Submit</button></div>
                                    </div>
                                </form>

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
    $(document).ready(function() {
        $("#ClientID").change(function() {
            var ClientID = $(this).val();

            window.location.href = "<?php echo $LINK; ?>/create_log/?hasClientID=" + ClientID + "&hasBranchID=<?php echo $hasBranchID; ?>&LogID=<?php echo $LogID; ?>&isNew=<?php echo $isNew; ?>&IsFor=<?php echo $IsFor ?>"
        })
    });

    $(document).ready(function() {
        $("#BranchID").change(function() {
            var BranchID = $(this).val();

            window.location.href = "<?php echo $LINK; ?>/create_log/?hasClientID=<?php echo $hasClientID; ?>&hasBranchID=" + BranchID + "&LogID=<?php echo $LogID; ?>&isNew=<?php echo $isNew; ?>&IsFor=<?php echo $IsFor ?>"
        })
    });

    $("#status").change(function() {
        var value = $(this).val();
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                InterviewID: "<?php echo $LogID; ?>",
                Status: value
            },
            success: function(response) {
                console.log(response);
                ShowToast("Interview status successfully updated")

            }
        });
    })
</script>

</html>