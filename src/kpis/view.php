<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$ID = $_GET['ID'];
$data = $conn->query("SELECT * FROM _kpis WHERE KpiID = '$ID'")->fetchObject();
$data_user = $conn->query("SELECT * FROM users WHERE UserID = '{$data->UserID}'")->fetchObject();
if (!$data) {
    header("location: $LINK/weekly_kpis ");
}
$view = isset($_GET['view']) ? $_GET['view'] : "details";

if (isset($_POST['UpdateAchieved'])) {
    $achieved = $_POST['achieved'];
    $KpiID = $_POST['KpiID'];

    $checkQuery = "SELECT COUNT(*) FROM `_kpis_achieved` WHERE `KpiID` = :KpiID AND `Date` BETWEEN :StartDate AND :EndDate";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bindParam(':KpiID', $KpiID, PDO::PARAM_STR);
    $stmt->bindParam(':StartDate', $FromDate, PDO::PARAM_STR);
    $stmt->bindParam(':EndDate', $ToDate, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        // If an entry exists, update it
        $updateQuery = "UPDATE `_kpis_achieved` 
                        SET `Achieved` = :Achieved, `CreatedBy` = :CreatedBy, `Date` = :Date 
                        WHERE `KpiID` = :KpiID AND `Date` BETWEEN :StartDate AND :EndDate";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':Achieved', $achieved, PDO::PARAM_STR);
        $stmt->bindParam(':CreatedBy', $USERID, PDO::PARAM_STR);
        $stmt->bindParam(':Date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':KpiID', $KpiID, PDO::PARAM_STR);
        $stmt->bindParam(':StartDate', $FromDate, PDO::PARAM_STR);
        $stmt->bindParam(':EndDate', $ToDate, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        // If no entry exists, insert a new one
        $insertQuery = "INSERT INTO `_kpis_achieved`(`ClientKeyID`, `KpiID`, `Achieved`, `CreatedBy`, `Date`) 
                        VALUES (:ClientKeyID, :KpiID, :Achieved, :CreatedBy, :Date)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bindParam(':ClientKeyID', $ClientKeyID, PDO::PARAM_STR);
        $stmt->bindParam(':KpiID', $KpiID, PDO::PARAM_STR);
        $stmt->bindParam(':Achieved', $achieved, PDO::PARAM_STR);
        $stmt->bindParam(':CreatedBy', $USERID, PDO::PARAM_STR);
        $stmt->bindParam(':Date', $date, PDO::PARAM_STR);
        $stmt->execute();
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
                                <h5 class="mb-0"><?php echo $page; ?></h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "VIEW_KPI")) : ?>
                                <div style="padding-bottom: 10px;">
                                    <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a href="<?php echo $LINK; ?>/view_weekly_kpis/?ID=<?php echo $ID; ?>&view=details">
                                                <button class="nav-link <?php echo ($view == "details") ? "active" : ""; ?>">Details</button>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="<?php echo $LINK; ?>/view_weekly_kpis/?ID=<?php echo $ID; ?>&view=kpis">
                                                <button class="nav-link <?php echo ($view == "kpis" || $view == "update") ? "active" : ""; ?>">KPIs</button>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <?php if ($view == "details") : ?>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="card shadow-none border mb-0">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="d-flex mb-1">
                                                                <div class="flex-shrink-0"><img style="border-radius: 50%;" src="<?php echo $data_user->ProfileImage; ?>" alt="user-image" class="user-avtar wid-35"></div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-1"><?php echo empty($data_user->Name) ? NoData() : $data_user->Name; ?></h6>
                                                                    <span><?php echo empty($data_user->Position) ? NoData() : $data_user->Position; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h6 class="mb-3">Date</h6>
                                                            From <b><?php echo empty($data->StartDate) ? NoData() : FormatDate($data->StartDate); ?></b> to <b><?php echo empty($data->EndDate) ? NoData() : FormatDate($data->EndDate); ?></b>
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
                                <?php endif; ?>

                                <?php if ($view == "kpis") : ?>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-12">
                                            <div class="card shadow-none border mb-0">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center" style="padding-bottom: 10px;">
                                                        <div class="flex-grow-1 me-3">
                                                            <h6 class="mb-0">Weekly KPI </h6>
                                                            <p><br> from <?php echo date("j F Y", strtotime($FromDate)); ?> to <?php echo date("j F Y", strtotime($ToDate)); ?></p>
                                                        </div>
                                                        <?php if ($data->UserID == $USERID) : ?>

                                                            <div class="flex-shrink-0"><a href="<?php echo $LINK; ?>/view_weekly_kpis/?ID=<?php echo $ID; ?>&view=update" class="btn btn-sm btn-light-secondary"><i class="ti ti-check"></i> Update Achieved KPI</a></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>KPI</th>
                                                                    <th>Target</th>
                                                                    <th>Achieved</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $n = 1;
                                                                $q = $conn->query("SELECT * FROM `_kpis_targets` WHERE _kpi_id = '$ID' ");
                                                                while ($r = $q->fetchObject()) { ?>
                                                                    <?php
                                                                    $Achieved = $conn->query("SELECT SUM(Achieved)  FROM `_kpis_achieved` WHERE KpiID = '{$r->KpiID}' AND Date BETWEEN '$FromDate' AND '$ToDate' ")->fetchColumn();
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $n++; ?></td>
                                                                        <td><?php echo $r->Name; ?></td>
                                                                        <td><?php echo $r->Target; ?></td>
                                                                        <td><?php echo number_format($Achieved); ?></td>
                                                                    </tr>
                                                                <?php } ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($view == "update") : ?>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-12">
                                            <div class="card shadow-none border mb-0">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center" style="padding-bottom: 10px;">
                                                        <div class="flex-grow-1 me-3">
                                                            <h6 class="mb-0">Weekly KPI</h6>
                                                        </div>
                                                        <?php if ($data->UserID == $USERID) : ?>
                                                            <div class="flex-shrink-0"><a href="<?php echo $LINK; ?>/view_weekly_kpis/?ID=<?php echo $ID; ?>&view=kpis" class="btn btn-sm btn-light-secondary">Go Back</a></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if ($data->UserID == $USERID) : ?>
                                                        <?php
                                                        $q = $conn->query("SELECT * FROM `_kpis_targets` WHERE _kpi_id = '$ID' ");
                                                        while ($r = $q->fetchObject()) { ?>
                                                            <?php
                                                            $achieved = $conn->query("SELECT SUM(Achieved) FROM `_kpis_achieved` WHERE KpiID = '{$r->KpiID}' AND Date BETWEEN '$FromDate' AND '$ToDate' ")->fetchColumn();
                                                            ?>
                                                            <div class="card CardKPI" data-id="<?php echo $r->KpiID; ?>">
                                                                <div class="row g-3" style="padding: 15px;">
                                                                    <div class="col-lg-10">
                                                                        <div class="mb-3" style="display: flex;">
                                                                            <input type="text" class="form-control kpi" readonly value="<?php echo $r->Name; ?>" placeholder="KPI" data-id="<?php echo $r->KpiID; ?>" style="margin-left: 5px;">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="mb-3">
                                                                            <input type="number" class="form-control achieved" value="<?php echo (empty($achieved) ? 0 : $achieved); ?>" placeholder="achieved" data-id="<?php echo $r->KpiID; ?>">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <button class="btn btn-primary" id="update">Submit</button>

                                                    <?php else : ?>
                                                        <?php DeniedAccess(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
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
</body>
<?php include "../../includes/js.php"; ?>

<script>
    $(document).ready(function() {
        $('.achieved').on('change', function() {
            var dataId = $(this).data('id');
            var achieved = $(this).val(); // Get value from the current element

            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    KpiID: dataId,
                    achieved: achieved,
                    UpdateAchieved: true,
                },
                success: function(response) {
                    ShowToast('Achieved KPI updated successfully');
                },
                error: function(xhr, status, error) {
                    ShowToast('Something went wrong. Please try again later');
                }
            });
        });

        $("#update").click(function() {
            ShowToast('Achieved KPI updated successfully');

        });
    });
</script>

</html>