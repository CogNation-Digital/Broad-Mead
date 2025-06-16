<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$hasClientID = $_GET['hasClientID'];
$hasBranchID = $_GET['hasBranchID'];
$VacancyID = $_GET['VacancyID'];
$isNew = $_GET['isNew'];
$isTab = $_GET['isTab'];

if (isset($_POST['submit'])) {
    // Retrieve form data
    $ClientID = $_POST['ClientID'];
    $BranchID = $_POST['BranchID'];
    $Type = $_POST['Type'];
    $Title = $_POST['Title'];
    $Roles = $_POST['Roles'];
    $FromDate = $_POST['FromDate'];
    $ToDate = $_POST['ToDate'];
    $target_dir = $File_Directory;
    $FilePath = "";


    if (isset($_FILES["Document"])) {
        $target_file = $target_dir . basename($_FILES["Document"]["name"]);
        $uploadOk = 1;
        $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = array("pdf", "doc", "docx", "txt");
        if (!in_array($FileType, $valid_extensions)) {
            $response = "Sorry, only PDF, DOC, DOCX & TXT files are allowed.";
            $uploadOk = 0;
            $error = 1;
        }
        if ($uploadOk == 1) {
            $newFileName = $RandomID . '.' . $FileType;
            $filePath = $target_dir . $newFileName;
            if (move_uploaded_file($_FILES["Document"]["tmp_name"], $filePath)) {
                $FilePath = $LINK . '/assets/files/' . $newFileName;
            } else {
                $FilePath = "";
            }
        }
    }
    if ($VacancyID) {
        // Check if the vacancy exists
        $checkQuery = $conn->prepare("SELECT * FROM `vacancies` WHERE `VacancyID` = :VacancyID");
        $checkQuery->bindParam(":VacancyID", $VacancyID);
        $checkQuery->execute();

        if ($checkQuery->rowCount() > 0) {
            $updateQuery = $conn->prepare("UPDATE `vacancies` SET `ClientKeyID` = :ClientKeyID, `hasClientID` = :ClientID, `hasBranchID` = :BranchID, `Title` = :Title, `Type` = :Type, `Roles` = :Roles, `FilePath` = :FilePath, `StartDate` = :StartDate, `EndDate` = :EndDate WHERE `VacancyID` = :VacancyID");

            $updateQuery->bindParam(":ClientKeyID", $ClientKeyID);
            $updateQuery->bindParam(":ClientID", $ClientID);
            $updateQuery->bindParam(":BranchID", $BranchID);
            $updateQuery->bindParam(":Title", $Title);
            $updateQuery->bindParam(":Type", $Type);
            $updateQuery->bindParam(":Roles", $Roles);
            $updateQuery->bindParam(":FilePath", $FilePath);
            $updateQuery->bindParam(":StartDate", $FromDate);
            $updateQuery->bindParam(":EndDate", $ToDate);
            $updateQuery->bindParam(":VacancyID", $VacancyID);

            if ($updateQuery->execute()) {
                $response = "Vacancy has been updated successfully.";
                $error = 0;
                $Modification = "Updated the vacancy";
                $Notification = "$NAME updated a $Type vacancy, titled $Title with $Roles number of roles. Vacancy period is from $FromDate to $ToDate ";
                LastModified($VacancyID, $USERID, $Modification);
                Notify($USERID, $ClientKeyID, $Notification);
            } else {
                $response = "Error: " . $updateQuery->errorInfo()[2];
                $error = 1;
            }
        } else {
            $insertQuery = $conn->prepare("INSERT INTO `vacancies` (`ClientKeyID`, `VacancyID`, `hasClientID`, `hasBranchID`, `Title`, `Type`, `Roles`, `FilePath`, `StartDate`, `EndDate`, `CreatedBy`, `Date`) 
                                           VALUES (:ClientKeyID, :VacancyID, :ClientID, :BranchID, :Title, :Type, :Roles, :FilePath, :StartDate, :EndDate, :CreatedBy, :Date)");

            $insertQuery->bindParam(":ClientKeyID", $ClientKeyID);
            $insertQuery->bindParam(":VacancyID", $VacancyID);
            $insertQuery->bindParam(":ClientID", $ClientID);
            $insertQuery->bindParam(":BranchID", $BranchID);
            $insertQuery->bindParam(":Title", $Title);
            $insertQuery->bindParam(":Type", $Type);
            $insertQuery->bindParam(":Roles", $Roles);
            $insertQuery->bindParam(":FilePath", $FilePath);
            $insertQuery->bindParam(":StartDate", $FromDate);
            $insertQuery->bindParam(":EndDate", $ToDate);
            $insertQuery->bindParam(":CreatedBy", $USERID);
            $insertQuery->bindParam(":Date", $date);

            if ($insertQuery->execute()) {
                $Modification = "Created the vacancy";
                $Notification = "$NAME created a $Type vacancy, titled $Title with $Roles number of roles. Vacancy period is from $FromDate to $ToDate ";
                LastModified($VacancyID, $USERID, $Modification);
                Notify($USERID, $ClientKeyID, $Notification);
                $response = "New vacancy has been added successfully.";
                $error = 0;
            } else {
                $response = "Error: " . $insertQuery->errorInfo()[2];
                $error = 1;
            }
        }
    }
}



// Securely prepare the SQL query to avoid SQL injection
$query = "SELECT * FROM `vacancies` WHERE ClientKeyID = :ClientKeyID AND VacancyID = :VacancyID";
$stmt = $conn->prepare($query);

// Bind parameters to avoid SQL injection
$stmt->bindParam(':ClientKeyID', $ClientKeyID);
$stmt->bindParam(':VacancyID', $VacancyID);

// Execute the query
$stmt->execute();

// Fetch the result
$VacancyData = $stmt->fetchObject();

// Set default values if no data is found
$ClientID = $hasClientID;
$BranchID = $hasBranchID;
$VacancyTitle = $VacancyData ? $VacancyData->Title : '';
$Type = $VacancyData ? $VacancyData->Type : '';
$Roles = $VacancyData ? $VacancyData->Roles : '';
$StartDate = $VacancyData ? $VacancyData->StartDate : '';
$EndDate = $VacancyData ? $VacancyData->EndDate : '';



if (isset($_POST['AddShift'])) {
    $zero = 0;
    $query = "INSERT INTO `__shifts__`(`VacancyID`, `PayRate`,`SupplyRate`, `CreatedBy`, `Date`) VALUES (:VacancyID, :PayRate,:SupplyRate, :CreatedBy, :Date)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':VacancyID', $VacancyID, PDO::PARAM_STR);
    $stmt->bindParam(':PayRate', $zero);
    $stmt->bindParam(':SupplyRate', $zero);
    $stmt->bindParam(':CreatedBy', $USERID, PDO::PARAM_STR);
    $stmt->bindParam(':Date', $date, PDO::PARAM_STR);
    $stmt->execute();

    // Get the last inserted ID
    $lastid = $conn->lastInsertId();

    $Modification = "Added Shift ID $lastid.";
    $Notification = "$NAME added Shift ID $lastid to the vacancy.";
    LastModified($VacancyID, $USERID, $Modification);
    Notify($USERID, $ClientKeyID, $Notification);
}


if (isset($_POST['UpdateShift'])) {
    $id = $_POST['id'];
    $ShiftType = $_POST['ShiftType'];
    $StartTime = $_POST['StartTime'];
    $EndTime = $_POST['EndTime'];
    $PayRate = $_POST['PayRate'];
    $SupplyRate = $_POST['SupplyRate'];

    $update = $conn->prepare("UPDATE `__shifts__` SET `ShiftType` = :ShiftType, `StartTime` = :StartTime, `EndTime` = :EndTime, `PayRate` = :PayRate, `SupplyRate` = :SupplyRate WHERE `id` = :id");

    $update->bindParam(':ShiftType', $ShiftType);
    $update->bindParam(':StartTime', $StartTime);
    $update->bindParam(':EndTime', $EndTime);
    $update->bindParam(':PayRate', $PayRate);
    $update->bindParam(':SupplyRate', $SupplyRate);
    $update->bindParam(':id', $id);

    $update->execute();
}


if (isset($_POST['DeleteShift'])) {
    $ID = $_POST['ID'];
    $delete = $conn->prepare("DELETE FROM `__shifts__` WHERE `id` = :id");
    $delete->bindParam(':id', $ID);
    $delete->execute();
    $Notification = "Shift ID $ID was removed from the vacancy by $NAME.";
    Notify($USERID, $ClientKeyID, $Notification);
}
$Title = ($isNew == "true") ? "Create Vacancy" : "Edit Vacancy";

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
                                <h5 class="mb-0">
                                     <?php echo $Title; ?>
                                </h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs analytics-tab" style="margin-bottom: 10px;">
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/create_vacancy/?isNew=<?php echo $isNew; ?>&hasClientID=<?php echo $hasClientID; ?>&hasBranchID=<?php echo $hasBranchID; ?>&VacancyID=<?php echo $VacancyID; ?>&isTab=Details" class="nav-link <?php echo ($isTab == 'Details') ? 'active' : ''; ?>">Details</a></li>
                                <?php if ($Type == "Shift") : ?>
                                    <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/create_vacancy/?isNew=<?php echo $isNew; ?>&hasClientID=<?php echo $hasClientID; ?>&hasBranchID=<?php echo $hasBranchID; ?>&VacancyID=<?php echo $VacancyID; ?>&isTab=Shifts" class="nav-link <?php echo ($isTab == 'Shifts') ? 'active' : ''; ?>">Shifts</a></li>
                                <?php endif; ?>
                            </ul>
                            <?php if (IsCheckPermission($USERID, "CREATE_VACANCY")) : ?>
                                <?php if ($isTab == "Details") : ?>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="col-md-12">
                                            <div class="card shadow-none border mb-0 h-100">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Client's Details</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Client</label>
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
                                                                    $branches_list = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isClient = '$hasClientID' AND isBranch IS NOT NULL");
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
                                                </div>
                                            </div>

                                            <div class="row g-3" style="margin-top: 10px;">
                                                <div class="col-md-12">
                                                    <div class="card shadow-none border mb-0 h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 me-3">
                                                                    <h6 class="mb-0">Vacancy Details</h6>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 mt-3">
                                                                <div class="col-sm-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Type</label>
                                                                        <select name="Type" class="form-control">
                                                                            <option <?php echo ($Type == "Shift") ? "selected" : ""; ?> value="Shift">Shift</option>
                                                                            <option <?php echo ($Type == "Permanent") ? "selected" : ""; ?> value="Permanent">Permanent</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Title</label>
                                                                        <input type="text" value="<?php echo $VacancyTitle; ?>" class="form-control" name="Title">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Number of roles</label>
                                                                        <input type="text" value="<?php echo $Roles; ?>" class="form-control" name="Roles">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card shadow-none border mb-0 h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 me-3">
                                                                    <h6 class="mb-0">Period</h6>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 mt-3">
                                                                <div class="col-sm-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">From</label>
                                                                        <input type="date" value="<?php echo $StartDate; ?>" class="form-control" name="FromDate">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">To</label>
                                                                        <input type="date" value="<?php echo $EndDate; ?>" class="form-control" name="ToDate">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card shadow-none border mb-0 h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 me-3">
                                                                    <h6 class="mb-0">Document</h6>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 mt-3">
                                                                <div class="col-sm-12">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Upload Document</label>
                                                                        <input type="file" class="form-control" name="Document">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="d-grid gap-2 mt-2">
                                                <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
                                <?php if ($isTab == "Shifts") : ?>
                                    <div class="card">
                                        <form method="POST">
                                            <div class="card-header">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 mx-3">
                                                        <h6 class="mb-0">Shifts</h6>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <button name="AddShift" class="btn  btn-primary"><i class="ti ti-plus" style="margin-top: 10px;"></i> Add Shift</button>
                                                        <span data-bs-toggle="modal" data-bs-target="#DeleteModal" class="btn btn-danger"><i class="ti ti-trash"></i> Delete Shift</span>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="card-body">
                                            <table class="table table-bordered table-hover">
                                                <tbody>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>
                                                            <span id="selectAll" style="cursor: pointer;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                    <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                                </svg>
                                                            </span>
                                                        </th>
                                                        <th>Shift Type</th>
                                                        <th>Start Time</th>
                                                        <th>End Time</th>
                                                        <th width="12%">Pay Rate</th>
                                                        <th width="12%">Supplier Rate</th>
                                                        <th width="12%">Total Margin</th>

                                                    </tr>
                                                    <?php
                                                    $n = 1;
                                                    $__shifts__query = "SELECT * FROM `__shifts__` WHERE VacancyID = '$VacancyID'";
                                                    $__shifts__stmt = $conn->prepare($__shifts__query);
                                                    $__shifts__stmt->execute();
                                                    while ($row = $__shifts__stmt->fetchObject()) { ?>
                                                        <tr data-id="<?php echo $row->id; ?>">
                                                            <td><?php echo $row->id; ?></td>
                                                            <td> <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->id; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                            <td>
                                                                <select id="ShiftType" class="form-control" autocomplete="off">
                                                                    <?php
                                                                    $shifttypes = $conn->query("SELECT * FROM `shifttype` WHERE ClientKeyID = '$ClientKeyID'");
                                                                    while ($shifttype = $shifttypes->fetchObject()) { ?>
                                                                        <option <?php echo ($shifttype->ShiftType == $row->ShiftType) ? 'selected' : ''; ?>><?php echo $shifttype->ShiftType; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="time" id="StartTime" value="<?php echo $row->StartTime; ?>" class="form-control" autocomplete="off"></td>
                                                            <td><input type="time" id="EndTime" value="<?php echo $row->EndTime; ?>" class="form-control quantity" autocomplete="off"></td>
                                                            <td width="12%"><input type="text" value="<?php echo $row->PayRate; ?>" id="PayRate" class="form-control" autocomplete="off"></td>
                                                            <td width="12%"><input type="text" value="<?php echo $row->SupplyRate; ?>" id="SupplyRate" class="form-control" autocomplete="off"></td>
                                                            <td width="12%"><input readonly type="text" value="<?php
                                                                                                                $TotalMargin = (float)$row->SupplyRate - (float)$row->PayRate ;
                                                                                                                echo $TotalMargin; ?>" id="TotalMargin" class="form-control" autocomplete="off"></td>
                                                        </tr>
                                                    <?php } ?>


                                                </tbody>
                                            </table>

                                            <?php if ($__shifts__stmt->rowCount() == 0) : ?>
                                                <div class="alert alert-danger">
                                                    No data found.
                                                </div>
                                            <?php endif; ?>
                                        </div>
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
                                                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
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
    $(document).ready(function() {
        $("#ClientID").change(function() {
            var ClientID = $(this).val();

            window.location.href = "<?php echo $LINK; ?>/create_vacancy/?hasClientID=" + ClientID + "&hasBranchID=<?php echo $hasBranchID; ?>&VacancyID=<?php echo $VacancyID; ?>&isNew=<?php echo $isNew; ?>&isTab=<?php echo $isTab ?>"
        })
    });

    $(document).ready(function() {
        $("#BranchID").change(function() {
            var BranchID = $(this).val();

            window.location.href = "<?php echo $LINK; ?>/create_vacancy/?hasClientID=<?php echo $hasClientID; ?>&hasBranchID=" + BranchID + "&VacancyID=<?php echo $VacancyID; ?>&isNew=<?php echo $isNew; ?>&isTab=<?php echo $isTab ?>"
        })
    });

    $("#status").change(function() {
        var value = $(this).val();
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                InterviewID: "<?php echo $VacancyID; ?>",
                Status: value
            },
            success: function(response) {
                console.log(response);
                ShowToast("Interview status successfully updated")

            }
        });
    })

    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.checkbox-item');
        let allChecked = this.classList.toggle('checked');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = allChecked;
        });
    });

    $(document).ready(function() {
        $('input, select').on('change', function() {
            var $row = $(this).closest('tr');
            var dataId = $row.data('id');
            var shiftType = $row.find('#ShiftType').val();
            var startTime = $row.find('#StartTime').val();
            var endTime = $row.find('#EndTime').val();
            var payRate = $row.find('#PayRate').val();
            var supplyRate = $row.find('#SupplyRate').val();

            var totalMargin = parseFloat(supplyRate) - parseFloat(payRate);
            $row.find('#TotalMargin').val(totalMargin);

            if (totalMargin < 0) {
                ShowToast('Error: Total Margin is negative. Please check the values.');
                return; // Exit the function to avoid sending the AJAX request
            }

            var postData = {
                UpdateShift: true,
                id: dataId,
                ShiftType: shiftType,
                StartTime: startTime,
                EndTime: endTime,
                PayRate: payRate,
                SupplyRate: supplyRate,
                TotalMargin: totalMargin
            };

            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: postData,
                success: function(response) {
                    ShowToast('Data updated successfully');
                },
                error: function(xhr, status, error) {
                    ShowToast('Something went wrong. Please try again.');
                }
            });
        });
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.checkbox-item:checked');
        let ids = [];

        checkboxes.forEach(function(checkbox) {
            ids.push(checkbox.value);
        });

        if (ids.length > 0) {
            $("#confirmDelete").text("Deleting...");

            ids.forEach(function(id) {
                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: {
                        ID: id,
                        DeleteShift: true
                    },
                    success: function(response) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                });

            });


        } else {
            ShowToast('Error 102: Something went wrong.');

        }
    });
</script>

</html>