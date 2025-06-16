<?php if ($isTab == "Interviews") : ?>
    <div class="card-body table-border-style">
        <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Interviews</h5>
            <div class="dropdown">
                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="<?php echo $LINK; ?>/create_interview/?hasClientID=false&hasBranchID=false&isID=<?php echo $RandomID; ?>&isNew=true&CandidateID=<?php echo $CandidateID; ?>">
                        <span class="text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                            </svg>
                        </span>
                        Create
                    </a>
                    <a class="dropdown-item" href="#" id="Edit">
                        <span class="text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                            </svg>
                        </span>
                        Edit</a>
                    <?php if (IsCheckPermission($USERID, "DELETE_INTERVIEW")) : ?>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                            <span class="text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                    <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                </svg>
                            </span>
                            Delete</a>
                    <?php endif; ?>

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
                        <th>Client</th>
                        <th>Key person</th>
                        <th>Interview Date</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $sql = "SELECT * FROM `interviews` WHERE ClientKeyID = '$ClientKeyID'";
                    $sql .= " AND CandidateID = '$CandidateID'";

                    $query = $conn->prepare($sql);
                    $query->execute();
                    while ($row = $query->fetchObject()) { ?>
                        <?php
                        $CandidateName = $conn->query("SELECT Name FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}'")->fetchColumn();
                        $KeyPerson = $conn->query("SELECT Name FROM `_clients_key_people` WHERE id = '{$row->KeyPerson}'")->fetchColumn();
                        $CLientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->hasClientID}'")->fetchColumn();
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}'")->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td style="display: none;"> <input class="form-check-input checkbox-item" type="checkbox" data-id="<?php echo $row->id; ?>" value="create_interview/?isNew=false&isID=<?php echo $row->InterviewID; ?>&hasClientID=<?php echo empty($row->hasClientID) ? 'false' : $row->hasClientID; ?>&hasBranchID=<?php echo $row->hasBranchID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                            <td><?php echo (!$CLientName) ? $ClientData->Name : $CLientName; ?></td>

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
                            <td>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">

                                        <a class="dropdown-item select-entry-row edit" href="javascript:void(0)">
                                            <span class="text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                </svg>
                                            </span>
                                            Edit</a>
                                        <?php if (IsCheckPermission($USERID, "DELETE_INTERVIEW")) : ?>
                                            <a class="dropdown-item  select-entry-row " href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
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
                    <div class="alert alert-danger">No data found.</div>
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
                    <div class="row" style="padding: 10px;">
                        <input required type="text" class="form-control" id="reason" name="reason" placeholder="Give a reason">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="ConfirmDelete">Confirm</button>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>