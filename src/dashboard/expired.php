<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
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
                                <h5 class="mb-0">Expired Documents</h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "VIEW_EXPIRED_DOCUMENTS")) : ?>
                                    <div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>
                                                        Candidate
                                                    </th>
                                                    <th>
                                                        Email address
                                                    </th>
                                                    <th>
                                                        Phone Number
                                                    </th>
                                                    <th>Type</th>
                                                    <th>Name</th>
                                                    <th>Download</th>
                                                    <th>Issued Date</th>
                                                    <th>Expiry Date</th>
                                                    <th>Created By</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $n = 1;
                                                $currentDate = new DateTime();
                                                $query = $conn->query("SELECT * FROM `_candidates_documents` WHERE ClientKeyID = '$ClientKeyID' AND ExpiryDate < CURDATE()");
                                                while ($row = $query->fetchObject()) { ?>
                                                    <?php if (!empty($row->ExpiryDate)) : ?>
                                                        <?php
                                                        $CandidateData = $conn->query("SELECT * FROM `_candidates` WHERE CandidateID = '{$row->CandidateID}' ")->fetchObject();
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $n++; ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0"><img width="40" height="40" style="object-fit: cover;" src="<?php echo $CandidateData->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40"></div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h6 class="mb-0"><?php echo $CandidateData->Name; ?></h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><?php echo $CandidateData->Email; ?></td>
                                                            <td><?php echo $CandidateData->Number; ?></td>
                                                            <td><?php echo $row->Type; ?></td>
                                                            <td><?php echo $row->Name; ?></td>
                                                            <td>
                                                                <div class="flex-shrink-0">
                                                                    <a href="<?php echo $row->Path; ?>" target="_blank" class="btn btn-sm btn-light-secondary">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                            <path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" />
                                                                        </svg> Download
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td><?php echo FormatDate($row->IssuedDate); ?></td>
                                                            <td>
                                                                <?php
                                                                $expiryDate = DateTime::createFromFormat('Y-m-d', $row->ExpiryDate);
                                                                $isExpired = $expiryDate && $expiryDate < $currentDate;
                                                                echo empty($row->ExpiryDate) ? "No expiry date" : FormatDate($row->ExpiryDate);

                                                               
                                                                ?>
                                                            </td>
                                                            <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                            <td><?php echo FormatDate($row->Date); ?></td>
                                                        </tr>


                                                    <?php endif; ?>
                                                <?php } ?>
                                            </tbody>


                                        </table>
                                        <?php if ($query->rowCount() == 0) : ?>
                                            <div class="alert alert-danger">
                                                No data found.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else : ?>
                                    <?php
                                    DeniedAccess();
                                    ?>
                                <?php endif; ?>

                                <?php if (isset($_GET['q'])) : ?>
                                    <a href="<?php echo $LINK; ?>/key_job_area">
                                        <button class="btn btn-primary">
                                            Reset Search
                                        </button>
                                    </a>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">Advance Search</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Client Type</label>
                                    <input type="text" name="ServiceUser" class="form-control" placeholder="Enter Client Type">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Location</label>
                                    <input type="text" name="Location" class="form-control" placeholder="Enter location">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Job Role</label>
                                    <div class="input-group search-form">
                                        <input type="text" name="Specification" class="form-control" placeholder="Enter Job Role">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3"><label class="form-label">Pay Rate</label>
                                    <div class="input-group search-form">
                                        <input type="text" name="PayRate" class="form-control" placeholder="Enter Pay Rate">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3"><label class="form-label">Description</label>
                                    <div class="input-group search-form">
                                        <textarea name="Description" class="form-control" id=""></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="Search" class="btn btn-primary me-0">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</body>
<?php include "../../includes/js.php"; ?>


</html>