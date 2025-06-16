<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}


$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : 'Emails';
$EmailID = isset($_GET['EmailID']) ? $_GET['EmailID'] : $RandomID;


$EmailData = $conn->query("SELECT * FROM `_emails` WHERE EmailID = '$EmailID'")->fetchObject();
$Subject = !$EmailData ? "" : $EmailData->Subject;
$TemplateID = !$EmailData ? "" : $EmailData->TemplateID;



if (isset($_POST['Draft'])) {
    $Subject = $_POST['Subject'];
    $EmailTemplate = $_POST['EmailTemplate'];
    $Status = "Draft";

    try {
        // Check if EmailID exists in the database
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM `_emails` WHERE `EmailID` = :EmailID");
        $checkStmt->bindParam(':EmailID', $EmailID);
        $checkStmt->execute();
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
            // Update existing email draft
            $stmt = $conn->prepare("UPDATE `_emails` SET `Subject` = :Subject, `TemplateID` = :TemplateID, `Status` = :Status  WHERE `EmailID` = :EmailID");
            $stmt->bindParam(':EmailID', $EmailID);
            $stmt->bindParam(':Subject', $Subject);
            $stmt->bindParam(':TemplateID', $EmailTemplate);
            $stmt->bindParam(':Status', $Status);
        } else {
            // Insert new email draft
            $stmt = $conn->prepare("INSERT INTO `_emails`(`ClientKeyID`,`EmailID`, `Subject`, `TemplateID`, `Status`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :EmailID, :Subject, :TemplateID, :Status, :CreatedBy, :Date)");
            $stmt->bindParam(':ClientKeyID', $ClientKeyID);
            $stmt->bindParam(':EmailID', $EmailID);
            $stmt->bindParam(':Subject', $Subject);
            $stmt->bindParam(':TemplateID', $EmailTemplate);
            $stmt->bindParam(':Status', $Status);
            $stmt->bindParam(':CreatedBy', $USERID);
            $stmt->bindParam(':Date', $date);
        }

        $stmt->execute();
        $response = $emailExists ? "Email draft updated successfully." : "Email draft saved successfully.";
    } catch (PDOException $e) {
        $response = "Error: " . $e->getMessage();
    }
}

if (isset($_POST['Send'])) {
    $Subject = $_POST['Subject'];
    $EmailTemplate = $_POST['EmailTemplate'];
    $Status = "Sending...";

    try {
        // Check if EmailID exists in the database
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM `_emails` WHERE `EmailID` = :EmailID");
        $checkStmt->bindParam(':EmailID', $EmailID);
        $checkStmt->execute();
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
            // Update existing email draft
            $stmt = $conn->prepare("UPDATE `_emails` SET `Subject` = :Subject, `TemplateID` = :TemplateID, `Status` = :Status  WHERE `EmailID` = :EmailID");
            $stmt->bindParam(':EmailID', $EmailID);
            $stmt->bindParam(':Subject', $Subject);
            $stmt->bindParam(':TemplateID', $EmailTemplate);
            $stmt->bindParam(':Status', $Status);
        } else {
            // Insert new email draft
            $stmt = $conn->prepare("INSERT INTO `_emails`(`ClientKeyID`,`EmailID`, `Subject`, `TemplateID`, `Status`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :EmailID, :Subject, :TemplateID, :Status, :CreatedBy, :Date)");
            $stmt->bindParam(':ClientKeyID', $ClientKeyID);
            $stmt->bindParam(':EmailID', $EmailID);
            $stmt->bindParam(':Subject', $Subject);
            $stmt->bindParam(':TemplateID', $EmailTemplate);
            $stmt->bindParam(':Status', $Status);
            $stmt->bindParam(':CreatedBy', $USERID);
            $stmt->bindParam(':Date', $date);
        }

        $stmt->execute();
        $openNewTab = true;

        if ($openNewTab) {
            echo "<script type='text/javascript'>
                window.open('$LINK/sending_email/?Email=$EmailID', '_blank');
              </script>";
        }
    } catch (PDOException $e) {
        $response = "Error: " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>
<style>
    .is-active {
        border-bottom: 2px solid var(--bs-primary) !important;
    }

    .nav-tabs a {
        color: <?php echo ($theme == "dark") ? "white" : "black"; ?> !important;
    }

    .border-red {
        border: 1px solid red !important;
    }

    .ql-picker-options {
        color: black !important;
    }
</style>

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
                        <div class="card-header d-flex align-items-center justify-content-between py-3">
                            <h5>Compose Email</h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php if (IsCheckPermission($USERID, "CREATE_EMAIL_TEMPLATE")) : ?>
                                    <div class="col-md-12">
                                        <form method="POST">

                                            <div class="card shadow-none border mb-0 h-100">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Compose Email</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Subject</label>
                                                                <input type="text" value="<?php echo $Subject; ?>" class="form-control" name="Subject">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email Template</label>
                                                                <select id="EmailTemplate" name="EmailTemplate" class="form-control">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    $_get_email_templates = $conn->query("SELECT * FROM `_email_templates` WHERE CreatedBy = '$USERID' GROUP BY TemplateID ");
                                                                    while ($list = $_get_email_templates->fetchObject()) {
                                                                        $EmailName = $conn->query("SELECT Title FROM `_email_list` WHERE ListID = '{$list->EmailListID}'")->fetchColumn();
                                                                    ?>
                                                                        <option data-email="<?php echo $EmailName; ?>" <?php echo ($TemplateID == $list->TemplateID) ? "Selected" : ""; ?> value="<?php echo $list->TemplateID; ?>"><?php echo $list->Title; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email List</label>
                                                                <input type="text" readonly id="ListID" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Attachment</label>
                                                                <input disabled name="Attachment" class="form-control">
                                                                <span class="text-danger">You cannot upload file at the moment</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12" style="margin-left: 10px;">
                                                            <button class="btn btn-primary" style="width: 100px;" name="Send">Next</button>
                                                            <button class="btn btn-secondary" style="width: 100px;" name="Draft">Draft</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                <?php else : ?>
                                    <?php DeniedAccess(); ?>
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
    document.getElementById('EmailTemplate').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var emailName = selectedOption.getAttribute('data-email');
        document.getElementById('ListID').value = emailName ? emailName : '';
    });


    var selectedValue = $("#EmailTemplate option:selected").attr("data-email");
    $("#ListID").val(selectedValue);
</script>


</html>