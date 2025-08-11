<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}



$CandidateID = isset($_GET['CandidateID']) ? $_GET['CandidateID'] : '';
$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : 'Details';
$isName =   'Candidate';

$LastID = ($conn->query("SELECT COUNT(id) FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID'")->fetchColumn()) + 1;
$NextIDNumber = str_pad($LastID, 5, '0', STR_PAD_LEFT);

if (isset($_POST['submit'])) {
    $name = $_POST['Name'];
    $IdentificationNumber = $_POST['IDNumber'];
    $birthDate = $_POST['BirthDate'];
    $jobTitle = $_POST['JobTitle'];
    $emailAddress = $_POST['EmailAddress'];
    $phoneNumber = $_POST['PhoneNumber'];
    $address = $_POST['Address'];
    $postcode = $_POST['Postcode'];
    $city = $_POST['City'];
    $Status = $_POST['Status'];
    $Gender = $_POST['Gender'];

    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM `_candidates` WHERE id = :candidateID");
    $checkStmt->bindParam(':candidateID', $CandidateID);
    $checkStmt->execute();
    $candidateExists = $checkStmt->fetchColumn() > 0;

    if ($candidateExists) {
        // Update the existing candidate record
        $stmt = $conn->prepare("UPDATE `_candidates` SET 
            `Status` = :Status, 
            `IdentificationNumber` = :IdentificationNumber, 
            `Name` = :name, 
            `Email` = :emailAddress, 
            `Number` = :phoneNumber, 
            `BirthDate` = :birthDate, 
            `JobTitle` = :jobTitle, 
            `Address` = :address, 
            `Postcode` = :postcode, 
            `City` = :city,
            `Gender` = :Gender
            WHERE `id` = :candidateID");

        $stmt->bindParam(':candidateID', $CandidateID);
        $stmt->bindParam(':IdentificationNumber', $IdentificationNumber);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':emailAddress', $emailAddress);
        $stmt->bindParam(':phoneNumber', $phoneNumber);
        $stmt->bindParam(':birthDate', $birthDate);
        $stmt->bindParam(':jobTitle', $jobTitle);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':postcode', $postcode);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':Status', $Status);
        $stmt->bindParam(':Gender', $Gender);

        if ($stmt->execute()) {
            $response = "Candidate updated successfully.";

            $Modification = "Updated candidate record";
            $Notification = "$NAME has successfully updated the candidate record for $name with ID $IdentificationNumber.";
            LastModified($CandidateID, $USERID, $Modification);
            Notify($USERID, $ClientKeyID, $Notification);
        } else {
            $response = "Error: " . $stmt->errorInfo()[2];
        }
    } else {
        // Insert a new candidate record
        $stmt = $conn->prepare("INSERT INTO `_candidates` (`ClientKeyID`, `CandidateID`, `Status`, `IdentificationNumber`, `Name`, `Gender`, `Email`, `Number`, `BirthDate`, `JobTitle`, `Address`, `Postcode`, `City`, `CreatedBy`, `Date`) VALUES (:clientKeyID, :candidateID, :status, :IdentificationNumber, :name, :Gender, :emailAddress, :phoneNumber, :birthDate, :jobTitle, :address, :postcode, :city, :createdBy, :date)");

        $stmt->bindParam(':clientKeyID', $ClientKeyID);
        $stmt->bindParam(':candidateID', $CandidateID);
        $stmt->bindParam(':IdentificationNumber', $IdentificationNumber);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':emailAddress', $emailAddress);
        $stmt->bindParam(':phoneNumber', $phoneNumber);
        $stmt->bindParam(':birthDate', $birthDate);
        $stmt->bindParam(':jobTitle', $jobTitle);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':postcode', $postcode);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':Status', $Status);
        $stmt->bindParam(':Gender', $Gender);
        $stmt->bindParam(':createdBy', $USERID);
        $stmt->bindParam(':date', $date);

        if ($stmt->execute()) {
            $response = "Candidate created successfully.";

            $Modification = "Created new candidate record";
            $Notification = "$NAME has successfully created a new candidate, $name, with ID $IdentificationNumber.";
            LastModified($CandidateID, $USERID, $Modification);
            Notify($USERID, $ClientKeyID, $Notification);
        } else {
            $response = "Error: " . $stmt->errorInfo()[2];
        }
    }
}


// Debug information for troubleshooting
if (empty($CandidateID)) {
    echo "<div class='alert alert-danger'>Error: No Candidate ID provided in URL</div>";
}

try {
    $CandidateData = $conn->query("SELECT * FROM `_candidates` WHERE id = '$CandidateID' ")->fetchObject();
    
    // Debug: Check if candidate was found
    if (!$CandidateData) {
        echo "<div class='alert alert-warning'>Debug: No candidate found with ID: " . htmlspecialchars($CandidateID) . "</div>";
        echo "<div class='alert alert-info'>Available candidates: ";
        $debugQuery = $conn->query("SELECT id, Name FROM `_candidates` LIMIT 5");
        while ($row = $debugQuery->fetchObject()) {
            echo "ID: " . $row->id . " - " . $row->Name . "; ";
        }
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
    $CandidateData = false;
}

$Name = !$CandidateData ? "" : $CandidateData->Name;
$IDNumber = !$CandidateData || empty($CandidateData->IdentificationNumber)  ? $NextIDNumber : $CandidateData->IdentificationNumber;
$Email = !$CandidateData ? "" : $CandidateData->Email;
$Number = !$CandidateData ? "" : $CandidateData->Number;
$BirthDate = !$CandidateData ? "" : $CandidateData->BirthDate;
$JobTitle = !$CandidateData ? "" : $CandidateData->JobTitle;
$Address = !$CandidateData ? "" : $CandidateData->Address;
$Postcode = !$CandidateData ? "" : $CandidateData->Postcode;
$City = !$CandidateData ? "" : $CandidateData->City;
$Gender = !$CandidateData ? "" : $CandidateData->Gender;
$Status = !$CandidateData ? "" : $CandidateData->Status;
$ProfileImage = !$CandidateData || empty($CandidateData->ProfileImage) ? $ProfilePlaceholder : $CandidateData->ProfileImage;

$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true);
if (isset($inputData['UpdateProfile'])) {
    $avatarSrc = $inputData['avatarSrc'];
    $query = $conn->query("UPDATE `_candidates` SET `ProfileImage`='$avatarSrc' WHERE `id`='$CandidateID' ");
}

if (isset($_POST['UpdateprofileImage'])) {
    $file = $_FILES['profileImage'];

    if ($file['error'] == UPLOAD_ERR_OK) {
        $fileInfo = pathinfo($file['name']);
        $newFileName = $RandomID . '.' . $fileInfo['extension'];
        $filePath = $Image_Directory . $newFileName;
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $FilePath = $LINK . '/assets/images/' . $newFileName;
            $query = $conn->query("UPDATE `_candidates` SET `ProfileImage`='$FilePath' WHERE `id`='$CandidateID' ");
        } else {
            $response = "Error uploading the file.";
        }
    } else {
        $response = "Error: " . $file['error'];
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php"; ?>
<style>
    /* Base styles for the image */
    .avatar-image {
        border-radius: 50%;
        cursor: pointer;
        width: 200px;
        /* Adjust as needed */
    }

    /* Add the animated border */
    @keyframes border-animate {
        0% {
            border-width: 3px;
            border-style: solid;
            border-color: transparent;
            border-top-color: #48f542;
        }


        25% {
            border-right-color: #48f542;
        }

        50% {
            border-bottom-color: #48f542;
        }

        75% {
            border-left-color: #48f542;
        }

        100% {
            border-color: #48f542;
        }
    }

    .animated-border {
        border-width: 3px;
        border-style: solid;
        border-color: transparent;
        animation: border-animate 2s linear forwards;
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
                                <h5 class="mb-0">
                                    Edit <?php echo $isName; ?> Information


                                </h5>

                            </div>
                        </div>
                        <ul class="nav nav-tabs analytics-tab" role="tablist" style="margin-left: 20px;">
                            <li class="nav-item" role="presentation"> <a href="<?php echo $LINK; ?>/edit_candidate/?CandidateID=<?php echo $CandidateID; ?>&isTab=Details"><button class="nav-link <?php echo ($isTab == "Details") ? 'active' : ''; ?>">Details</button></a> </li>
                            <li class="nav-item" role="presentation"> <a href="<?php echo $LINK; ?>/edit_candidate/?CandidateID=<?php echo $CandidateID; ?>&isTab=Picture"><button class="nav-link <?php echo ($isTab == "Picture") ? 'active' : ''; ?>">Profile Picture</button></a> </li>
                        </ul>
                        <div class="card-body">

                            <?php if (IsCheckPermission($USERID, "EDIT_CANDIDATE")) : ?>
                                <?php if ($isTab == "Details") : ?>
                                    <form method="POST">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="Name" value="<?php echo htmlspecialchars($Name); ?>" required class="form-control">
                                                    <small class="form-text text-muted">Please enter candidate's name</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Candidate ID</label>
                                                    <div class="input-group search-form">
                                                        <input required name="IDNumber" type="text" class="form-control" value="<?php echo htmlspecialchars($IDNumber); ?>">
                                                    </div>
                                                    <small class="form-text text-muted">Please enter candidate's ID</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Date of Birth</label>
                                                    <div class="input-group search-form">
                                                        <input required type="date" class="form-control" value="<?php echo htmlspecialchars($BirthDate); ?>" name="BirthDate">
                                                    </div>
                                                    <small class="form-text text-muted">Please enter candidate's date of birth</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Job Title</label>
                                                    <input type="text" required name="JobTitle" class="form-control" value="<?php echo htmlspecialchars($JobTitle); ?>">
                                                    <small class="form-text text-muted">Enter candidate's job title</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Email address</label>
                                                    <div class="input-group search-form">
                                                        <input required type="email" class="form-control" name="EmailAddress" value="<?php echo htmlspecialchars($Email); ?>">
                                                    </div>
                                                    <small class="form-text text-muted">Please enter candidate's email address</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone Number</label>
                                                    <div class="input-group search-form">
                                                        <input required type="text" class="form-control" name="PhoneNumber" value="<?php echo htmlspecialchars($Number); ?>">
                                                    </div>
                                                    <small class="form-text text-muted">Please enter candidate's contact number</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <div class="input-group search-form">
                                                        <select name="Status" class="form-control" id="">
                                                            <?php foreach ($candidate_status as $status): ?>
                                                                <option <?php echo ($status == $Status) ? "selected" : ""; ?>><?php echo $status; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <small class="form-text text-muted">Please select candidate's status</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Gender</label>
                                                    <div class="input-group search-form">
                                                        <input required type="text" class="form-control" name="Gender" value="<?php echo htmlspecialchars($Gender); ?>">
                                                    </div>
                                                    <small class="form-text text-muted">Please enter candidate's gender</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <div class="input-group search-form">
                                                        <input required type="text" class="form-control" name="Address" value="<?php echo htmlspecialchars($Address); ?>">
                                                    </div>
                                                    <small class="form-text text-muted">Please enter candidate's address</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Postcode</label>
                                                    <div class="input-group search-form">
                                                        <input required type="text" class="form-control" name="Postcode" value="<?php echo htmlspecialchars($Postcode); ?>">
                                                    </div>
                                                    <small class="form-text text-muted">Please enter candidate's postcode</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label">City</label>
                                                    <div class="input-group search-form">
                                                        <input required type="text" class="form-control" name="City" value="<?php echo htmlspecialchars($City); ?>">
                                                    </div>
                                                    <small class="form-text text-muted">Please enter candidate's city</small>
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
                                <?php endif; ?>
                                <?php if ($isTab == "Picture") : ?>
                                    <div class="card shadow-none border">
                                        <div class="card-header">
                                            <div class="d-flex align-items-center">

                                                <div class="flex-grow-1 mx-3">
                                                    <h6 class="mb-0">Candidate's Profile Picture</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="card shadow-none border mb-0 h-100">
                                                        <div class="card-body">
                                                            <h6 class="mb-2">Current Profile Picture</h6>
                                                            <div class="row">

                                                                <div class="col-sm-12">
                                                                    <center>
                                                                        <div class="mb-3">
                                                                            <img id="profileImage" width="300" height="300" src="<?php echo $ProfileImage; ?>" style="border-radius: 50%; border: 2px solid #48f542; cursor: pointer; object-fit: cover; padding: 2px;" alt="Profile Image">
                                                                            <input type="file" id="imageUpload" class="form-control" style="display: none;" accept="image/*">
                                                                        </div>
                                                                    </center>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="card shadow-none border mb-0 h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 me-3">
                                                                    <h6 class="mb-0">Avaters</h6>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 mt-3">
                                                                <div class="d-flex align-items-center justify-content-between gap-2 my-3">
                                                                    <?php
                                                                    // Loop
                                                                    $avatarArray = array('avatar-1.jpg', 'avatar-2.jpg', 'avatar-3.jpg', 'avatar-4.jpg', 'avatar-5.jpg', 'avatar-6.jpg', 'avatar-7.jpg', 'avatar-8.jpg', 'avatar-9.jpg', 'avatar-10.jpg');
                                                                    foreach ($avatarArray as $avatar) { ?>
                                                                        <?php
                                                                        $img = $LINK . "/assets/images/avatars/$avatar";
                                                                        ?>
                                                                        <div>
                                                                            <a href="javascript:void(0)" class="avatar"><img style="  padding: 2px;" class="rounded-circle img-fluid" src="<?php echo $img; ?>" alt="User image"></a>
                                                                        </div>
                                                                    <?php } ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

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

<?php if ($isTab == "Picture") : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatars = document.querySelectorAll('.avatar img');
            avatars.forEach(avatar => {
                avatar.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove animation and border from all avatars
                    avatars.forEach(img => {
                        img.classList.remove('animated-border');
                    });

                    // Add animation and border to the selected avatar
                    this.classList.add('animated-border');

                    // Send the src to the endpoint using XHR
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', window.location.href, true);
                    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                    xhr.send(JSON.stringify({
                        avatarSrc: this.src,
                        UpdateProfile: true
                    }));

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            ShowToast('Avatar updated successfully');
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                    };
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const profileImage = document.getElementById('profileImage');
            const imageUpload = document.getElementById('imageUpload');

            profileImage.addEventListener('click', function() {
                imageUpload.click();
            });

            imageUpload.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImage.src = e.target.result;
                        profileImage.classList.add('animated-border');
                        uploadFile(file);
                    };
                    reader.readAsDataURL(file);
                }
            });

            function uploadFile(file) {
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('profileImage', file);
                formData.append('UpdateprofileImage', true);

                xhr.open('POST', window.location.href, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log('File uploaded successfully');
                        ShowToast('Profile image updated successfully');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        console.error('File upload failed');
                        ShowToast('Failed to update profile image');
                    }
                };

                xhr.send(formData);
            }


        });
    </script>

<?php endif; ?>


</html>