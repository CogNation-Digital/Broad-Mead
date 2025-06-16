<?php if ($isTab == "Shifts") : ?>
    <?php
    if (isset($_GET['hasCandidateID'])) {
        $CandidateID = $_GET['hasCandidateID'];

        // Check if the CandidateID already exists in the __vacancy_candidates table
        $check = $conn->query("SELECT * FROM `__vacancy_candidates` WHERE CandidateID = '$CandidateID'");

        if ($check->rowCount() == 0) {
            // Prepare the INSERT query with placeholders
            $query = "INSERT INTO `__vacancy_candidates` (`ClientKeyID`, `VacancyID`, `CandidateID`, `CreatedBy`, `Date`) 
                  VALUES (:ClientKeyID, :VacancyID, :CandidateID, :CreatedBy, NOW())";

            // Prepare the statement
            $stmt = $conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':ClientKeyID', $ClientKeyID);
            $stmt->bindParam(':VacancyID', $VacancyID);
            $stmt->bindParam(':CandidateID', $CandidateID);
            $stmt->bindParam(':CreatedBy', $USERID);

            // Execute the statement
            $stmt->execute();
        }
    }

    ?>
    <div id="Shifts">
        <?php if (IsCheckPermission($USERID, "CREATE_SHIFT")) : ?>
            <div class="row" style="margin-top: 10px;">
                <div class="col-12">
                    <div class="card shadow-none border mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1 ">
                                    <h6 class="mb-0">Shifts</h6>
                                </div>
                                <div class="flex-shrink-0">
                                    <form method="POST">
                                        <button name="AddShift" class="btn  btn-primary"><i class="ti ti-plus" style="margin-top: 10px;"></i> Add </button>
                                    </form>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive dt-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SN</th>
                                                <th style="display: none;">
                                                    <span id="selectAll" style="cursor: pointer;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                        </svg>
                                                    </span>
                                                </th>
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
                                                <th width="12%">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $n = 1;
                                            $__shifts__query = "SELECT * FROM `_shifts` WHERE VacancyID = '$VacancyID' AND (ShiftDate BETWEEN '$FromDate' AND '$ToDate' OR ShiftDate = '') ";
                                            if (isset($_GET['hasCandidateID'])) {
                                                $__shifts__query .= "AND CandidateID = '{$_GET['hasCandidateID']}' OR CandidateID = ''  ";
                                            }
                                            $__shifts__stmt = $conn->prepare($__shifts__query);
                                            $__shifts__stmt->execute();
                                            while ($row = $__shifts__stmt->fetchObject()) { ?>
                                                <tr data-id="<?php echo $row->ShiftID; ?>">
                                                    <td><?php echo $n++; ?></td>
                                                    <td style="display: none;"><input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->ShiftID; ?>" id="flexCheckDefault<?php echo $row->id; ?>"></td>
                                                    <td>
                                                        <select style="width: 200px;" name="candidate" class="form-control candidate-select">
                                                            <?php if (isset($_GET['hasCandidateID'])): ?>
                                                                <?php
                                                                $CandidateID = $_GET['hasCandidateID'];
                                                                $_query = "SELECT * FROM `__vacancy_candidates` WHERE ClientKeyID = '$ClientKeyID' AND VacancyID = '$VacancyID' AND  CandidateID = '$CandidateID'";
                                                                $result = $conn->query($_query);
                                                                while ($candidate_row = $result->fetchObject()) {
                                                                    $CandidateName = $conn->query("SELECT Name FROM `_candidates` WHERE CandidateID = '{$candidate_row->CandidateID}' ")->fetchColumn();
                                                                ?>
                                                                    <option value="<?php echo $candidate_row->CandidateID; ?>"><?php echo $CandidateName; ?></option>
                                                                <?php } ?>
                                                            <?php else: ?>
                                                                <?php
                                                                $_query = "SELECT * FROM `__vacancy_candidates` WHERE ClientKeyID = '$ClientKeyID' AND VacancyID = '$VacancyID'";
                                                                $result = $conn->query($_query);
                                                                while ($candidate_row = $result->fetchObject()) {
                                                                    $CandidateName = $conn->query("SELECT Name FROM `_candidates` WHERE CandidateID = '{$candidate_row->CandidateID}' ")->fetchColumn();
                                                                ?>
                                                                    <option <?php echo ($row->CandidateID == $candidate_row->CandidateID) ? "selected" : ""; ?> value="<?php echo $candidate_row->CandidateID; ?>"><?php echo $CandidateName; ?></option>
                                                                <?php } ?>
                                                            <?php endif; ?>

                                                        </select>
                                                    </td>
                                                    <td width="15%">
                                                        <select style="width: 150px;" class="form-control shift-type" autocomplete="off">
                                                            <option value=""></option>
                                                            <?php
                                                            $__shifts__ = $conn->query("SELECT * FROM `__shifts__` WHERE VacancyID = '$VacancyID' GROUP BY `ShiftType`");
                                                            while ($__shifts__row = $__shifts__->fetchObject()) { ?>
                                                                <option <?php echo ($__shifts__row->ShiftType == $row->Type) ? 'selected' : ''; ?> data-starttime="<?php echo $__shifts__row->StartTime; ?>" data-endtime="<?php echo $__shifts__row->EndTime; ?>" data-payrate="<?php echo $__shifts__row->PayRate; ?>" data-supplyrate="<?php echo $__shifts__row->SupplyRate; ?>">
                                                                    <?php echo $__shifts__row->ShiftType; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control shift-date" value="<?php echo $row->ShiftDate; ?>" autocomplete="off">
                                                    </td>
                                                    <td><input type="time" class="form-control shift-starttime" value="<?php echo $row->StartTime; ?>" autocomplete="off"></td>
                                                    <td><input type="time" class="form-control shift-endtime" value="<?php echo $row->EndTime; ?>" autocomplete="off"></td>
                                                    <td><input type="text" class="form-control shift-hours" style="width: 80px;" value="<?php echo $row->Hours; ?>" autocomplete="off"></td>
                                                    <td width="12%"><input type="text" class="form-control shift-payrate" value="<?php echo $row->PayRate; ?>" autocomplete="off"></td>
                                                    <td width="12%"><input type="text" class="form-control shift-supplyrate" value="<?php echo $row->SupplyRate; ?>" autocomplete="off"></td>
                                                    <td width="12%"><input style="width: 100px;" readonly type="text" class="form-control shift-margin" value="<?php echo  (float)$row->SupplyRate - (float)$row->PayRate; ?>" autocomplete="off"></td>
                                                    <td width="12%"><input style="width: 100px;" readonly type="text" class="form-control shift-totalmargin" value="<?php echo (float)$row->Hours * ((float)$row->SupplyRate - (float)$row->PayRate); ?>" autocomplete="off"></td>
                                                    <td>
                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#DeleteModal" class="btn btn-danger select-entry-row"><i class="ti ti-trash"></i> Delete</button>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

                                    </table>

                                </div>


                                <?php if ($__shifts__stmt->rowCount() == 0) : ?>
                                    <div class="alert alert-danger">
                                        No data found.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <?php DeniedAccess(); ?>
        <?php endif; ?>

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
                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.shift-type').forEach(function(select) {
            select.addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var row = this.closest('tr');

                var startTime = selectedOption.getAttribute('data-starttime');
                var endTime = selectedOption.getAttribute('data-endtime');
                var payRate = selectedOption.getAttribute('data-payrate');
                var supplyRate = selectedOption.getAttribute('data-supplyrate');

                row.querySelector('.shift-starttime').value = startTime;
                row.querySelector('.shift-endtime').value = endTime;
                row.querySelector('.shift-payrate').value = payRate;
                row.querySelector('.shift-supplyrate').value = supplyRate;
                row.querySelector('.shift-margin').value = (parseFloat(supplyRate) - parseFloat(payRate)).toFixed(2);
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            var rows = document.querySelectorAll('tbody tr');

            rows.forEach(function(row) {
                var shiftTypeSelect = row.querySelector('.shift-type');
                var startTimeInput = row.querySelector('.shift-starttime');
                var endTimeInput = row.querySelector('.shift-endtime');
                var hoursInput = row.querySelector('.shift-hours');
                var payRateInput = row.querySelector('.shift-payrate');
                var supplyRateInput = row.querySelector('.shift-supplyrate');
                var marginInput = row.querySelector('.shift-margin');
                var totalMarginInput = row.querySelector('.shift-totalmargin');
                var candidateSelect = row.querySelector('.candidate-select');
                var shiftDateInput = row.querySelector('.shift-date');

                // Event listeners for change in inputs
                [shiftTypeSelect, startTimeInput, endTimeInput, hoursInput, payRateInput, supplyRateInput, candidateSelect, shiftDateInput].forEach(function(element) {
                    element.addEventListener('change', function() {
                        updateShiftData(row);
                    });
                });

                // Event listener for keyup on hoursInput
                hoursInput.addEventListener('keyup', function() {
                    if (startTimeInput.value !== '' && /^\d*\.?\d*$/.test(this.value)) {
                        var start = new Date('1970-01-01T' + startTimeInput.value);
                        var hoursNum = parseFloat(this.value);
                        var endTimeDate = new Date(start.getTime() + hoursNum * 60 * 60 * 1000);
                        endTimeInput.value = endTimeDate.toTimeString().slice(0, 5); // Format as HH:MM
                    } else {
                        this.value = ''; // Clear non-numeric or invalid input
                    }
                    updateShiftData(row); // Update shift data after calculating EndTime and Hours
                });

                function updateShiftData(row) {
                    var dataId = row.getAttribute('data-id');
                    var shiftType = shiftTypeSelect.value;
                    var startTime = startTimeInput.value;
                    var endTime = endTimeInput.value;
                    var hours = hoursInput.value.trim();
                    var payRate = payRateInput.value;
                    var supplyRate = supplyRateInput.value;

                    // Calculate hours if start and end time are provided
                    if (startTime !== '' && endTime !== '') {
                        var startDateTime = new Date('1970-01-01T' + startTime);
                        var endDateTime = new Date('1970-01-01T' + endTime);
                        var timeDifference = (endDateTime - startDateTime) / (1000 * 60 * 60); // Difference in hours
                        hoursInput.value = timeDifference.toFixed(2); // Display hours with two decimals
                        hours = timeDifference.toFixed(2); // Update hours variable
                    }

                    // Validate and calculate margin
                    var margin = parseFloat(supplyRate) - parseFloat(payRate);
                    marginInput.value = margin.toFixed(2); // Display margin with two decimals

                    // Validate and calculate total margin
                    var totalMargin = parseFloat(hours) * parseFloat(margin);
                    totalMarginInput.value = totalMargin.toFixed(2); // Display total margin with two decimals

                    // Prepare data to send via XHR
                    var postData = {
                        UpdateShift: true,
                        id: dataId,
                        ShiftType: shiftType,
                        StartTime: startTime,
                        EndTime: endTime,
                        Hours: hours,
                        PayRate: payRate,
                        SupplyRate: supplyRate,
                        Margin: margin,
                        TotalMargin: totalMargin,
                        Candidate: candidateSelect.value,
                        ShiftDate: shiftDateInput.value
                    };

                    // Send data via XHR
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', window.location.href);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            ShowToast('Shift updated successfully');
                            // Optionally handle success response
                        } else {
                            ShowToast('Failed to update shift');
                            // Optionally handle error response
                        }
                    };
                    xhr.onerror = function() {
                        ShowToast('Error occurred while updating shift');
                        // Optionally handle XHR error
                    };
                    xhr.send(JSON.stringify(postData));
                }
            });
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item:checked');
            let ids = [];
            let successCount = 0;

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
                            Delete_Shift: true
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
        });
    </script>
<?php endif; ?>