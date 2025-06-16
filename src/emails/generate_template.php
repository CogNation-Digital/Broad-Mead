<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}


$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : 'Emails';
$TemplateID = isset($_GET['TemplateID']) ? $_GET['TemplateID'] : $RandomID;


$TemplateData = $conn->query("SELECT * FROM `_email_templates` WHERE TemplateID = '$TemplateID'")->fetchObject();
$Title = !$TemplateData ? "" : $TemplateData->Title;
$EmailListID = !$TemplateData ? "" : $TemplateData->EmailListID;
$Template = !$TemplateData ? "<p></p>" : $TemplateData->Template;


if (isset($_POST['submit'])) {
    $Title = $_POST['Title'];
    $ListID = $_POST['ListID'];
    $templateContent = empty($_POST['templateContent']) ? $Template : $_POST['templateContent'];

    // Check for empty fields
    if (empty($Title)) {
        $response = "Title cannot be empty.";
        $error = 1;
    } elseif (empty($ListID)) {
        $response = "Email List ID cannot be empty.";
        $error = 1;
    } elseif (empty($templateContent)) {
        $response = "Template content cannot be empty.";
        $error = 1;
    } else {
        try {
            // Check if TemplateID exists in the database
            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM `_email_templates` WHERE `TemplateID` = :TemplateID");
            $checkStmt->bindParam(':TemplateID', $TemplateID);
            $checkStmt->execute();
            $templateExists = $checkStmt->fetchColumn();

            if ($templateExists) {
                // Update existing template
                $stmt = $conn->prepare("UPDATE `_email_templates` SET `ClientKeyID` = :ClientKeyID, `Title` = :Title, `EmailListID` = :ListID, `Template` = :templateContent WHERE `TemplateID` = :TemplateID");
                $stmt->bindParam(':ClientKeyID', $ClientKeyID);
                $stmt->bindParam(':TemplateID', $TemplateID);
                $stmt->bindParam(':Title', $Title);
                $stmt->bindParam(':ListID', $ListID);
                $stmt->bindParam(':templateContent', $templateContent);
            } else {
                // Insert new template
                $stmt = $conn->prepare("INSERT INTO `_email_templates`(`ClientKeyID`,`TemplateID`, `Title`, `EmailListID`, `Template`, `CreatedBy`, `Date`) VALUES (:ClientKeyID, :TemplateID, :Title, :ListID, :templateContent, :CreatedBy, :Date)");
                $stmt->bindParam(':ClientKeyID', $ClientKeyID);
                $stmt->bindParam(':TemplateID', $TemplateID);
                $stmt->bindParam(':Title', $Title);
                $stmt->bindParam(':ListID', $ListID);
                $stmt->bindParam(':templateContent', $templateContent);
                $stmt->bindParam(':CreatedBy', $USERID);
                $stmt->bindParam(':Date', $date);
            }

            // Bind parameters


            // Execute the statement
            $stmt->execute();
            $response = $templateExists ? "Template updated successfully." : "Template created successfully.";
            $error = 0;
        } catch (PDOException $e) {
            $response = "Error: " . $e->getMessage();
            $error = 1;
        }
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
                            <h5>Email Template</h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php if (IsCheckPermission($USERID, "CREATE_EMAIL_TEMPLATE")) : ?>
                                    <div class="col-md-12">
                                        <form method="POST">

                                            <div class="card shadow-none border mb-0 h-100">
                                                <div class="card-body">
                                                    <h6 class="mb-2">Email Template</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" value="<?php echo $Title; ?>" class="form-control" name="Title">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email List</label>
                                                                <select name="ListID" class="form-control">
                                                                    <?php
                                                                    $_get_email_list = $conn->query("SELECT * FROM `_email_list` WHERE CreatedBy = '$USERID' GROUP BY ListID ");
                                                                    while ($list = $_get_email_list->fetchObject()) { ?>
                                                                        <option <?php echo ($EmailListID == $list->ListID) ? "selected" : ""; ?> value="<?php echo $list->ListID; ?>"><?php echo $list->Title; ?></option>
                                                                    <?php }  ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Template</label>
                                                                <div style="margin: 10px;">
                                                                    <div id="toolbar-container">
                                                                        <span class="ql-formats">
                                                                            <select class="ql-font"></select>
                                                                            <select class="ql-size"></select>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-bold"></button>
                                                                            <button class="ql-italic"></button>
                                                                            <button class="ql-underline"></button>
                                                                            <button class="ql-strike"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <select class="ql-color"></select>
                                                                            <select class="ql-background"></select>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-script" value="sub"></button>
                                                                            <button class="ql-script" value="super"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-header" value="1"></button>
                                                                            <button class="ql-header" value="2"></button>
                                                                            <button class="ql-blockquote"></button>
                                                                            <button class="ql-code-block"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-list" value="ordered"></button>
                                                                            <button class="ql-list" value="bullet"></button>
                                                                            <button class="ql-indent" value="-1"></button>
                                                                            <button class="ql-indent" value="+1"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-direction" value="rtl"></button>
                                                                            <select class="ql-align"></select>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-link"></button>
                                                                            <button class="ql-image"></button>
                                                                            <button class="ql-video"></button>
                                                                        </span>
                                                                        <span class="ql-formats">
                                                                            <button class="ql-clean"></button>
                                                                        </span>
                                                                    </div>
                                                                    <div id="editor-container" style="height: 400px;"><?php echo $Template; ?></div>
                                                                    <div style="display: none;">
                                                                        <input type="hidden" name="templateContent" id="templateContent">

                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4" style="margin-left: 10px;">
                                                            <button class="btn btn-primary" name="submit">Save Template</button>
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
    var quill = new Quill('#editor-container', {
        modules: {
            toolbar: '#toolbar-container'
        },
        theme: 'snow'
    });

    function imageHandler() {
        var range = quill.getSelection();
        var value = prompt('Enter the image URL');
        if (value) {
            quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
        }
    }

    function videoHandler() {
        var range = quill.getSelection();
        var value = prompt('Enter the video URL');
        if (value) {
            quill.insertEmbed(range.index, 'video', value, Quill.sources.USER);
        }
    }

    var toolbar = quill.getModule('toolbar');
    toolbar.addHandler('image', imageHandler);
    toolbar.addHandler('video', videoHandler);

    // Update the hidden input field whenever there is a change in the Quill editor
    quill.on('text-change', function() {
        var content = quill.root.innerHTML;
        console.log(content); // Log the content for debugging
        document.querySelector('#templateContent').value = content;
    });
</script>


</html>