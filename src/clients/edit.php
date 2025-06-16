<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

$ID = isset($_GET['ID']) ? $_GET['ID'] : '';
$ClientID = $ID; // Set ClientID from ID
$isBranch = isset($_GET['isBranch']);
$isName = $isBranch ? 'Branch' : 'Client';
$ClientName = '';

if ($ClientID !== "") {
    // Fetch client name if ClientID is provided
    $queryClientName = "SELECT Name FROM `_clients` WHERE ClientID = :ClientID AND isClient = ''";
    $stmtClientName = $conn->prepare($queryClientName);
    $stmtClientName->bindParam(':ClientID', $ClientID);
    $stmtClientName->execute();
    $ClientName = $stmtClientName->fetchColumn();
}

if (isset($_POST['submit'])) {
    $Name = $_POST['Name'];
    $ClientType = isset($_POST['ClientType']) ? $_POST['ClientType'] : '';
    $_client_id = $_POST['_client_id'];
    $EmailAddress = $_POST['EmailAddress'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $Address = $_POST['Address'];
    $Postcode = $_POST['Postcode'];
    $City = $_POST['City'];
    $Status = $_POST['Status'];

    // Check if the client exists
    $checkQuery = "SELECT COUNT(*) FROM `_clients` WHERE `ClientID` = :ClientID";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bindParam(':ClientID', $ID);
    $stmtCheck->execute();
    $clientExists = $stmtCheck->fetchColumn();

    if ($clientExists) {
        // Determine if it's a branch or client
        $entityType = $isBranch ? 'branch' : 'client';

        // Client exists, proceed with update
        $updateQuery = "UPDATE `_clients` 
                        SET `_client_id` = :_client_id, 
                            `ClientType` = :ClientType, 
                            `Name` = :Name, 
                            `Email` = :Email, 
                            `Number` = :Number, 
                            `Address` = :Address, 
                            `City` = :City, 
                            `Postcode` = :Postcode,
                            `Status` = :Status 
                        WHERE `ClientID` = :ClientID";
        $stmtUpdate = $conn->prepare($updateQuery);
        // Bind parameters
        $stmtUpdate->bindParam(':ClientID', $ID);
        $stmtUpdate->bindParam(':_client_id', $_client_id);
        $stmtUpdate->bindParam(':ClientType', $ClientType);
        $stmtUpdate->bindParam(':Name', $Name);
        $stmtUpdate->bindParam(':Email', $EmailAddress);
        $stmtUpdate->bindParam(':Number', $PhoneNumber);
        $stmtUpdate->bindParam(':Address', $Address);
        $stmtUpdate->bindParam(':City', $City);
        $stmtUpdate->bindParam(':Postcode', $Postcode);
        $stmtUpdate->bindParam(':Status', $Status);

        if ($stmtUpdate->execute()) {
            $Modification = "Updated $entityType information";
            $Notification = "$NAME updated $entityType information for '$Name'.";
            LastModified($ID, $USERID, $Modification);
            Notify($USERID, $ClientKeyID, $Notification);
            $response = ucfirst($entityType) . " details successfully updated.";
        } else {
            $response = "Something went wrong. Please try again.";
            $error = true;
        }
    } else {
        $response = "Client does not exist.";
        $error = true;
    }
}



$client_data = $conn->query("SELECT * FROM `_clients` WHERE ClientID = '$ID' ")->fetchObject();
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
                            <?php if (IsCheckPermission($USERID, "EDIT_CLIENT")) : ?>
                                <form method="POST">
                                    <div class="row">
                                        <div class="<?php echo (isset($_GET['isBranch']) ? 'col-lg-12' : 'col-lg-6'); ?>">
                                            <div class="mb-3"><label class="form-label">Name</label>
                                                <input type="text" value="<?php echo $client_data->Name ?>" name="Name" required class="form-control">
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s name</small>
                                            </div>
                                        </div>


                                        <?php if (isset($_GET['isBranch'])) : ?>

                                        <?php else : ?>
                                            <div class="col-lg-6">
                                                <div class="mb-3"><label class="form-label">Client Type</label>
                                                    <select required name="ClientType" class="select-input">
                                                        <?php
                                                        foreach ($clientype as $type) { ?>
                                                            <option <?php echo ($client_data->ClientType == $type) ? "selected" : ""; ?>><?php echo $type; ?></option>

                                                        <?php } ?>
                                                    </select>
                                                    <small class="form-text text-muted">Please select client type</small>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Client ID</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" value="<?php echo $client_data->_client_id; ?>" name="_client_id">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?> ID</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label"><?php echo $isName; ?> Status</label>
                                                <div class="input-group search-form">
                                                    <select class="form-control" name="Status">
                                                        <?php foreach ($clients_status as $status): ?>
                                                            <option <?php echo $client_data->Status == $status ? "selected" : "" ; ?>><?php echo $status; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <small class="form-text text-muted">Please select <?php echo $isName; ?>'s status</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Email address</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" value="<?php echo $client_data->Email; ?>" name="EmailAddress">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s email address</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Phone Number</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" value="<?php echo $client_data->Number; ?>" name="PhoneNumber">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s contact number</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Address</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" value="<?php echo $client_data->Address; ?>" class="form-control" name="Address">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s address</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Postcode</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" value="<?php echo $client_data->Postcode; ?>" name="Postcode">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s postcode</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">City</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" value="<?php echo $client_data->City; ?>" name="City">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s city</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <div class="d-grid gap-2 mt-2">
                                                <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                                            </div>
                                        </div>
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