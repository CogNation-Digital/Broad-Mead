<?php if ($isTab == "Details") : ?>
    <div id="Details">
        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-none border mb-0">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <h6 class="mb-3">Client</h6>
                                <p class="mb-3">
                                    <?php echo empty($data->hasClientID) ? NoData() : $ClientName; ?>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <h6 class="mb-3">Branch</h6>
                                <p class="mb-3">
                                    <?php echo empty($data->hasBranchID) ? NoData() : $BranchName; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <div class="card shadow-none border mb-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 me-3">
                                <h6 class="mb-0">Vacancy Details</h6>
                            </div>
                        </div>
                        <div class="row mb-3 mt-3">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <h6 class="mb-3">Type</h6>
                                    <p class="mb-3">
                                        <?php echo empty($data->Type) ? NoData() : $data->Type; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <h6 class="mb-3">Title</h6>
                                    <p class="mb-3">
                                        <?php echo empty($data->Title) ? NoData() : $data->Title; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <h6 class="mb-3">Number of roles</h6>
                                    <p class="mb-3">
                                        <?php echo empty($data->Roles) ? NoData() : $data->Roles; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <h6 class="mb-3">Period</h6>
                                    <p class="mb-3">
                                        <?php echo empty($data->StartDate) ? NoData() : 'from ' . FormatDate($data->StartDate); ?>
                                        <?php echo empty($data->EndDate) ? NoData() : 'to ' . FormatDate($data->EndDate); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <h6 class="mb-3">Document</h6>
                                    <p class="mb-3">
                                        <?php
                                        if (empty($data->FilePath)) { ?>
                                            <?php echo NoData(); ?>
                                        <?php } else { ?>
                                            <a href="<?php echo $data->FilePath; ?>" target="_blank" class="btn btn-sm btn-light-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" />
                                                </svg> Download
                                            </a>
                                        <?php }  ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <h6 class="mb-3">Status</h6>
                                    <p class="mb-3">
                                        <?php
                                        $currentDate = new DateTime();
                                        $startDate = new DateTime($data->StartDate);
                                        $endDate = new DateTime($data->EndDate);

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
                                </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-12">
                <div class="card shadow-none border mb-0">
                    <div class="card-body">
                        <h6 class="mb-3">Modications</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Modication</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $n = 1;
                                    $query = "SELECT * FROM `datamodifications` WHERE KeyID = '$VacancyID' ORDER BY id DESC ";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetchObject()) { ?>
                                        <?php
                                        $userdata =  $conn->query("SELECT * FROM `users` WHERE UserID = '{$row->UserID}'")->fetchObject();
                                        ?>
                                        <tr>
                                            <td><?php echo $n++; ?></td>
                                            <td>


                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0"><img src="<?php echo $userdata->ProfileImage; ?>" alt="user image" class="img-radius wid-40"></div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0"><?php echo $userdata->Name; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $row->Modification; ?></td>
                                            <td><?php echo FormatDate($row->Date); ?></td>
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