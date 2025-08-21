<?php
// Ensure $CandidateID is available from parent scope
if (!isset($CandidateID) && isset($_GET['ID'])) {
    $CandidateID = $_GET['ID'];
}
if ($isTab == "CommunicationLog") : ?>
    <div class="card-body table-border-style">
        <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Communication Log</h5>
            <div class="dropdown">
                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#CreateLog">
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
                        <th>Type</th>
                        <th>Description</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $query = "SELECT * FROM `_communication_logs` WHERE RecipientID = '$CandidateID'";
                    $query .= " ORDER BY _communication_logs.id DESC";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    while ($row = $stmt->fetchObject()) { ?>
                        <?php
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td style="display: none;"> <input class="form-check-input checkbox-item" value="<?php echo $row->id; ?>" data-type="<?php echo $row->Type; ?>" data-details="<?php echo $row->Details; ?>" type="checkbox" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                            <td><?php echo $row->Type; ?></td>
                            <td><?php echo TruncateText($row->Details, 100); ?></td>
                            <td><?php echo $CreatedBy; ?></td>
                            <td><?php echo FormatDate($row->Date); ?></td>
                            <td>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item select-entry-row" href="#" id="Edit">
                                            <span class="text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                </svg>
                                            </span>
                                            Edit</a>
                                        <!-- <a class="dropdown-item select-entry-row" href="#" >
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
                                        </a> -->
                                        <?php if (IsCheckPermission($USERID, "DELETE_LOG")) : ?>
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
            <?php if ($stmt->rowCount() == 0) : ?>
                <div class="alert alert-danger">No data found</div>
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
    <div id="CommunicationLogModal" class="modal fade" tabindex="-1" aria-labelledby="CommunicationLogModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Communication Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="Details"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="CreateLog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateLogLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreateLogLabel">Create Log</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="padding: 10px;">
                                <label class="form-label">Type</label>
                                <input required type="text" name="Type" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="Description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="SaveLog" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div id="EditCommunicationLogModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditCommunicationLogModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditCommunicationLogModalLabel">Edit Log</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="padding: 10px;">
                                <label class="form-label">Type</label>
                                <input required type="text" id="Type" name="Type" class="form-control" required>
                                <input required type="hidden" name="ID" id="ID" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="Description" id="Description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="UpdateLog" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php endif; ?>