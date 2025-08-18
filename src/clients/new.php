<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}





$ClientID = isset($_GET['ClientID']) ? $_GET['ClientID'] : '';
$isBranch = isset($_GET['isBranch']);
$isName = $isBranch ? 'Branch' : 'Client';
$ClientName = ($ClientID !== "") ? ($conn->query("SELECT Name FROM _clients WHERE ClientID = '$ClientID' AND isClient = '' ")->fetchColumn()) : '';

if (isset($_POST['submit'])) {
    $Name = $_POST['Name'];
    $ManagerFirstName = isset($_POST['ManagerFirstName']) ? $_POST['ManagerFirstName'] : '';
    $ManagerLastName = isset($_POST['ManagerLastName']) ? $_POST['ManagerLastName'] : '';
    $ClientType = isset($_POST['ClientType']) ? $_POST['ClientType'] : '';
    $_client_id = $_POST['_client_id'];
    $EmailAddress = $_POST['EmailAddress'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $Address = $_POST['Address'];
    $Postcode = $_POST['Postcode'];
    $City = $_POST['City'];
    $Status = $_POST['Status'];
    $RegistrationNumber = '';
    $VatNo = '';


    $isBranch = isset($_GET['isBranch']) ? $_GET['isBranch'] : null;
    $isClient = !$isBranch;
    $HasBranches = !$isBranch;

    if (!$isBranch) {
        $checkQuery = "SELECT COUNT(*) FROM `_clients` WHERE `ClientID` = :RandomID";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':RandomID', $RandomID);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            $response = "Something went wrong. Please try again.";
            $error = true;
        }
    }

    if ($error !== true) {
    $query = "INSERT INTO `_clients` (`ClientKeyID`, `ClientID`, `_client_id`, `isClient`, `isBranch`, `Name`, `Email`, `Number`, `Address`, `City`, `Postcode`, `RegistrationNumber`, `VatNo`, `HasBranch`, `Status`, `CreatedBy`, `Date`, `manager_first_name`, `manager_last_name`) 
    VALUES (:ClientKeyID, :ClientID, :_client_id, :isClient, :isBranch, :Name, :Email, :Number, :Address, :City, :Postcode, :RegistrationNumber, :VatNo, :HasBranch, :Status, :CreatedBy, :Date, :ManagerFirstName, :ManagerLastName)";

    $stmt = $conn->prepare($query);
    // Bind parameters
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':ClientID', $RandomID);
    $stmt->bindParam(':_client_id', $_client_id);
    $stmt->bindParam(':isClient', $ClientID);
    $stmt->bindParam(':isBranch', $isBranch);
    $stmt->bindParam(':Name', $Name);
    $stmt->bindParam(':Email', $EmailAddress);
    $stmt->bindParam(':Number', $PhoneNumber);
    $stmt->bindParam(':Address', $Address);
    $stmt->bindParam(':City', $City);
    $stmt->bindParam(':Postcode', $Postcode);
    $stmt->bindParam(':RegistrationNumber', $RegistrationNumber);
    $stmt->bindParam(':VatNo', $VatNo);
    $stmt->bindParam(':HasBranch', $HasBranches);
    $stmt->bindParam(':Status', $Status);
    $stmt->bindParam(':CreatedBy', $USERID);
    $stmt->bindParam(':Date', $date);
    $stmt->bindParam(':ManagerFirstName', $ManagerFirstName);
    $stmt->bindParam(':ManagerLastName', $ManagerLastName);

        if ($stmt->execute()) {
            $Modification = "Created $isName";
            $Notification = "$NAME created a new $isName '$Name'.";
            LastModified($RandomID, $USERID, $Modification);
            Notify($USERID, $ClientKeyID, $Notification);
            $response = "$isName successfully created.";
        } else {
            $response = "Something went wrong. Please try again.";
            $error = true;
        }
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
                                <h5 class="mb-0">
                                    Create a new <?php echo $isName; ?>


                                </h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "CREATE_CLIENT")) : ?>
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3"><label class="form-label">Name</label>
                                                <input type="text" name="Name" required class="form-control">
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s name</small>
                                            </div>
                                            <div class="mb-3"><label class="form-label">Manager First Name</label>
                                                <input type="text" name="ManagerFirstName" class="form-control" placeholder="Enter manager's first name">
                                                <small class="form-text text-muted">Enter the manager's first name (for email personalization)</small>
                                            </div>
                                            <div class="mb-3"><label class="form-label">Manager Last Name</label>
                                                <input type="text" name="ManagerLastName" class="form-control" placeholder="Enter manager's last name">
                                                <small class="form-text text-muted">Enter the manager's last name</small>
                                            </div>
                                        </div>
                                        <?php if (isset($_GET['isBranch'])) : ?>
                                            <div class="col-lg-6">
                                                <div class="mb-3"><label class="form-label">Client's Name</label>
                                                    <input type="text" readonly class="form-control" value="<?php echo $ClientName; ?>">
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <div class="col-lg-6">
                                                <div class="mb-3"><label class="form-label">Client Type</label>
                                                    <select required name="ClientType" class="select-input">
                                                        <?php
                                                        foreach ($clientype as $type) { ?>
                                                            <option><?php echo $type; ?></option>

                                                        <?php } ?>
                                                    </select>
                                                    <small class="form-text text-muted">Please select client type</small>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label"><?php echo $isName; ?> ID</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" value="<?php echo rand(10000, 100000); ?>" name="_client_id">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?> ID</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label"><?php echo $isName; ?> Status</label>
                                                <div class="input-group search-form">
                                                    <select class="form-control" name="Status">
                                                        <?php foreach ($clients_status as $status): ?>
                                                            <option><?php echo $status; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <small class="form-text text-muted">Please select <?php echo $isName; ?>'s status</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Email address</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" name="EmailAddress">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s email address</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Phone Number</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" name="PhoneNumber">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s contact number</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Address</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" name="Address">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s address</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">Postcode</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" name="Postcode">
                                                </div>
                                                <small class="form-text text-muted">Please enter <?php echo $isName; ?>'s postcode</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-lg-4">
                                            <div class="mb-3"><label class="form-label">City</label>
                                                <div class="input-group search-form">
                                                    <input required type="text" class="form-control" name="City">
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