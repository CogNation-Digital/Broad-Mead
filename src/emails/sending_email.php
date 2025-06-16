<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}


$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : 'Emails';

$EmailID = isset($_GET['Email']) ? $_GET['Email'] : $KeyID;

$_emails_data = $conn->query("SELECT * FROM `_emails` WHERE EmailID = '$EmailID' ")->fetchObject();
$TemplateID = !$_emails_data->TemplateID ? $KeyID : $_emails_data->TemplateID;
$_email_templates_data = $conn->query("SELECT * FROM `_email_templates` WHERE TemplateID = '$TemplateID' ")->fetchObject();
$EmailListID = !$_email_templates_data  ? $KeyID : $_email_templates_data->EmailListID;

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
                            <h5>Emails</h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Template</th>
                                            <th>Email List</th>
                                            <th>Email Address</th>
                                            <th>Status</th>
                                            <th>Sent By</th>
                                            <th>Sent On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $_email_list_query = $conn->query("SELECT * FROM `_email_list` WHERE ListID = '$EmailListID' ");
                                        $i = 1;
                                        while ($row = $_email_list_query->fetchObject()) { ?>
                                            <?php if (!empty($row->Email)) : ?>
                                                <?php
                                                $_email_log = $conn->query("SELECT Status FROM `_email_logs` WHERE EmailID = '$EmailID' AND Email = '{$row->Email}' ")->fetchColumn();
                                                ?>
                                                <tr data-email="<?php echo $row->Email; ?>" data-email-id="<?php echo $EmailID; ?>" data-emaillist-id="<?php echo $EmailListID; ?>" data-template-id="<?php echo $TemplateID; ?>" data-subject="<?php echo $_emails_data->Subject; ?>" data-page-url="<?php echo $PageURL; ?>" data-user-id="<?php echo $USERID; ?>">
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $_emails_data->Subject; ?></td>
                                                    <td><?php echo $_email_templates_data->Title; ?></td>
                                                    <td><?php echo $row->Title; ?></td>
                                                    <td><?php echo $row->Email; ?></td>
                                                    <td>
                                                        <?php if (!$_email_log) : ?>
                                                            <span class="badge bg-warning">
                                                                Sending...
                                                            </span>
                                                        <?php else : ?>
                                                            <?php if ($_email_log == "Success") : ?>
                                                                <span class="badge bg-success">
                                                                    Sent
                                                                </span>
                                                            <?php else : ?>
                                                                <span class="badge bg-danger">
                                                                    <?php echo $_email_log; ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo CreatedBy($row->CreatedBy); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo FormatDate($row->Date); ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </tbody>


                                </table>

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
    const url = "<?php echo $LINK; ?>/api/v1/check_email_status";
    $.ajax({
            url: url,
            type: "POST",
            data: {
                EmailID: "<?php echo $EmailID; ?>"
            },
            success: function(response) {
                if (response == "Sending...") {
                    $('tr[data-email]').each(function() {
                        sendEmailData(this);
                    });
                } else {
                    //window.location.reload();
                 }
            },
        });

    function sendEmailData(row) {
        const data = {
            email: row.getAttribute('data-email'),
            emailID: row.getAttribute('data-email-id'),
            templateID: row.getAttribute('data-template-id'),
            subject: row.getAttribute('data-subject'),
            pageURL: row.getAttribute('data-page-url'),
            userID: row.getAttribute('data-user-id'),
            EmailListID: row.getAttribute('data-emaillist-id')
        };

        const sendEmailUrl = "<?php echo $LINK; ?>/api/v1/send_email";

        $.ajax({
            url: sendEmailUrl,
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                 if(response.status == "Success"){
                     updateStatus(row, 'Sent', 'bg-success');
                 }else{
                     updateStatus(row, 'Failed', 'bg-danger');
                 }
             },
            error: function(error) {
                console.log('Error sending email:', error);
                updateStatus(row, 'Failed', 'bg-danger');
            }
        });
    }

    function updateStatus(row, statusText, badgeClass) {
        const statusBadge = row.querySelector('.badge');
        if (statusBadge) {
            statusBadge.textContent = statusText;
            statusBadge.className = 'badge ' + badgeClass;
        }
    }
</script>



</html>