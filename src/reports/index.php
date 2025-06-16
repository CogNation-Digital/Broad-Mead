<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['SearchReport'])) {
    $CreatedBy = $_POST['CreatedBy'];

    $query = "INSERT INTO `search_queries`(`SearchID`, `column`, `value`) VALUES ('$SearchID','CreatedBy','$CreatedBy')";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    // Current Page URL

    // Parse the URL to get query parameters
    $parsed_url = parse_url($PageURL);
    $query_params = [];
    if (isset($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query_params);
    }

    // Check if 'q' parameter exists and update it
    if (isset($query_params['q'])) {
        // Update the 'q' parameter with new SearchID
        $query_params['q'] = $SearchID;
    } else {
        // Add 'q' parameter with new SearchID
        $query_params['q'] = $SearchID;
    }

    // Rebuild the query string
    $new_query_string = http_build_query($query_params);

    // Rebuild the full URL
    $new_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'] . '?' . $new_query_string;

    // Redirect to the new URL
    header("Location: $new_url");
    exit();
}
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "Summary";
$QueryID = isset($_GET['q']) ? $_GET['q'] : "";

$SearchQueryURL = "&q=$QueryID";

$CREATEDBY = $conn->query("SELECT value FROM `search_queries` WHERE `SearchID` = '$QueryID' AND `column` = 'CreatedBy' ")->fetchColumn();

$HAS_CREATEDBY = !empty($CREATEDBY) ? " AND CreatedBy = '$CREATEDBY'" : "";

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
</style>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../../includes/sidebar.php"; ?>
    <?php include "../../includes/header.php"; ?>
    <?php include "../../includes/toast.php"; ?>

    <div class="pc-container" style="margin-left: 280px;">
        <div class="pc-content">
            <?php include "../../includes/breadcrumb.php"; ?>
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "Summary") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report?1=1<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">Summary Report</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "KPIs") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=KPIs<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">KPIs</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "KeyJobAreas") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=KeyJobAreas<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">Key Job Areas</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "Clients") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=Clients<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">clients</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "Candidates") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=Candidates<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">Candidates</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "Vacancy") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=Vacancy<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">Vacancy</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "Interview") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=Interview<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">Interviews</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "Invoices") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=Invoices<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">Invoices</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "Shifts") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=Shifts<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">Shifts</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link text-uppercase <?php echo ($isTab == "Timesheets") ? "active is-active" : ""; ?>" href="<?php echo $LINK; ?>/report/?isTab=Timesheets<?php echo (!empty($QueryID)) ? $SearchQueryURL : "" ?>">Timesheets</a></li>

                <li class="nav-item" data-bs-toggle="modal" data-bs-target="#SearchModal" style="cursor: pointer;">
                    <a class="nav-link text-uppercase"><svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="m19.6 21l-6.3-6.3q-.75.6-1.725.95T9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l6.3 6.3zM9.5 14q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14" />
                        </svg>
                    </a>
                </li>
            </ul>
            <?php if (!empty($QueryID)) : ?>
                <a href="<?php echo $LINK; ?>/report/?isTab=<?php echo $isTab; ?>">
                    <div style="padding-bottom: 10px;">
                        <button class="btn btn-primary">
                            Reset Search
                        </button>
                    </div>
                </a>


            <?php endif; ?>
            <?php if ($isTab == "Summary") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_SUMMARY_REPORT")) : ?>
                    <?php include "report/summary.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($isTab == "KPIs") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_KPIs_REPORT")) : ?>
                    <?php include "report/kpi.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($isTab == "KeyJobAreas") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_KEY_JOB_AREA_REPORT")) : ?>
                    <?php include "report/kja.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($isTab == "Clients") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_CLIENTS_REPORT")) : ?>
                    <?php include "report/clients.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($isTab == "Candidates") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_CANDIDATES_REPORT")) : ?>
                    <?php include "report/candidates.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($isTab == "Vacancy") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_VACANCY_REPORT")) : ?>
                    <?php include "report/vacancy.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($isTab == "Interview") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_INTERVIEW_REPORT")) : ?>
                    <?php include "report/interviews.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($isTab == "Invoices") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_INVOICES_REPORT")) : ?>
                    <?php include "report/invoices.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($isTab == "Shifts") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_SHIFTS_REPORT")) : ?>
                    <?php include "report/shifts.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($isTab == "Timesheets") : ?>
                <?php if (IsCheckPermission($USERID, "VIEW_TIMESHEET_REPORT")) : ?>
                    <?php include "report/timesheet.php" ?>
                <?php else : ?>
                    <?php DeniedAccess() ?>
                <?php endif; ?>
            <?php endif; ?>
            <div id="SearchModal" class="modal fade" tabindex="-1" aria-labelledby="SearchModalTitle" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="SearchModalTitle">Search</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="Subject">User</label>
                                            <select name="CreatedBy" class="form-control">
                                                <?php
                                                $get_users = $conn->query("SELECT * FROM users WHERE ClientKeyID = '$ClientKeyID' ");
                                                while ($row = $get_users->fetchObject()) { ?>
                                                    <option value="<?php echo $row->UserID; ?>"><?php echo $row->Name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="SearchReport" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<?php include "../../includes/js.php"; ?>




</html>