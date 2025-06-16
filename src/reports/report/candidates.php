<div class="row">
    <?php
    // Query the total number of candidates
    $total_candidates_query = $conn->query("SELECT COUNT(*) FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY");
    $total_candidates = $total_candidates_query->fetchColumn();

    // Loop through each candidate status
    foreach ($candidate_status as $status) {
        // Default classes and icon
        $bgClass = 'bg-light-primary';
        $textClass = 'text-primary';
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M13.07 10.41a5 5 0 0 0 0-5.82A3.4 3.4 0 0 1 15 4a3.5 3.5 0 0 1 0 7a3.4 3.4 0 0 1-1.93-.59M5.5 7.5A3.5 3.5 0 1 1 9 11a3.5 3.5 0 0 1-3.5-3.5m2 0A1.5 1.5 0 1 0 9 6a1.5 1.5 0 0 0-1.5 1.5M16 17v2H2v-2s0-4 7-4s7 4 7 4m-2 0c-.14-.78-1.33-2-5-2s-4.93 1.31-5 2m11.95-4A5.32 5.32 0 0 1 18 17v2h4v-2s0-3.63-6.06-4Z"/></svg>';

        // Adjust classes and icon based on status
        switch ($status) {
            case 'Active':
                $bgClass = 'bg-light-success';
                $textClass = 'text-success';
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 17v2H7v-2s0-4 6-4s6 4 6 4m-3-9a3 3 0 1 0-3 3a3 3 0 0 0 3-3m3.2 5.06A5.6 5.6 0 0 1 21 17v2h3v-2s0-3.45-4.8-3.94M18 5a2.9 2.9 0 0 0-.89.14a5 5 0 0 1 0 5.72A2.9 2.9 0 0 0 18 11a3 3 0 0 0 0-6M7.34 8.92l1.16 1.41l-4.75 4.75l-2.75-3l1.16-1.16l1.59 1.58z"/></svg>';
                break;
            case 'Archived':
                $bgClass = 'bg-light-warning';
                $textClass = 'text-warning';
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 17v2H7v-2s0-4 6-4s6 4 6 4m-3-9a3 3 0 1 0-3 3a3 3 0 0 0 3-3m3.2 5.06A5.6 5.6 0 0 1 21 17v2h3v-2s0-3.45-4.8-3.94M18 5a2.9 2.9 0 0 0-.89.14a5 5 0 0 1 0 5.72A2.9 2.9 0 0 0 18 11a3 3 0 0 0 0-6M8 10H0v2h8Z"/></svg>';
                break;
            case 'Inactive':
                $bgClass = 'bg-light-danger';
                $textClass = 'text-danger';
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M13.07 10.41a5 5 0 0 0 0-5.82A3.4 3.4 0 0 1 15 4a3.5 3.5 0 0 1 0 7a3.4 3.4 0 0 1-1.93-.59M5.5 7.5A3.5 3.5 0 1 1 9 11a3.5 3.5 0 0 1-3.5-3.5m2 0A1.5 1.5 0 1 0 9 6a1.5 1.5 0 0 0-1.5 1.5M16 17v2H2v-2s0-4 7-4s7 4 7 4m-2 0c-.14-.78-1.33-2-5-2s-4.93 1.31-5 2m11.95-4A5.32 5.32 0 0 1 18 17v2h4v-2s0-3.63-6.06-4Z"/></svg>';
                break;
            case 'Pending Compliance':
                $bgClass = 'bg-light-info';
                $textClass = 'text-info';
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 17v2H7v-2s0-4 6-4s6 4 6 4m-3-9a3 3 0 1 0-3 3a3 3 0 0 0 3-3m3.2 5.06A5.6 5.6 0 0 1 21 17v2h3v-2s0-3.45-4.8-3.94M18 5a2.9 2.9 0 0 0-.89.14a5 5 0 0 1 0 5.72A2.9 2.9 0 0 0 18 11a3 3 0 0 0 0-6M8 10H0v2h8Z"/></svg>';
                break;
        }

        // Get the number of candidates with the current status
        $candidates_count = $conn->query("SELECT COUNT(*) FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' AND Status = '$status' $HAS_CREATEDBY")->fetchColumn();

        // Calculate the percentage
        $percentage = $total_candidates > 0 ? ($candidates_count / $total_candidates) * 100 : 0;
        $percentage = number_format($percentage, 1); // Format to 1 decimal place

        // Determine the CSS class based on the percentage
        $arrowClass = $percentage > 30 ? 'text-success' : 'text-danger';
        $arrowIcon = $percentage > 30 ? '<i class="ti ti-arrow-up-right"></i>' : '<i class="ti ti-arrow-down-left"></i>';
    ?>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s <?php echo $bgClass; ?>">
                                <?php echo $icon; ?>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0"><?php echo $status; ?></h6>
                        </div>
                    </div>
                    <div class="bg-body p-3 mt-3 rounded">
                        <div class="mt-3 row align-items-center">
                            <div class="col-7">
                                <div id="all-earnings-graph" style="min-height: 50px;">
                                    <h2 class="mb-1"><?php echo $candidates_count; ?></h2>
                                </div>
                            </div>
                            <div class="col-5">
                                <p class="<?php echo $arrowClass; ?> mb-0"><?php echo $arrowIcon; ?> <?php echo $percentage; ?>%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>


</div>


<div class="card">
    <div class="card-header">
        Candidates
    </div>
    <div class="card-body">
        <div class="table-responsive dt-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Candidate ID</th>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Email Address </th>
                        <th>Phone Number </th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $query = "SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID'";

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
                    $query .= " ORDER BY id DESC ";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    while ($row = $stmt->fetchObject()) {  ?>
                        <?php
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td><?php echo empty($row->IDNumber) ? str_pad($row->id, 5, '0', STR_PAD_LEFT) : $row->IDNumber; ?></td>
                            <td>
                                <?php if ($row->Status == "Active") : ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else : ?>
                                    <?php if ($row->Status == "Archived") : ?>
                                        <span class="badge bg-warning">Archived</span>

                                    <?php else : ?>
                                        <?php if ($row->Status == "Inactive") : ?>
                                            <span class="badge bg-danger"><?php echo $row->Status; ?></span>
                                        <?php endif; ?>
                                        <?php if ($row->Status == "Pending Compliance") : ?>
                                            <span class="badge bg-info"><?php echo $row->Status; ?></span>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img width="40" height="40" style="object-fit: cover;" src="<?php echo !empty($row->ProfileImage) ? $row->ProfileImage : $ProfilePlaceholder; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40">

                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0"><?php echo $row->Name; ?></h6>
                                    </div>
                                </div>

                            </td>
                            <td><?php echo $row->Email; ?></td>
                            <td><?php echo $row->Number; ?></td>
                            <td><?php echo $CreatedBy; ?></td>
                            <td><?php echo FormatDate($row->Date); ?></td>

                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>

        <?php if ($stmt->rowCount() == 0) : ?>
            <div class="alert alert-danger">
                No data found. Please try again with different search criteria.
            </div>
        <?php endif; ?>
    </div>
</div>