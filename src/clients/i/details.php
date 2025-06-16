<?php if ($isTab == "Details") : ?>
    <div class="card-body">
        <div class="card shadow-none border">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 mx-3">
                        <h6 class="mb-0"><?php echo $ClientData->Name; ?></h6>
                        <p class="mb-0"><?php echo $ClientData->_client_id; ?> <?php echo !isset($_GET['isBranch']) ?  $ClientData->ClientType : ''; ?></p>
                    </div>
                    <?php if (isset($_GET['isBranch'])) : ?>
                        <div class="flex-shrink-0"><a href="<?php echo $LINK; ?>/edit_client/?isBranch=true&ID=<?php echo $ClientID; ?>" class="btn btn-sm btn-light-secondary"><i class="ti ti-edit"></i> Edit</a></div>

                    <?php else : ?>
                        <div class="flex-shrink-0"><a href="<?php echo $LINK; ?>/edit_client/?ID=<?php echo $ClientID; ?>" class="btn btn-sm btn-light-secondary"><i class="ti ti-edit"></i> Edit</a></div>

                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card shadow-none border mb-0 ">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                        <h6 class="mb-0">Email Address</h6>
                                    </div>
                                </div>
                                <div class="mb-3 mt-3">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2zm-2 0l-8 5l-8-5zm0 12H4V8l8 5l8-5z" />
                                        </svg>
                                    </span>
                                    <a href="mailto:<?php echo empty($ClientData->Email) ? $Email : $ClientData->Email; ?>" class="form-label"><?php echo empty($ClientData->Email) ? NoData() : $ClientData->Email; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-none border mb-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                        <h6 class="mb-0">Phone number</h6>
                                    </div>
                                </div>
                                <div class="mb-3 mt-3">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24c1.12.37 2.33.57 3.57.57c.55 0 1 .45 1 1V20c0 .55-.45 1-1 1c-9.39 0-17-7.61-17-17c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1c0 1.25.2 2.45.57 3.57c.11.35.03.74-.25 1.02z" />
                                        </svg>
                                    </span>
                                    <a href="tel:<?php echo empty($ClientData->Number) ? $Number : $ClientData->Number; ?>" class="form-label">
                                        <?php echo empty($ClientData->Number) ? NoData() : $ClientData->Number; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-6">
                        <div class="card shadow-none mb-0" style="height: 200px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h6 class="mb-3">Address</h6>
                                        <p class="mb-3">
                                            <?php echo $ClientData->Address; ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <h6 class="mb-3">Postcode</h6>
                                        <p>
                                            <?php echo $ClientData->Postcode; ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <h6 class="mb-3">City</h6>
                                        <p>
                                            <?php echo $ClientData->City; ?>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-none border mb-0 h-100">
                            <form method="post">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3"><label class="form-label">Registration Number</label>
                                                <input type="text" name="RegistrationNumber" class="form-control" value="<?php echo $ClientData->RegistrationNumber; ?>" placeholder="Enter Registration Number">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3"><label class="form-label">Value Added Tax Number</label>
                                                <input type="text" name="VatNo" class="form-control" value="<?php echo $ClientData->VatNo; ?>" placeholder="Enter VAT">
                                            </div>
                                        </div>
                                    </div>
                                    <button name="update" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <div class="card shadow-none mb-0">
                                <div class="card-header">
                                    <h5>Modications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="table-responsive dt-responsive">
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
                                                    $query = "SELECT * FROM `datamodifications` WHERE KeyID = '$ClientID' ORDER BY id DESC ";
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
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($isTab == "Documents") : ?>
        <div class="card-body table-border-style">
            <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Documents</h5>
                <div class="dropdown">
                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#CreateModal">
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
                            <th>Name</th>
                            <th>Download</th>
                            <th>Created By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 1;
                        $query = "SELECT * FROM `_clients_documents` WHERE ClientKeyID = '$ClientKeyID' AND ClientID = '$ClientID' ORDER BY id DESC ";
                        $stmt = $conn->query($query);
                        while ($row = $stmt->fetchObject()) { ?>
                            <?php
                            $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                            ?>
                            <tr>
                                <td><?php echo $n++; ?></td>
                                <td style="display: none;"> <input class="form-check-input checkbox-item" type="checkbox" data-name="<?php echo $row->Name; ?>" data-value="<?php echo $row->id; ?>" value="<?php echo $row->id; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                <td>
                                    <div class="flex-shrink-0"><?php echo $row->Name; ?> </div>
                                </td>
                                <td>
                                    <div class="flex-shrink-0">
                                        <a href="<?php echo $row->Path; ?>" target="_blank" class="btn btn-sm btn-light-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" />
                                            </svg> Download
                                        </a>
                                    </div>
                                </td>
                                <td><?php echo $CreatedBy; ?></td>
                                <td><?php echo FormatDate($row->Date); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item EditClientsDocuments" href="#">
                                                <span class="text-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                    </svg>
                                                </span>
                                                Edit</a>
                                            <?php if (IsCheckPermission($USERID, "DELETE_CLIENT_DOCUMENTS")) : ?>
                                                <a class="dropdown-item select-entry-row"  href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
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
                        <?php }  ?>

                    </tbody>
                </table>
                <?php
                if ($stmt->rowCount() == 0) {
                    echo '<div class="alert alert-danger" role="alert">No documents found.</div>';
                }
                ?>
            </div>
        </div>

        <div id="CreateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="CreateModalLabel">Create Document</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <?php if (IsCheckPermission($USERID, "CREATE_CLIENT_DOCUMENT")) : ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label" for="filename">Name of the document</label>
                                    <input required type="text" name="name" class="form-control" id="filename" placeholder="Enter name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="file">Upload File</label>
                                    <input required type="file" name="file" class="form-control" id="file" required>
                                </div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="CreateDocument" class="btn btn-primary">Submit</button>
                            </div>
                        </form>

                    <?php else : ?>
                        <div style="padding: 10px;">
                            <?php DeniedAccess(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div id="EditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditModalLabel">Edit Document</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <?php if (IsCheckPermission($USERID, "EDIT_CLIENT_DOCUMENT")) : ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3" style="display: none;">
                                    <label class="form-label" for="filename">ID Document</label>
                                    <input required type="text" name="id" class="form-control" id="DocumentID" placeholder="Enter name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="filename">Name of the document</label>
                                    <input required type="text" name="name" class="form-control" id="DocumentName" placeholder="Enter name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="file">Upload File</label>
                                    <input required type="file" name="file" class="form-control" id="file" required>
                                </div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="UpdateDocument" class="btn btn-primary">Submit</button>
                            </div>
                        </form>

                    <?php else : ?>
                        <div style="padding: 10px;">
                            <?php DeniedAccess(); ?>
                        </div>
                    <?php endif; ?>
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
                        <p>Are you sure you want to delete? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="ConfirmDeleteClientDocuments">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>