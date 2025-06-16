<?php if ($isTab == "Vacancy") : ?>
    <div class="card-body table-border-style">
        <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Vacancy</h5>
            <div class="dropdown">
                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="<?php echo $LINK; ?>/create_vacancy/?hasClientID=<?php echo $isClientID; ?>&hasBranchID=<?php echo isset($_GET['isBranch']) ? $ClientID : 'false'; ?>&VacancyID=<?php echo $KeyID; ?>&isNew=true&isTab=Details">
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
                        <th>#</th>
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
                        <th>Type</th>
                        <th>Title</th>
                        <th>Number of roles</th>
                        <th>Candidates</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $sql = "SELECT * FROM `vacancies` WHERE ClientKeyID = '$ClientKeyID'";
                    if (isset($_GET['isBranch'])) {
                        $sql .= " AND hasBranchID = '$ClientID'";
                    } else {
                        $sql .= " AND hasClientID = '$ClientID'";
                    }
                    $query = $conn->prepare($sql);
                    $query->execute();
                    while ($row = $query->fetchObject()) { ?>
                        <?php
                        $BranchName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasBranchID}'")->fetchColumn();
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}'")->fetchColumn();
                        $CandidatesNum = $conn->query("SELECT COUNT(id) FROM `__vacancy_candidates` WHERE VacancyID = '{$row->VacancyID}'")->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td style="display: none;"> <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->VacancyID; ?>" hasBranchID="<?php echo (empty($row->hasBranchID) ? "false" : $row->hasBranchID); ?>" hasClientID="<?php echo $row->hasClientID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                            <?php if (!isset($_GET['isBranch'])) : ?>
                                <td><?php echo (!$BranchName) ? $ClientData->Name : $BranchName; ?></td>
                            <?php endif; ?>
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
                            <td>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">

                                        <a class="dropdown-item" href="<?php echo $LINK ?>/create_vacancy/?isNew=false&isTab=Details&hasClientID=<?php echo $row->hasClientID; ?>&hasBranchID=<?php echo $row->hasBranchID; ?>&VacancyID=<?php echo $row->VacancyID; ?>">
                                            <span class="text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                </svg>
                                            </span>
                                            Edit</a>
                                        <a class="dropdown-item" href="<?php echo $LINK ?>/view_vacancy/?VacancyID=<?php echo $row->VacancyID; ?>">
                                            <span class="text-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                    <g fill="currentColor">
                                                        <path d="M6.5 12a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z" />
                                                        <path fill-rule="evenodd" d="M11.185 1H4.5A1.5 1.5 0 0 0 3 2.5v15A1.5 1.5 0 0 0 4.5 19h11a1.5 1.5 0 0 0 1.5-1.5V7.202a1.5 1.5 0 0 0-.395-1.014l-4.314-4.702A1.5 1.5 0 0 0 11.185 1M4 2.5a.5.5 0 0 1 .5-.5h6.685a.5.5 0 0 1 .369.162l4.314 4.702a.5.5 0 0 1 .132.338V17.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5z" clip-rule="evenodd" />
                                                        <path d="M11 7h5.5a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 1 0z" />
                                                    </g>
                                                </svg>
                                            </span>
                                            View
                                        </a>
                                        <?php if (IsCheckPermission($USERID, "DELETE_VACANCY")) : ?>
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
            <?php if ($query->rowCount() == 0) : ?>
                <div>
                    <div class="alert alert-danger">No vacancies found.</div>
                </div>
            <?php endif; ?>
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
                    <button type="button" class="btn btn-danger" id="ConfirmDeleteVacancy">Confirm</button>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>