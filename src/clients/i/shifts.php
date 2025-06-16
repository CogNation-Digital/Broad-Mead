<?php if ($isTab == "Shifts") : ?>
    <div class="card-body table-border-style">
        <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Shifts</h5>
            <div class="dropdown">
                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#CreateModal">
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
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th style="display: none;">
                            <span id="selectAll" style="cursor: pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                </svg>
                            </span>
                        </th>
                        <?php if (!isset($_GET['isBranch'])) : ?>
                            <th>Branch</th>
                        <?php endif; ?>
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
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $__shifts__query = "SELECT * FROM `_shifts` WHERE (hasClientID = '$ClientID' OR hasBranchID = '$ClientID') AND ShiftDate BETWEEN '$FromDate' AND '$ToDate'";
                    $__shifts__stmt = $conn->prepare($__shifts__query);
                    $__shifts__stmt->execute();
                    while ($row = $__shifts__stmt->fetchObject()) { ?>
                        <?php
                        $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                        ?>
                        <tr data-id="<?php echo $row->ShiftID; ?>">
                            <td><?php echo $n++; ?></td>
                            <td style="display: none;"><input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->ShiftID; ?>" VacancyID="<?php echo $row->VacancyID; ?>" id="flexCheckDefault<?php echo $row->id; ?>"></td>
                            <?php if (!isset($_GET['isBranch'])) : ?>
                                <td><?php echo (!$BranchName) ? $ClientData->Name : $BranchName; ?></td>
                            <?php endif; ?>
                            <td>
                                <?php
                                $CandidateName = $conn->query("SELECT Name FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}' ")->fetchColumn();

                                echo $CandidateName; ?>
                            </td>
                            <td width="15%">
                                <?php echo $row->Type; ?>
                            </td>
                            <td>
                                <?php echo FormatDate($row->ShiftDate); ?>
                            </td>
                            <td><span class="shift-starttime"><?php echo $row->StartTime; ?></span></td>
                            <td><span class="shift-endtime"><?php echo $row->EndTime; ?></span></td>
                            <td><span class="shift-hours" style="width: 80px;"><?php echo $row->Hours; ?></span></td>
                            <td width="12%"><span class="shift-payrate"><?php echo $row->PayRate; ?></span></td>
                            <td width="12%"><span class="shift-supplyrate"><?php echo $row->SupplyRate; ?></span></td>
                            <td width="12%"><span style="width: 100px;" class="shift-margin"><?php echo (float)$row->SupplyRate - (float)$row->PayRate; ?></span></td>
                            <td width="12%"><span style="width: 100px;" class="shift-totalmargin"><?php echo (float)$row->Hours * ((float)$row->SupplyRate - (float)$row->PayRate); ?></span></td>
                            <td>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?php echo $LINK ?>/view_vacancy/?VacancyID=<?php echo $row->VacancyID; ?>&isTab=Shifts" id="Edit">
                                            <span class="text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                </svg>
                                            </span>
                                            Edit</a>
                                        <?php if (IsCheckPermission($USERID, "DELETE_SHIFT")) : ?>
                                            <a class="dropdown-item select-entry-row" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                <span class="text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                        <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                    </svg>
                                                </span>
                                                Delete</a>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php
            if ($__shifts__stmt->rowCount() == 0) {
                echo '<div class="alert alert-danger" role="alert">No shifts found.</div>';
            }
            ?>
        </div>
    </div>

    <div id="CreateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreateModalLabel">Create Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (IsCheckPermission($USERID, "CREATE_SHIFT")) : ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="email">Vacancy</label>
                                <select name="VacancyID" class="form-control">
                                    <?php
                                    $query = "SELECT * FROM `vacancies` WHERE hasClientID = '$ClientID' OR hasBranchID = '$ClientID' ";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetchObject()) {
                                        echo '<option value="' . $row->VacancyID . '">' . $row->Title . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="CreateShift" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                <?php else : ?>
                    <div style="padding: 10px;">
                        <?php DeniedAccess(); ?>
                    </div>
                <?php endif; ?>
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
                    <button type="button" class="btn btn-danger" id="ConfirmDeleteShift">Confirm</button>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>