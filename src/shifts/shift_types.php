<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
if (isset($_POST['CreateShiftType'])) {
    $ShiftType = $_POST['ShiftType'];

    try {
        $query = "INSERT INTO `shifttype`(`ClientKeyID`, `ShiftType`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :ShiftType, :CreatedBy, :Date)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ClientKeyID', $ClientKeyID);
        $stmt->bindParam(':ShiftType', $ShiftType);
        $stmt->bindParam(':CreatedBy', $USERID);
        $stmt->bindParam(':Date', $date);
        $stmt->execute();
        $response = "Shift Type Created Successfully";

        $NOTIFICATION = "$NAME has successfully created shift type $ShiftType";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    } catch (PDOException $e) {
        $response = "Error: " . $e->getMessage();
    }
}
if (isset($_POST['UpdateShiftType'])) {
    $ShiftType = $_POST['ShiftType'];
    $ShiftID = $_POST['ShiftID'];

    try {
        $query = "UPDATE shifttype SET ShiftType = :ShiftType WHERE id = :ShiftID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ShiftID', $ShiftID);
        $stmt->bindParam(':ShiftType', $ShiftType);
        $stmt->execute();
        $response = "Shift Type updated Successfully";

        $NOTIFICATION = "$NAME has successfully updated  shift type $ShiftType ";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    } catch (PDOException $e) {
        $response = "Error: " . $e->getMessage();
    }
}

if (isset($_POST['DeleteShiftType'])) {
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $del = $conn->query("DELETE FROM shifttype WHERE id = $ID");
    if ($del) {
        $NOTIFICATION = "$NAME has successfully deleted shift type $name";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
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
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CreateModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M11 13H5v-2h6V5h2v6h6v2h-6v6h-2z" />
                                    </svg>
                                    Add Shift Type

                                </button>
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
                                            <th width="15%">Shift Type</th>
                                            <th width="15%">EDIT</th>
                                            <th width="15%">Remove</th>
                                            <th>Created By</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $n = 1;
                                        $__shifttype_query = "SELECT * FROM `shifttype` WHERE (ClientKeyID = '$ClientKeyID')";
                                        $_shifttype_stmt_ = $conn->prepare($__shifttype_query);
                                        $_shifttype_stmt_->execute();
                                        while ($row = $_shifttype_stmt_->fetchObject()) { ?>
                                            <tr>
                                                <td style="width: 10px;"><?php echo $n++; ?></td>
                                                <td style="width: 10px;"><input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->id; ?>" data-name="<?php echo $row->ShiftType; ?>" id="flexCheckDefault<?php echo $row->id; ?>"></td>
                                                <td><?php echo $row->ShiftType; ?></td>
                                                <td>
                                                    <span style="cursor: pointer;" class="text-info EditShiftType" data-shift-id="<?php echo $row->id; ?>" data-shift-type="<?php echo $row->ShiftType; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="M5 19h1.425L16.2 9.225L14.775 7.8L5 17.575zm-2 2v-4.25L16.2 3.575q.3-.275.663-.425t.762-.15t.775.15t.65.45L20.425 5q.3.275.438.65T21 6.4q0 .4-.137.763t-.438.662L7.25 21zM19 6.4L17.6 5zm-3.525 2.125l-.7-.725L16.2 9.225z" />
                                                        </svg>
                                                        Edit
                                                    </span>
                                                </td>
                                                <td>
                                                    <span style="cursor: pointer;" class="text-danger DeleteShifType" data-shift-id="<?php echo $row->id; ?>" data-shift-type="<?php echo $row->ShiftType; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM9 17h2V8H9zm4 0h2V8h-2zM7 6v13z" />
                                                        </svg>
                                                        <span class="delete_text">Delete</span>
                                                    </span>
                                                </td>
                                                <td style="width: 20px;"><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                <td style="width: 10px;"><?php echo FormatDate($row->Date); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php
                                if ($_shifttype_stmt_->rowCount() == 0) {
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
                    <h5 class="modal-title" id="CreateModalLabel">Create Shift Type</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="ShiftType">Shift Type</label>
                            <input type="text" class="form-control" name="ShiftType">
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="CreateShiftType" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="EditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditModalLabel">Update Shift Type</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="ShiftType">Shift Type</label>
                            <input type="text" class="form-control" name="ShiftType">
                            <input type="hidden" class="form-control" name="ShiftID">
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="UpdateShiftType" class="btn btn-primary">Submit</button>
                    </div>
                </form>
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
    $(".EditShiftType").click(function() {
        var ID = $(this).attr("data-shift-id");
        var name = $(this).attr("data-shift-type");

        $("#EditModal").modal("show");
        $("#EditModal input[name=ShiftType]").val(name);
        $("#EditModal input[name=ShiftID]").val(ID);

    });
    $(".DeleteShifType").click(function() {
        if (confirm("Are you sure you want to delete")) {
            var ID = $(this).attr("data-shift-id");
            var name = $(this).attr("data-shift-type");

            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    ID: ID,
                    name: name,
                    DeleteShiftType: true
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
        }
    })
</script>

</html>