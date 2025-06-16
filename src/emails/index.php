<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}


$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : 'Emails';

if (isset($_POST['SaveEmailListTitle'])) {
    $Title = $_POST['Title'];

    // Check if the KeyID already exists
    $checkQuery = "SELECT COUNT(*) FROM `_email_list` WHERE `ListID` = :ListID";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(":ListID", $KeyID);
    $checkStmt->execute();
    $exists = $checkStmt->fetchColumn();

    if ($exists) {
        $response = "List ID already exists.";
    } else {
        // Proceed with the insertion
        $query = "INSERT INTO `_email_list` (`ClientKeyID`, `ListID`, `Title`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :ListID, :Title, :CreatedBy, :Date)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":ClientKeyID", $ClientKeyID);
        $stmt->bindParam(":ListID", $KeyID);
        $stmt->bindParam(":Title", $Title);
        $stmt->bindParam(":CreatedBy", $USERID);
        $stmt->bindParam(":Date", $date);
        $stmt->execute();

        $Notification = "$NAME created a new email list titled $Title.";
        Notify($USERID, $ClientKeyID, $Notification);
        $response = "List created successfully.";
        header("Location: $LINK/email_list/?ListID=$KeyID");
        exit;
    }
}


if (isset($_POST['UpdateEmailListTitle'])) {
    $Title = $_POST['Title'];
    $ListID = $_POST['ListID'];
    $query = "UPDATE `_email_list` SET `Title` = :Title WHERE `ListID` = :ListID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":Title", $Title);
    $stmt->bindParam(":ListID", $ListID);
    if ($stmt->execute()) {
        $Notification = "$NAME successfully updated email list title to $Title.";
        Notify($USERID, $ClientKeyID, $Notification);
        $response = "Title successfully upated.";
    } else {
        $response = "ERROR: Title could not be updated";
        $error = 1;
    }
}

if (isset($_POST['DeleteEmailList'])) {
    $ID = $_POST['ID'];
    $reason = $_POST['reason'];

    // Retrieve the title of the email list before deleting
    $titleQuery = $conn->prepare("SELECT Title FROM _email_list WHERE ListID = :ListID");
    $titleQuery->bindParam(':ListID', $ID);
    $titleQuery->execute();
    $title = $titleQuery->fetchColumn(); // Fetch the title

    if ($title) {
        // Proceed with the delete operation
        $deleteQuery = $conn->prepare("DELETE FROM _email_list WHERE ListID = :ListID");
        $deleteQuery->bindParam(':ListID', $ID);
        if ($deleteQuery->execute()) {
            $Notification = "$NAME successfully deleted the email list titled '$title'.";
            Notify($USERID, $ClientKeyID, $Notification);
            $response = "Title successfully deleted.";
        } else {
            $response = "Failed to delete the title.";
        }
    } else {
        $response = "Email list not found.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>
<style>
    .is-active {
        border-bottom: 2px solid var(--bs-primary) !important;
    }

    .nav-tabs a {
        color: <?php echo ($theme == "dark") ? "white" : "black"; ?> !important;
    }

    .border-red {
        border: 1px solid red !important;
    }
</style>

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
                        <div class="card-header d-flex align-items-center justify-content-between py-3">
                            <h5>Emails</h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="card shadow-none border">
                                <div style="padding: 10px;">
                                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/emails?isTab=Emails" class="nav-link <?php echo ($isTab == "Emails") ? "active is-active" : "" ?>">Your Emails</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/emails?isTab=Template" class="nav-link <?php echo ($isTab == "Template") ? "active is-active" : "" ?>">Templates</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/emails?isTab=EmailList" class="nav-link <?php echo ($isTab == "EmailList") ? "active is-active" : "" ?>">Email List</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/emails?isTab=EmailLogs" class="nav-link <?php echo ($isTab == "EmailLogs") ? "active is-active" : "" ?>">Email Log</a></li>
                                    </ul>
                                </div>
                                <?php if ($isTab == "Emails") : ?>
                                    <div class="card table-card" style="border: none;">
                                        <div class="card-header d-flex align-items-center justify-content-between py-3">
                                            <h5 class="mb-0">Emails</h5>
                                            <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <?php if (IsCheckPermission($USERID, "COMPOSE_EMAIL")) : ?>
                                                        <a class="dropdown-item"  href="<?php echo $LINK; ?>/compose_email/?EmailID=<?php echo $KeyID ?>">
                                                            <span class="text-success">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                                </svg>
                                                            </span>
                                                            Compose
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (IsCheckPermission($USERID, "VIEW_EMAIL_LOG")) : ?>
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
                                                    <?php endif; ?>



                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="pc-dt-simple">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Subject</th>
                                                            <th>Template</th>
                                                            <th>Email List</th>
                                                            <th>Emails Sent</th>
                                                            <th>Emails Failed</th>
                                                            <th>Created By</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $n = 1;
                                                        $_get_email_list = $conn->query("SELECT * FROM `_emails` WHERE ClientKeyID = '$ClientKeyID'");
                                                        while ($row = $_get_email_list->fetchObject()) { ?>
                                                            <?php
                                                            $TemplateData = $conn->query("SELECT Title,EmailListID FROM `_email_templates` WHERE TemplateID = '{$row->TemplateID}' ")->fetchObject();

                                                            $SuccessEmail = $conn->query("SELECT * FROM `_email_logs` WHERE EmailID = '{$row->EmailID}' AND Status = 'Success' ")->rowCount();
                                                            $FailedEmail = $conn->query("SELECT * FROM `_email_logs` WHERE EmailID = '{$row->EmailID}' AND Status != 'Success' ")->rowCount();
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $n++; ?></td>
                                                                <td>
                                                                    <?php echo $row->Subject; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $TemplateData->Title; ?>
                                                                </td>
                                                                <td><?php echo EmailListName($TemplateData->EmailListID); ?></td>
                                                                <td><?php echo number_format($SuccessEmail); ?></td>
                                                                <td><?php echo number_format($FailedEmail); ?></td>
                                                                <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                                <td><?php echo FormatDate($row->Date); ?></td>
                                                            </tr>
                                                        <?php } ?>


                                                    </tbody>
                                                </table>
                                                <?php if ($_get_email_list->rowCount() == 0) : ?>
                                                    <div style="padding: 10px;">
                                                        <div class="alert alert-danger">
                                                            No data found
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($isTab == "Template") : ?>
                                    <?php if (IsCheckPermission($USERID, "VIEW_EMAIL_TEMPLATES")) : ?>
                                        <div class="card table-card" style="border: none;">
                                            <div class="card-header d-flex align-items-center justify-content-between py-3">
                                                <h5 class="mb-0">Email Templates</h5>
                                                <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <?php if (IsCheckPermission($USERID, "CREATE_EMAIL_TEMPLATE")) : ?>
                                                            <a class="dropdown-item" href="<?php echo $LINK; ?>/email_template/?TemplateID=<?php echo $KeyID; ?>">
                                                                <span class="text-success">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                                    </svg>
                                                                </span>
                                                                Create
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (IsCheckPermission($USERID, "EDIT_EMAIL_TEMPLATE")) : ?>
                                                            <a class="dropdown-item" href="#" id="EditTemplate">
                                                                <span class="text-info">

                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                        <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                    </svg>
                                                                </span>
                                                                Edit
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (IsCheckPermission($USERID, "DELETE_EMAIL_TEMPLATE")) : ?>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                                <span class="text-danger">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                        <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                    </svg>
                                                                </span>
                                                                Delete</a>
                                                        <?php endif; ?>

                                                        <a class="dropdown-item" href="#" id="ViewTemplate">
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

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="pc-dt-simple">
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
                                                                <th>Title</th>
                                                                <th>Email List</th>
                                                                <th>Created By</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $n = 1;
                                                            $_get_email_list = $conn->query("SELECT * FROM `_email_templates` WHERE ClientKeyID = '$ClientKeyID' GROUP BY `TemplateID`");
                                                            while ($row = $_get_email_list->fetchObject()) { ?>

                                                                <tr>
                                                                    <td><?php echo $n++; ?></td>
                                                                    <td> <input type="checkbox" class="form-check-input checkbox-item" data-title="<?php echo $row->Title; ?>" value="<?php echo $row->TemplateID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                                    <td>
                                                                        <?php echo $row->Title; ?>
                                                                    </td>
                                                                    <td><?php echo EmailListName($row->EmailListID); ?></td>
                                                                    <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                                </tr>
                                                            <?php } ?>


                                                        </tbody>
                                                    </table>
                                                    <?php if ($_get_email_list->rowCount() == 0) : ?>
                                                        <div style="padding: 10px;">
                                                            <div class="alert alert-danger">
                                                                No data found
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="CreateEmailList" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateEmailListTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="CreateEmailListTitle">Create Email List</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="mb-3"><label class="form-label">Title</label>
                                                                        <input type="text" name="Title" class="form-control" placeholder="Enter email list title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button name="SaveEmailListTitle" class="btn btn-primary">Next</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <div id="EditEmailListTitleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditEmailListTitleModalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="EditEmailListTitleModalTitle">Edit Email List</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="mb-3"><label class="form-label">Title</label>
                                                                        <input type="text" name="Title" id="EditTitleInput" class="form-control" placeholder="Enter email list title">
                                                                        <input type="hidden" name="ListID" id="EditListID" class="form-control" placeholder="Enter email list title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button name="UpdateEmailListTitle" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div style="padding: 10px;">
                                            <?php DeniedAccess(); ?>
                                        </div>
                                    <?php endif; ?>


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

                                <?php endif; ?>
                                <?php if ($isTab == "EmailList") : ?>
                                    <?php if (IsCheckPermission($USERID, "VIEW_EMAIL_LIST")) : ?>
                                        <div class="card table-card" style="border: none;">
                                            <div class="card-header d-flex align-items-center justify-content-between py-3">
                                                <h5 class="mb-0">Email List</h5>
                                                <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <?php if (IsCheckPermission($USERID, "CREATE_EMAIL_LIST")) : ?>
                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#CreateEmailList">
                                                                <span class="text-success">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                                    </svg>
                                                                </span>
                                                                Create
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (IsCheckPermission($USERID, "EDIT_EMAIL_LIST")) : ?>
                                                            <a class="dropdown-item" href="#" id="EditTitle">
                                                                <span class="text-info">

                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                        <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                    </svg>
                                                                </span>
                                                                Edit Title
                                                            </a>
                                                            <a class="dropdown-item" href="#" id="EditList">
                                                                <span class="text-info">

                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                        <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                    </svg>
                                                                </span>
                                                                Edit List
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (IsCheckPermission($USERID, "DELETE_EMAIL_LIST")) : ?>
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
                                                            View List
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="pc-dt-simple">
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
                                                                <th>Title</th>
                                                                <th>Total Emails</th>
                                                                <th>Created By</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $n = 1;
                                                            $_get_email_list = $conn->query("SELECT COUNT(id) as EmailNums, Title, ListID, CreatedBy, Date FROM `_email_list` WHERE ClientKeyID = '$ClientKeyID' AND Email != '' GROUP BY `ListID`");
                                                            while ($row = $_get_email_list->fetchObject()) { ?>
                                                                <tr>
                                                                    <td><?php echo $n++; ?></td>
                                                                    <td> <input type="checkbox" class="form-check-input checkbox-item" data-title="<?php echo $row->Title; ?>" value="<?php echo $row->ListID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                                    <td>
                                                                        <?php echo $row->Title; ?>
                                                                    </td>
                                                                    <td><?php echo $row->EmailNums; ?></td>
                                                                    <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                                </tr>
                                                            <?php } ?>


                                                        </tbody>
                                                    </table>
                                                    <?php if ($_get_email_list->rowCount() == 0) : ?>
                                                        <div style="padding: 10px;">
                                                            <div class="alert alert-danger">
                                                                No data found
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="CreateEmailList" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateEmailListTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="CreateEmailListTitle">Create Email List</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="mb-3"><label class="form-label">Title</label>
                                                                        <input type="text" name="Title" class="form-control" placeholder="Enter email list title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button name="SaveEmailListTitle" class="btn btn-primary">Next</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <div id="EditEmailListTitleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditEmailListTitleModalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="EditEmailListTitleModalTitle">Edit Email List</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="mb-3"><label class="form-label">Title</label>
                                                                        <input type="text" name="Title" id="EditTitleInput" class="form-control" placeholder="Enter email list title">
                                                                        <input type="hidden" name="ListID" id="EditListID" class="form-control" placeholder="Enter email list title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button name="UpdateEmailListTitle" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div style="padding: 10px;">
                                            <?php DeniedAccess(); ?>
                                        </div>
                                    <?php endif; ?>


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

                                <?php endif; ?>

                                <?php if ($isTab == "EmailLogs") : ?>
                                    <?php if (IsCheckPermission($USERID, "VIEW_EMAIL_LOG")) : ?>
                                        <div class="card table-card" style="border: none;">
                                            <div class="card-header d-flex align-items-center justify-content-between py-3">
                                                <h5 class="mb-0">Email Log</h5>

                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="pc-dt-simple">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>

                                                                <th>Log</th>
                                                                <th>Email</th>
                                                                <th>Status</th>
                                                                <th>View</th>
                                                                <th>Created By</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $n = 1;
                                                            $_email_logs = $conn->query("SELECT * FROM `_email_logs` WHERE ClientKeyID = '$ClientKeyID' ");
                                                            while ($row = $_email_logs->fetchObject()) { ?>
                                                                <tr>
                                                                    <td><?php echo $n++; ?></td>
                                                                    <td>
                                                                        <?php echo $row->Description; ?>
                                                                    </td>
                                                                    <td><?php echo $row->Email; ?></td>
                                                                    <td>
                                                                        <?php if ($row->Status == "Success") : ?>
                                                                            <span class="badge bg-success">Sent</span>
                                                                        <?php else : ?>
                                                                            <span class="badge bg-danger">Error: Not Sent</span>

                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="flex-shrink-0">
                                                                            <a href="<?php echo $row->PageUrl; ?>" class="btn btn-sm btn-light-secondary">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                                    <path fill="currentColor" d="M5 3c-1.11 0-2 .89-2 2v14c0 1.11.89 2 2 2h14c1.11 0 2-.89 2-2V5c0-1.11-.89-2-2-2zm0 2h14v14H5zm2 2v2h10V7zm0 4v2h10v-2zm0 4v2h7v-2z"></path>
                                                                                </svg> View
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                    <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                                </tr>
                                                            <?php } ?>


                                                        </tbody>
                                                    </table>
                                                    <?php if ($_email_logs->rowCount() == 0) : ?>
                                                        <div style="padding: 10px;">
                                                            <div class="alert alert-danger">
                                                                No data found
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="CreateEmailList" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateEmailListTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="CreateEmailListTitle">Create Email List</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="mb-3"><label class="form-label">Title</label>
                                                                        <input type="text" name="Title" class="form-control" placeholder="Enter email list title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button name="SaveEmailListTitle" class="btn btn-primary">Next</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <div id="EditEmailListTitleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditEmailListTitleModalTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="EditEmailListTitleModalTitle">Edit Email List</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="mb-3"><label class="form-label">Title</label>
                                                                        <input type="text" name="Title" id="EditTitleInput" class="form-control" placeholder="Enter email list title">
                                                                        <input type="hidden" name="ListID" id="EditListID" class="form-control" placeholder="Enter email list title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button name="UpdateEmailListTitle" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div style="padding: 10px;">
                                            <?php DeniedAccess(); ?>
                                        </div>
                                    <?php endif; ?>


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
<?php if ($isTab == "EmailList") : ?>
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
        $("#EditList").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let ListID = checkboxes[0].value;
                window.location.href = "<?php echo $LINK ?>/email_list/?ListID=" + ListID + "";
            }
        });

        $("#EditTitle").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let ListID = checkboxes[0].value;
                let Title = checkboxes[0].getAttribute('data-title');
                $("#EditTitleInput").val(Title)
                $("#EditListID").val(ListID)
                $("#EditEmailListTitleModal").modal('show');


            }
        });



        $("#View").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let ListID = checkboxes[0].value;
                window.location.href = "<?php echo $LINK ?>/view_email_list/?ListID=" + ListID + "";
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
                                DeleteEmailList: true
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
<?php endif; ?>
<?php if ($isTab == "Template") : ?>
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

        $("#EditTemplate").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let TemplateID = checkboxes[0].value;
                window.location.href = "<?php echo $LINK ?>/email_template/?TemplateID=" + TemplateID + "";
            }
        });

        $("#ViewTemplate").click(function() {
            let checkboxes = getSelectedCheckboxes();
            if (checkboxes.length == 0) {
                ShowToast('Error 102: Something went wrong.');
                return;
            } else {
                let TemplateID = checkboxes[0].value;
                let url = "<?php echo $LINK ?>/templates/email/?TemplateID=" + TemplateID;
                window.open(url, '_blank');
            }
        });
    </script>
<?php endif; ?>

</html>