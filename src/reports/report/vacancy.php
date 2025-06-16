<div class="card">
    <div class="card-header">
        Vacancies
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                       
                        <th>Client</th>
                        <th>Branch</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Number of roles</th>
                        <th>Candidates</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $query = "SELECT * FROM `vacancies` WHERE ClientKeyID = '$ClientKeyID'";
                    if (isset($_GET['q'])) {
                        $SearchID = $_GET['q'];
                        $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                        while ($r = $qu->fetchObject()) {
                            $column = $r->column;
                            $value = $r->value;
                            $query .= " AND " . $column . " LIKE '%$value%'";
                        }
                    }
                    $query .= " AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate'";


                    $query = $conn->prepare($query);
                    $query->execute();
                    while ($row = $query->fetchObject()) { ?>
                        <?php
                        $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                        $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}'")->fetchColumn();
                        $CandidatesNum = $conn->query("SELECT COUNT(id) FROM `__vacancy_candidates` WHERE VacancyID = '{$row->VacancyID}'")->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                             <td><?php echo (!$ClientName) ? NoData() : $ClientName; ?></td>
                            <td><?php echo (!$BranchName) ? NoData() : $BranchName; ?></td>

                            <td>
                                <?php echo $row->Type; ?>
                            </td>
                            <td>
                                <?php echo $row->Title; ?>
                            </td>
                            <td>
                                <?php echo $row->Roles; ?>
                            </td>
                            <td>
                                <?php echo $CandidatesNum; ?>
                            </td>
                            <td>
                                From <?php echo FormatDate($row->StartDate); ?> to <?php echo FormatDate($row->EndDate); ?>
                            </td>

                            <td>

                                <?php
                                $currentDate = new DateTime();
                                $startDate = new DateTime($row->StartDate);
                                $endDate = new DateTime($row->EndDate);

                                if ($currentDate >= $startDate && $currentDate <= $endDate) {
                                    $status = "Active";
                                } elseif ($currentDate > $endDate) {
                                    $status = "Not Active";
                                } else {
                                    $status = "Not Active";
                                }
                                ?>

                                <?php if ($status == "Active") : ?>
                                    <div class="badge bg-success"><?php echo $status; ?></div>
                                <?php else : ?>
                                    <div class="badge bg-danger">
                                        <?php echo $status; ?>
                                    </div>
                                <?php endif; ?>
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
        <?php if ($query->rowCount() == 0) : ?>
            <div class="alert alert-danger">
                No data found. Please try again with different search criteria.
            </div>
        <?php endif; ?>
    </div>
</div>