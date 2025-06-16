<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$ClientID = $_GET['ID'];
$isTab = (isset($_GET['isTab'])) ? $_GET['isTab'] : 'Details';
$ClientData = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND ClientID = '$ClientID'")->fetchObject();

if (isset($_POST['update'])) {
    $RegistrationNumber = $_POST['RegistrationNumber'];
    $VatNo = $_POST['VatNo'];

    // Assuming $ClientKeyID and $ClientID are already defined and sanitized

    // Prepare the SQL statement
    $update = "UPDATE `_clients` SET RegistrationNumber = :RegistrationNumber, VatNo = :VatNo WHERE ClientKeyID = :ClientKeyID AND ClientID = :ClientID";
    $stmt = $conn->prepare($update);

    // Bind parameters
    $stmt->bindParam(':RegistrationNumber', $RegistrationNumber);
    $stmt->bindParam(':VatNo', $VatNo);
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':ClientID', $ClientID);

    // Execute the update
    if ($stmt->execute()) {
        // Update successful
        $Modification = "Updated Registration and VAT number";
        $Notification = "$NAME updated Registration and VAT number   '$ClientData->Name'";
        LastModified($RandomID, $USERID, $Modification);
        Notify($USERID, $ClientKeyID, $Notification);
        $response =  "Registration and VAT number updated successfully";
    } else {
        // Update failed
        $response =  "Update failed. Please try again.";
    }
}
if (isset($_POST['CreateDocument'])) {
    $name = $_POST['name'];
    $file = $_FILES['file'];

    if ($file['error'] == UPLOAD_ERR_OK) {
        $fileInfo = pathinfo($file['name']);
        $newFileName = $RandomID . '.' . $fileInfo['extension'];
        $filePath = $File_Directory . $newFileName;
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $FilePath = $LINK . '/assets/files/' . $newFileName;
            $query = "INSERT INTO `_clients_documents`(`ClientKeyID`, `ClientID`, `Name`, `Path`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :ClientID, :Name, :Path, :CreatedBy, :Date)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':ClientKeyID', $ClientKeyID);
            $stmt->bindParam(':ClientID', $ClientID);
            $stmt->bindParam(':Name', $name);
            $stmt->bindParam(':Path', $FilePath);
            $stmt->bindParam(':CreatedBy', $USERID);
            $stmt->bindParam(':Date', $date);
            $stmt->execute();
            $Modification = "Uploaded document '$name'";
            $Notification = "$NAME uploaded a document '$name'  for '$ClientData->Name'";
            Notify($USERID, $ClientKeyID, $Notification);
            $response = "The file has been uploaded successfully.";
        } else {
            $response = "Error uploading the file.";
        }
    } else {
        $response = "Error: " . $file['error'];
    }
}

if (isset($_POST['UpdateDocument'])) {
    $name = $_POST['name'];
    $id = $_POST['id'];
    $file = $_FILES['file'];

    if ($file['error'] == UPLOAD_ERR_OK) {
        $fileInfo = pathinfo($file['name']);
        $newFileName = $RandomID . '.' . $fileInfo['extension'];
        $filePath = $File_Directory . $newFileName;
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $FilePath = $LINK . '/assets/files/' . $newFileName;
            $query = "UPDATE `_clients_documents` SET `Name`=:Name, `Path`=:Path WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':Name', $name);
            $stmt->bindParam(':Path', $FilePath);
            $stmt->execute();
            $Modification = "Uploaded document '$name'";
            $Notification = "$NAME uploaded a document '$name' for  '$ClientData->Name'";
            Notify($USERID, $ClientKeyID, $Notification);
            $response = "The file has been uploaded successfully.";
        } else {
            $response = "Error uploading the file.";
        }
    } else {
        $response = "Error: " . $file['error'];
    }
}

if (isset($_POST['DeleteClientDocument'])) {
    $ID = $_POST['ID'];
    $query = "DELETE FROM `_clients_documents` WHERE id = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    $stmt->execute();
    $Notification = "$NAME deleted a document for '$ClientData->Name'";
    Notify($USERID, $ClientKeyID, $Notification);
}

if (isset($_POST['CreateKeyPeople'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $position = $_POST['position'];
    $query = "INSERT INTO `_clients_key_people`(`ClientID`, `Name`, `Email`, `Number`, `Position`, `CreatedBy`, `Date`) 
    VALUES (:ClientID, :Name, :Email, :Number, :Position, :CreatedBy, :Date)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ClientID', $ClientID);
    $stmt->bindParam(':Name', $name);
    $stmt->bindParam(':Email', $email);
    $stmt->bindParam(':Number', $number);
    $stmt->bindParam(':Position', $position);
    $stmt->bindParam(':CreatedBy', $USERID);
    $stmt->bindParam(':Date', $date);
    $stmt->execute();
    $Modification = "Created key person '$name'";
    $Notification = "$NAME created a key person '$name'   '$ClientData->Name'";
    Notify($USERID, $ClientKeyID, $Notification);
    $response = "Key person successfully created.";
}


if (isset($_POST['UpdateKeyPeople'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $position = $_POST['position'];
    $id = $_POST['id'];
    $query = "UPDATE `_clients_key_people` SET `Name`=:Name, `Email`=:Email, `Number`=:Number, `Position`=:Position WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':Name', $name);
    $stmt->bindParam(':Email', $email);
    $stmt->bindParam(':Number', $number);
    $stmt->bindParam(':Position', $position);
    $stmt->execute();
    $Notification = "$NAME updated a key person '$name'   '$ClientData->Name'";
    Notify($USERID, $ClientKeyID, $Notification);
    $response = "Key person successfully updated.";
}

if (isset($_POST['DeleteKeyPerson'])) {
    $id = $_POST['ID'];
    $query = "DELETE FROM `_clients_key_people` WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $Notification = "$NAME deleted a key person for  '$ClientData->Name'";
    Notify($USERID, $ClientKeyID, $Notification);
    $response = "Key person successfully deleted.";
}

if (isset($_POST['DeleteBranch'])) {
    $id = $_POST['ID'];
    $query = "DELETE FROM `_clients` WHERE ClientID = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $Notification = "$NAME deleted a branch for '$ClientData->Name'";
    Notify($USERID, $ClientKeyID, $Notification);
    $response = "Branch successfully deleted.";
}

if (isset($_POST['DeleteInterview'])) {
    $ID = $_POST['ID'];
    $query = "DELETE FROM `interviews` WHERE InterviewID = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    $stmt->execute();
    $Notification = "$NAME deleted an interview for  '$ClientData->Name'";
    Notify($USERID, $ClientKeyID, $Notification);
    $response = "Interview successfully deleted.";
}

if (isset($_POST['DeleteCommunicationLog'])) {
    $ID = $_POST['ID'];
    $query = "DELETE FROM `_communication_logs` WHERE LogID = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    $stmt->execute();
    $Notification = "$NAME deleted a communication log for '$ClientData->Name'";
    Notify($USERID, $ClientKeyID, $Notification);
    $response = "Interview successfully deleted.";
}

if (isset($_GET['isBranch'])) {
    $isClientID = $conn->query("SELECT isClient FROM `_clients` WHERE ClientID = '$ClientID'")->fetchColumn();
} else {
    $isClientID = $ClientID;
}


if (isset($_POST['DeleteVacancy'])) {
    $ID = $_POST['ID'];
    $query = "DELETE FROM `vacancies` WHERE VacancyID = :ID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    $stmt->execute();
    $Notification = "$NAME deleted a vacancy for $ClientData->Name";
    Notify($USERID, $ClientKeyID, $Notification);
}




if (isset($_POST['Delete_Shift'])) {
    $shiftID = $_POST['ID'];
    $query = "DELETE FROM `_shifts` WHERE ShiftID = '$shiftID'";
    $conn->exec($query);
    $Modification = "Deleted a shift";
    LastModified($ClientID, $USERID, $Modification);
    $Notification = "$NAME successfully deleted a shift";

    Notify($USERID, $ClientKeyID, $Notification);
}



if (isset($_POST['DeleteTimesheet'])) {

    $TimesheetID = $_POST['ID'];
    $TimesheetNo = $conn->query("SELECT TimesheetNo FROM `_timesheet` WHERE TimesheetID = '$TimesheetID' ")->fetchColumn();

    $del = $conn->query("DELETE FROM `_timesheet` WHERE TimesheetID = '$TimesheetID' ");
    $del2 = $conn->query("DELETE FROM `__time_sheets` WHERE TimesheetID = '$TimesheetID' ");

    $Modification = "Delete Timesheet No $TimesheetNo";
    LastModified($ClientID, $USERID, $Modification);
    $Notification = "$NAME successfully deleted Timesheet No. $TimesheetNo";

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
                                <h5 class="mb-0"><?php echo $page; ?></h5>
                            </div>

                        </div>

                        <div class="card-body">
                            <ul class="nav nav-tabs analytics-tab">
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=Details&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'Details') ? 'active' : ''; ?>">Details</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=Documents&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'Documents') ? 'active' : ''; ?>">Documents</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=KeyPeople&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'KeyPeople') ? 'active' : ''; ?>">Key People</a></li>
                                <?php if (!isset($_GET['isBranch'])) : ?>
                                    <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=Branches&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'Branches') ? 'active' : ''; ?>">Branches</a></li>

                                <?php endif; ?>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=Interviews&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'Interviews') ? 'active' : ''; ?>">Interviews</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=Vacancy&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'Vacancy') ? 'active' : ''; ?>">Vacancy</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=Shifts&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'Shifts') ? 'active' : ''; ?>">Shifts</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=Timesheets&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'Timesheets') ? 'active' : ''; ?>">Timesheets</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=Invoices&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'Invoices') ? 'active' : ''; ?>">Invoices</a></li>
                                <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/view_client/?<?php echo (isset($_GET['isBranch'])) ? "isBranch=true&" : "" ?>isTab=CommunicationLog&ID=<?php echo $ClientID; ?>" class="nav-link <?php echo ($isTab == 'CommunicationLog') ? 'active' : ''; ?>">Communication Log</a></li>
                            </ul>
                            <div class="table-responsive dt-responsive" style="margin-top: 10px;">
                                <?php if (IsCheckPermission($USERID, "VIEW_CLIENT")) : ?>
                                    <?php include "i/details.php" ?>
                                    <?php include "i/keypeople.php" ?>
                                    <?php include "i/branches.php" ?>
                                    <?php include "i/interviews.php" ?>
                                    <?php include "i/vacancy.php" ?>
                                    <?php include "i/shifts.php" ?>
                                    <?php include "i/timesheet.php" ?>
                                    <?php include "i/invoice.php" ?>
                                    <?php include "i/logs.php" ?>

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

    function getSelectedCheckboxes() {
        return document.querySelectorAll('.checkbox-item:checked');
    }

    function ShowToast(message) {
        ShowToast(message);
    }

    function showModal(modalId) {
        $("#" + modalId).modal('show');
    }

    function updateElementText(elementId, text) {
        document.getElementById(elementId).innerText = text;
    }

    function makeAjaxRequest(url, data, onSuccess) {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: onSuccess
        });
    }

    function handleEditButtonClick(editButtonClass, modalId, dataFields) {
        $(editButtonClass).click(function() {
            let checkboxes = getSelectedCheckboxes();
            console.log('Selected Checkboxes:', checkboxes);

            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let checkbox = checkboxes[0];
                console.log('Checkbox Data:', checkbox);

                dataFields.forEach(function(field) {
                    let value = checkbox.getAttribute('data-' + field.dataAttr);
                    console.log('Setting field:', field.inputId, 'with value:', value);
                    $("#" + field.inputId).val(value);
                });
                showModal(modalId);
            }
        });
    }


    function handleDeleteButtonClick(deleteButtonId, postDataKey, url, reloadOnSuccess = true) {
        document.getElementById(deleteButtonId).addEventListener('click', function() {
            let checkboxes = getSelectedCheckboxes();
            let ids = [];
            let successCount = 0;

            checkboxes.forEach(function(checkbox) {
                ids.push(checkbox.value);
            });

            if (ids.length > 0) {
                updateElementText(deleteButtonId, "Deleting...");

                ids.forEach(function(id) {
                    let data = {
                        ID: id
                    };
                    data[postDataKey] = true;

                    makeAjaxRequest(url, data, function(response) {
                        successCount++;
                        if (successCount === ids.length && reloadOnSuccess) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    });
                });
            } else {
                ShowToast('Error 102: Something went wrong.');
            }
        });
    }

    <?php if ($isTab == "Documents") : ?>
        $(".EditClientsDocuments").click(function() {
            var row = $(this).closest('tr');
            var checkbox = row.find('.checkbox-item');
            const id = checkbox.val();
            const name = checkbox.attr("data-name");
            $("#EditModal #DocumentID").val(id);
            $("#EditModal #DocumentName").val(name);
            showModal("EditModal");
        })
        handleDeleteButtonClick('ConfirmDeleteClientDocuments', 'DeleteClientDocument', window.location.href);
    <?php endif; ?>

    <?php if ($isTab == "KeyPeople") : ?>
        $(".EditKeyPerson").click(function() {
            // Define the data mapping
            let data = [{
                    inputId: 'EditID',
                    dataAttr: 'value'
                },
                {
                    inputId: 'EditName',
                    dataAttr: 'name'
                },
                {
                    inputId: 'EditEmail',
                    dataAttr: 'email'
                },
                {
                    inputId: 'EditNumber',
                    dataAttr: 'number'
                },
                {
                    inputId: 'EditPosition',
                    dataAttr: 'position'
                }
            ];

            // Find the closest row and the checkbox within it
            var row = $(this).closest('tr');
            var checkbox = row.find('.checkbox-item');

            // Loop through the data mapping and set the corresponding input values
            data.forEach(function(field) {
                let value = checkbox.attr('data-' + field.dataAttr);
                $("#" + field.inputId).val(value);
            });
            showModal("EditModal");
        });




        handleDeleteButtonClick('ConfirmDeleteKeyPerson', 'DeleteKeyPerson', window.location.href);
    <?php endif; ?>

    <?php if ($isTab == "Branches") : ?>
        $("#Edit").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let id = checkboxes[0].value;
                window.location.href = "<?php echo $LINK; ?>/edit_client/?isBranch=true&ID=" + id;
            }
        });

        handleDeleteButtonClick('ConfirmDeleteBranch', 'DeleteBranch', window.location.href);

    <?php endif; ?>

    <?php if ($isTab == "Interviews") : ?>
        $("#Edit").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let id = checkboxes[0].value;
                const hasBranchID = checkboxes[0].getAttribute("hasBranchID");
                const hasClientID = checkboxes[0].getAttribute("hasClientID");
                window.location.href = "<?php echo $LINK; ?>/create_interview/?isNew=false&hasClientID=" + hasClientID + "&hasBranchID=" + hasBranchID + "&isID=" + id;
            }
        });

        handleDeleteButtonClick('ConfirmDeleteInterview', 'DeleteInterview', window.location.href, true);

    <?php endif; ?>

    <?php if ($isTab == "CommunicationLog") : ?>
        $("#Edit").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let id = checkboxes[0].value;
                const hasBranchID = checkboxes[0].getAttribute("hasBranchID");
                const hasClientID = checkboxes[0].getAttribute("hasClientID");
                window.location.href = "<?php echo $LINK; ?>/create_log/?isNew=false&IsFor=Client&hasClientID=" + hasClientID + "&hasBranchID=" + hasBranchID + "&LogID=" + id;
            }
        });

        handleDeleteButtonClick('ConfirmDeleteLog', 'DeleteCommunicationLog', window.location.href, false);
        $("#ConfirmDeleteLog").click(function() {
            setTimeout(() => {
                window.location.reload();

            }, 2000);
        });

        $(".ViewLog").click(function() {
            const details = $(this).attr("data-details");
            $("#CommunicationLogModal").modal('show');
            $("#Details").text(details);
        });

    <?php endif; ?>
    <?php if ($isTab == "Vacancy") : ?>
        $("#Edit").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let id = checkboxes[0].value;
                const hasBranchID = checkboxes[0].getAttribute("hasBranchID");
                const hasClientID = checkboxes[0].getAttribute("hasClientID");
                window.location.href = "<?php echo $LINK ?>/create_vacancy/?isNew=false&isTab=Details&hasClientID=" + hasClientID + "&hasBranchID=" + hasBranchID + "&VacancyID=" + id + "";
            }
        });

        handleDeleteButtonClick('ConfirmDeleteVacancy', 'DeleteVacancy', window.location.href, false);
        $("#ConfirmDeleteVacancy").click(function() {
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });

        $("#View").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let id = checkboxes[0].value;
                window.location.href = "<?php echo $LINK ?>/view_vacancy/?VacancyID=" + id + "";
            }
        });

    <?php endif; ?>

    <?php if ($isTab == "Shifts") : ?>
        $("#Edit").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                const VacancyID = checkboxes[0].getAttribute("VacancyID");
                window.location.href = "<?php echo $LINK ?>/view_vacancy/?VacancyID=" + VacancyID + "&isTab=Shifts";
            }
        });

        document.getElementById('ConfirmDeleteShift').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;

            checkboxes.forEach(function(checkbox) {
                ids.push({
                    id: checkbox.value,
                });
            });

            if (ids.length > 0) {
                $("#ConfirmDeleteShift").text("Deleting...");

                ids.forEach(function(item) {
                    $.ajax({
                        url: window.location.href,
                        type: 'POST',
                        data: {
                            ID: item.id,
                            Delete_Shift: true
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

    <?php if ($isTab == "Timesheets") : ?>
        $("#Edit").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                const TimesheetID = checkboxes[0].getAttribute("value");
                window.location.href = "<?php echo $LINK ?>/generate_timesheet/?ID=" + TimesheetID + "&isTab=Timesheet";
            }
        });

        $("#View").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                const TimesheetID = checkboxes[0].getAttribute("value");
                window.location.href = "<?php echo $LINK ?>/generate_timesheet/?ID=" + TimesheetID + "&isTab=Details";
            }
        });

        document.getElementById('ConfirmDeleteShift').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;

            checkboxes.forEach(function(checkbox) {
                ids.push({
                    id: checkbox.value,
                });
            });

            if (ids.length > 0) {
                $("#ConfirmDeleteShift").text("Deleting...");

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
    <?php if ($isTab == "Invoices") : ?>
        $("#View").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                const TimeSheetID = checkboxes[0].getAttribute("value");
                window.location.href = "<?php echo $LINK ?>/generate_timesheet/?ID=" + TimeSheetID + "&isTab=Invoice";
            }
        });
    <?php endif; ?>
</script>

</html>