<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['delete'])) {
    // Retrieve the 'ID' parameter from the POST request
    $ID = $_POST['ID'];
    $stmt = $conn->prepare("DELETE FROM `_kpis` WHERE KpiID = :ID");
    $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
    if ($stmt->execute()) {
        // Delete from `_kpis_targets` table
        $q = $conn->query("SELECT KpiID FROM `_kpis_targets` WHERE _kpi_id = '$ID'");
        while ($d = $q->fetchObject()) {
            $delete_kpis_achieved = $conn->prepare("DELETE FROM `_kpis_achieved` WHERE KpiID = :ID");
            $delete_kpis_achieved->bindParam(':ID', $d->KpiID, PDO::PARAM_STR);
            $delete_kpis_achieved->execute();
        }
        $delete_kpis_targets = $conn->prepare("DELETE FROM `_kpis_targets` WHERE _kpi_id = :ID");
        $delete_kpis_targets->bindParam(':ID', $ID, PDO::PARAM_STR);
        $delete_kpis_targets->execute();
        $delete_kpis_achieved->bindParam(':ID', $ID, PDO::PARAM_STR);
        $delete_kpis_achieved->execute();

        // Notify the user
        $NOTIFICATION = "$NAME successfully deleted a KPI";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);

        echo "Record deleted successfully.";
    }
}



if (isset($_POST['Search'])) {
    $user = $_POST['user'];
    $StartDate = $_POST['StartDate'];
    $EndDate = $_POST['EndDate'];
    $Description = $_POST['Description'];
    if (!empty($SearchID)) {
        $query = $conn->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
                  VALUES (:SearchID, :column, :value)");

        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                $query->bindParam(':SearchID', $SearchID);
                $query->bindParam(':column', $key);
                $query->bindParam(':value', $value);
                $query->execute();
            }
        }

        header("location: $LINK/weekly_kpis/?q=$SearchID");
        exit();
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
                                <h5 class="mb-0">Weekly KPIs</h5>
                                <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?php echo $LINK; ?>/create_weekly_kpis/?ID=<?php echo $RandomID; ?>">
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                </svg>
                                            </span>
                                            Create
                                        </a>
                                        <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14" />
                                                </svg>
                                            </span>
                                            Advanced Search
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">

                                <?php if (IsCheckPermission($USERID, "VIEW_KPIs")) : ?>
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
                                                <th>Consultant </th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Description</th>
                                                <th>Created By</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM `_kpis` WHERE ClientKeyID = '$ClientKeyID'  ";

                                            if (isset($_GET['q'])) {
                                                $SearchID = $_GET['q'];
                                                $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                                                while ($r = $qu->fetchObject()) {
                                                    $column = $r->column;
                                                    $value = $r->value;
                                                    if ($column == "UserID") {
                                                        $query .= " AND " . $column . " = '$value'";
                                                    } else {
                                                        $query .= " AND " . $column . " LIKE '%$value%'";
                                                    }
                                                }
                                            }

                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $n = 1;
                                            while ($row = $stmt->fetchObject()) { ?>
                                                <?php
                                                $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                                $ConsultantData = $conn->query("SELECT Name, ProfileImage FROM `users` WHERE UserID = '{$row->UserID}' ")->fetchObject();
                                                ?>
                                                <tr>
                                                    <td><?php echo $n++; ?></td>
                                                    <td style="display: none;"> <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->KpiID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                    <td>
                                                        <div class="d-flex mb-1">
                                                            <div class="flex-shrink-0"><img style="border-radius: 50%; height: 50px; width: 50px; object-fit: cover;" src="<?php echo $ConsultantData->ProfileImage; ?>" alt="user-image" class="user-avtar wid-35"></div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <?php if ($USERID == $row->UserID) : ?>
                                                                    <h6 class="mb-1" style="margin-top: 0px;"><?php echo $ConsultantData->Name; ?></h6>
                                                                    <span class="badge text-bg-primary">Your KPI</span>
                                                                <?php else : ?>
                                                                    <h6 class="mb-1" style="margin-top: 7px;"><?php echo $ConsultantData->Name; ?></h6>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo FormatDate($row->StartDate); ?></td>
                                                    <td><?php echo FormatDate($row->EndDate); ?></td>
                                                    <td><?php echo TruncateText($row->Description, 30); ?></td>
                                                    <td><?php echo $CreatedBy; ?></td>
                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/edit_weekly_kpis/?ID=<?php echo $row->KpiID; ?>">
                                                                    <span class="text-info">

                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                            <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                        </svg>
                                                                    </span>
                                                                    Edit</a>
                                                                <?php if (IsCheckPermission($USERID, "DELETE_KPI")) : ?>
                                                                    <a class="dropdown-item delete-entry" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                                        <span class="text-danger">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                            </svg>
                                                                        </span>
                                                                        Delete</a>
                                                                <?php endif; ?>

                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/view_weekly_kpis/?ID=<?php echo $row->KpiID; ?>">
                                                                    <span class="text-warning">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                            <g fill="currentColor">
                                                                                <path d="M6.5 12a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z" />
                                                                                <path fill-rule="evenodd" d="M11.185 1H4.5A1.5 1.5 0 0 0 3 2.5v15A1.5 1.5 0 0 0 4.5 19h11a1.5 1.5 0 0 0 1.5-1.5V7.202a1.5 1.5 0 0 0-.395-1.014l-4.314-4.702A1.5 1.5 0 0 0 11.185 1M4 2.5a.5.5 0 0 1 .5-.5h6.685a.5.5 0 0 1 .369.162l4.314 4.702a.5.5 0 0 1 .132.338V17.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5z" clip-rule="evenodd" />
                                                                                <path d="M11 7h5.5a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 1 0z" />
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                    View</a>

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>


                                        </tbody>
                                    </table>
                                    <?php if ($stmt->rowCount() == 0) : ?>
                                        <div class="alert alert-danger">
                                            No data found.
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($_GET['q'])) : ?>
                                        <a href="<?php echo $LINK; ?>/weekly_kpis">
                                            <button class="btn btn-primary">
                                                Reset Search
                                            </button>
                                        </a>

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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">Advance Search</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Consultant</label>
                                    <select name="UserID" class="select-input" style="padding: 20px;">
                                        <option value=""></option>
                                        <?php
                                        $q = $conn->query("SELECT * FROM `users` WHERE ClientKeyID = '$ClientKeyID' ");
                                        while ($r = $q->fetchObject()) {
                                            echo "<option value='$r->UserID'>$r->Name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3"><label class="form-label">Starts</label>
                                    <input type="date" class="form-control" name="StartDate">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3"><label class="form-label">Ends</label>
                                    <input type="date" class="form-control" name="EndDate">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3"><label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="Description">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="Search" class="btn btn-primary me-0">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</body>
<?php include "../../includes/js.php"; ?>
<script>
    // Confirm Delete
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
                        delete: true
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