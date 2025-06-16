<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}


$ListID = isset($_GET['ListID']) ? $_GET['ListID'] : '';

 

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
                        <?php if (IsCheckPermission($USERID, "VIEW_EMAIL_LIST")) : ?>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Recipient</th>
                                                <th>Source</th>
                                                <th>Email Address</th>
                                                <th>Created By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $n = 1;
                                            $_get_email_list = $conn->query("SELECT * FROM `_email_list` WHERE ClientKeyID = '$ClientKeyID' AND ListID = '$ListID'");
                                            while ($row = $_get_email_list->fetchObject()) { ?>
                                                <?php if (!empty($row->Email)) : ?>
                                                    <?php
                                                    $IsTable = $row->Source;
                                                    $column = ($IsTable == "_clients") ? "ClientID" : "CandidateID";
                                                    if ($IsTable == "_clients" || $IsTable == "_candidates") {
                                                        $RecipientName = $conn->query("SELECT Name FROM $IsTable WHERE $column = '{$row->RecipientID}'")->fetchObject();
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $n++; ?></td>
                                                        <td>
                                                            <?php
                                                            if ($row->Source == "_others") {
                                                                echo  $row->RecipientID;
                                                            } else {
                                                                echo $RecipientName->Name;  // Get the name of the client or candidate
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php
                                                            if ($row->Source == "_others") {
                                                                echo "OTHER EMAIL";
                                                            }
                                                            if ($row->Source == "_clients") {
                                                                echo "CLIENT";
                                                            }
                                                            if ($row->Source == "_candidates") {
                                                                echo "CANDIDATE";
                                                            }
                                                            ?></td>
                                                        <td><?php echo $row->Email; ?></td>
                                                        <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                        <td><?php echo FormatDate($row->Date); ?></td>
                                                    </tr>


                                                <?php endif; ?>
                                            <?php } ?>


                                        </tbody>
                                    </table>
                                    <?php if ($_get_email_list->rowCount() == 0) : ?>
                                        <div style="padding: 10px;">
                                            <div class="alert alert-danger">
                                                No data found
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div style="padding: 10px;">
                                <?php DeniedAccess(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
<?php include "../../includes/js.php"; ?>
 

</html>