<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

$Notification = isset($_POST['Notification']) ? $_POST['Notification'] : "";
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

                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <form method="POST">
                                    <div class="btn-group" style="width: 100%; padding-bottom: 10px;" role="group">
                                        <input type="text" name="Notification" class="form-control" placeholder="Search notifications">
                                        <button type="submit" name="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>


                                <div class="col-md-12">
                                    <ol class="list-group list-group-numbered" id="notificationList">
                                        <?php
                                        $NOTIFICATION_QUERY = "SELECT * FROM `notifications` WHERE ClientKeyID = :ClientKeyID AND DATE(Date) BETWEEN :FromDate AND :ToDate";

                                        if (!IsCheckPermission($USERID, "IS_RECEIVE_ALL_NOTIFICATIONS")) {
                                            $NOTIFICATION_QUERY .= " AND hasUseID = :UserID";
                                        }

                                        if (!empty($Notification)) {
                                            $NOTIFICATION_QUERY .= " AND Notification LIKE '%$Notification%'";
                                        }
                                        $NOTIFICATION_QUERY .= " ORDER BY id DESC";

                                        $NOTIFICATION_QUERY_STMT = $conn->prepare($NOTIFICATION_QUERY);
                                        $NOTIFICATION_QUERY_STMT->bindParam(':ClientKeyID', $ClientKeyID);
                                        $NOTIFICATION_QUERY_STMT->bindParam(':FromDate', $FromDate);
                                        $NOTIFICATION_QUERY_STMT->bindParam(':ToDate', $ToDate);

                                        if (!IsCheckPermission($USERID, "IS_RECEIVE_ALL_NOTIFICATIONS")) {
                                            $NOTIFICATION_QUERY_STMT->bindParam(':UserID', $USERID);
                                        }

                                        $NOTIFICATION_QUERY_STMT->execute();

                                        while ($row = $NOTIFICATION_QUERY_STMT->fetch(PDO::FETCH_OBJ)) { ?>
                                            <?php
                                            $_user_data = $conn->query("SELECT * FROM users WHERE UserID = '{$row->hasUseID}'")->fetchObject();
                                            ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="d-flex align-items-center" style="cursor: pointer;" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                                                        <div class="flex-shrink-0">
                                                            <img src="<?php echo $_user_data->ProfileImage; ?>" width="50" height="50" style="object-fit: cover;" alt="user-image" class="user-avtar rounded-circle">
                                                        </div>
                                                        <div class="flex-grow-1 ms-3 me-2">
                                                            <h6 class="mb-0" style="font-size: 12px;"><?php echo $_user_data->Name; ?></h6>
                                                            <span class="mb-0" style="font-size: 10px;"><?php echo $_user_data->Position; ?></span>
                                                        </div>
                                                    </div>
                                                    <div style="margin-left: 65px;" class="notification"><?php echo $row->Notification; ?></div>
                                                </div>
                                                <span class=""><?php echo TimeAgo($row->Date); ?></span>
                                            </li>
                                        <?php  } ?>
                                    </ol>
                                    <?php if ($NOTIFICATION_QUERY_STMT->rowCount() == 0): ?>
                                        <div class="alert alert-danger">No data found.</div>
                                    <?php endif; ?>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>


<?php include "../../includes/js.php"; ?>



</html>