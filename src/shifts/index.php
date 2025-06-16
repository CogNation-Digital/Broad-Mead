<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['DeleteShift'])) {
    // Retrieve the 'ID' parameter from the POST request
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    try {
        // Start a transaction
        $conn->beginTransaction();



        $stmt3 = $conn->prepare("DELETE FROM `_shifts` WHERE ShiftID = :ID");
        $stmt3->bindParam(':ID', $ID);
        $stmt3->execute();

        // Commit the transaction
        $conn->commit();

        $NOTIFICATION = "$NAME has successfully deleted a shift. Reason for deletion: $reason.";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
        echo "Record deleted successfully";
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        echo "Error deleting record: " . $e->getMessage();
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
                                <h5 class="mb-0">Shifts</h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#CreateModal">
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                </svg>
                                            </span>
                                            Create
                                        </a>
                                        <a class="dropdown-item" href="#" id="Edit">
                                            <span class="text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                </svg>
                                            </span>
                                            Edit</a>
                                        <?php if (IsCheckPermission($USERID, "DELETE_SHIFT")) : ?>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                <span class="text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                        <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                    </svg>
                                                </span>
                                                Delete</a>
                                        <?php endif; ?>
                                        <a class="dropdown-item" href="#" id="View">
                                            <span class="text-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                    <g fill="currentColor">
                                                        <path d="M6.5 12a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z" />
                                                        <path fill-rule="evenodd" d="M11.185 1H4.5A1.5 1.5 0 0 0 3 2.5v15A1.5 1.5 0 0 0 4.5 19h11a1.5 1.5 0 0 0 1.5-1.5V7.202a1.5 1.5 0 0 0-.395-1.014l-4.314-4.702A1.5 1.5 0 0 0 11.185 1M4 2.5a.5.5 0 0 1 .5-.5h6.685a.5.5 0 0 1 .369.162l4.314 4.702a.5.5 0 0 1 .132.338V17.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5z" clip-rule="evenodd" />
                                                        <path d="M11 7h5.5a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 1 0z" />
                                                    </g>
                                                </svg>
                                            </span>
                                            View
                                        </a>

                                        <a class="dropdown-item" href="<?php echo $LINK; ?>/shift_types">
                                            <span class="text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M14 14H7v2h7m5 3H5V8h14m0-5h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m-2 7H7v2h10z" />
                                                </svg>
                                            </span>
                                            Shift Type
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>
                                                <span id="selectAll" style="cursor: pointer;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                    </svg>
                                                </span>
                                            </th>
                                            <th>Client</th>
                                            <th>Branch</th>
                                            <th>Candidate</th>

                                            <th width="15%">Shift Type</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Hours</th>
                                            <th width="12%">Pay Rate</th>
                                            <th width="12%">Supplier Rate</th>
                                            <th width="12%">Margin</th>
                                            <th width="12%">Total Margin</th>
                                            <th width="12%">Created By</th>
                                            <th width="12%">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $n = 1;
                                        $__shifts__query = "SELECT * FROM `_shifts` WHERE (ClientKeyID = '$ClientKeyID') AND ShiftDate BETWEEN '$FromDate' AND '$ToDate'";
                                        $__shifts__stmt = $conn->prepare($__shifts__query);
                                        $__shifts__stmt->execute();
                                        while ($row = $__shifts__stmt->fetchObject()) { ?>
                                            <?php
                                            $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                                            $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                                            $CandidateData = $conn->query("SELECT Name, ProfileImage FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}'")->fetchObject();

                                            ?>
                                            <tr data-id="<?php echo $row->ShiftID; ?>">
                                                <td><?php echo $row->id; ?></td>
                                                <td><input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->ShiftID; ?>" VacancyID="<?php echo $row->VacancyID; ?>" id="flexCheckDefault<?php echo $row->id; ?>"></td>
                                                <td><?php echo (!$ClientName) ? NoData() : $ClientName; ?></td>
                                                <td><?php echo (!$BranchName) ? NoData() : $BranchName; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0"><img width="40" height="40" style="object-fit: cover;" src="<?php echo $CandidateData->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40"></div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0"> <?php echo $CandidateData->Name; ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="15%">
                                                    <?php echo $row->Type; ?>
                                                </td>
                                                <td>
                                                    <?php echo FormatDate($row->ShiftDate); ?>
                                                    <?php if ($row->ShiftDate == date("Y-m-d")) : ?>
                                                        <div class="badge bg-success">Today</div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="shift-starttime"><?php echo $row->StartTime; ?></span></td>
                                                <td><span class="shift-endtime"><?php echo $row->EndTime; ?></span></td>
                                                <td><span class="shift-hours" style="width: 80px;"><?php echo $row->Hours; ?></span></td>
                                                <td width="12%"><span class="shift-payrate"><?php echo $Currency; ?><?php echo number_format((float)$row->PayRate, 2); ?></span></td>
                                                <td width="12%"><span class="shift-supplyrate"><?php echo $Currency; ?><?php echo number_format((float)$row->SupplyRate, 2); ?></span></td>
                                                <td width="12%"><span style="width: 100px;" class="shift-margin"><?php echo $Currency; ?><?php echo number_format((float)$row->SupplyRate - (float)$row->PayRate, 2); ?></span></td>
                                                <td width="12%"><span style="width: 100px;" class="shift-totalmargin"><?php echo $Currency; ?><?php echo number_format((float)$row->Hours * ((float)$row->SupplyRate - (float)$row->PayRate), 2); ?></span></td>
                                                <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                <td><?php echo FormatDate($row->Date); ?></td>


                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php
                                if ($__shifts__stmt->rowCount() == 0) {
                                    echo '<div class="alert alert-danger" role="alert">No data found.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="CreateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreateModalLabel">Create Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (IsCheckPermission($USERID, "CREATE_SHIFT")) : ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="email">Vacancy</label>
                                <select name="VacancyID" class="form-control">
                                    <?php
                                    $query = "SELECT * FROM `vacancies` WHERE ClientKeyID = '$ClientKeyID' ";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetchObject()) {
                                        echo '<option value="' . $row->VacancyID . '">' . $row->Title . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="CreateShift" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                <?php else : ?>
                    <div style="padding: 10px;">
                        <?php DeniedAccess(); ?>
                    </div>
                <?php endif; ?>
            </div>
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
    $("#Edit").click(function() {
        let checkboxes = getSelectedCheckboxes();
        if (checkboxes.length == 0) {
            ShowToast('Error 102: Something went wrong.');
            return;
        } else {
            let VacancyID = checkboxes[0].getAttribute('VacancyID');
            window.location.href = "<?php echo $LINK ?>/view_vacancy/?VacancyID=" + VacancyID + "&isTab=Shifts";
        }
    });



    $("#View").click(function() {
        let checkboxes = getSelectedCheckboxes();
        if (checkboxes.length == 0) {
            ShowToast('Error 102: Something went wrong.');
            return;
        } else {
            let VacancyID = checkboxes[0].getAttribute('VacancyID');
            window.location.href = "<?php echo $LINK ?>/view_vacancy/?VacancyID=" + VacancyID + "";
        }
    });

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
                            reason: reason,
                            DeleteShift: true
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