<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['delete'])) {
    // Retrieve the 'ID' parameter from the POST request
    $ID = $_POST['ID'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM `_keyjobarea` WHERE KjaID = :ID");

    // Bind the 'ID' parameter to the prepared statement
    $stmt->bindParam(':ID', $ID);

    // Execute the statement to delete the record
    if ($stmt->execute()) {
        echo "Deleted record";
        $NOTIFICATION = "$NAME successfully deleted a key job area";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    } else {
        echo "Error deleting record";
    }
}


if (isset($_POST['Search'])) {
    $Location = $_POST['Location'];
    $Specification = $_POST['Specification'];
    $ServiceUser = $_POST['ServiceUser'];
    $PayRate = $_POST['PayRate'];
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

        header("location: $LINK/key_job_area/?q=$SearchID");
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
                                <h5 class="mb-0">Activity Log</h5>

                            </div>
                        </div>
                        <?php if (IsCheckPermission($USERID, "VIEW_ACIVITY_LOG")): ?>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="analytics-tab-1-pane" role="tabpanel" aria-labelledby="analytics-tab-1" tabindex="0">
                                        <ul class="list-group list-group-flush">
                                            <?php
                                            $_query = $conn->query("SELECT Notification AS log, Date AS log_date, hasUseID AS UserID, 'null' AS PageUrl 
                                                                    FROM notifications
                                                                    WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate'

                                                                    UNION ALL

                                                                    SELECT Modification AS log, Date AS log_date, UserID AS UserID, PageUrl
                                                                    FROM datamodifications
                                                                    WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate'

                                                                    UNION ALL

                                                                    SELECT CONCAT('Viewing ', PageName) AS log, Date AS log_date, UserID AS UserID, PageUrl
                                                                    FROM logs
                                                                    WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate'

                                                                    ORDER BY log_date DESC");
                                            while ($row = $_query->fetchObject()) { ?>
                                                <?php
                                                $createdby_data = $conn->query("SELECT * FROM `users` WHERE UserID = '{$row->UserID}' ")->fetchObject();
                                                ?>
                                                <li class="list-group-item">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="flex-shrink-0"><img width="40" height="40" style="object-fit: cover;" src="<?php echo $createdby_data->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40"></div>

                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="row g-1">
                                                                <div class="col-6">
                                                                    <h6 class="mb-0"><?php echo $createdby_data->Name; ?></h6>
                                                                    <p class=" mb-0"><span><?php echo $row->log; ?></span></p>
                                                                </div>
                                                                <div class="col-6 text-end">
                                                                    <h6 class="mb-1"><?php echo FormatDate($row->log_date); ?></h6>
                                                                </div>
                                                                <?php if ($row->PageUrl !== "null") : ?>
                                                                    <div style="display: flex;">
                                                                        <a class="badge text-bg-primary" style="width: 70px;" href="<?php echo $row->PageUrl; ?>" target="_blank">
                                                                            Open Link
                                                                        </a>
                                                                        <span class="badge ">
                                                                            Log
                                                                        </span>
                                                                    </div>

                                                                <?php else : ?>
                                                                    <span style="width: 20px;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                            <path fill="currentColor" d="M10 21h4c0 1.1-.9 2-2 2s-2-.9-2-2m11-2v1H3v-1l2-2v-6c0-3.1 2-5.8 5-6.7V4c0-1.1.9-2 2-2s2 .9 2 2v.3c3 .9 5 3.6 5 6.7v6zm-4-8c0-2.8-2.2-5-5-5s-5 2.2-5 5v7h10z" />
                                                                        </svg>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                        <?php if ($_query->rowCount() == 0) : ?>
                                            <div class="alert alert-danger">
                                                No data found.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                            
                        <?php else: ?>
                            <div style="padding: 10px;">
                            <?php DeniedAccess(); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
<?php include "../../includes/js.php"; ?>


</html>