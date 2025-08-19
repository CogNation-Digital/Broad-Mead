<?php if ($isTab == "KeyPeople") : ?>
<?php
// Handle create key person with first and last name
if (isset($_POST['CreateKeyPeople'])) {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $name = $firstName . ' ' . $lastName;
    $email = trim($_POST['email']);
    $number = trim($_POST['number']);
    $position = trim($_POST['position']);
    $createdBy = $USERID;
    $date = date('Y-m-d H:i:s');
    $clientID = $ClientID;
    $stmt = $conn->prepare("INSERT INTO `_clients_key_people` (ClientID, Name, Email, Number, Position, CreatedBy, Date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$clientID, $name, $email, $number, $position, $createdBy, $date]);
    echo '<script>window.location.reload();</script>';
    exit();
}
?>
    <div class="card-body table-border-style">
        <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Key People</h5>
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
                        <th>Position</th>
                        <th>Email</th>
                        <th>Number</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $query = "SELECT * FROM `_clients_key_people` WHERE ClientID = '$ClientID' ORDER BY id DESC ";
                    $stmt = $conn->query($query);
                    while ($row = $stmt->fetchObject()) { ?>
                        <?php
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td style="display: none;"> <input class="form-check-input checkbox-item" type="checkbox" data-value="<?php echo $row->id; ?>" data-name="<?php echo $row->Name; ?>" data-email="<?php echo $row->Email; ?>" data-number="<?php echo $row->Number; ?>" data-position="<?php echo $row->Position; ?>" value="<?php echo $row->id; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                            <td><?php echo $row->Name; ?></td>
                            <td><?php echo $row->Position; ?></td>
                            <td><a href="mailto:<?php echo $row->Email; ?>"><?php echo $row->Email; ?></a></td>
                            <td><a href="tel:<?php echo $row->Number; ?>"><?php echo $row->Number; ?></a></td>
                            <td><?php echo $CreatedBy; ?></td>
                            <td><?php echo FormatDate($row->Date); ?></td>
                            <td>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item EditKeyPerson" href="#" >
                                            <span class="text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                </svg>
                                            </span>
                                            Edit</a>
                                        <?php if (IsCheckPermission($USERID, "DELETE_CLIENT_DOCUMENTS")) : ?>
                                            <a class="dropdown-item select-entry-row" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                <span class="text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                        <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                    </svg>
                                                </span>
                                                Delete</a>
                                        <?php endif; ?>

                                    </div>
                            </td>
                        </tr>

                    <?php }  ?>

                </tbody>
            </table>
            <?php
            if ($stmt->rowCount() == 0) {
                echo '<div class="alert alert-danger" role="alert">No key people found.</div>';
            }
            ?>
        </div>
    </div>

    <div id="CreateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreateModalLabel">Create Key Person</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (IsCheckPermission($USERID, "CREATE_CLIENT_KEY_PEOPLE")) : ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="first_name">First Name</label>
                                <input required type="text" name="first_name" class="form-control" placeholder="Enter First Name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input required type="text" name="last_name" class="form-control" placeholder="Enter Last Name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email address</label>
                                <input required type="text" name="email" class="form-control" placeholder="Enter Email address" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="number">Phone number</label>
                                <input required type="text" name="number" class="form-control" placeholder="Enter Phone number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="position">Position</label>
                                <input required type="text" name="position" class="form-control" placeholder="Enter Position" required>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="CreateKeyPeople" class="btn btn-primary">Submit</button>
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
                    <h5 class="modal-title" id="EditModalLabel">Edit Key Person</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (IsCheckPermission($USERID, "EDIT_CLIENT_KEY_PEOPLE")) : ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3" style="display: none;">
                                <label class="form-label" for="name">ID</label>
                                <input required type="text" id="EditID" name="id" class="form-control" placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input required type="text" id="EditName" name="name" class="form-control" placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email address</label>
                                <input required type="text" id="EditEmail" name="email" class="form-control" placeholder="Enter Email address" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="number">Phone number</label>
                                <input required type="text" id="EditNumber" name="number" class="form-control" placeholder="Enter Phone number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="position">Position</label>
                                <input required type="text" id="EditPosition" name="position" class="form-control" placeholder="Enter Position" required>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="UpdateKeyPeople" class="btn btn-primary">Submit</button>
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
                    <button type="button" class="btn btn-danger" id="ConfirmDeleteKeyPerson">Confirm</button>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>