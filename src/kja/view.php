<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$ID = $_GET['ID'];
$data = $conn->query("SELECT * FROM _keyjobarea WHERE KjaID = '$ID'")->fetchObject();
if (!$data) {
    header("location: $LINK/key_job_area ");
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
                                <h5 class="mb-0">Key Job Area</h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "VIEW_KEY_JOB_AREA")) : ?>


                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="card shadow-none border mb-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-3">Client Type</h6>
                                                        <p class="mb-3">
                                                            <?php echo empty($data->ServiceUser) ? NoData() : $data->ServiceUser; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-3">Location</h6>
                                                        <?php echo empty($data->Location) ? NoData() : $data->Location; ?>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-3">Job Role</h6>
                                                        <?php echo empty($data->Specification) ? NoData() : $data->Specification; ?>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h6 class="mb-3">Pay Rate</h6>
                                                        <?php echo empty($data->PayRate) ? NoData() : $data->PayRate; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-12">
                                        <div class="card shadow-none border mb-0">
                                            <div class="card-body">
                                                <h6 class="mb-3">Description</h6>
                                                <?php echo empty($data->Description) ? NoData() : $data->Description; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-12">
                                        <div class="card shadow-none border mb-0">
                                            <div class="card-body">
                                                <h6 class="mb-3">Modications</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>User</th>
                                                                <th>Modication</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $n = 1;
                                                            $query = "SELECT * FROM `datamodifications` WHERE KeyID = '$ID' ORDER BY id DESC ";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetchObject()) { ?>
                                                                <?php
                                                                $userdata =  $conn->query("SELECT * FROM `users` WHERE UserID = '{$row->UserID}'")->fetchObject();
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $n++; ?></td>
                                                                    <td>


                                                                        <div class="d-flex align-items-center">
                                                                            <div class="flex-shrink-0"><img src="<?php echo $userdata->ProfileImage; ?>" alt="user image" class="img-radius wid-40"></div>
                                                                            <div class="flex-grow-1 ms-3">
                                                                                <h6 class="mb-0"><?php echo $userdata->Name; ?></h6>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><?php echo $row->Modification; ?></td>
                                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                                </tr>
                                                            <?php } ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <?php DeniedAccess(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include "../../includes/js.php"; ?>


</html>