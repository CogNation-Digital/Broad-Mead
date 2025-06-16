<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}


$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : 'Candidates';

$ListID = $_GET['ListID'];

$EmailListTitle = $conn->query("SELECT Title FROM `_email_list` WHERE ListID = '$ListID' ")->fetchColumn();



if (isset($_POST['SearchCandidates'])) {
    $Name = $_POST['Name'];
    $JobTitle = $_POST['JobTitle'];
    $IDNumber = $_POST['IDNumber'];
    $EmailAddress = $_POST['Email'];
    $PhoneNumber = $_POST['Number'];
    $Address = $_POST['Address'];
    $Postcode = $_POST['Postcode'];
    $City = $_POST['City'];
    $Status = $_POST['Status'];
    if (!empty($SearchID)) {
        $query = $conn->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
                  VALUES (:SearchID, :column, :value)");

        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                $query->bindParam(':SearchID', $SearchID);
                $query->bindParam(':column', $key);
                $query->bindParam(':value', $value);
                $query->execute();
            }
        }

        header("location: $LINK/email_list/?ListID=$ListID&isTab=Candidates&q=$SearchID");
        exit();
    }
}

if (isset($_POST['SearchClients'])) {
    $Name = $_POST['Name'];
    $ClientType = $_POST['ClientType'];
    $_client_id = $_POST['_client_id'];
    $EmailAddress = $_POST['Email'];
    $PhoneNumber = $_POST['Number'];
    $Address = $_POST['Address'];
    $Postcode = $_POST['Postcode'];
    $City = $_POST['City'];
    if (!empty($SearchID)) {
        $query = $conn->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
                  VALUES (:SearchID, :column, :value)");

        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                $query->bindParam(':SearchID', $SearchID);
                $query->bindParam(':column', $key);
                $query->bindParam(':value', $value);
                $query->execute();
            }
        }

        header("location: $LINK/email_list/?ListID=$ListID&isTab=Clients&q=$SearchID");
        exit();
    }
}

if (isset($_POST['SearchBranch'])) {
    $Name = $_POST['Name'];
    $ClientType = $_POST['ClientType'];
    $_client_id = $_POST['_client_id'];
    $EmailAddress = $_POST['Email'];
    $PhoneNumber = $_POST['Number'];
    $Address = $_POST['Address'];
    $Postcode = $_POST['Postcode'];
    $City = $_POST['City'];
    if (!empty($SearchID)) {
        $query = $conn->prepare("INSERT INTO `search_queries`(`SearchID`, `column`, `value`) 
                  VALUES (:SearchID, :column, :value)");

        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                $query->bindParam(':SearchID', $SearchID);
                $query->bindParam(':column', $key);
                $query->bindParam(':value', $value);
                $query->execute();
            }
        }

        header("location: $LINK/email_list/?ListID=$ListID&isTab=Branches&q=$SearchID");
        exit();
    }
}

if (isset($_POST['UpdateEmailList'])) {
    // Delete entries with empty RecipientID and Email
    $deleteQuery = $conn->prepare("DELETE FROM `_email_list` WHERE RecipientID = '' AND Email = '' AND ListID = :ListID");
    $deleteQuery->bindParam(':ListID', $ListID);
    $deleteQuery->execute();

    $RecipientID = $_POST['RecipientID'];
    $email = $_POST['email'];
    $source = $_POST['source'];
    $title = $_POST['title'];

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit();
    }

    // Check if the RecipientID is already in the list
    $checkQuery = $conn->prepare("SELECT RecipientID FROM `_email_list` WHERE RecipientID = :RecipientID AND ListID = :ListID");
    $checkQuery->bindParam(':RecipientID', $RecipientID);
    $checkQuery->bindParam(':ListID', $ListID);
    $checkQuery->execute();
    $count = $checkQuery->rowCount();

    if ($count == 0) {
        $query = "INSERT INTO `_email_list` (`ClientKeyID`, `ListID`, `Title`, `RecipientID`, `Source`, `Email`, `CreatedBy`, `Date`) 
                  VALUES (:ClientKeyID, :ListID, :Title, :RecipientID, :Source, :Email, :CreatedBy, :Date)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ClientKeyID', $ClientKeyID);
        $stmt->bindParam(':ListID', $ListID);
        $stmt->bindParam(':Title', $title);
        $stmt->bindParam(':RecipientID', $RecipientID);
        $stmt->bindParam(':Source', $source);
        $stmt->bindParam(':Email', $email);
        $stmt->bindParam(':CreatedBy', $USERID);
        $stmt->bindParam(':Date', $date);
        $stmt->execute();

        echo "List updated successfully.";
    } else {
        echo "Recipient ID already exists.";
    }
}


if (isset($_POST['RemoveEmailList'])) {
    $RecipientID = $_POST['RecipientID'];
    $email = $_POST['email'];
    $deleteQuery = $conn->prepare("DELETE FROM `_email_list` WHERE RecipientID = :RecipientID AND email = :email AND ListID = '$ListID' ");
    $deleteQuery->bindParam(':RecipientID', $RecipientID);
    $deleteQuery->bindParam(':email', $email);
    $deleteQuery->execute();
}


if (isset($_POST['AddOtherEmail'])) {
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $RecipientID = '_others';
    // Validate email
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $response = "Invalid email.";
        $error = 1;
    } else {
        // Check if the email already exists
        $checkQuery = $conn->prepare("SELECT Email FROM `_email_list` WHERE Email = :Email AND ListID = :ListID");
        $checkQuery->bindParam(':Email', $Email);
        $checkQuery->bindParam(':ListID', $ListID);
        $checkQuery->execute();
        $count = $checkQuery->rowCount();

        if ($count > 0) {
            $response = "Email already exists.";
            $error = 1;
        } else {
            // Insert the new email
            $query = "INSERT INTO `_email_list`(`ClientKeyID`, `ListID`, `Title`, `RecipientID`, `Source`, `Email`, `CreatedBy`, `Date`) 
                      VALUES (:ClientKeyID, :ListID, :Title, :RecipientID, :Source, :Email, :CreatedBy, :Date)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':ClientKeyID', $ClientKeyID);
            $stmt->bindParam(':ListID', $ListID);
            $stmt->bindParam(':Title', $EmailListTitle);
            $stmt->bindParam(':RecipientID', $Name); // If '0' should be a default value, you can use it directly in the query
            $stmt->bindParam(':Source', $RecipientID);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':CreatedBy', $USERID);
            $stmt->bindParam(':Date', $date);
            $stmt->execute();

            $response = "Email successfully added.";
        }
    }
}

if (isset($_POST['RemoveOtherEmail'])) {
    $id = $_POST['id'];
    $deleteQuery = $conn->prepare("DELETE FROM `_email_list` WHERE id = '$id' ");
    $deleteQuery->execute();
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
                            <h5><?php echo $EmailListTitle; ?></h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="card shadow-none border">
                                <div style="padding: 10px;">
                                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/email_list/?ListID=<?php echo $ListID ?>&isTab=Candidates" class="nav-link <?php echo ($isTab == "Candidates") ? "active is-active" : "" ?>">Candidates</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/email_list/?ListID=<?php echo $ListID ?>&isTab=Clients" class="nav-link <?php echo ($isTab == "Clients") ? "active is-active" : "" ?>">Clients</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/email_list/?ListID=<?php echo $ListID ?>&isTab=Branches" class="nav-link <?php echo ($isTab == "Branches") ? "active is-active" : "" ?>">Branches</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/email_list/?ListID=<?php echo $ListID ?>&isTab=OtherEmails" class="nav-link <?php echo ($isTab == "OtherEmails") ? "active is-active" : "" ?>">Other Emails</a></li>
                                    </ul>
                                </div>

                                <?php if ($isTab == "Candidates") : ?>
                                    <div id="SearchForCandidates" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="SearchForCandidatesTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="SearchForCandidatesTitle">Advance Search</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Name</label>
                                                                    <input type="text" name="Name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Status</label>
                                                                    <select name="Status" class="form-control" id="">
                                                                        <?php
                                                                        foreach ($candidate_status as $status) { ?>
                                                                            <option><?php echo $status; ?></option>
                                                                        <?php }  ?>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Candidate ID</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="IDNumber">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Job Title</label>
                                                                    <input type="text" name="JobTitle" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Email address</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Email">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Phone Number</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Number">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Address</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Address">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Postcode</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Postcode">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">City</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="City">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Created By</label>
                                                                    <div class="input-group search-form">
                                                                        <select name="CreatedBy" id="" class="form-control">
                                                                            <option value=""></option>
                                                                            <?php
                                                                            $q = "SELECT * FROM `users` WHERE ClientKeyID = '$ClientKeyID'";
                                                                            $stmt = $conn->prepare($q);
                                                                            $stmt->execute();
                                                                            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) { ?>
                                                                                <option value="<?php echo $row->UserID; ?>"><?php echo $row->Name ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="SearchCandidates" class="btn btn-primary me-0">Search</button>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Candidates</h6>
                                        </div>
                                        <div class="flex-shrink-0 ms-3" style="padding: 5px;">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#SearchForCandidates">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="m19.6 21l-6.3-6.3q-.75.6-1.725.95T9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l6.3 6.3zM9.5 14q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive dt-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>
                                                        <span id="selectAll" style="cursor: pointer;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                            </svg>
                                                        </span>
                                                    </th>
                                                    <th>Candidate ID</th>
                                                    <th>Status</th>
                                                    <th>Name</th>
                                                    <th>Email Address </th>
                                                    <th>Phone Number </th>
                                                    <th>Created By</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $n = 1;
                                                $query = "SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID'";

                                                if (isset($_GET['q'])) {
                                                    $SearchID = $_GET['q'];
                                                    $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                                                    while ($r = $qu->fetchObject()) {
                                                        $column = $r->column;
                                                        $value = $r->value;
                                                        $query .= " AND " . $column . " LIKE '%$value%'";
                                                    }
                                                }
                                                $query .= " ORDER BY id DESC ";
                                                $stmt = $conn->prepare($query);
                                                $stmt->execute();
                                                while ($row = $stmt->fetchObject()) {  ?>
                                                    <?php
                                                    $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                                    $isChecked = $conn->query("SELECT * FROM `_email_list` WHERE ListID = '{$ListID}' AND RecipientID = '{$row->CandidateID}'  ")->rowCount();
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $n++; ?></td>
                                                        <td> <input class="form-check-input checkbox-item" <?php echo ($isChecked > 0) ? 'checked' : ""; ?> type="checkbox" value="<?php echo $row->CandidateID; ?>" data-source="_candidates" data-email="<?php echo strtolower($row->Email); ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                        <td><?php echo empty($row->IDNumber) ? str_pad($row->id, 5, '0', STR_PAD_LEFT) : $row->IDNumber; ?></td>
                                                        <td>
                                                            <?php if ($row->Status == "Active") : ?>
                                                                <span class="badge bg-success">Active</span>
                                                            <?php else : ?>
                                                                <?php if ($row->Status == "Archived") : ?>
                                                                    <span class="badge bg-warning">Archived</span>

                                                                <?php else : ?>
                                                                    <?php if ($row->Status == "Inactive") : ?>
                                                                        <span class="badge bg-danger"><?php echo $row->Status; ?></span>
                                                                    <?php endif; ?>
                                                                    <?php if ($row->Status == "Pending Compliance") : ?>
                                                                        <span class="badge bg-info"><?php echo $row->Status; ?></span>
                                                                    <?php endif; ?>

                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    <img width="40" height="40" style="object-fit: cover;" src="<?php echo !empty($row->ProfileImage) ? $row->ProfileImage : $ProfilePlaceholder; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40">

                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0"><?php echo $row->Name; ?></h6>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td><?php echo $row->Email; ?> <?php echo !filter_var($row->Email, FILTER_VALIDATE_EMAIL) ? "<span class='badge bg-danger'>Invalid Email</span>" : ""; ?></td>
                                                        <td><?php echo $row->Number; ?></td>
                                                        <td><?php echo $CreatedBy; ?></td>
                                                        <td><?php echo FormatDate($row->Date); ?></td>

                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                        <?php if ($stmt->rowCount() == 0) : ?>
                                            <div style="padding: 10px;">
                                                <div class="alert alert-danger">
                                                    No data found.
                                                </div>
                                            </div>

                                        <?php endif; ?>

                                    </div>
                                <?php endif; ?>
                                <?php if ($isTab == "Clients") : ?>
                                    <div id="SearchModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="SearchModalTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="SearchModalTitle">Advance Search</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Name</label>
                                                                    <input type="text" name="Name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Client Type</label>
                                                                    <select name="ClientType" class="select-input">
                                                                        <option value=""></option>
                                                                        <?php
                                                                        foreach ($clientype as $type) { ?>
                                                                            <option><?php echo $type; ?></option>

                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Client ID</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="_client_id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Email address</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Email">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Phone Number</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Email">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Address</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Address">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Postcode</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="PostCode">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">City</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="City">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Created By</label>
                                                                    <div class="input-group search-form">
                                                                        <select name="CreatedBy" id="" class="form-control">
                                                                            <option value=""></option>
                                                                            <?php
                                                                            $q = "SELECT * FROM `users` WHERE ClientKeyID = '$ClientKeyID'";
                                                                            $stmt = $conn->prepare($q);
                                                                            $stmt->execute();
                                                                            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) { ?>
                                                                                <option value="<?php echo $row->UserID; ?>"><?php echo $row->Name ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="SearchClients" class="btn btn-primary me-0">Search</button>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Clients</h6>
                                        </div>
                                        <div class="flex-shrink-0 ms-3" style="padding: 5px;">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#SearchModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="m19.6 21l-6.3-6.3q-.75.6-1.725.95T9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l6.3 6.3zM9.5 14q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive dt-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>
                                                        <span id="selectAll" style="cursor: pointer;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                            </svg>
                                                        </span>
                                                    </th>
                                                    <th>Client Name </th>
                                                    <th>Client ID</th>
                                                    <th>Email Address </th>
                                                    <th>Phone Number </th>
                                                    <th>Created By</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isBranch IS NULL ";

                                                if (isset($_GET['q'])) {
                                                    $SearchID = $_GET['q'];
                                                    $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                                                    while ($r = $qu->fetchObject()) {
                                                        $column = $r->column;
                                                        $value = $r->value;
                                                        $query .= " AND " . $column . " LIKE '%$value%'";
                                                    }
                                                }
                                                $query .= " ORDER BY id DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->execute();
                                                $n = 1;
                                                while ($row = $stmt->fetchObject()) { ?>
                                                    <?php
                                                    $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                                    $isChecked = $conn->query("SELECT * FROM `_email_list` WHERE ListID = '{$ListID}' AND RecipientID = '{$row->ClientID}'  ")->rowCount();

                                                    ?>
                                                    <tr>
                                                        <td><?php echo $n++; ?></td>
                                                        <td> <input class="form-check-input checkbox-item" <?php echo ($isChecked > 0) ? 'checked' : ""; ?> type="checkbox" value="<?php echo $row->ClientID; ?>" data-source="_clients" data-email="<?php echo strtolower($row->Email); ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                        <td><?php echo $row->Name; ?></td>
                                                        <td><?php echo $row->_client_id; ?></td>
                                                        <td><?php echo $row->Email; ?> <?php echo !filter_var($row->Email, FILTER_VALIDATE_EMAIL) ? "<span class='badge bg-danger'>Invalid Email</span>" : ""; ?></td>
                                                        <td><?php echo $row->Number; ?></td>
                                                        <td><?php echo $CreatedBy; ?></td>
                                                        <td><?php echo FormatDate($row->Date); ?></td>
                                                    </tr>
                                                <?php } ?>


                                            </tbody>
                                        </table>
                                        <?php if ($stmt->rowCount() == 0) : ?>
                                            <div style="padding: 10px;">
                                                <div class="alert alert-danger">
                                                    No data found.
                                                </div>
                                            </div>

                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($isTab == "Branches") : ?>
                                    <div id="SearchModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="SearchModalTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="SearchModalTitle">Advance Search</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Name</label>
                                                                    <input type="text" name="Name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Client Type</label>
                                                                    <select name="ClientType" class="select-input">
                                                                        <option value=""></option>
                                                                        <?php
                                                                        foreach ($clientype as $type) { ?>
                                                                            <option><?php echo $type; ?></option>

                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Client ID</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="_client_id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Email address</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Email">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Phone Number</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Email">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Address</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="Address">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Postcode</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="PostCode">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">City</label>
                                                                    <div class="input-group search-form">
                                                                        <input type="text" class="form-control" name="City">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3"><label class="form-label">Created By</label>
                                                                    <div class="input-group search-form">
                                                                        <select name="CreatedBy" id="" class="form-control">
                                                                            <option value=""></option>
                                                                            <?php
                                                                            $q = "SELECT * FROM `users` WHERE ClientKeyID = '$ClientKeyID'";
                                                                            $stmt = $conn->prepare($q);
                                                                            $stmt->execute();
                                                                            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) { ?>
                                                                                <option value="<?php echo $row->UserID; ?>"><?php echo $row->Name ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="SearchBranch" class="btn btn-primary me-0">Search</button>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Clients</h6>
                                        </div>
                                        <div class="flex-shrink-0 ms-3" style="padding: 5px;">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#SearchModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="m19.6 21l-6.3-6.3q-.75.6-1.725.95T9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l6.3 6.3zM9.5 14q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive dt-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>
                                                        <span id="selectAll" style="cursor: pointer;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                                            </svg>
                                                        </span>
                                                    </th>
                                                    <th>Client Name </th>
                                                    <th>Branch Name </th>
                                                    <th>BRANCH ID</th>
                                                    <th>Email Address </th>
                                                    <th>Phone Number </th>
                                                    <th>Created By</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isBranch IS NOT NULL ";

                                                if (isset($_GET['q'])) {
                                                    $SearchID = $_GET['q'];
                                                    $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                                                    while ($r = $qu->fetchObject()) {
                                                        $column = $r->column;
                                                        $value = $r->value;
                                                        $query .= " AND " . $column . " LIKE '%$value%'";
                                                    }
                                                }
                                                $query .= " ORDER BY id DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->execute();
                                                $n = 1;
                                                while ($row = $stmt->fetchObject()) { ?>
                                                    <?php
                                                    $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                                                    $isChecked = $conn->query("SELECT * FROM `_email_list` WHERE ListID = '{$ListID}' AND RecipientID = '{$row->ClientID}'  ")->rowCount();
                                                    $isClientName = $conn->query("SELECT Name FROM `_clients` WHERE ClientID = '{$row->isClient}' ")->fetchColumn();
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $n++; ?></td>
                                                        <td> <input class="form-check-input checkbox-item" <?php echo ($isChecked > 0) ? 'checked' : ""; ?> type="checkbox" value="<?php echo $row->ClientID; ?>" data-source="_clients" data-email="<?php echo strtolower($row->Email); ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                                        <td><?php echo $isClientName; ?></td>
                                                        <td><?php echo $row->Name; ?></td>
                                                        <td><?php echo $row->_client_id; ?></td>
                                                        <td><?php echo $row->Email; ?> <?php echo !filter_var($row->Email, FILTER_VALIDATE_EMAIL) ? "<span class='badge bg-danger'>Invalid Email</span>" : ""; ?></td>
                                                        <td><?php echo $row->Number; ?></td>
                                                        <td><?php echo $CreatedBy; ?></td>
                                                        <td><?php echo FormatDate($row->Date); ?></td>
                                                    </tr>
                                                <?php } ?>


                                            </tbody>
                                        </table>
                                        <?php if ($stmt->rowCount() == 0) : ?>
                                            <div style="padding: 10px;">
                                                <div class="alert alert-danger">
                                                    No data found.
                                                </div>
                                            </div>

                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($isTab == "OtherEmails") : ?>
                                    <div id="AddEmail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddEmailTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="AddEmailTitle">Add Email</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3"><label class="form-label">Name</label>
                                                                    <input type="text" name="Name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="mb-3"><label class="form-label">Email address</label>
                                                                    <input type="email" name="Email" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="AddOtherEmail" class="btn btn-primary me-0">Add Email</button>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Other Emails</h6>
                                        </div>
                                        <div class="flex-shrink-0 ms-3" style="padding: 5px;">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddEmail">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M19 12.998h-6v6h-2v-6H5v-2h6v-6h2v6h6z" />
                                                </svg> Add
                                            </button>


                                        </div>
                                    </div>
                                    <div class="table-responsive dt-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name </th>
                                                    <th>Source </th>
                                                    <th>Email Address </th>
                                                    <th>Created By</th>
                                                    <th>Date</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM `_email_list` WHERE ClientKeyID = '$ClientKeyID' AND ListID= '$ListID' AND Source = '_others' ";

                                                $query .= " ORDER BY id DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->execute();
                                                $n = 1;
                                                while ($row = $stmt->fetchObject()) { ?>

                                                    <tr>
                                                        <td><?php echo $n++; ?></td>
                                                        <td><?php echo $row->RecipientID; ?></td>
                                                        <td><?php echo "Others"; ?></td>
                                                        <td><?php echo $row->Email; ?> <?php echo !filter_var($row->Email, FILTER_VALIDATE_EMAIL) ? "<span class='badge bg-danger'>Invalid Email</span>" : ""; ?></td>
                                                        <td><?php echo CreatedBy($row->CreatedBy); ?></td>
                                                        <td><?php echo FormatDate($row->Date); ?></td>
                                                        <td>
                                                            <span class="text-danger RemoveOtherEmail" style="cursor: pointer;" data-id="<?php echo $row->id; ?>">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                                    <path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM9 17h2V8H9zm4 0h2V8h-2zM7 6v13z" />
                                                                </svg> Remove
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>


                                            </tbody>
                                        </table>
                                        <?php if ($stmt->rowCount() == 0) : ?>
                                            <div style="padding: 10px;">
                                                <div class="alert alert-danger">
                                                    No data found.
                                                </div>
                                            </div>

                                        <?php endif; ?>
                                    </div>
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
    <?php if ($isTab == "OtherEmails") : ?>
        $(".RemoveOtherEmail").click(function() {
            let id = $(this).data("id");
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    id: id,
                    RemoveOtherEmail: true
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function() {
                    ShowToast("Failed to remove email.");
                }
            });

        })
    <?php else : ?>
        document.getElementById('selectAll').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item');
            let allChecked = this.classList.toggle('checked');
            let totalRequests = checkboxes.length;
            let successCount = 0;
            let failureCount = 0;

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = allChecked;
                let source = checkbox.getAttribute('data-source');
                let email = checkbox.getAttribute('data-email');
                let RecipientID = checkbox.value;

                if (allChecked) {
                    if (isValidEmail(email)) {
                        UpdateEmailList(RecipientID, email, source, "<?php echo $EmailListTitle; ?>", function(success) {
                            if (success) {
                                successCount++;
                            } else {
                                failureCount++;
                            }
                            totalRequests--;

                            if (totalRequests === 0) {
                                ShowToast(successCount + " emails successfully updated, " + failureCount + " failed to update.");
                            }
                        });
                    } else {
                        failureCount++;
                        totalRequests--;
                        if (totalRequests === 0) {
                            ShowToast(successCount + " emails successfully updated, " + failureCount + " failed to update.");
                        }
                    }
                } else {
                    RemoveEmailList(RecipientID, email, function(success) {
                        if (success) {
                            successCount++;
                        } else {
                            failureCount++;
                        }
                        totalRequests--;

                        if (totalRequests === 0) {
                            ShowToast(successCount + " emails successfully removed, " + failureCount + " failed to remove.");
                        }
                    });
                }
            });
        });

        document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                let source = checkbox.getAttribute('data-source');
                let email = checkbox.getAttribute('data-email');
                let RecipientID = checkbox.value;

                if (checkbox.checked) {
                    if (isValidEmail(email)) {
                        UpdateEmailList(RecipientID, email, source, "<?php echo $EmailListTitle; ?>", function(success) {
                            if (success) {
                                ShowToast("Email successfully updated.");
                            } else {
                                ShowToast("Failed to update email.");
                            }
                        });
                    } else {
                        ShowToast("Invalid email address.");
                        checkbox.checked = false;
                    }
                } else {
                    RemoveEmailList(RecipientID, email, function(success) {
                        if (success) {
                            ShowToast("Email successfully removed.");
                        } else {
                            ShowToast("Failed to remove email.");
                        }
                    });
                }
            });
        });

        function isValidEmail(email) {
            // Regular expression for basic email validation
            const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(String(email).toLowerCase());
        }

        function UpdateEmailList(RecipientID, email, source, emailListTitle, callback) {
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    RecipientID: RecipientID,
                    email: email,
                    source: source,
                    title: emailListTitle,
                    UpdateEmailList: true
                },
                success: function(response) {
                    callback(true);


                },
                error: function() {
                    callback(false);
                }
            });
        }

        function RemoveEmailList(RecipientID, email, callback) {
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    RecipientID: RecipientID,
                    email: email,
                    RemoveEmailList: true
                },
                success: function(response) {
                    callback(true);
                },
                error: function() {
                    callback(false);
                }
            });
        }


    <?php endif; ?>
</script>


</html>