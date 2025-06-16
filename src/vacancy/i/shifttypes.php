<?php if ($isTab == "ShiftTypes") : ?>
    <div id="ShiftTypes">

        <div class="row" style="margin-top: 10px;">
            <div class="col-12">
                <div class="card shadow-none border mb-0">
                    <div class="card-body">
                        <h6 class="mb-3">Shift Types</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Shift</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Pay Rate</th>
                                        <th>Supply Rate</th>
                                        <th>Total margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $n = 1;
                                    $query = "SELECT * FROM `__shifts__` WHERE VacancyID = '$VacancyID' ORDER BY id DESC ";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetchObject()) { ?>
                                        <tr>
                                            <td><?php echo $n++; ?></td>
                                            <td><?php echo empty($row->ShiftType) ? "No data" : $row->ShiftType ; ?></td>
                                            <td><?php echo empty($row->StartTime) ? "No data" : $row->StartTime ; ?></td>
                                            <td><?php echo empty($row->EndTime) ? "No data" : $row->EndTime ; ?></td> 
                                            <td><?php echo $row->PayRate; ?></td>
                                            <td><?php echo $row->SupplyRate; ?></td>
                                            <td>
                                                <?php
                                                $TotalMargin = (float)$row->SupplyRate - (float)$row->PayRate;
                                                echo $TotalMargin;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>