<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$hasClientID = $_GET['hasClientID'];
$hasBranchID = $_GET['hasBranchID'];
$hasCandidateID = (isset($_GET['CandidateID'])) ? $_GET['CandidateID'] : '';
$InterviewID = $_GET['isID'];
$isNew = $_GET['isNew'];


if (isset($_POST['submit'])) {
    // Assuming InterviewID is provided via $_GET
    $ClientID = $_POST['ClientID'];
    $BranchID = $_POST['BranchID'];
    $CandidateID = $_POST['CandidateID'];
    $InterviewDate = $_POST['InterviewDate'];
    $KeyPerson = $_POST['KeyPerson'];

    $file = $_FILES['file'];
    $FilePath = 'null'; // Initialize FilePath

    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM `interviews` WHERE `InterviewID` = :InterviewID");
    $stmt_check->bindParam(':InterviewID', $InterviewID);
    $stmt_check->execute();
    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        $query = "UPDATE `interviews` SET `ClientKeyID` = :ClientKeyID, `hasClientID` = :ClientID, `hasBranchID` = :BranchID, `CandidateID` = :CandidateID, `DateTime` = :InterviewDate, `KeyPerson` = :KeyPerson, `FilePath` = :FilePath WHERE `InterviewID` = :InterviewID";
    } else {
        $query = "INSERT INTO `interviews` (`InterviewID`, `ClientKeyID`, `hasClientID`, `hasBranchID`, `CandidateID`, `DateTime`, `KeyPerson`, `FilePath`, `CreatedBy`, `Date`) 
        VALUES (:InterviewID, :ClientKeyID, :ClientID, :BranchID, :CandidateID, :InterviewDate, :KeyPerson, :FilePath, :CreatedBy, NOW())";
    }

    if ($file['error'] == UPLOAD_ERR_OK) {
        $fileInfo = pathinfo($file['name']);
        $newFileName = $RandomID . '.' . $fileInfo['extension'];
        $filePath = $File_Directory . '/' . $newFileName;
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $FilePath = $LINK . '/assets/files/' . $newFileName;
        }
    }

    if (isset($query)) {
        // Prepare and bind parameters
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ClientKeyID', $ClientKeyID);
        $stmt->bindParam(':ClientID', $ClientID);
        $stmt->bindParam(':BranchID', $BranchID);
        $stmt->bindParam(':CandidateID', $CandidateID);
        $stmt->bindParam(':InterviewDate', $InterviewDate);
        $stmt->bindParam(':KeyPerson', $KeyPerson);
        $stmt->bindParam(':FilePath', $FilePath);
        if ($isNew == "true") {
            $stmt->bindParam(':CreatedBy', $USERID);
        }

        // Bind InterviewID for both insert and update
        $stmt->bindParam(':InterviewID', $InterviewID);

        // Execute the query
        if ($stmt->execute()) {
            if ($count > 0) {
                $response = "Interview updated successfully.";
                $Modification = "Updated interview";
                $Notification = "$NAME updated an interview.";
            } else {
                $response = "Interview created successfully.";
                $Modification = "Created interview";
                $Notification = "$NAME created a new interview.";
            }
            LastModified($InterviewID, $USERID, $Modification);
            Notify($USERID, $ClientKeyID, $Notification);
        } else {
            $response = "Error: " . $stmt->errorInfo()[2];
        }
    }
}

if ($isNew == "true") {
    $ClientID = $hasClientID;
    $BranchID = $hasBranchID;
    $CandidateID = $hasCandidateID;
    $InterviewDate = date("Y-m-d H:i");
    $keyPerson = "";
    $Title = "Create Interview";
    $Status = "Pending";
}
if ($isNew == "false") {
    $query = "SELECT * FROM `interviews` WHERE InterviewID = '$InterviewID'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    $ClientID = $data->hasClientID;
    $BranchID = $data->hasBranchID;
    $CandidateID = $data->CandidateID;
    $InterviewDate = $data->DateTime;
    $keyPerson = $data->KeyPerson;
    $Status = $data->Status;

    $Title = "Edit Interview";
}



if (isset($_POST['Status'])) {
    $Status = $_POST['Status'];
    $query = "UPDATE `interviews` SET `Status` = :Status WHERE `InterviewID` = :InterviewID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':Status', $Status);
    $stmt->bindParam(':InterviewID', $InterviewID);
    if ($stmt->execute()) {
        $response = "Status updated successfully.";
        $Modification = "Updated interview status";
        $Notification = "$NAME updated the status of an interview to $Status.";
        LastModified($InterviewID, $USERID, $Modification);
        Notify($USERID, $ClientKeyID, $Notification);
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
                                <h5 class="mb-0"><?php echo $Title; ?></h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "CREATE_INTERVIEW")) : ?>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="col-md-12">
                                        <div class="card shadow-none border mb-0 h-100">
                                            <div class="card-body">
                                                <h6 class="mb-2">Client's Details</h6>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3"><label class="form-label">Client</label>
                                                            <select name="ClientID" id="ClientID" class="select-input">
                                                                <option value=""></option>
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
                                                                $branches_list = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isClient = '$ClientID' AND isBranch IS NOT NULL");
                                                                while ($row = $branches_list->fetchObject()) { ?>
                                                                    <option <?php echo ($row->ClientID == $BranchID) ? "selected" : ""; ?> value="<?php echo $row->ClientID; ?>"><?php echo $row->Name; ?></option>
                                                                <?php }  ?>
                                                                <?php if ($branches_list->rowCount() == 0) : ?>
                                                                    <option value="">No branches found</option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row g-3" style="margin-top: 10px;">
                                            <div class="col-md-6">
                                                <div class="card shadow-none border mb-0 h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 me-3">
                                                                <h6 class="mb-0">Candidate</h6>
                                                            </div>
                                                            <div class="flex-shrink-0"><a href="<?php echo $LINK; ?>/create_candidate" class="btn btn-sm btn-light-secondary"><i class="ti ti-plus"></i> Add Candidate</a></div>
                                                        </div>
                                                        <div class="mb-3 mt-3">
                                                            <label class="form-label">Candidate name</label>
                                                            <select required name="CandidateID" id="CandidateID" class="select-input">
                                                                <option value=""></option>
                                                                <?php
                                                                $candidates_list = $conn->query("SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' AND Status = 'Active' ");
                                                                while ($row = $candidates_list->fetchObject()) { ?>
                                                                    <option <?php echo ($CandidateID == $row->CandidateID) ? "selected" : ""; ?> value="<?php echo $row->CandidateID; ?>"><?php echo $row->Name; ?></option>
                                                                <?php }  ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card shadow-none border mb-0 h-100">
                                                    <div class="card-body">
                                                        <?php if ($isNew == "true") : ?>
                                                            <h6 class="mb-2">Interview Date and Time</h6>

                                                        <?php else : ?>
                                                            <h6 class="mb-2">Interview Date and Status</h6>

                                                        <?php endif; ?>
                                                        <div class="row mt-4">
                                                            <div class="<?php echo ($isNew == "true") ? "col-sm-12" : "col-sm-6"; ?>">
                                                                <div class="mb-3"><label class="form-label">Date Time</label>
                                                                    <input required type="datetime-local" name="InterviewDate" class="form-control" value="<?php echo $InterviewDate; ?>" placeholder="Enter">
                                                                </div>
                                                            </div>

                                                            <?php if ($isNew == "false") : ?>
                                                                <div class="col-sm-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Status</label>


                                                                        <select class="form-control" name="status" id="status">
                                                                            <?php foreach ($InterView_Status as $statusOption) : ?>
                                                                                <option value="<?php echo $statusOption; ?>" <?php if ($Status == $statusOption) echo 'selected'; ?>>
                                                                                    <?php echo $statusOption; ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>

                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3" style="margin-top: 10px;">
                                            <div class="col-md-6">
                                                <div class="card shadow-none border mb-0 h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 me-3">
                                                                <h6 class="mb-0">Key Person</h6>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 mt-3">
                                                            <label class="form-label">Select Key Person</label>
                                                            <select name="KeyPerson" id="KeyPeople" class="select-input">
                                                                <option value=""></option>
                                                                <?php
                                                                $_key_people_list = $conn->query("SELECT * FROM `_clients_key_people` WHERE ClientID = '$hasClientID' OR ClientID = '$hasBranchID' GROUP BY Name");
                                                                while ($row = $_key_people_list->fetchObject()) { ?>
                                                                    <option <?php echo ($keyPerson == $row->id) ? "selected" : ""; ?> value="<?php echo $row->id; ?>"><?php echo $row->Name; ?></option>
                                                                <?php }  ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card shadow-none border mb-0 h-100">
                                                    <div class="card-body">
                                                        <h6 class="mb-2">Documents</h6>
                                                        <div class="row">

                                                            <div class="col-sm-12">
                                                                <div class="mb-3"><label class="form-label">Upload Document</label>
                                                                    <input type="file" class="form-control" name="file">
                                                                </div>
                                                            </div>
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

            if (ClientID.length > 0) {
                window.location.href = "<?php echo $LINK; ?>/create_interview/?hasClientID=" + ClientID + "&hasBranchID=1&isID=<?php echo $InterviewID; ?>&isNew=<?php echo $isNew; ?>&CandidateID=<?php echo $CandidateID ?>"

            }

        })
    });

    $(document).ready(function() {
        $("#BranchID").change(function() {
            var BranchID = $(this).val();
            if (BranchID.length > 0) {
                window.location.href = "<?php echo $LINK; ?>/create_interview/?hasClientID=<?php echo $hasClientID; ?>&hasBranchID=" + BranchID + "&isID=<?php echo $InterviewID; ?>&isNew=<?php echo $isNew; ?>&CandidateID=<?php echo $CandidateID ?>"

            }
        })
    });

    $("#status").change(function() {
        var value = $(this).val();
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                InterviewID: "<?php echo $InterviewID; ?>",
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