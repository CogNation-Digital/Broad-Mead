<div class="card">
    <div class="card-header">
        Shifts
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th> 
                        <th>Client</th>
                        <th>Branch</th>
                        <th>Candidate</th>

                        <th width="15%">Shift Type</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Hours</th>
                        <th width="12%">Pay Rate</th>
                        <th width="12%">Supplier Rate</th>
                        <th width="12%">Margin</th>
                        <th width="12%">Total Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $__shifts__query = "SELECT * FROM `_shifts` WHERE (ClientKeyID = '$ClientKeyID') AND ShiftDate BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY";
                    $__shifts__stmt = $conn->prepare($__shifts__query);
                    $__shifts__stmt->execute();
                    while ($row = $__shifts__stmt->fetchObject()) { ?>
                        <?php
                        $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                        $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                        $CandidateData = $conn->query("SELECT Name, ProfileImage FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}'")->fetchObject();

                        ?>
                        <tr data-id="<?php echo $row->ShiftID; ?>">
                            <td><?php echo $row->id; ?></td>
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
                            <td width="15%">
                                <?php echo $row->Type; ?>
                            </td>
                            <td>
                                <?php echo FormatDate($row->ShiftDate); ?>
                                <?php if ($row->ShiftDate == date("Y-m-d")) : ?>
                                    <div class="badge bg-success">Today</div>
                                <?php endif; ?>
                            </td>
                            <td><span class="shift-starttime"><?php echo $row->StartTime; ?></span></td>
                            <td><span class="shift-endtime"><?php echo $row->EndTime; ?></span></td>
                            <td><span class="shift-hours" style="width: 80px;"><?php echo $row->Hours; ?></span></td>
                            <td width="12%"><span class="shift-payrate"><?php echo $Currency; ?><?php echo number_format((float)$row->PayRate, 2); ?></span></td>
                            <td width="12%"><span class="shift-supplyrate"><?php echo $Currency; ?><?php echo number_format((float)$row->SupplyRate, 2); ?></span></td>
                            <td width="12%"><span style="width: 100px;" class="shift-margin"><?php echo $Currency; ?><?php echo number_format((float)$row->SupplyRate - (float)$row->PayRate, 2); ?></span></td>
                            <td width="12%"><span style="width: 100px;" class="shift-totalmargin"><?php echo $Currency; ?><?php echo number_format((float)$row->Hours * ((float)$row->SupplyRate - (float)$row->PayRate), 2); ?></span></td>


                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
        <?php if ($__shifts__stmt->rowCount() == 0) : ?>
            <div class="alert alert-danger">
                No data found. Please try again with different search criteria.
            </div>
        <?php endif; ?>
    </div>
</div>