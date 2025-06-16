<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['delete'])) {
    // Retrieve the 'ID' parameter from the POST request
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM `interviews` WHERE InterviewID = :ID");

    // Bind the 'ID' parameter to the prepared statement
    $stmt->bindParam(':ID', $ID);

    // Execute the statement to delete the record
    if ($stmt->execute()) {
        $NOTIFICATION = "$NAME has successfully deleted an interview for a candidate, Reason for deletion: $reason.";

        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    } else {
        echo "Error deleting record";
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
                                <h5 class="mb-0">Interviews</h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?php echo $LINK; ?>/create_interview/?hasClientID=false&hasBranchID=false&isID=<?php echo $RandomID; ?>&isNew=true">
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                </svg>
                                            </span>
                                            Create
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "VIEW_INTERVIEWS")) : ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th style="display: none;">
                                                        <span id="selectAll" style="cursor: pointer;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                            </svg>
                                                        </span>
                                                    </th>
                                                    <th>Client</th>
                                                    <th>Branch</th>

                                                    <th>Candidate</th>
                                                    <th>Key person</th>
                                                    <th>Interview Date</th>
                                                    <th>File</th>
                                                    <th>Status</th>
                                                    <th>Created By</th>
                                                    <th>Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $n = 1;
                                                $sql = "SELECT * FROM `interviews` WHERE ClientKeyID = '$ClientKeyID'";

                                                $query = $conn->prepare($sql);
                                                $query->execute();
                                                while ($row = $query->fetchObject()) { ?>
                                                    <?php
                                                    $CandidateData = $conn->query("SELECT Name, ProfileImage FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}'")->fetchObject();
                                                    $KeyPerson = $conn->query("SELECT Name FROM `_clients_key_people` WHERE id = '{$row->KeyPerson}'")->fetchColumn();
                                                    $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                                                    $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                                                    $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}'")->fetchColumn();
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $n++; ?></td>
                                                        <td style="display: none;"> <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->InterviewID; ?>" hasBranchID="<?php echo $row->hasBranchID; ?>" hasClientID="<?php echo $row->hasClientID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
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
                                                        <td>
                                                            <?php echo (!$KeyPerson) ? NoData() : $KeyPerson; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo FormatDate($row->DateTime); ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($row->FilePath == "null") : ?>
                                                                <div class="text text-danger">File not found</div>
                                                            <?php else : ?>
                                                                <div class="flex-shrink-0">
                                                                    <a href="<?php echo $row->FilePath; ?>" target="_blank" class="btn btn-sm btn-light-secondary">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                            <path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" />
                                                                        </svg> Download
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($row->Status == "Active" || $row->Status == "Accepted" || $row->Status == "Interviewed") : ?>
                                                                <div class="badge bg-success"><?php echo $row->Status; ?></div>
                                                            <?php else : ?>
                                                                <div class="badge bg-danger">
                                                                    <?php echo (empty($row->Status) ? "Pending" : $row->Status); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $CreatedBy; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo FormatDate($row->Date); ?>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-end">

                                                                    <a class="dropdown-item select-entry-row" href="#" id="Edit">
                                                                        <span class="text-info">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                                <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                            </svg>
                                                                        </span>
                                                                        Edit</a>
                                                                    <?php if (IsCheckPermission($USERID, "DELETE_INTERVIEW")) : ?>
                                                                        <a class="dropdown-item select-entry-row" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                                            <span class="text-danger">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                    <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                                </svg>
                                                                            </span>
                                                                            Delete</a>
                                                                    <?php endif; ?>

                                                                </div>
                                                            </div>
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
                                <?php else : ?>
                                    <?php
                                    DeniedAccess();
                                    ?>
                                <?php endif; ?>

                                <?php if (isset($_GET['q'])) : ?>
                                    <a href="<?php echo $LINK; ?>/clients">
                                        <button class="btn btn-primary">
                                            Reset Search
                                        </button>
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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">Advance Search</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Name</label>
                                    <input type="text" name="Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Client Type</label>
                                    <select name="ClientType" class="select-input">
                                        <option value=""></option>
                                        <?php
                                        foreach ($clientype as $type) { ?>
                                            <option><?php echo $type; ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Client ID</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="_client_id">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Email address</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Phone Number</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="Email">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Address</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="Address">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Postcode</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="PostCode">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">City</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="City">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="Search" class="btn btn-primary me-0">Search</button>
                        </div>

                    </form>
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


    $("#Edit").click(function() {
        let checkboxes = document.querySelectorAll('.checkbox-item:checked');
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


    // Confirm Delete
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
                            // Optionally, handle the error case if needed
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