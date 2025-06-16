<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}
$ID = $_GET['ID'];
if (isset($_POST['submit'])) {
    $Location = $_POST['Location'];
    $Specification = $_POST['JobRole'];
    $ServiceUser = $_POST['ClientType'];
    $PayRate = $_POST['PayRate'];
    $Description = $_POST['Description'];

    // Update query
    $sql = "UPDATE `_keyjobarea` SET `Location` = :Location, `Specification` = :Specification, `PayRate` = :PayRate, `ServiceUser` = :ServiceUser, `Description` = :Description  WHERE `KjaID` = :KjaID";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':Location', $Location);
    $stmt->bindParam(':Specification', $Specification);
    $stmt->bindParam(':PayRate', $PayRate);
    $stmt->bindParam(':ServiceUser', $ServiceUser);
    $stmt->bindParam(':Description', $Description);
    $stmt->bindParam(':KjaID', $ID);

    if ($stmt->execute()) {
        $Modification = "Updated key job area";
        $Notification = "$NAME updated the key job area.";
        LastModified($ID, $USERID, $Modification); // Use the provided ID
        Notify($USERID, $ClientKeyID, $Notification);
        $response = "Key Job Area successfully updated";
    } else {
        $error = 1;
        $response = "Error updating Key Job Area. Please try again later.";
    }
}



$data = $conn->query("SELECT * FROM _keyjobarea WHERE KjaID = '$ID'")->fetchObject();
if (!$data) {
    header("location: $LINK/key_job_area ");
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
                                <h5 class="mb-0"><?php echo $page; ?></h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "EDIT_KEY_JOB_AREA")) : ?>
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3"><label class="form-label">Client Type</label>
                                                <input type="text" value="<?php echo $data->ServiceUser; ?>" name="ClientType" required class="form-control" placeholder="Enter Client Type">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3"><label class="form-label">Location</label>
                                                <input type="text" value="<?php echo $data->Location; ?>" name="Location" required class="form-control" placeholder="Enter location">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3"><label class="form-label">Job Role</label>
                                                <div class="input-group search-form">
                                                    <input type="text" value="<?php echo $data->Specification; ?>" name="JobRole" required class="form-control" placeholder="Enter Job Role">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3"><label class="form-label">Pay Rate</label>
                                                <div class="input-group search-form">
                                                    <input type="text" value="<?php echo $data->PayRate; ?>" name="PayRate" required class="form-control" placeholder="Enter Pay Rate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3"><label class="form-label">Description</label>
                                                <div class="input-group search-form">
                                                    <textarea name="Description" class="form-control" id=""><?php echo $data->Description; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="d-grid gap-2 mt-2"><button class="btn btn-primary" type="submit" name="submit">Submit</button></div>
                                        </div>
                                </form>
                            <?php else : ?>
                                <?php DeniedAccess(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include "../../includes/js.php"; ?>

</html>