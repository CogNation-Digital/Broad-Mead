<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$view = (isset($_GET['view'])) ? $_GET['view'] : 'calendar';
if (isset($_POST['delete'])) {
    // Retrieve the 'ID' parameter from the POST request
    $ID = $_POST['ID'];

    try {
        // Retrieve the event name before deletion
        $stmt = $conn->prepare("SELECT `Name`, `EventDate` FROM `calendar` WHERE id = :ID");
        $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            $eventName = $event['Name'];
            $eventDate = FormatDate($event['EventDate']);

            // Prepare the DELETE statement
            $stmt = $conn->prepare("DELETE FROM `calendar` WHERE id = :ID");
            $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
            if ($stmt->execute()) {
                // Send notification
                $NOTIFICATION = "$NAME successfully deleted the event '$eventName' that was scheduled $eventDate";
                Notify($USERID, $ClientKeyID, $NOTIFICATION);

                echo "Record deleted successfully.";
            } else {
                echo "Error deleting record.";
            }
        } else {
            echo "Event not found.";
        }
    } catch (Exception $e) {
        echo "Something went wrong: " . $e->getMessage();
    }
}




if (isset($_POST['submit'])) {
    $Event = $_POST['Event'];
    $EventDate = $_POST['EventDate'];
    $Color = $_POST['Color'];

    try {
        // Prepare the INSERT statement
        $query = "INSERT INTO `calendar`(`ClientKeyID`, `Name`, `Color`, `EventDate`, `CreatedBy`, `Date`) 
                  VALUES (:ClientKeyID, :Name, :Color, :EventDate, :CreatedBy, :Date)";
        $stmt = $conn->prepare($query);

        // Bind the parameters to the prepared statement
        $stmt->bindParam(':ClientKeyID', $ClientKeyID, PDO::PARAM_STR);
        $stmt->bindParam(':Name', $Event, PDO::PARAM_STR);
        $stmt->bindParam(':Color', $Color, PDO::PARAM_STR);
        $stmt->bindParam(':EventDate', $EventDate, PDO::PARAM_STR);
        $stmt->bindParam(':CreatedBy', $USERID, PDO::PARAM_STR);
        $stmt->bindParam(':Date', $date, PDO::PARAM_STR);

        // Execute the statement to insert the record
        if ($stmt->execute()) {
            $Modification = "Created a new calendar event.";
            $EventDate = FormatDate($EventDate);
            $Notification = "$NAME created a new event '$Event' scheduled for $EventDate.";
            LastModified($ClientKeyID, $USERID, $Modification);
            Notify($USERID, $ClientKeyID, $Notification);

            $response = "Event added successfully.";
        } else {
            $response = "Error adding event.";
        }
    } catch (Exception $e) {
        $response = "Something went wrong: " . $e->getMessage();
    }
}

if (isset($_POST['update'])) {
    $Event = $_POST['Event'];
    $EventDate = $_POST['EventDate'];
    $Color = $_POST['Color'];
    $EventID = $_POST['EventID'];

    try {
        // Prepare the UPDATE statement
        $query = "UPDATE `calendar` 
                  SET `Name` = :Name, `Color` = :Color, `EventDate` = :EventDate 
                  WHERE `id` = :EventID";
        $stmt = $conn->prepare($query);

        // Bind the parameters to the prepared statement
        $stmt->bindParam(':Name', $Event, PDO::PARAM_STR);
        $stmt->bindParam(':Color', $Color, PDO::PARAM_STR);
        $stmt->bindParam(':EventDate', $EventDate, PDO::PARAM_STR);
        $stmt->bindParam(':EventID', $EventID, PDO::PARAM_INT);

        // Execute the statement to update the record
        if ($stmt->execute()) {
            $Modification = "Updated calendar event.";
            $EventDateFormatted = FormatDate($EventDate);
            $Notification = "$NAME updated the event '$Event' scheduled for $EventDateFormatted.";
            LastModified($ClientKeyID, $USERID, $Modification);
            Notify($USERID, $ClientKeyID, $Notification);

            $response = "Event updated successfully.";
        } else {
            $response = "Error updating event.";
        }
    } catch (Exception $e) {
        $response = "Something went wrong: " . $e->getMessage();
    }
}


$query = "SELECT `Name`, `EventDate`, `Color` FROM `calendar` WHERE ClientKeyID = '$ClientKeyID' ";
$stmt = $conn->prepare($query);
$stmt->execute();

// Fetch all events
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
$formatted_events = [];
foreach ($events as $event) {
    $formatted_events[] = [
        'title' => $event['Name'],
        'start' => $event['EventDate'],
        'backgroundColor' => $event['Color'],
        'borderColor' => $event['Color'],
    ];
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
                                <h5 class="mb-0">Calendar</h5>
                                <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <?php if ($view == "calendar") : ?>
                                            <a class="dropdown-item" href="<?php echo $LINK; ?>/calendar/?view=list" id="View">
                                                <span class="text-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M7 9V7h14v2zm0 4v-2h14v2zm0 4v-2h14v2zM4 9q-.425 0-.712-.288T3 8t.288-.712T4 7t.713.288T5 8t-.288.713T4 9m0 4q-.425 0-.712-.288T3 12t.288-.712T4 11t.713.288T5 12t-.288.713T4 13m0 4q-.425 0-.712-.288T3 16t.288-.712T4 15t.713.288T5 16t-.288.713T4 17" />
                                                    </svg>
                                                </span>
                                                List View
                                            </a>
                                        <?php else : ?>
                                            <a class="dropdown-item" href="<?php echo $LINK; ?>/calendar" id="View">
                                                <span class="text-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3m1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z" />
                                                    </svg>
                                                </span>
                                                Calendar View
                                            </a>
                                        <?php endif; ?>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#CreateEvent">
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
                                <?php if (IsCheckPermission($USERID, "VIEW_EVENTS")) : ?>
                                    <?php if ($view == "list") : ?>
                                        <ul class="list-group list-group-flush">
                                            <?php
                                            $query = $conn->query("SELECT * FROM `calendar` WHERE ClientKeyID = '$ClientKeyID' ORDER BY id DESC");
                                            while ($row = $query->fetchObject()) { ?>
                                                <?php
                                                $CreatorData = $conn->query("SELECT * FROM `users` WHERE UserID = '{$row->CreatedBy}'")->fetchObject();
                                                ?>
                                                <li class="list-group-item">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <input class="form-check-input checkbox-item" style="display: none;" type="checkbox" value="<?php echo $row->id; ?>" data-name="<?php echo $row->Name; ?>" data-color="<?php echo $row->Color; ?>" data-date="<?php echo $row->EventDate; ?>" id="flexCheckDefault<?php echo $row->id ?>" style="margin-top: 10px;">

                                                            <img src="<?php echo $CreatorData->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user-image" class="user-avtar wid-35" style="border-radius: 50%;">
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="row g-1">
                                                                <div class="col-6">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <div class="flex-shrink-0"><span class="p-1 d-block rounded-circle" style="background-color: <?php echo $row->Color; ?>;"><span class="visually-hidden">New alerts</span></span></div>
                                                                        <div class="flex-grow-1 ms-2">
                                                                            <h6 class="mb-0">
                                                                                <?php echo $row->Name; ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-muted mb-0"><small><?php echo $CreatorData->Name; ?>, <?php echo FormatDate($row->Date); ?></small></p>
                                                                </div>
                                                                <div class="col-6 text-end">
                                                                    <h6 class="mb-1"><?php echo FormatDate($row->EventDate); ?></h6>
                                                                    <?php if ($row->EventDate == date("Y-m-d")) : ?>
                                                                        <div class="badge bg-success">
                                                                            Today
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div style="display: flex; width: 70px; margin-left: 87%;">
                                                                        <a class="dropdown-item delete-entry Edit" href="#">
                                                                            <span class="text-info">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                                    <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                                </svg>
                                                                            </span>
                                                                        </a>
                                                                        <?php if (IsCheckPermission($USERID, "DELETE_KPI")) : ?>
                                                                            <a class="dropdown-item delete-entry" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                                                <span class="text-danger">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                        <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                                    </svg>
                                                                                </span>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>

                                        </ul>
                                        <?php if ($query->rowCount() == 0) : ?>
                                            <div class="alert alert-danger">
                                                No event found.
                                            </div>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <div class="container">
                                            <div id='calendarFull'></div>
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

    <div id="CreateEvent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateEventLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreateEventLabel">Create Event</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (IsCheckPermission($USERID, "CREATE_EVENT")) : ?>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3"><label class="form-label">Event</label>
                                        <input type="text" required class="form-control" name="Event">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3"><label class="form-label">Date</label>
                                        <input type="date" value="<?php echo date("Y-m-d"); ?>" required class="form-control" name="EventDate">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3"><label class="form-label">Color</label>
                                        <input type="color" class="form-control" value="#3452eb" name="Color" style="height: 45px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                <?php else : ?>
                    <div style="padding: 10px;">
                        <?php DeniedAccess() ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>


    <div id="EditEvent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditEventLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditEventLabel">Edit Event</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (IsCheckPermission($USERID, "EDIT_EVENT")) : ?>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3"><label class="form-label">Event</label>
                                        <input type="text" id="editEventName" required class="form-control" name="Event">
                                        <input type="hidden" id="editEventId" required class="form-control" name="EventID">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3"><label class="form-label">Date</label>
                                        <input type="date" id="editEventDate" required class="form-control" name="EventDate">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3"><label class="form-label">Color</label>
                                        <input type="color" id="editEventColor" class="form-control" name="Color" style="height: 45px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                <?php else : ?>
                    <div style="padding: 10px;">
                        <?php DeniedAccess() ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<?php include "../../includes/js.php"; ?>
<script>
    $(function() {
        $("#calendarFull").fullCalendar({
            themeSystem: "bootstrap3",
            header: {
                left: "prev,next today",
                center: "title",
                right: "month,listMonth"
            },
            //weekNumbers: true,
            eventLimit: true, // allow "more" link when too many events
            events: <?php echo json_encode($formatted_events); ?>
        });
    });

    $(document).ready(function() {
        $('.delete-entry').on('click', function() {

            $(".checkbox-item").prop('checked', false);
            var row = $(this).closest('li');
            var checkbox = row.find('.checkbox-item');
            checkbox.prop('checked', true);
        });
    });

    $(".Edit").click(function() {
        var row = $(this).closest('li');
        var checkbox = row.find('.checkbox-item');
        checkbox.prop('checked', true);
        if (checkbox.length == 0) {
            ShowToast('Error 102: Something went wrong.');
            return;
        } else {
            let id = checkbox[0].value;
            let color = checkbox[0].getAttribute('data-color');
            let name = checkbox[0].getAttribute('data-name');
            let date = checkbox[0].getAttribute('data-date');
            $("#editEventId").val(id);
            $("#editEventColor").val(color);
            $("#editEventName").val(name);
            $("#editEventDate").val(date);
            $("#EditEvent").modal('show');
        }
    });

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