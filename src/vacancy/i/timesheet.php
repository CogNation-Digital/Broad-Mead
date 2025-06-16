<?php if ($isTab == "Timesheet") : ?>
    <div id="Timesheets">

        <div class="row" style="margin-top: 10px;">
            <div class="col-12">
                <div class="card shadow-none border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">

                            <div class="flex-grow-1 ">
                                <h6 class="mb-0">Timesheets</h6>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#AddCandidate">
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                </svg>
                                            </span>
                                            Create
                                        </a>
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
                                            View</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                            <span class="text-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                    <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                </svg>
                                            </span>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
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
                                        <th>Timesheet ID</th>
                                        <th>Client</th>
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
                                    $_timesheet_query = "SELECT * FROM `_timesheet` WHERE VacancyID = '$VacancyID'  AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate'";
                                    $_timesheet_stmt = $conn->prepare($_timesheet_query);
                                    $_timesheet_stmt->execute();
                                    while ($row = $_timesheet_stmt->fetchObject()) { ?>
                                        <?php
                                        $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                        $TOTAL_HOURS = $conn->query("SELECT SUM(Hours) AS Hours FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                                        $TOTAL_MARGIN = $conn->query("SELECT SUM(Margin * Hours) FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                                        $TOTAL_PAYRATE = $conn->query("SELECT SUM(PayRate * Hours) FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                                        $TOTAL_SUPPLY = $conn->query("SELECT SUM(SupplyRate * Hours) FROM `__time_sheets` WHERE TimesheetID = '{$row->TimesheetID}' ")->fetchColumn();
                                        $CandidateName = $conn->query("SELECT Name FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}' ")->fetchColumn();


                                        ?>
                                        <tr>
                                            <td><?php echo $n++; ?></td>
                                            <td><input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->TimesheetID; ?>" id="flexCheckDefault<?php echo $row->id; ?>"></td>
                                            <td><?php echo $row->TimesheetNo; ?></td>
                                            <td><?php echo $ClientName; ?></td>
                                            <td><?php echo (empty($BranchName)) ? NoData() : $BranchName; ?></td>
                                            <td>
                                                <?php

                                                echo $CandidateName; ?>
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
                            <?php
                            if ($_timesheet_stmt->rowCount() == 0) {
                                echo '<div class="alert alert-danger" role="alert">No timesheet found.</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="AddCandidate" class="modal fade" tabindex="-1" aria-labelledby="AddCandidateLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddCandidateLabel">Candidate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Candidate</label>
                                    <select name="candidate" class="select-input">
                                        <?php
                                        $_query = "SELECT * FROM `__vacancy_candidates` WHERE ClientKeyID = '$ClientKeyID' AND VacancyID = '$VacancyID'";
                                        $result = $conn->query($_query);
                                        while ($candidate_row = $result->fetchObject()) {
                                            $CandidateName = $conn->query("SELECT Name FROM `_candidates` WHERE CandidateID = '{$candidate_row->CandidateID}' ")->fetchColumn();
                                        ?>
                                            <option value="<?php echo $candidate_row->CandidateID; ?>"><?php echo $CandidateName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Period</label>
                                    <input type="text" class="form-control text-left" name="Period" id="PeriodRange" value="<?php echo FormatDate($FromDate) . ' to ' . FormatDate($ToDate); ?>">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Timesheet ID</label>
                                    <input type="text" class="form-control text-left" maxlength="6" name="TimesheetID" value="<?php echo rand(); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="GenerateTimesheet" class="btn btn-primary">Next</button>
                    </div>
                </form>
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
                    <p>Are you sure you want to remove? This action cannot be undone.</p>
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

    <script>
        function populateCandidateDetails() {
            var candidateSelect = document.getElementById('candidateSelect');
            var selectedOption = candidateSelect.options[candidateSelect.selectedIndex];

            var email = selectedOption.getAttribute('data-email');
            var number = selectedOption.getAttribute('data-number');

            document.getElementById('Email').value = email;
            document.getElementById('Phone').value = number;
        }

        document.getElementById('selectAll').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item');
            let allChecked = this.classList.toggle('checked');

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = allChecked;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {


            $("#View").click(function() {
                let checkboxes = document.querySelectorAll('.checkbox-item:checked');
                // if no checkboxes are selected alert
                if (checkboxes.length == 0) {
                    ShowToast('Error 102: Something went wrong.');
                    return;
                } else {
                    let id = checkboxes[0].value;
                    window.location.href = "<?php echo $LINK; ?>/generate_timesheet/?ID=" + id + "&isTab=Details"
                }
            })

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
                                    DeleteTimesheet: true
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
                                    // Optionally, handle the error case if needed
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


        });
    </script>
<?php endif; ?>