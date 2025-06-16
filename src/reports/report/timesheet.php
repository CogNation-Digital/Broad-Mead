<div class="card">
    <div class="card-header">
        Timesheets
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Timesheet ID</th>
                        <th>CLient</th>
                        <th>Branch</th>
                        <th>Candidate</th>
                        <th width="15%">Status</th>
                        <th>Total Hours</th>
                        <th width="12%">Total Margin</th>
                        <th width="12%">Total Pay Rate</th>
                        <th width="12%">Total Supply</th>
                        <th width="12%">Created by</th>
                        <th width="12%">date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $_timesheet_query = "SELECT * FROM `_timesheet` WHERE (ClientKeyID = '$ClientKeyID') $HAS_CREATEDBY AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate'";
                    $_timesheet_stmt = $conn->prepare($_timesheet_query);
                    $_timesheet_stmt->execute();
                    while ($row = $_timesheet_stmt->fetchObject()) { ?>
                        <?php
                        $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                        $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                        $TOTAL_HOURS = $conn->query("SELECT SUM(Hours) AS Hours FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                        $TOTAL_MARGIN = $conn->query("SELECT SUM(Margin * Hours) FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                        $TOTAL_PAYRATE = $conn->query("SELECT SUM(PayRate * Hours) FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                        $TOTAL_SUPPLY = $conn->query("SELECT SUM(SupplyRate * Hours) FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                        $CandidateData = $conn->query("SELECT Name, ProfileImage FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}' ")->fetchObject();


                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td><?php echo $row->TimesheetNo; ?></td>
                            <td><?php echo (!$ClientName) ? NoData() : $ClientName; ?></td>
                            <td><?php echo (!$BranchName) ? NoData() : $BranchName; ?></td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0"><img width="40" height="40" style="object-fit: cover;" src="<?php echo $CandidateData->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40"></div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0"> <?php echo $CandidateData->Name; ?></h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($row->isApproved == "Approved") : ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php else : ?>
                                    <span class="badge bg-danger">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo number_format($TOTAL_HOURS, 2); ?> <?php echo ($TOTAL_HOURS == 1) ? 'hr' : 'hrs'; ?>
                            </td>

                            <td>
                                <?php echo $Currency; ?> <?php echo number_format($TOTAL_MARGIN, 2); ?>
                            </td>

                            <td>
                                <?php echo $Currency; ?> <?php echo number_format($TOTAL_PAYRATE, 2); ?>
                            </td>

                            <td>
                                <?php echo $Currency; ?> <?php echo number_format($TOTAL_SUPPLY, 2); ?>
                            </td>

                            <td>
                                <?php echo $CreatedBy; ?>
                            </td>
                            <td>
                                <?php echo FormatDate($row->Date); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php if ($_timesheet_stmt->rowCount() == 0) : ?>
            <div class="alert alert-danger">
                No data found. Please try again with different search criteria.
            </div>
        <?php endif; ?>
    </div>
</div>