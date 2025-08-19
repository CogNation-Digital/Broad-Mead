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

            <ul class="nav nav-tabs mb-3" id="kpiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="weekly-kpi-tab" data-bs-toggle="tab" data-bs-target="#weekly-kpi" type="button" role="tab" aria-controls="weekly-kpi" aria-selected="true">Weekly KPI Search</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="all-kpi-tab" data-bs-toggle="tab" data-bs-target="#all-kpi" type="button" role="tab" aria-controls="all-kpi" aria-selected="false">All KPIs</button>
                </li>
            </ul>
            <div class="tab-content" id="kpiTabContent">
                <div class="tab-pane fade show active" id="weekly-kpi" role="tabpanel" aria-labelledby="weekly-kpi-tab">
                    <form method="GET" class="row g-3 align-items-end mb-4">
                        <div class="col-md-4">
                            <label for="consultant" class="form-label">Consultant</label>
                            <select class="form-select" id="consultant" name="consultant">
                                <option value="">Select Consultant</option>
                                <?php $users = $conn->query("SELECT UserID, Name FROM users WHERE ClientKeyID = '$ClientKeyID'");
                                while ($u = $users->fetchObject()) {
                                    $selected = (isset($_GET['consultant']) && $_GET['consultant'] == $u->UserID) ? 'selected' : '';
                                    echo "<option value=\"{$u->UserID}\" $selected>{$u->Name}</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="week" class="form-label">Week Starting</label>
                            <?php
                            $week = isset($_GET['week']) ? $_GET['week'] : date('Y-m-d', strtotime('monday this week'));
                            $prevWeek = date('Y-m-d', strtotime($week . ' -7 days'));
                            $nextWeek = date('Y-m-d', strtotime($week . ' +7 days'));
                            ?>
                            <div class="input-group">
                                <a href="?consultant=<?php echo isset($_GET['consultant']) ? $_GET['consultant'] : ''; ?>&week=<?php echo $prevWeek; ?>" class="btn btn-outline-secondary">&lt;</a>
                                <input type="date" class="form-control" id="week" name="week" value="<?php echo $week; ?>">
                                <a href="?consultant=<?php echo isset($_GET['consultant']) ? $_GET['consultant'] : ''; ?>&week=<?php echo $nextWeek; ?>" class="btn btn-outline-secondary">&gt;</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                    <?php
                    if (isset($_GET['consultant']) && $_GET['consultant'] && isset($_GET['week']) && $_GET['week']) {
                        $consultantID = $_GET['consultant'];
                        $weekStart = $_GET['week'];
                        $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
                        $kpiQuery = $conn->prepare("SELECT * FROM _kpis WHERE ClientKeyID = ? AND UserID = ? AND StartDate >= ? AND EndDate <= ?");
                        $kpiQuery->execute([$ClientKeyID, $consultantID, $weekStart, $weekEnd]);
                        if ($kpiQuery->rowCount() > 0) {
                            echo '<table class="table table-bordered"><thead><tr><th>#</th><th>Description</th><th>Start Date</th><th>End Date</th><th>Action</th></tr></thead><tbody>';
                            $i = 1;
                            while ($kpi = $kpiQuery->fetchObject()) {
                                echo "<tr><td>{$i}</td><td>{$kpi->Description}</td><td>".FormatDate($kpi->StartDate)."</td><td>".FormatDate($kpi->EndDate)."</td><td><a href='$LINK/edit_weekly_kpis/?ID={$kpi->KpiID}' class='btn btn-sm btn-info'>Edit</a> <a href='$LINK/src/kpis/view.php?ID={$kpi->KpiID}' class='btn btn-sm btn-warning'>View</a></td></tr>";
                                $i++;
                            }
                            echo '</tbody></table>';
                        } else {
                            echo '<div class="alert alert-info">No KPIs found for this consultant and week. <a href="' . $LINK . '/create_weekly_kpis/?UserID=' . $consultantID . '&StartDate=' . $weekStart . '&EndDate=' . $weekEnd . '" class="btn btn-success btn-sm ms-2">Create KPI</a></div>';
                        }
                    }
                    ?>
                </div>
                <div class="tab-pane fade" id="all-kpi" role="tabpanel" aria-labelledby="all-kpi-tab">
                    <!-- Existing All KPIs Table -->
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

                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/src/kpis/view.php?ID=<?php echo $row->KpiID; ?>">
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