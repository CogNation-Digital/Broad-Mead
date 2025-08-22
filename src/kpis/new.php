<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
  
    header("location: $LINK/login ");
}
$KpiID = isset($_GET['ID']) && !empty($_GET['ID']) ? $_GET['ID'] : bin2hex(random_bytes(16));
if (isset($_POST['submit'])) {
    $user = $_POST['user'];
    $EndDate = $_POST['EndDate']; 


    $endDateObj = new DateTime($EndDate);


    $endDateObj->modify('-7 days');

   
    $StartDate = $endDateObj->format('Y-m-d');
    $Description = $_POST['Description'];

    if (empty($user) || empty($StartDate) || empty($EndDate) || empty($Description)) {
        $response = "Something went wrong. Please try again";
        $error = 1;
    } else {
        $checkQuery = "SELECT COUNT(*) FROM `_kpis` WHERE `KpiID` = :kpiID";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bindParam(':kpiID', $KpiID, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $response = "KPI ID already exists. Please try again.";
            $error = 1;
        } else {
            $query = "INSERT INTO `_kpis`(`ClientKeyID`, `KpiID`, `UserID`, `StartDate`, `EndDate`, `Description`, `CreatedBy`, `Date`) 
                      VALUES (:clientKeyID, :kpiID, :userID, :startDate, :endDate, :description, :createdBy, :date)";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':clientKeyID', $ClientKeyID, PDO::PARAM_STR);
            $stmt->bindParam(':kpiID', $KpiID, PDO::PARAM_STR);
            $stmt->bindParam(':userID', $user, PDO::PARAM_STR);
            $stmt->bindParam(':startDate', $StartDate, PDO::PARAM_STR);
            $stmt->bindParam(':endDate', $EndDate, PDO::PARAM_STR);
            $stmt->bindParam(':description', $Description, PDO::PARAM_STR);
            $stmt->bindParam(':createdBy', $USERID, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $Modification = "Created key performance indicator.";
                $Notification = "$NAME created a new key performance indicator.";
                LastModified($KpiID, $USERID, $Modification);
                Notify($USERID, $ClientKeyID, $Notification);
                header("Location: $LINK/create_weekly_kpis/?ID=$KpiID&Add_KPIs=true");
            } else {
                $response = "Something went wrong. Please try again";
                $error = 1;
            }
        }
    }
}
if (isset($_POST['addkpi'])) {
    $check = $conn->query("SELECT * FROM _kpis_targets WHERE KpiID = '$RandomID'");
    if ($check->rowCount() > 0) {
        $error = 1;
        $response = "KPI ID already exists. Please try again.";
    } else {
        $query = "INSERT INTO `_kpis_targets`(`ClientKeyID`, `KpiID`, `_kpi_id`, `CreatedBy`, `Date`) 
                  VALUES (:ClientKeyID, :KpiID, :_kpi_id, :CreatedBy, :Date)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ClientKeyID', $ClientKeyID, PDO::PARAM_STR);
        $stmt->bindParam(':KpiID', $RandomID, PDO::PARAM_STR);
        $stmt->bindParam(':_kpi_id', $KpiID, PDO::PARAM_STR);
        $stmt->bindParam(':CreatedBy', $USERID, PDO::PARAM_STR);
        $stmt->bindParam(':Date', $date, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $response = "KPI added successfully.";
            $error = 0;
        } else {
            $response = "Something went wrong. Please try again.";
            $error = 1;
        }
    }
}
if (isset($_POST['update'])) {
    $KpiID = $_POST['KpiID'];
    $kpi = $_POST['kpi'];
    $target = $_POST['target'];
    $query = "UPDATE `_kpis_targets` SET Name = :kpi, Target = :target WHERE KpiID = :KpiID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':kpi', $kpi);
    $stmt->bindParam(':target', $target, PDO::PARAM_INT);
    $stmt->bindParam(':KpiID', $KpiID);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
    }
}

if (isset($_POST['DeleteKPI'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM `_kpis_targets` WHERE KpiID = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'KPI deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete KPI']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>

<style>
    .CardKPI:hover .delete {
        display: block;
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
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0"><?php echo $page; ?></h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (IsCheckPermission($USERID, "CREATE_KPIs")) : ?>
                                <div class="card shadow-none border">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">
                                                <?php
                                                // Heading only, no input here
                                                ?>
                                            </h6>
                                            <?php if (isset($_GET['Add_KPIs'])) : ?>

                                                <form method="post">
                                                    <button class="btn btn-primary" name="addkpi">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="currentColor" d="M19 12.998h-6v6h-2v-6H5v-2h6v-6h2v6h6z" />
                                                        </svg>
                                                        Add KPI
                                                    </button>
                                                    <span id="deleteKpiBtn" class="btn btn-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                        Delete KPI
                                                    </span>
                                                </form>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <form method="POST">
                                        <?php if (isset($_GET['Add_KPIs'])) : ?>
                                            <div class="card-body">
                                                <?php
                                                // Fetch KPI info for context
                                                $kpiInfo = $conn->query("SELECT * FROM `_kpis` WHERE KpiID = '$KpiID'")->fetch(PDO::FETCH_OBJ);
                                                $consultantName = '';
                                                $weekStart = '';
                                                $weekEnd = '';
                                                if ($kpiInfo) {
                                                    $consultant = $conn->query("SELECT Name FROM users WHERE UserID = '{$kpiInfo->UserID}'")->fetchColumn();
                                                    $consultantName = $consultant ? $consultant : $kpiInfo->UserID;
                                                    $weekStart = $kpiInfo->StartDate;
                                                    $weekEnd = $kpiInfo->EndDate;
                                                }
                                                ?>
                                                <div class="alert alert-info mb-4">
                                                    <strong>Consultant:</strong> <?php echo htmlspecialchars($consultantName); ?><br>
                                                    <strong>Week:</strong> <?php echo htmlspecialchars($weekStart); ?> to <?php echo htmlspecialchars($weekEnd); ?>
                                                </div>
                                                <?php
                                                $q = $conn->query("SELECT * FROM `_kpis_targets` WHERE _kpi_id = '$KpiID' ");
                                                while ($r = $q->fetchObject()) { ?>
                                                    <div class="card CardKPI" data-id="<?php echo $r->KpiID; ?>">
                                                        <div class="row g-3" style="padding: 15px;">
                                                            <div class="col-lg-10">
                                                                <div class="mb-3" style="display: flex;">
                                                                    <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $r->KpiID; ?>" id="flexCheckDefault<?php echo $r->id ?>" style="margin-top: 15px;">
                                                                    <input type="text" class="form-control kpi" value="<?php echo $r->Name; ?>" placeholder="KPI" data-id="<?php echo $r->KpiID; ?>" style="margin-left: 5px;">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="mb-3">
                                                                    <input type="number" class="form-control target" value="<?php echo (empty($r->Target) ? 0 : $r->Target); ?>" placeholder="Target" data-id="<?php echo $r->KpiID; ?>">
                                                                </div>
                                                                <span class="delete" style="display: none; cursor: pointer; position: absolute; top: 60px; right: 5px;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 1024 1024">
                                                                        <path fill="red" d="M160 256H96a32 32 0 0 1 0-64h256V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64h-64v672a32 32 0 0 1-32 32H192a32 32 0 0 1-32-32zm448-64v-64H416v64zM224 896h576V256H224zm192-128a32 32 0 0 1-32-32V416a32 32 0 0 1 64 0v320a32 32 0 0 1-32 32m192 0a32 32 0 0 1-32-32V416a32 32 0 0 1 64 0v320a32 32 0 0 1-32 32" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($q->rowCount() == 0) : ?>
                                                    <div class="alert alert-danger">
                                                        No KPIs found. Please create KPIs first.
                                                    </div>
                                                                            $weekending.val(today.toISOString().slice(0, 10));
                            <?php else : ?>
                                <div class="mb-3 mt-3"><label class="form-label">Search and select the consultant</label>
                                    <select name="user" class="select-input" style="padding: 20px;">
                                        <option value=""></option>
                                        <?php
                                        $q = $conn->query("SELECT * FROM `users` WHERE ClientKeyID = '$ClientKeyID' ");
                                        while ($r = $q->fetchObject()) {
                                            echo "<option value='$r->UserID'>$r->Name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date (any day, any year)</label>
                                    <input type="date" name="EndDate" class="form-control" id="weekendingDate" placeholder="Select Date" min="2021-01-01">
                                </div>
                                        <div class="col-12">
                                            <div class="card shadow-none border mb-0">
                                                <div class="card-body">
                                                    <h6 class="mb-3">Description</h6>
                                                    <div class="mb-3">
                                                        <textarea name="Description" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="d-grid gap-2 mt-2"><button class="btn btn-primary" type="submit" name="submit">Submit</button></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>


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
</body>

<?php include "../../includes/js.php"; ?>

<script>
 

    $(document).ready(function() {
        var $weekending = $('#weekendingDate');
        $weekending.attr('min', '2021-01-01');
        // Set default to today if empty
        if (!$weekending.val()) {
            var today = new Date();
            $weekending.val(today.toISOString().slice(0, 10));
        }
    });

  
    $(document).ready(function() {
        $('.kpi, .target').on('keyup', function() {
            var dataId = $(this).data('id');
            var kpiValue = $('.kpi[data-id="' + dataId + '"]').val();
            var targetValue = $('.target[data-id="' + dataId + '"]').val();
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    KpiID: dataId,
                    kpi: kpiValue,
                    target: targetValue,
                    update: true,
                },
                success: function(response) {},
                error: function(xhr, status, error) {
                    ShowToast('Something went wrong. Please try again later');
                }
            });
        });
    });
    $(document).ready(function() {
        $('#deleteKpiBtn').click(function() {
            var selectedIds = [];
            $('.checkbox-item:checked').each(function() {
                selectedIds.push($(this).val());
            });
            if (selectedIds.length > 0) {
                $.each(selectedIds, function(index, kpiId) {
                    var Data = {
                        'id': kpiId,
                        'DeleteKPI': true
                    };
                    $.ajax({
                        url: window.location.href,
                        type: 'POST',
                        data: Data,
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            ShowToast('Error deleting KPI with ID ' + kpiId + ':', error);
                        }
                    });
                });
            } else {
                ShowToast('Please select KPIs to delete.');
            }
        });
    });
</script>
</html>
<?php endif; ?>