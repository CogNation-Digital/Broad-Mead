<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "All";


$invoicestatus = array("Paid", "Pending", "Overdue");
$invoice_status = array("Paid", "Pending", "Overdue", "Cancelled");

$TotalNumber = 0;
$TotalAmount = 0;
$subtotal = 0;

// Query to get the total number of distinct invoices and the total amount
$TotalInvoiceQuery = $conn->query("SELECT COUNT(DISTINCT _invoices.id) as Number, SUM(_invoice_.Rate * _invoice_.Hours) as Amount FROM `_invoices`
INNER JOIN _invoice_ ON _invoices.InvoiceID = _invoice_.InvoiceID WHERE _invoices.ClientKeyID = '$ClientKeyID'");

if ($row = $TotalInvoiceQuery->fetchObject()) {
    $TotalNumber = $row->Number;
    $subtotal = $row->Amount;

    // Calculate tax
    $taxpercent = $BANKDATA->VatPercent / 100;
    $tax = $subtotal * $taxpercent;

    // Calculate total amount including tax
    $TotalAmount = $subtotal + $tax;
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
                <div class="col-xxl-12">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="mb-2 d-flex align-items-center justify-content-between gap-1">
                                        <h6 class="mb-0">Total Invoices</h6>

                                    </div>
                                    <div class="row g-2 align-items-center">
                                        <div class="col-6">
                                            <h5 class="mb-2 mt-3"><?php echo $Currency; ?> <?php echo number_format($TotalAmount, 2); ?></h5>
                                            <div class="d-flex align-items-center gap-1">
                                                <h5 class="mb-0"><?php echo number_format($TotalNumber); ?></h5>
                                                <p class="mb-0 text-muted d-flex align-items-center gap-2"><?php echo ($TotalNumber == 1) ? "invoice" : "invoices"; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div id="total-invoice-1-chart" style="min-height: 55px;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        foreach ($invoicestatus as $status) { ?>

                            <?php
                            $TotalNumber = 0;
                            $TotalAmount = 0;
                            $subtotal = 0;
                            $TotalInvoiceQuery = $conn->query("SELECT COUNT(DISTINCT _invoices.id) as Number, SUM(_invoice_.Rate * _invoice_.Hours) as Amount FROM `_invoices`
                          INNER JOIN _invoice_ ON _invoices.InvoiceID = _invoice_.InvoiceID WHERE _invoices.ClientKeyID = '$ClientKeyID' AND _invoices.Status = '$status' ");

                            if ($row = $TotalInvoiceQuery->fetchObject()) {
                                $TotalNumber = $row->Number;
                                $subtotal = $row->Amount;

                                // Calculate tax
                                $taxpercent = $BANKDATA->VatPercent / 100;
                                $tax = $subtotal * $taxpercent;

                                // Calculate total amount including tax
                                $TotalAmount = $subtotal + $tax;
                            }
                            ?>
                            <div class="col-md-3">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="mb-2 d-flex align-items-center justify-content-between gap-1">
                                            <h6 class="mb-0"><?php echo $status; ?></h6>
                                            <p class="mb-0 text-muted d-flex align-items-center gap-1">
                                                <?php if ($status == "Paid") : ?>
                                                    <svg style="position: absolute; right: 30px;" class="text-success" xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="m23.5 17l-5 5l-3.5-3.5l1.5-1.5l2 2l3.5-3.5zM6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h7.8c-.5-.9-.8-1.9-.8-3v1h-2v-8h2v7c0-3.3 2.7-6 6-6c.3 0 .7 0 1 .1V8l-6-6zm7 1.5L18.5 9H13zM7 14h2v6H7z" />
                                                    </svg>
                                                <?php endif; ?>

                                                <?php if ($status == "Overdue") : ?>

                                                    <svg style="position: absolute; right: 30px;" class="text-danger" xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M20 17h2v-2h-2zm0-10v6h2V7M6 16h5v2H6m0-6h8v2H6M4 2c-1.11 0-2 .89-2 2v16c0 1.11.89 2 2 2h12c1.11 0 2-.89 2-2V8l-6-6M4 4h7v5h5v11H4Z" />
                                                    </svg>

                                                <?php endif; ?>

                                                <?php if ($status == "Pending") : ?>
                                                    <svg style="position: absolute; right: 30px;" class="text-warning" xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m-1-4h2V7h-2zm1 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22" />
                                                    </svg>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="row g-2 align-items-center">
                                            <div class="col-6">
                                                <h5 class="mb-2 mt-3"><?php echo $Currency; ?> <?php echo number_format($TotalAmount, 2); ?></h5>
                                                <div class="d-flex align-items-center gap-1">
                                                    <h5 class="mb-0"><?php echo number_format($TotalNumber); ?></h5>
                                                    <p class="mb-0 text-muted d-flex align-items-center gap-2"><?php echo ($TotalNumber == 1) ? "invoice" : "invoices"; ?></p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div id="total-invoice-1-chart" style="min-height: 55px;">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>


                    </div> 
                </div>
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-body">


                            <ul class="nav nav-tabs invoice-tab border-bottom mb-3" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation"> <a href="<?php echo $LINK; ?>/invoices"> <button class="nav-link <?php echo ($isTab == "All") ? "active" : ""; ?>" type="button" aria-selected="true"><span class="d-flex align-items-center gap-2">All</span></button></a></li>
                                <?php
                                foreach ($invoice_status as $status) { ?>
                                    <?php
                                    $statusClass = '';

                                    switch ($status) {
                                        case 'Paid':
                                            $statusClass = 'bg-light-success';
                                            break;
                                        case 'Pending':
                                            $statusClass = 'bg-light-info';
                                            break;
                                        case 'Overdue':
                                            $statusClass = 'bg-light-warning';
                                            break;
                                        case 'Cancelled':
                                            $statusClass = 'bg-light-danger';
                                            break;
                                        default:
                                            $statusClass = 'bg-light-light'; // Default class if none of the statuses match
                                            break;
                                    }

                                    $invoice_num = $conn->query("SELECT COUNT(id) as Num FROM `_invoices` WHERE ClientKeyID = '$ClientKeyID' AND Status = '$status' ")->fetchColumn();
                                    ?>

                                    <li class="nav-item" role="presentation">
                                        <a href="<?php echo $LINK; ?>/invoices?isTab=<?php echo $status; ?>">
                                            <button class="nav-link <?php echo ($isTab == $status) ? "active" : ""; ?>">
                                                <span class="d-flex align-items-center gap-2">
                                                    <?php echo $status; ?>
                                                    <span class="avtar rounded-circle <?php echo $statusClass; ?>"><?php echo $invoice_num; ?></span>
                                                </span>
                                            </button>
                                        </a>

                                    </li>
                                <?php } ?>

                            </ul>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Client</th>
                                            <th>Branch</th>
                                            <th>Invoice Number</th>
                                            <th>Invoice Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Sub Total</th>
                                            <th>Taxes</th>
                                            <th>Grand Total</th>
                                            <th>View</th>
                                            <th>Created By</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $n = 1;
                                        $query = "SELECT * FROM `_invoices` WHERE ClientKeyID = '$ClientKeyID'";
                                        if ($isTab !== "All") {
                                            $query .= " AND Status = '$isTab'";
                                        }
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        while ($row = $stmt->fetchObject()) { ?>
                                            <?php
                                            $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                            $subtotal = $conn->query("SELECT SUM(Hours * Rate) FROM `_invoice_` WHERE InvoiceID = '{$row->InvoiceID}' ")->fetchColumn();
                                            $taxes = $subtotal * $BANKDATA->VatPercent / 100;

                                            $ClientName = $conn->query("SELECT Name FROM _clients WHERE ClientID = '{$row->hasClientID}' ")->fetchColumn();
                                            $BranchName = $conn->query("SELECT Name FROM _clients WHERE ClientID = '{$row->hasBranchID}' ")->fetchColumn();
                                            ?>
                                            <tr>
                                                <td><?php echo $n++; ?></td>
                                                <td><?php echo $ClientName; ?></td>
                                                <td><?php echo (!$BranchName) ? NoData() : $BranchName; ?></td>
                                                <td><?php echo $row->Number; ?></td>
                                                <td><?php echo FormatDate($row->InvoiceDate); ?></td>
                                                <td><?php echo FormatDate($row->DueDate); ?></td>
                                                <td>
                                                    <?php if ($row->Status == "Paid") : ?>
                                                        <div class="badge bg-success">Paid</div>
                                                    <?php else : ?>
                                                        <div class="badge bg-danger"><?php echo $row->Status; ?></div>

                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $Currency; ?> <?php echo number_format($subtotal, 2); ?></td>
                                                <td><?php echo $Currency; ?> <?php echo number_format($taxes, 2); ?></td>
                                                <td><?php echo $Currency; ?> <?php echo number_format($subtotal + $taxes, 2); ?></td>
                                                <td>
                                                    <div class="flex-shrink-0">
                                                        <a href="<?php echo $LINK; ?>/generate_timesheet/?ID=<?php echo $row->TimesheetID; ?>&isTab=Invoice" class="btn btn-sm btn-light-secondary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="M5 3c-1.11 0-2 .89-2 2v14c0 1.11.89 2 2 2h14c1.11 0 2-.89 2-2V5c0-1.11-.89-2-2-2zm0 2h14v14H5zm2 2v2h10V7zm0 4v2h10v-2zm0 4v2h7v-2z" />
                                                            </svg> View
                                                        </a>
                                                    </div>
                                                </td>
                                                <td><?php echo $CreatedBy; ?></td>

                                                <td><?php echo FormatDate($row->Date); ?></td>

                                            </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php if ($stmt->rowCount() == 0) : ?>
                                    <div class="alert alert-danger">No data found</div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="CreateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreateModalLabel">Create Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (IsCheckPermission($USERID, "CREATE_SHIFT")) : ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="email">Vacancy</label>
                                <select name="VacancyID" class="form-control">
                                    <?php
                                    $query = "SELECT * FROM `vacancies` WHERE ClientKeyID = '$ClientKeyID' ";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetchObject()) {
                                        echo '<option value="' . $row->VacancyID . '">' . $row->Title . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="CreateShift" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                <?php else : ?>
                    <div style="padding: 10px;">
                        <?php DeniedAccess(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="DeleteModal" class="modal fade" tabindex="-1" aria-labelledby="DeleteModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete? This action cannot be undone.</p>
                    <div class="row" style="padding: 10px;">
                        <input required type="text" class="form-control" id="reason" name="reason" placeholder="Give a reason">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                </div>
            </div>
        </div>
    </div>

</body>
<?php include "../../includes/js.php"; ?>


</html>