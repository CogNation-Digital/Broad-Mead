<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

$UserID = $_GET['ID'];

if (isset($_POST['updatePermission'])) {

    $permission = $_POST['permission'];
    $checked = isset($_POST['checked']) ? filter_var($_POST['checked'], FILTER_VALIDATE_BOOLEAN) : false;

    if ($checked) {
        // Insert the permission if it's checked
        $query = $conn->prepare("INSERT INTO `userpermissions`(`UserID`, `Permission`) VALUES (:UserID, :Permission)");
        $query->bindParam(':UserID', $UserID);
        $query->bindParam(':Permission', $permission);
        $query->execute();
    } else {
        // Delete the permission if it's unchecked
        $query = $conn->prepare("DELETE FROM `userpermissions` WHERE UserID = :UserID AND Permission = :Permission");
        $query->bindParam(':UserID', $UserID);
        $query->bindParam(':Permission', $permission);
        $query->execute();
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
                                <h5 class="mb-0">Upate Permissions</h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "EDIT_USERS")) : ?>
                                    <div>
                                        <div class="container">
                                            <div class="accordion" id="accordionExample">
                                                <?php

                                                $permissions = $conn->query("SELECT * FROM `permissions` ORDER BY Category ASC, LabelName ASC");

                                                $currentCategory = '';
                                                $index = 0;

                                                while ($row = $permissions->fetchObject()) {
                                                    if ($row->Category !== $currentCategory) {
                                                        if ($currentCategory !== '') {
                                                            // Close the previous category
                                                            echo '</div></div></div>';
                                                        }

                                                        // Start a new category
                                                        $currentCategory = $row->Category;
                                                        $index++;
                                                        echo '<div class="accordion-item">';
                                                        echo '<h2 class="accordion-header" id="heading' . $index . '">';
                                                        echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $index . '" aria-expanded="false" aria-controls="collapse' . $index . '">';
                                                        echo $currentCategory;
                                                        echo '</button></h2>';
                                                        echo '<div id="collapse' . $index . '" class="accordion-collapse collapse" aria-labelledby="heading' . $index . '" data-bs-parent="#accordionExample">';
                                                        echo '<div class="accordion-body">';
                                                    }

                                                    // Check if the permission should be checked
                                                    $isChecked = IsCheckPermission($UserID, $row->Permission) ? 'checked' : '';

                                                    // Add the label to the current category
                                                    echo '<div class="form-check mb-2">';
                                                    echo '<input class="form-check-input checkbox-item" type="checkbox" value="' . $row->Permission . '" id="flexCheck' . $row->id . '" ' . $isChecked . '>';
                                                    echo '<label class="form-check-label" for="flexCheck' . $row->id . '">' . $row->LabelName . '</label>';
                                                    echo '</div>';
                                                }

                                                // Close the last category
                                                if ($currentCategory !== '') {
                                                    echo '</div></div></div>';
                                                }
                                                ?>

                                            </div>
                                        </div>

                                    </div>
                                <?php else : ?>
                                    <?php
                                    DeniedAccess();
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

<?php include "../../includes/js.php"; ?>
<script>
    $(document).ready(function() {
        $('#SearchPermissions').on('input', function() {
            var filter = $(this).val().toLowerCase();
            $('#PermissionsList .list-group-item').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(filter) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    $(document).ready(function() {
        $('.checkbox-item').on('change', function() {
            var isChecked = $(this).is(':checked');
            var permissionValue = $(this).val();

            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    updatePermission: true,
                    permission: permissionValue,
                    checked: isChecked
                },
                success: function(response) {
                     ShowToast('Permission updated successfully');
                    
                },
                error: function(xhr, status, error) {
                    ShowToast('AJAX request failed:', status, error);
                }
            });
        });
    });
</script>

</html>