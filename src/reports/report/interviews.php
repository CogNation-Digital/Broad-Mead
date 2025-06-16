<div class="row">
    <?php
    // Define interview statuses
 
    // Define status properties (classes and icons) in an associative array
    $status_properties = [
        'Pending' => [
            'bgClass' => 'bg-light-primary',
            'textClass' => 'text-primary',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M13.07 10.41a5 5 0 0 0 0-5.82A3.4 3.4 0 0 1 15 4a3.5 3.5 0 0 1 0 7a3.4 3.4 0 0 1-1.93-.59M5.5 7.5A3.5 3.5 0 1 1 9 11a3.5 3.5 0 0 1-3.5-3.5m2 0A1.5 1.5 0 1 0 9 6a1.5 1.5 0 0 0-1.5 1.5M16 17v2H2v-2s0-4 7-4s7 4 7 4m-2 0c-.14-.78-1.33-2-5-2s-4.93 1.31-5 2m11.95-4A5.32 5.32 0 0 1 18 17v2h4v-2s0-3.63-6.06-4Z"/></svg>',
        ],
        'Active' => [
            'bgClass' => 'bg-light-success',
            'textClass' => 'text-success',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 17v2H7v-2s0-4 6-4s6 4 6 4m-3-9a3 3 0 1 0-3 3a3 3 0 0 0 3-3m3.2 5.06A5.6 5.6 0 0 1 21 17v2h3v-2s0-3.45-4.8-3.94M18 5a2.9 2.9 0 0 0-.89.14a5 5 0 0 1 0 5.72A2.9 2.9 0 0 0 18 11a3 3 0 0 0 0-6M7.34 8.92l1.16 1.41l-4.75 4.75l-2.75-3l1.16-1.16l1.59 1.58z"/></svg>',
        ],
        'Accepted' => [
            'bgClass' => 'bg-light-info',
            'textClass' => 'text-info',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 17v2H7v-2s0-4 6-4s6 4 6 4m-3-9a3 3 0 1 0-3 3a3 3 0 0 0 3-3m3.2 5.06A5.6 5.6 0 0 1 21 17v2h3v-2s0-3.45-4.8-3.94M18 5a2.9 2.9 0 0 0-.89.14a5 5 0 0 1 0 5.72A2.9 2.9 0 0 0 18 11a3 3 0 0 0 0-6M8 10H0v2h8Z"/></svg>',
        ],
        'Rejected' => [
            'bgClass' => 'bg-light-danger',
            'textClass' => 'text-danger',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M13.07 10.41a5 5 0 0 0 0-5.82A3.4 3.4 0 0 1 15 4a3.5 3.5 0 0 1 0 7a3.4 3.4 0 0 1-1.93-.59M5.5 7.5A3.5 3.5 0 1 1 9 11a3.5 3.5 0 0 1-3.5-3.5m2 0A1.5 1.5 0 1 0 9 6a1.5 1.5 0 0 0-1.5 1.5M16 17v2H2v-2s0-4 7-4s7 4 7 4m-2 0c-.14-.78-1.33-2-5-2s-4.93 1.31-5 2m11.95-4A5.32 5.32 0 0 1 18 17v2h4v-2s0-3.63-6.06-4Z"/></svg>',
        ],
        'Interviewed' => [
            'bgClass' => 'bg-light-warning',
            'textClass' => 'text-warning',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 17v2H7v-2s0-4 6-4s6 4 6 4m-3-9a3 3 0 1 0-3 3a3 3 0 0 0 3-3m3.2 5.06A5.6 5.6 0 0 1 21 17v2h3v-2s0-3.45-4.8-3.94M18 5a2.9 2.9 0 0 0-.89.14a5 5 0 0 1 0 5.72A2.9 2.9 0 0 0 18 11a3 3 0 0 0 0-6M8 10H0v2h8Z"/></svg>',
        ]
    ];

    // Query the total number of interviews
    $total_interviews_query = $conn->query("SELECT COUNT(*) FROM `interviews` WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' $HAS_CREATEDBY");
    $total_interviews = $total_interviews_query->fetchColumn();

    // Loop through each interview status
    foreach ($InterView_Status as $status) {
        // Get properties for the current status
        $properties = $status_properties[$status] ?? [
            'bgClass' => 'bg-light-primary',
            'textClass' => 'text-primary',
            'icon' => ''
        ];
        $bgClass = $properties['bgClass'];
        $textClass = $properties['textClass'];
        $icon = $properties['icon'];

        // Get the number of interviews with the current status
        $interviews_count = $conn->query("SELECT COUNT(*) FROM `interviews` WHERE ClientKeyID = '$ClientKeyID' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate' AND Status = '$status' $HAS_CREATEDBY")->fetchColumn();

        // Calculate the percentage
        $percentage = $total_interviews > 0 ? ($interviews_count / $total_interviews) * 100 : 0;
        $percentage = number_format($percentage, 1); // Format to 1 decimal place

        // Determine the CSS class and icon based on the percentage
        $arrowClass = $percentage > 30 ? 'text-success' : 'text-danger';
        $arrowIcon = $percentage > 30 ? '<i class="ti ti-arrow-up-right"></i>' : '<i class="ti ti-arrow-down-left"></i>';
    ?>

        <div style="width: 273px;">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="<?php echo $textClass; ?>"><?php echo $status; ?></h5>
                            <div class="d-flex align-items-center">
                                <div class="text-muted"><?php echo $interviews_count; ?> <?php echo ($interviews_count == 1) ? "Interview" : "Interviews "; ?> </div>
                                <div style="margin-left: 50px;" class="ml-auto <?php echo $arrowClass; ?>"><?php echo $arrowIcon; ?> <?php echo $percentage; ?>%</div>
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
        Interviews
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Branch</th>

                        <th>Candidate</th>
                        <th>Key person</th>
                        <th>Interview Date</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $query = "SELECT * FROM `interviews` WHERE ClientKeyID = '$ClientKeyID'";
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
                        $CandidateData = $conn->query("SELECT Name, ProfileImage FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}'")->fetchObject();
                        $KeyPerson = $conn->query("SELECT Name FROM `_clients_key_people` WHERE id = '{$row->KeyPerson}'")->fetchColumn();
                        $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                        $ClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}'")->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
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
                                <?php echo (!$KeyPerson) ? NoData() : $KeyPerson; ?>
                            </td>
                            <td>
                                <?php echo FormatDate($row->DateTime); ?>
                            </td>
                            <td>
                                <?php if ($row->FilePath == "null") : ?>
                                    <div class="text text-danger">File not found</div>
                                <?php else : ?>
                                    <div class="flex-shrink-0">
                                        <a href="<?php echo $row->FilePath; ?>" target="_blank" class="btn btn-sm btn-light-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" />
                                            </svg> Download
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row->Status == "Active" || $row->Status == "Accepted" || $row->Status == "Interviewed") : ?>
                                    <div class="badge bg-success"><?php echo $row->Status; ?></div>
                                <?php else : ?>
                                    <div class="badge bg-danger">
                                        <?php echo (empty($row->Status) ? "Pending" : $row->Status); ?>
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
            <?php if ($query->rowCount() == 0) : ?>
                <div class="alert alert-danger">
                    No data found. Please try again with different search criteria.
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>