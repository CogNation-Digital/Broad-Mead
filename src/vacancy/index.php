<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['DeleteVacancy'])) {
    // Retrieve the 'ID' parameter from the POST request
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    try {
        // Start a transaction
        $conn->beginTransaction();

        // Prepare and execute DELETE statements
        $stmt1 = $conn->prepare("DELETE FROM `vacancies` WHERE VacancyID = :ID");
        $stmt1->bindParam(':ID', $ID);
        $stmt1->execute();

        $stmt2 = $conn->prepare("DELETE FROM `__vacancy_candidates` WHERE VacancyID = :ID");
        $stmt2->bindParam(':ID', $ID);
        $stmt2->execute();

        $stmt3 = $conn->prepare("DELETE FROM `_shifts` WHERE VacancyID = :ID");
        $stmt3->bindParam(':ID', $ID);
        $stmt3->execute();

        $stmt4 = $conn->prepare("DELETE FROM `__shifts__` WHERE VacancyID = :ID");
        $stmt4->bindParam(':ID', $ID);
        $stmt4->execute();

        $stmt5 = $conn->prepare("DELETE FROM `_timesheet` WHERE VacancyID = :ID");
        $stmt5->bindParam(':ID', $ID);
        $stmt5->execute();

        // Commit the transaction
        $conn->commit();

        $NOTIFICATION = "$NAME has successfully deleted a vacancy titled $name. Reason for deletion: $reason.";
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
                                <h5 class="mb-0">Vacancies</h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?php echo $LINK; ?>/create_vacancy/?hasClientID=false&hasBranchID=false&VacancyID=<?php echo $KeyID; ?>&isNew=true&isTab=Details">
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                </svg>
                                            </span>
                                            Create
                                        </a>
                                        <?php if (IsCheckPermission($USERID, "EDIT_VACANCY")) : ?>
                                            <a class="dropdown-item" href="#" id="Edit">
                                                <span class="text-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                    </svg>
                                                </span>
                                                Edit
                                            </a>
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
                                        <?php if (IsCheckPermission($USERID, "DELETE_VACANCY")) : ?>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                <span class="text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                        <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                    </svg>
                                                </span>
                                                Delete</a>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <span id="selectAll" style="cursor: pointer;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                    </svg>
                                                </span>
                                            </th>
                                            <th>Client</th>
                                            <th>Branch</th>
                                            <th>Type</th>
                                            <th>Title</th>
                                            <th>Number of roles</th>
                                            <th>Candidates</th>
                                            <th>Period</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $n = 1;
                                        $sql = "SELECT * FROM `vacancies` WHERE ClientKeyID = '$ClientKeyID'";

                                        $query = $conn->prepare($sql);
                                        $query->execute();
                                        while ($row = $query->fetchObject()) { ?>
                                            <?php
                                            $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                                            $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                                            $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}'")->fetchColumn();
                                            $CandidatesNum = $conn->query("SELECT COUNT(id) FROM `__vacancy_candidates` WHERE VacancyID = '{$row->VacancyID}'")->fetchColumn();
                                            ?>
                                            <tr>
                                                <td><?php echo $n++; ?></td>
                                                <td> <input class="form-check-input checkbox-item" type="checkbox" data-name="<?php echo $row->Title; ?>" value="<?php echo $row->VacancyID; ?>" hasBranchID="<?php echo (empty($row->hasBranchID) ? "false" : $row->hasBranchID); ?>" hasClientID="<?php echo $row->hasClientID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                <td><?php echo (!$ClientName) ? NoData() : $ClientName; ?></td>
                                                <td><?php echo (!$BranchName) ? NoData() : $BranchName; ?></td>

                                                <td>
                                                    <?php echo $row->Type; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row->Title; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row->Roles; ?>
                                                </td>
                                                <td>
                                                    <?php echo $CandidatesNum; ?>
                                                </td>
                                                <td>
                                                    From <?php echo FormatDate($row->StartDate); ?> to <?php echo FormatDate($row->EndDate); ?>
                                                </td>

                                                <td>

                                                    <?php
                                                    $currentDate = new DateTime();
                                                    $startDate = new DateTime($row->StartDate);
                                                    $endDate = new DateTime($row->EndDate);

                                                    if ($currentDate >= $startDate && $currentDate <= $endDate) {
                                                        $status = "Active";
                                                    } elseif ($currentDate > $endDate) {
                                                        $status = "Not Active";
                                                    } else {
                                                        $status = "Not Active";
                                                    }
                                                    ?>

                                                    <?php if ($status == "Active") : ?>
                                                        <div class="badge bg-success"><?php echo $status; ?></div>
                                                    <?php else : ?>
                                                        <div class="badge bg-danger">
                                                            <?php echo $status; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php echo $CreatedBy; ?>
                                                </td>
                                                <td>
                                                    <?php echo FormatDate($row->Date); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php if ($query->rowCount() == 0) : ?>
                                    <div>
                                        <div class="alert alert-danger">No data found.</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <p>Are you sure you want to delete? This action cannot be undone. This deletion will also delete shifts and timesheets associated with the vacancy.</p>
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
            let id = checkboxes[0].value;
            const hasBranchID = checkboxes[0].getAttribute("hasBranchID");
            const hasClientID = checkboxes[0].getAttribute("hasClientID");
            window.location.href = "<?php echo $LINK ?>/create_vacancy/?isNew=false&isTab=Details&hasClientID=" + hasClientID + "&hasBranchID=" + hasBranchID + "&VacancyID=" + id + "";
        }
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
                            DeleteVacancy: true
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