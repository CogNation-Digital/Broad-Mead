<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['delete'])) {
    // Retrieve the 'ID' parameter from the POST request
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $reason = $_POST['reason'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM `_clients` WHERE ClientID = :ID");

    // Bind the 'ID' parameter to the prepared statement
    $stmt->bindParam(':ID', $ID);

    // Execute the statement to delete the record
    if ($stmt->execute()) {
        $NOTIFICATION = "$NAME has successfully deleted the client named '$name'. Reason for deletion: $reason.";

        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    } else {
        echo "Error deleting record";
    }
}


if (isset($_POST['Search'])) {
    $Name = $_POST['Name'];
    $ClientType = $_POST['ClientType'];
    $_client_id = $_POST['_client_id'];
    $EmailAddress = $_POST['Email'];
    $PhoneNumber = $_POST['Number'];
    $Address = $_POST['Address'];
    $Postcode = $_POST['Postcode'];
    $City = $_POST['City'];
    if (!empty($SearchID)) {
        $query = $conn->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
                  VALUES (:SearchID, :column, :value)");

        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                $query->bindParam(':SearchID', $SearchID);
                $query->bindParam(':column', $key);
                $query->bindParam(':value', $value);
                $query->execute();
            }
        }

        header("location: $LINK/clients/?q=$SearchID");
        exit();
    }
}

$SearchID = isset($_GET['q']) ? $_GET['q'] : "";
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "all";
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php"; ?>

    <div class="pc-container" style="margin-left: 280px;">
        <div class="pc-content">
            <?php include "../../includes/breadcrumb.php"; ?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Clients</h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?php echo $LINK; ?>/create_client">
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                </svg>
                                            </span>
                                            Create
                                        </a>


                                        <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14" />
                                                </svg>
                                            </span>
                                            Advanced Search
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist" style="margin-left:30px;">
                            <li class="nav-item" role="presentation">
                                <a href="<?php echo $LINK; ?>/clients<?php echo !empty($SearchID) ? "/?q=$SearchID" : "" ?>">
                                    <button class="nav-link <?php echo ($isTab == "all") ? 'active' : ''; ?>">All Clients</button>
                                </a>
                            </li>


                            <ul class="nav">
                                <?php foreach ($clients_status as $tab) : ?>
                                    <li class="nav-item" role="presentation">
                                        <a href="<?php echo $LINK; ?>/clients<?php echo !empty($SearchID) ? "/?q=$SearchID" : "/?i=0"  ?>&isTab=<?php echo $tab; ?>">
                                            <button class="nav-link <?php echo ($isTab == $tab) ? 'active' : ''; ?>">
                                                <?php echo $tab; ?>
                                            </button>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>


                        </ul>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "VIEW_CLIENTS")) : ?>
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
                                                <th>Client Name </th>
                                                <th>Client ID</th>
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
                                            $query = "SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isBranch IS NULL ";

                                            if (isset($_GET['q'])) {
                                                $SearchID = $_GET['q'];
                                                $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                                                while ($r = $qu->fetchObject()) {
                                                    $column = $r->column;
                                                    $value = $r->value;
                                                    $query .= " AND " . $column . " LIKE '%$value%'";
                                                }
                                            }
                                            if($isTab !== "all"){
                                                 $query .= " AND Status = '$isTab'";
 
                                            }
                                            $query .= " ORDER BY Name ASC";
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
                                                                <?php if ($row->Status == "Targeted") : ?>
                                                                    <span class="badge bg-info"><?php echo $row->Status; ?></span>
                                                                <?php endif; ?>

                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php if (empty($row->Status)): ?>
                                                        <span class="badge bg-danger">Not Updated</span>
                                                        
                                                    <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $row->Email; ?></td>
                                                    <td><?php echo $row->Number; ?></td>
                                                    <td><?php echo $CreatedBy; ?></td>
                                                    <td><?php echo FormatDate($row->Date); ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">

                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/edit_client/?ID=<?php echo $row->ClientID; ?>">
                                                                    <span class="text-info">

                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                            <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                                        </svg>
                                                                    </span>
                                                                    Edit</a>
                                                                <?php if (IsCheckPermission($USERID, "DELETE_CLIENT")) : ?>
                                                                    <a class="dropdown-item delete-entry" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                                        <span class="text-danger">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                                <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                                            </svg>
                                                                        </span>
                                                                        Delete</a>
                                                                <?php endif; ?>

                                                                <a class="dropdown-item" href="<?php echo $LINK; ?>/view_client/?ID=<?php echo $row->ClientID; ?>">
                                                                    <span class="text-warning">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                                            <g fill="currentColor">
                                                                                <path d="M6.5 12a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z" />
                                                                                <path fill-rule="evenodd" d="M11.185 1H4.5A1.5 1.5 0 0 0 3 2.5v15A1.5 1.5 0 0 0 4.5 19h11a1.5 1.5 0 0 0 1.5-1.5V7.202a1.5 1.5 0 0 0-.395-1.014l-4.314-4.702A1.5 1.5 0 0 0 11.185 1M4 2.5a.5.5 0 0 1 .5-.5h6.685a.5.5 0 0 1 .369.162l4.314 4.702a.5.5 0 0 1 .132.338V17.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5z" clip-rule="evenodd" />
                                                                                <path d="M11 7h5.5a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 1 0z" />
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                    View</a>
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
                                    <a href="<?php echo $LINK; ?>/clients">
                                        <button class="btn btn-primary">
                                            Reset Search
                                        </button>
                                    </a>
                                    <span style="margin-left: 20px;"><?php echo $stmt->rowCount() . ' ' . ($stmt->rowCount() == 1 ? 'client' : 'clients'); ?> found</span>


                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <div class="row" style="padding: 10px;">
                        <input required type="text" class="form-control" id="reason" name="reason" placeholder="Give a reason">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">Advance Search</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Name</label>
                                    <input type="text" name="Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Client Type</label>
                                    <select name="ClientType" class="select-input">
                                        <option value=""></option>
                                        <?php
                                        foreach ($clientype as $type) { ?>
                                            <option><?php echo $type; ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Client ID</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="_client_id">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Email address</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Phone Number</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="Email">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Address</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="Address">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Postcode</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="PostCode">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">City</label>
                                    <div class="input-group search-form">
                                        <input type="text" class="form-control" name="City">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="Search" class="btn btn-primary me-0">Search</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
</body>
<?php include "../../includes/js.php"; ?>
<script>
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.checkbox-item');
        let allChecked = this.classList.toggle('checked');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = allChecked;
        });
    });



    // Confirm Delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.checkbox-item:checked');
        let ids = [];
        let successCount = 0;

        var reason = $("#reason").val();

        if (reason.length > 0) {



            checkboxes.forEach(function(checkbox) {
                ids.push({
                    id: checkbox.value,
                    name: checkbox.getAttribute('data-name')
                });
            });

            if (ids.length > 0) {
                $("#confirmDelete").text("Deleting...");



                ids.forEach(function(item) {
                    $.ajax({
                        url: window.location.href,
                        type: 'POST',
                        data: {
                            ID: item.id,
                            name: item.name,
                            reason: reason,
                            delete: true
                        },
                        success: function(response) {
                            successCount++;
                            if (successCount === ids.length) {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("Error: " + error);
                            // Optionally, handle the error case if needed
                        }
                    });
                });

            } else {
                ShowToast('Error 102: Something went wrong.');
            }
        } else {
            ShowToast('Error 101: Reason field is required.');
            return;
        }
    });
</script>

</html>