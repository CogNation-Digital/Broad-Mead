<?php

$TotalClients = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isClient = '' $HAS_CREATEDBY")->rowCount();
$TotalClientsThisWeek = $conn->query("SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isClient = '' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY")->rowCount();
$TotalCandidates = $conn->query("SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' $HAS_CREATEDBY")->rowCount();
$TotalCandidatesThisWeek = $conn->query("SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY")->rowCount();
$TotalShiftsThisWeek = $conn->query("SELECT * FROM `_shifts` WHERE ClientKeyID = '$ClientKeyID' AND DATE(ShiftDate) BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY")->rowCount();
$TotalTimesheetsThisWeek = $conn->query("SELECT * FROM `_timesheet` WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY")->rowCount();

$client_num = [];
$client_dates = [];
$client_data = $conn->query("SELECT COUNT(id) as num, CreatedBy as CreatedBy FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isClient = '' $HAS_CREATEDBY GROUP BY CreatedBy");
while ($row = $client_data->fetch(PDO::FETCH_ASSOC)) {
    $client_num[] = ['num' => $row['num'], 'CreatedBy' => CreatedBy($row['CreatedBy'])];
}

$client_creators = array_column($client_num, 'CreatedBy');
$client_numbers = array_column($client_num, 'num');

$candidate_num = [];
$candidate_dates = [];
$candidate_data = $conn->query("SELECT COUNT(id) as num, CreatedBy as CreatedBy FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' $HAS_CREATEDBY GROUP BY CreatedBy");

while ($row = $candidate_data->fetch(PDO::FETCH_ASSOC)) {
    $candidate_num[] = ['num' => $row['num'], 'CreatedBy' => CreatedBy($row['CreatedBy'])];
}

$candidate_creators = array_column($candidate_num, 'CreatedBy');
$candidate_numbers = array_column($candidate_num, 'num');

$candidate_status_num = [];
$candidate_status_data = $conn->query("SELECT COUNT(id) as num, Status as status FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' $HAS_CREATEDBY GROUP BY Status");

while ($row = $candidate_status_data->fetch(PDO::FETCH_ASSOC)) {
    $candidate_status_num[] = ['num' => floatval($row['num']), 'status' => $row['status']];
}

$candidate_statuses = array_column($candidate_status_num, 'status');
$candidate_status_numbers = array_column($candidate_status_num, 'num');

?>
<div class="row">
    <div class="col-md-6 col-xxl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Clients</h5>
                </div>
                <div class="my-3">
                    <div id="Clients-data" style="min-height: 80px;"></div>
                    <h3 class="text-center mt-3"><?php echo $TotalClients; ?> <small style="font-size: 13px;" class="text-primary">Total Clients</small></h3>
                    <h3 class="text-center mt-3"><?php echo $TotalClientsThisWeek; ?> <small style="font-size: 13px;" class="text-primary">Total <?php echo ($TotalClientsThisWeek == 1) ? 'client' : 'clients'; ?> this Week</small></h3>
                </div>
                <div class="d-grid"><a class="btn btn-link-primary" role="button" href="<?php echo $LINK; ?>/clients">View All</a></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xxl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Candidates</h5>
                </div>
                <div class="my-3">
                    <div id="candidate-data" style="min-height: 80px;"></div>
                    <h3 class="text-center mt-3"><?php echo $TotalCandidates; ?> <small style="font-size: 13px;" class="text-primary">Total Candidates</small></h3>
                    <h3 class="text-center mt-3"><?php echo $TotalCandidatesThisWeek; ?> <small style="font-size: 13px;" class="text-primary">Total <?php echo ($TotalCandidatesThisWeek == 1) ? 'candidate' : 'candidates'; ?> this week</small></h3>
                </div>
                <div class="d-grid"><a class="btn btn-link-primary" role="button" href="<?php echo $LINK; ?>/candidates">View All</a></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xxl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Candidates Status</h5>
                </div>
                <div class="my-3">
                    <div id="candidate-status-data" style="min-height: 80px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12">
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
                    <h3 class="mt-3" style="margin-left: 10px;"><?php echo $TotalShiftsThisWeek; ?> <small style="font-size: 13px;" class="text-primary">Total <?php echo ($TotalShiftsThisWeek == 1) ? 'shift ' : 'shifts '; ?>this week</small></h3>
                    <div class="card">

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="analytics-tab-1-pane" role="tabpanel" aria-labelledby="analytics-tab-1" tabindex="0">
                                <ul class="list-group list-group-flush">
                                    <?php
                                    $query = "SELECT * FROM _shifts WHERE ClientKeyID = '$ClientKeyID' AND ShiftDate BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY";
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
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12">
        <div class="card table-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Timesheets</h5>
                    <a href="<?php echo $LINK; ?>/timesheets">
                        <button class="btn btn-sm btn-link-primary">View All</button>
                    </a>
                </div>
            </div>
            <div class="card-body pb-0" style="height: 430px; overflow-y: scroll; padding: 10px;">
                <div class="table-responsive">
                    <h3 class="mt-3" style="margin-left: 10px;"><?php echo $TotalTimesheetsThisWeek; ?> <small style="font-size: 13px;" class="text-primary">Total <?php echo ($TotalTimesheetsThisWeek == 1) ? 'timesheet ' : 'timesheets '; ?>this week</small></h3>
                    <div class="card">

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="analytics-tab-1-pane" role="tabpanel" aria-labelledby="analytics-tab-1" tabindex="0">
                                <ul class="list-group list-group-flush">
                                    <?php
                                    $n = 1;
                                    $_timesheet_query = "SELECT * FROM `_timesheet` WHERE (ClientKeyID = '$ClientKeyID') AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY";
                                    $_timesheet_stmt = $conn->prepare($_timesheet_query);
                                    $_timesheet_stmt->execute();
                                    while ($row = $_timesheet_stmt->fetchObject()) { ?>
                                        <?php
                                        $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                                        $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                        $__time_sheets_data = $conn->query("SELECT * FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchObject();
                                        $TOTAL_HOURS = $conn->query("SELECT SUM(Hours) AS Hours FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                                        $CandidateData = $conn->query("SELECT Name, ProfileImage FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}' ")->fetchObject();
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
                                                            <p class="text-muted mb-0"><small> <?php echo $ClientName; ?> / <?php echo $BranchName; ?></small></p>
                                                        </div>
                                                        <div class="col-6 text-end">
                                                            <?php if ($__time_sheets_data->Date == date("Y-m-d")) : ?>
                                                                <h6 class="mb-1 badge bg-success">Today</h6>
                                                            <?php else : ?>
                                                                <h6 class="mb-1"><?php echo FormatDate($__time_sheets_data->Date); ?></h6>
                                                            <?php endif; ?>
                                                            <p class="mb-0"> <?php echo $TOTAL_HOURS; ?> <?php echo ($TOTAL_HOURS == 1) ? 'hr' : 'hrs'; ?> <?php if ($row->isApproved == "Approved") : ?>
                                                                    <span class="badge bg-success">Approved</span>
                                                                <?php else : ?>
                                                                    <span class="badge bg-danger">Pending</span>
                                                                <?php endif; ?>
                                                            </p>
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
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var CLIENTS_OPTIONS = {
        series: [{
            name: 'Total Clients',
            data: <?php echo json_encode($client_numbers); ?>
        }],
        chart: {
            height: 110,
            type: 'bar',
            toolbar: {
                show: false,
            },
            sparkline: {
                enabled: true
            }
        },
        grid: {
            show: false,
            padding: {
                left: 0,
                right: 0
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: <?php echo json_encode($client_creators); ?>,
            lines: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            labels: {
                show: true,
            },
        },
        yaxis: [{
            y: 0,
            offsetX: 0,
            offsetY: 0,
            labels: {
                show: false,
            },
            padding: {
                left: 0,
                right: 0
            },
        }],
        tooltip: {
            x: {
                show: true,
            },
        },
    };

    var CANDIDATE_OPTIONS = {
        series: [{
            name: 'Total Candidates',
            data: <?php echo json_encode($candidate_numbers); ?>
        }],
        chart: {
            height: 110,
            type: 'bar',
            toolbar: {
                show: false,
            },
            sparkline: {
                enabled: true
            }
        },
        grid: {
            show: false,
            padding: {
                left: 0,
                right: 0
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: <?php echo json_encode($candidate_creators); ?>,
            lines: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            labels: {
                show: true,
            },
        },
        yaxis: [{
            y: 0,
            offsetX: 0,
            offsetY: 0,
            labels: {
                show: false,
            },
            padding: {
                left: 0,
                right: 0
            },
        }],
        tooltip: {
            x: {
                show: true,
            },
        },
    };
    // Define colors based on status
    var statusColors = {
        "Active": "#28a745", // green
        "Archived": "#ffc107", // yellow
        "Inactive": "#dc3545", // red
        "Pending Compliance": "#007bff" // blue
    };

    var colors = <?php echo json_encode($candidate_statuses); ?>.map(status => statusColors[status]);

    var options = {
        series: <?php echo json_encode($candidate_status_numbers); ?>,
        chart: {
            width: 420,
            type: 'pie',
        },
        labels: <?php echo json_encode($candidate_statuses); ?>,
        colors: colors,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#candidate-status-data"), options);
    chart.render();


    var CLIENT_CHART = new ApexCharts(document.querySelector("#Clients-data"), CLIENTS_OPTIONS);
    CLIENT_CHART.render();


    var CANDIDATE_CHART = new ApexCharts(document.querySelector("#candidate-data"), CANDIDATE_OPTIONS);
    CANDIDATE_CHART.render();
</script>