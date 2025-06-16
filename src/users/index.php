<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['DeleteUser'])) {
    // Retrieve the 'ID' parameter from the POST request
    $ID = $_POST['ID'];

    try {
        // Start a transaction
        $conn->beginTransaction();

        // Prepare the SELECT statement to get the user's name
        $stmt = $conn->prepare("SELECT Name FROM `users` WHERE UserID = :ID");
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();

        // Fetch the user's name
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $USERNAME_ = $user['Name'];

            // Prepare the DELETE statement
            $stmt = $conn->prepare("DELETE FROM `users` WHERE UserID = :ID");
            $stmt->bindParam(':ID', $ID);

            // Execute the DELETE statement
            if ($stmt->execute()) {
                echo "Deleted record";
                $NOTIFICATION = "$NAME successfully deleted $USERNAME_";
                Notify($USERID, $ClientKeyID, $NOTIFICATION);
            } else {
                echo "Error deleting record";
            }
        } else {
            echo "User not found";
        }

        // Commit the transaction
        $conn->commit();
    } catch (PDOException $e) {
        // Roll back the transaction if something went wrong
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}




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
                                <h5 class="mb-0">Users</h5>
                                <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <?php if (IsCheckPermission($USERID, "CREATE_USERS")) : ?>
                                            <a class="dropdown-item" href="<?php echo $LINK; ?>/create_user">
                                                <span class="text-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                    </svg>
                                                </span>
                                                Create
                                            </a>
                                        <?php endif; ?>

                                        <a class="dropdown-item" href="#" id="Edit">
                                            <span class="text-info">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" />
                                                </svg>
                                            </span>
                                            Edit
                                        </a>
                                        <a class="dropdown-item" href="#" id="EditPermissions">
                                            <span class="text-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z" />
                                                </svg>
                                            </span>
                                            Permissions
                                        </a>
                                        <?php if (IsCheckPermission($USERID, "DELETE_USERS")) : ?>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                <span class="text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                        <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                    </svg>
                                                </span>
                                                Delete</a>
                                        <?php endif; ?>

                                        <a class="dropdown-item" href="#" id="View">
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
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "VIEW_USERS")) : ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>
                                                    <span id="selectAll" style="cursor: pointer;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                        </svg>
                                                    </span>
                                                </th>
                                                <th>NAME </th>
                                                <th>EMAIL address</th>
                                                <th>POSITION</th>
                                                <th>DEPARTMENT</th>
                                                <th>Created By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM `users` WHERE ClientKeyID = '$ClientKeyID'";


                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $n = 1;
                                            while ($row = $stmt->fetchObject()) { ?>
                                                <?php
                                                $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                                ?>
                                                <tr>
                                                    <td><?php echo $n++; ?></td>
                                                    <td> <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->UserID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0"><img width="40" height="40" style="object-fit: cover;" src="<?php echo $row->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image"  class="img-radius wid-40"></div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="mb-0"><?php echo $row->Name; ?></h6>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td><?php echo $row->Email; ?></td>
                                                    <td><?php echo $row->Position; ?></td>
                                                    <td><?php echo $row->Department; ?></td>
                                                    <td><?php echo $CreatedBy; ?></td>
                                                    <td><?php echo FormatDate($row->Date); ?></td>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                </div>
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


    //View   EditPermissions


    $("#Edit").click(function() {
        let checkboxes = document.querySelectorAll('.checkbox-item:checked');
        // if no checkboxes are selected alert
        if (checkboxes.length == 0) {
            ShowToast('Error 102: Something went wrong.');
            return;
        } else {
            let id = checkboxes[0].value;
            window.location.href = "<?php echo $LINK; ?>/edit_user/?ID=" + id
        }
    })

    $("#EditPermissions").click(function() {
        let checkboxes = document.querySelectorAll('.checkbox-item:checked');
        // if no checkboxes are selected alert
        if (checkboxes.length == 0) {
            ShowToast('Error 102: Something went wrong.');
            return;
        } else {
            let id = checkboxes[0].value;
            window.location.href = "<?php echo $LINK; ?>/update_permissions/?ID=" + id
        }
    })
    // Confirm Delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.checkbox-item:checked');
        let ids = [];

        checkboxes.forEach(function(checkbox) {
            ids.push(checkbox.value);
        });

        if (ids.length > 0) {
            $("#confirmDelete").text("Deleting...");

            ids.forEach(function(id) {
                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: {
                        ID: id,
                        DeleteUser: true
                    },
                    success: function(response) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                });

            });


        } else {
            ShowToast('Error 102: Something went wrong.');

        }
    });
</script>

</html>