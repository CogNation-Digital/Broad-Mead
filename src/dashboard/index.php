<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$TotalClientCount = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isBranch IS NULL ")->rowCount();
$TotalClientCountThisWeek = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND   isBranch IS NULL  AND Date BETWEEN '$FromDate' AND '$ToDate' ")->rowCount();

$TotalCandidateCount = $conn->query("SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID'")->rowCount();
$TotalCandidateCountThisWeek = $conn->query("SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' AND Date BETWEEN '$FromDate' AND '$ToDate' ")->rowCount();


$TotalShift = $conn->query("SELECT * FROM `_shifts` WHERE ClientKeyID = '$ClientKeyID' AND DATE(ShiftDate) BETWEEN '$FromDate' AND '$ToDate'  ")->rowCount();
$TotalShiftToday = $conn->query("SELECT * FROM `_shifts` WHERE ClientKeyID = '$ClientKeyID' AND DATE(ShiftDate) = '$today' ")->rowCount();

$TotalTimesheet = $conn->query("SELECT * FROM `_timesheet` WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' ")->rowCount();
$TotalTimesheetToday = $conn->query("SELECT * FROM `_timesheet` WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) = '$today' ")->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php" ?>
    <div class="pc-container" style="margin-left: 280px;">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Dashboard</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-1 fw-medium text-muted">Total Clients</p>
                                    <?php if (IsCheckPermission($USERID, "SEE_TOTAL_CLIENTS_COUNT")) : ?>
                                        <h4 class="mb-1"><?php echo number_format($TotalClientCount); ?></h4>
                                        <p class="mb-0 text-sm"><?php echo number_format($TotalClientCountThisWeek); ?> <?php echo ($TotalClientCountThisWeek == 1) ? 'client' : 'clients'; ?> this week </p>
                                    <?php else : ?>
                                        <h4 class="text-danger mb-1">--</h4>
                                        <p class="text-danger mb-0 text-sm">-- </p>
                                    <?php endif; ?>

                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avtar avtar-l bg-light-primary rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.6em" height="1.6em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M7 14.001h2v2H7z" />
                                            <path fill="currentColor" d="M19 2h-8a2 2 0 0 0-2 2v6H5c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4a2 2 0 0 0-2-2M5 20v-8h6v8zm9-12h-2V6h2zm4 8h-2v-2h2zm0-4h-2v-2h2zm0-4h-2V6h2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-1 fw-medium text-muted">Total Candidates</p>
                                    <?php if (IsCheckPermission($USERID, "SEE_TOTAL_CANDIDATES_COUNT")) : ?>
                                        <h4 class="mb-1"><?php echo number_format($TotalCandidateCount); ?></h4>
                                        <p class="mb-0 text-sm"><?php echo number_format($TotalCandidateCountThisWeek); ?> <?php echo ($TotalCandidateCountThisWeek == 1) ? 'candidate' : 'candidates'; ?> this week </p>
                                    <?php else : ?>
                                        <h4 class="text-danger mb-1">--</h4>
                                        <p class="text-danger mb-0 text-sm">-- </p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avtar avtar-l bg-light-success rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.6em" height="1.6em" viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0-8 0M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2m1-17.87a4 4 0 0 1 0 7.75M21 21v-2a4 4 0 0 0-3-3.85" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-1 fw-medium text-muted">Total Shifts</p>
                                    <?php if (IsCheckPermission($USERID, "SEE_TOTAL_SHIFTS_COUNT")) : ?>
                                        <h4 class="mb-1"><?php echo number_format($TotalShift); ?></h4>
                                        <p class="mb-0 text-sm"><?php echo number_format($TotalShiftToday); ?> <?php echo ($TotalShiftToday == 1) ? 'shift' : 'shifts'; ?> today </p>
                                    <?php else : ?>
                                        <h4 class="text-danger mb-1">--</h4>
                                        <p class="text-danger mb-0 text-sm">-- </p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avtar avtar-l bg-light-warning rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.6em" height="1.6em" viewBox="0 0 256 256">
                                            <path fill="currentColor" d="m213.66 66.34l-40-40A8 8 0 0 0 168 24H88a16 16 0 0 0-16 16v16H56a16 16 0 0 0-16 16v144a16 16 0 0 0 16 16h112a16 16 0 0 0 16-16v-16h16a16 16 0 0 0 16-16V72a8 8 0 0 0-2.34-5.66M168 216H56V72h76.69L168 107.31zm32-32h-16v-80a8 8 0 0 0-2.34-5.66l-40-40A8 8 0 0 0 136 56H88V40h76.69L200 75.31Zm-56-32a8 8 0 0 1-8 8H88a8 8 0 0 1 0-16h48a8 8 0 0 1 8 8m0 32a8 8 0 0 1-8 8H88a8 8 0 0 1 0-16h48a8 8 0 0 1 8 8" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-1 fw-medium text-muted">Total timesheet</p>
                                    <?php if (IsCheckPermission($USERID, "SEE_TIMESHEET_SHIFTS_COUNT")) : ?>
                                        <h4 class="mb-1"><?php echo number_format($TotalTimesheet); ?></h4>
                                        <p class="mb-0 text-sm"><?php echo number_format($TotalTimesheetToday); ?> <?php echo ($TotalTimesheetToday == 1) ? 'timesheet ' : 'timesheets'; ?> today </p>
                                    <?php else : ?>
                                        <h4 class="text-danger mb-1">--</h4>
                                        <p class="text-danger mb-0 text-sm">-- </p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avtar avtar-l bg-light-info rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.6em" height="1.6em" viewBox="0 0 1792 1792">
                                            <path fill="currentColor" d="M1696 384q40 0 68 28t28 68v1216q0 40-28 68t-68 28H736q-40 0-68-28t-28-68v-288H96q-40 0-68-28t-28-68V640q0-40 20-88t48-76L476 68q28-28 76-48t88-20h416q40 0 68 28t28 68v328q68-40 128-40zm-544 213L853 896h299zM512 213L213 512h299zm196 647l316-316V128H640v416q0 40-28 68t-68 28H128v640h512v-256q0-40 20-88t48-76m956 804V512h-384v416q0 40-28 68t-68 28H768v640z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-7 col-md-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Shifts</h5>
                                <a href="<?php echo $LINK; ?>/shifts">
                                    <button class="btn btn-sm btn-link-primary">View All</button>
                                </a>
                            </div>
                        </div>
                        <div class="card-body pb-0" style="height: 430px; overflow-y: scroll; padding: 10px;">
                            <div class="table-responsive">
                                <?php if (IsCheckPermission($USERID, "SEE_SHIFTS_FOR_TODAY")) : ?>
                                    <div class="card">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="analytics-tab-1-pane" role="tabpanel" aria-labelledby="analytics-tab-1" tabindex="0">
                                                <ul class="list-group list-group-flush">
                                                    <?php
                                                    $query = "SELECT * FROM _shifts WHERE ClientKeyID = '$ClientKeyID' AND ShiftDate BETWEEN '$FromDate' AND '$ToDate'";
                                                    $stmt = $conn->prepare($query);
                                                    $stmt->execute();
                                                    while ($row = $stmt->fetchObject()) { ?>
                                                        <?php
                                                        $CandidateData = $conn->query("SELECT * FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}' ")->fetchObject();
                                                        $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}' ")->fetchColumn();
                                                        ?>
                                                        <li class="list-group-item">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    <div class="avtar avtar  ">
                                                                        <img width="40" height="40" style="object-fit: cover;" src="<?php echo $CandidateData->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40">
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <div class="row g-1">
                                                                        <div class="col-6">
                                                                            <h6 class="mb-0"><?php echo $CandidateData->Name; ?></h6>
                                                                            <p class="text-muted mb-0"><small><?php echo $row->Type; ?> / <?php echo $ClientName; ?> </small></p>
                                                                        </div>
                                                                        <div class="col-6 text-end">
                                                                            <?php if ($row->ShiftDate == date("Y-m-d")) : ?>
                                                                                <h6 class="mb-1 badge bg-success">Today</h6>
                                                                            <?php else : ?>
                                                                                <h6 class="mb-1"><?php echo FormatDate($row->ShiftDate); ?></h6>
                                                                            <?php endif; ?>
                                                                            <p class="mb-0"><i class="ti ti-clock"></i> <?php echo $row->StartTime; ?> - <?php echo $row->EndTime; ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?php } ?>

                                                </ul>

                                            </div>
                                            <?php if ($stmt->rowCount() == 0) : ?>
                                                <div style="padding: 10px;">
                                                    <div class="alert alert-danger">
                                                        No shifts found
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                <?php else : ?>
                                    <div style="padding: 10px;">
                                        <?php DeniedAccess(); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="card">
                        <div class="card-body" style="height: 500px;">
                            <h5 class="mb-0">Calendar</h5>
                            <div id="carouselExample" class="carousel slide">
                                <div class="d-flex align-items-center justify-content-end my-1"><button class="carousel-control-prev position-relative" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                        <a href="<?php echo $LINK; ?>/calendar">
                                            <button class="btn btn-sm btn-link-primary">View All</button>

                                        </a>
                                </div>
                                <?php if (IsCheckPermission($USERID, "SEE_CALENDER_EVENTS")) : ?>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <p><?php echo FormatDate(date("Y-m-d")); ?> <span class="badge text-bg-primary">TODAY</span></p>
                                            <?php
                                            $date = date("Y-m-d");
                                            $query = $conn->query("SELECT * FROM `calendar` WHERE ClientKeyID = '$ClientKeyID' AND EventDate = '$date' ORDER BY id DESC LIMIT 5");
                                            while ($row = $query->fetchObject()) { ?>
                                                <div class="card overflow-hidden mb-2">
                                                    <div class="card-body px-3 py-2 border-start border-4 " style="border-left: 5px solid <?php echo $row->Color; ?> !important;">
                                                        <h6><?php echo $row->Name; ?></h6>
                                                        <p class="mb-0"><?php echo FormatDate($row->Date); ?></p>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($query->rowCount() == 0) : ?>
                                                <div class="alert alert-danger">
                                                    No events for today
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div style="margin-top: 10px;">
                                        <?php DeniedAccess(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card table-card">
                        <div class="card-header d-flex align-items-center justify-content-between py-3">
                            <h5 class="mb-0">Key Job Area </h5>
                            <a href="<?php echo $LINK; ?>/key_job_area">
                                <button class="btn btn-sm btn-link-primary">View All</button>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php if (IsCheckPermission($USERID, "SEE_KEY_JOB_AREAS_LIST")) : ?>
                                    <table class="table table-hover" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Client Type </th>
                                                <th>Location</th>
                                                <th>Job Role</th>
                                                <th>Pay Rate</th>
                                                <th>Created By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM `_keyjobarea` WHERE ClientKeyID = '$ClientKeyID' ";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $n = 1;
                                            while ($row = $stmt->fetchObject()) { ?>
                                                <?php
                                                $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                                ?>
                                                <tr>
                                                    <td><?php echo $n++; ?></td>
                                                    <td><?php echo TruncateText($row->ServiceUser, 30); ?></td>
                                                    <td><?php echo TruncateText($row->Location, 30); ?></td>
                                                    <td><?php echo TruncateText($row->Specification, 30); ?></td>
                                                    <td><?php echo $row->PayRate; ?></td>
                                                    <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                </tr>
                                            <?php } ?>


                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <div style="padding: 10px;">
                                        <?php DeniedAccess() ?>
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

</html>