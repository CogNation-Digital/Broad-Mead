     <?php if ($isTab == "Branches") : ?>
         <div class="card-body table-border-style">
             <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
                 <h5 class="mb-0">Branches</h5>
                 <?php
                    // Check all permissions
                    $CREATE_BRANCH_PERMISSION = IsCheckPermission($USERID, "CREATE_BRANCH");
                    $EDIT_BRANCH_PERMISSION = IsCheckPermission($USERID, "EDIT_BRANCH");
                    $DELETE_BRANCH_PERMISSION = IsCheckPermission($USERID, "DELETE_BRANCH");
                    $VIEW_BRANCH_PERMISSION = IsCheckPermission($USERID, "VIEW_BRANCH");

                    if ($CREATE_BRANCH_PERMISSION || $EDIT_BRANCH_PERMISSION || $DELETE_BRANCH_PERMISSION || $VIEW_BRANCH_PERMISSION) :
                    ?>
                     <div class="dropdown">
                         <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <i class="ti ti-dots-vertical f-18"></i>
                         </a>
                         <div class="dropdown-menu dropdown-menu-end">
                             <?php if ($CREATE_BRANCH_PERMISSION) : ?>
                                 <a class="dropdown-item" href="<?php echo $LINK; ?>/create_client/?ClientID=<?php echo $ClientID; ?>&isBranch=true">
                                     <span class="text-success">
                                         <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                             <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                         </svg>
                                     </span>
                                     Create
                                 </a>
                             <?php endif; ?>
                         </div>
                     </div>
                 <?php endif; ?>

             </div>
             <div class="table-responsive">
                 <?php if (IsCheckPermission($USERID, "VIEW_CLIENTS_BRANCHES")) : ?>
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
                                 <th> Name </th>
                                 <th>Branch ID</th>
                                 <th>Status</th>
                                 <th>Email Address </th>
                                 <th>Phone Number </th>
                                 <th>Created By</th>
                                 <th>Date</th>
                                 <th>Actions</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                                $query = "SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isClient = '$ClientID'";

                                if (isset($_GET['q'])) {
                                    $SearchID = $_GET['q'];
                                    $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                                    while ($r = $qu->fetchObject()) {
                                        $column = $r->column;
                                        $value = $r->value;
                                        $query .= " AND " . $column . " LIKE '%$value%'";
                                    }
                                }
                                $query .= " ORDER BY id DESC";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $n = 1;
                                while ($row = $stmt->fetchObject()) { ?>
                                 <?php
                                    $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                    ?>
                                 <tr>
                                     <td><?php echo $n++; ?></td>
                                     <td style="display: none;"> <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->ClientID; ?>" data-name="<?php echo $row->Name; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>

                                     <td><?php echo $row->Name; ?></td>
                                     <td><?php echo $row->_client_id; ?></td>
                                     <td><?php echo $row->Status; ?></td>
                                     <td><?php echo $row->Email; ?></td>
                                     <td><?php echo $row->Number; ?></td>
                                     <td><?php echo $CreatedBy; ?></td>
                                     <td><?php echo FormatDate($row->Date); ?></td>
                                     <td>
                                         <div class="dropdown">
                                             <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 <i class="ti ti-dots-vertical f-18"></i>
                                             </a>
                                             <div class="dropdown-menu dropdown-menu-end">


                                                 <?php if ($EDIT_BRANCH_PERMISSION) : ?>
                                                     <a class="dropdown-item" href="<?php echo $LINK; ?>/edit_client/?isBranch=true&ID=<?php echo $row->ClientID; ?>">
                                                         <span class="text-info">
                                                             <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                 <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                             </svg>
                                                         </span>
                                                         Edit
                                                     </a>
                                                 <?php endif; ?>

                                                 <?php if ($DELETE_BRANCH_PERMISSION) : ?>
                                                     <a class="dropdown-item select-entry-row" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                         <span class="text-danger">
                                                             <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                 <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                             </svg>
                                                         </span>
                                                         Delete
                                                     </a>
                                                 <?php endif; ?>

                                                 <?php if ($VIEW_BRANCH_PERMISSION) : ?>
                                                     <a class="dropdown-item" href="<?php echo $LINK; ?>/view_client/?isBranch=true&ID=<?php echo $row->ClientID; ?>">
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
                                                 <?php endif; ?>
                                             </div>
                                         </div>
                                     </td>
                                 </tr>
                             <?php } ?>


                         </tbody>
                     </table>
                     <?php if ($stmt->rowCount() == 0) : ?>
                         <div class="alert alert-danger">
                             No data found.
                         </div>
                     <?php endif; ?>
                 <?php else : ?>
                     <?php
                        DeniedAccess();
                        ?>
                 <?php endif; ?>

                 <?php if (isset($_GET['q'])) : ?>
                     <a href="<?php echo $LINK; ?>/key_job_area">
                         <button class="btn btn-primary">
                             Reset Search
                         </button>
                     </a>

                 <?php endif; ?>
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
                                     <label class="form-label" for="name">Name</label>
                                     <input required type="text" name="name" class="form-control" placeholder="Enter Name" required>
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
                         <button type="button" class="btn btn-danger" id="ConfirmDeleteBranch">Confirm</button>
                     </div>
                 </div>
             </div>
         </div>

     <?php endif; ?>