<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}


if (isset($_POST['UpdateNamePosition'])) {
    $Name = $_POST['Name'];
    $Position = $_POST['Position'];

    if ($USER_DATA->Position !== $Position) {
        $NOTIFICATION = "$NAME has successfully changed their position from $USER_DATA->Position to $Position.";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    }

    $query = "UPDATE users SET Name = :name, Position = :position WHERE UserID = :userID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $Name);
    $stmt->bindParam(':position', $Position);
    $stmt->bindParam(':userID', $USERID);
    $stmt->execute();

    $response = "You have successfully updated your profile";
}

if (isset($_POST['UpdateEmail'])) {
    $Email = $_POST['Email'];

    if ($USER_DATA->Email !== $Email) {
        $NOTIFICATION = "$NAME has successfully changed their email address from $USER_DATA->Email to $Email.";
        Notify($USERID, $ClientKeyID, $NOTIFICATION);
    }

    $query = "UPDATE users SET Email = :Email WHERE UserID = :userID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':Email', $Email);
    $stmt->bindParam(':userID', $USERID);
    $stmt->execute();

    $response = "You have successfully updated your profile";
}

if (isset($_POST['ChangePassword'])) {
    $Password = $_POST['Password'];
    $ConfirmPassword = $_POST['ConfirmPassword'];
    if (!empty($Password) and !empty($ConfirmPassword)) {
        if ($Password != $ConfirmPassword) {
            $response = "Passwords do not match";
            $error = 1;
        } else {
            $query = "UPDATE users SET Password = :Password WHERE UserID = :userID";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':Password', $Password);
            $stmt->bindParam(':userID', $USERID);
            $stmt->execute();

            $response = "You have successfully updated your profile";
        }
    } else {
        $response = "Password and Confirm Password fields cannot be empty.";
        $error = 1;
    }
}


$isTab = isset($_GET['isTab']) ? $_GET['isTab'] : "Details";


$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true);
if (isset($inputData['UpdateProfile'])) {
    $avatarSrc = $inputData['avatarSrc'];
    $query = $conn->query("UPDATE `users` SET `ProfileImage`='$avatarSrc' WHERE `UserID`='$USERID' ");
}

if (isset($_POST['UpdateprofileImage'])) {
    $file = $_FILES['profileImage'];

    if ($file['error'] == UPLOAD_ERR_OK) {
        $fileInfo = pathinfo($file['name']);
        $newFileName = $RandomID . '.' . $fileInfo['extension'];
        $filePath = $Image_Directory . $newFileName;
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $FilePath = $LINK . '/assets/images/' . $newFileName;
            $query = $conn->query("UPDATE `users` SET `ProfileImage`='$FilePath' WHERE `UserID`='$USERID' ");
        } else {
            $response = "Error uploading the file.";
        }
    } else {
        $response = "Error: " . $file['error'];
    }
}

if (isset($_POST['UpdateAuthType'])) {
    $UpdateAuthType = $_POST['UpdateAuthType'];
    $query = $conn->query("UPDATE `users` SET `AuthType`='$UpdateAuthType' WHERE `UserID`='$USERID' ");
    $response = "You have successfully updated your authentication type";
}

if (isset($_POST['UpdateSecretCode'])) {
    $SecretCode = $_POST['SecretCode'];
    if (!empty($SecretCode)) {
        $query = $conn->query("UPDATE `users` SET `IsSecretKey`='$SecretCode' WHERE `UserID`='$USERID' ");

        $response = "You have successfully updated your authentication type";
    } else {
        $response = "Authentication failed. Please enter your secret code";
        $error = 1;
    }
}

if (isset($_POST['UpdateSessionTime'])) {
    $Time = $_POST['Time'];
    $Units = $_POST['Units'];
    $HasSessionTime = $Time . ' ' . $Units;
    if (!empty($Time)) {
        $query = $conn->query("UPDATE `users` SET `HasSessionTime`='$HasSessionTime' WHERE `UserID`='$USERID' ");

        $response = "You have successfully updated your profile";
    } else {
        $response = "Updating your profile failed";
        $error = 1;
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
                            <h5>Profile</h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="card shadow-none border">
                                <div style="padding: 10px;">
                                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/profile?isTab=Details" class="nav-link <?php echo ($isTab == "Details") ? "active is-active" : "" ?>">Your Details</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/profile?isTab=Profile" class="nav-link <?php echo ($isTab == "Profile") ? "active is-active" : "" ?>">Profile Picture</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/profile?isTab=Auth" class="nav-link <?php echo ($isTab == "Auth") ? "active is-active" : "" ?>">Authentication and Session</a></li>
                                        <li class="nav-item" role="presentation"><a href="<?php echo $LINK; ?>/profile?isTab=Permission" class="nav-link <?php echo ($isTab == "Permission") ? "active is-active" : "" ?>">Your Permission</a></li>
                                    </ul>
                                </div>
                                <?php if ($isTab == "Details") : ?>
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">

                                            <div class="flex-shrink-0"><img width="40" height="40" style="object-fit: cover;" src="<?php echo $USER_DATA->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40"></div>

                                            <div class="flex-grow-1 mx-3">
                                                <h6 class="mb-0"><?php echo $USER_DATA->Name; ?></h6>
                                                <p class="mb-0"><?php echo $USER_DATA->Position; ?></p>
                                            </div>
                                            <div class="flex-shrink-0"><button class="btn btn-sm btn-light-secondary" data-bs-toggle="modal" data-bs-target="#EditName"><i class="ti ti-edit"></i> Edit</button></div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="card shadow-none border mb-0 h-100">
                                                    <form method="POST">
                                                        <div class="card-body">
                                                            <div>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-grow-1 me-3">
                                                                        <h6 class="mb-0">Email Address</h6>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <label class="form-label">Your email address is</label>
                                                                    <input type="email" name="Email" class="form-control" placeholder="Enter email" value="<?php echo $USER_DATA->Email; ?>">
                                                                </div>
                                                            </div>
                                                            <div>

                                                                <div class="mb-3 mt-3">
                                                                    <label class="form-label">Your department is</label>
                                                                    <input type="text" readonly name="Department" class="form-control" placeholder="Enter Position" value="<?php echo $USER_DATA->Department; ?>">
                                                                </div>
                                                            </div>
                                                            <button name="UpdateEmail" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card shadow-none border mb-0 h-100">
                                                    <div class="card-body">
                                                        <h6 class="mb-2">Change Password</h6>
                                                        <form method="post">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="mb-3"><label class="form-label">New Password</label>
                                                                        <input type="password" name="Password" class="form-control" placeholder="Enter New Password">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12">
                                                                    <div class="mb-3"><label class="form-label">Confirm Password</label>
                                                                        <input type="password" name="ConfirmPassword" class="form-control" placeholder="Enter Confirm New Password">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-primary" name="ChangePassword">Save Password</button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="EditName" class="modal fade" tabindex="-1" aria-labelledby="EditNameTitle" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="EditNameTitle">Update</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <div class="mb-3 mt-3">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" name="Name" class="form-control" placeholder="Enter name" value="<?php echo $USER_DATA->Name; ?>">
                                                        </div>
                                                        <div class="mb-3 mt-3">
                                                            <label class="form-label">Position</label>
                                                            <input type="text" name="Position" class="form-control" placeholder="Enter Position" value="<?php echo $USER_DATA->Position; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="UpdateNamePosition" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($isTab == "Profile") : ?>
                                    <div class=" ">

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
                                                                            <img id="profileImage" width="300" height="300" src="<?php echo $USER_DATA->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" style="border-radius: 50%; border: 2px solid #48f542; cursor: pointer; object-fit: cover; padding: 2px;" alt="Profile Image">
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

                                <?php if ($isTab == "Auth") : ?>
                                    <div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="card shadow-none border mb-0 h-100">
                                                        <div class="card-body">
                                                            <h6 class="mb-2">Authentication and Session</h6>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="card shadow-none border mb-0 h-100">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-grow-1 me-3">
                                                                        <h6 class="mb-0">Email Verification Code</h6>
                                                                        <p>An email verification code is a security measure used to confirm your identity by verifying your email address during login. Upon logging in, a unique 5 or 6-digit numeric code will be sent to your registered email address (<?php echo $USER_DATA->Email; ?>). This code ensures that you are the rightful owner of the account.</p>
                                                                    </div>
                                                                    <div class="flex-shrink-0"></div>
                                                                </div>
                                                                <div class="mb-3 mt-3">
                                                                    <label class="form-label">Your email address is</label>
                                                                    <input type="email" class="form-control" placeholder="Enter email" value="<?php echo $USER_DATA->Email; ?>" readonly>
                                                                    <div class="form-check mb-2" style="margin-top: 10px;">
                                                                        <input class="form-check-input" type="radio" name="authType" value="email" id="emailVerificationRadio" <?php echo ($USER_DATA->AuthType == "Email") ? "checked" : ""; ?>>
                                                                        <label class="form-check-label" for="emailVerificationRadio">Use Email Address Verification Code</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card shadow-none border mb-0 h-100">
                                                            <div class="card-body">
                                                                <h6 class="mb-2">Secret Code</h6>
                                                                <p>A secret code can be used as an alternative verification method. It can be anything from a number to a word or a combination of both. This code provides an additional layer of security by allowing you to authenticate using a code known only to you.</p>
                                                                <form method="POST">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="mb-3">
                                                                                <label class="form-label" style="width: 100%; display: flex;">
                                                                                    <span>Your Secret Code is</span>
                                                                                    <a style="margin-left: 68%;" href="javascript:void(0)" id="toggleSecretCode">show</a>
                                                                                </label>
                                                                                <input type="password" name="SecretCode" class="form-control <?php echo ($USER_DATA->AuthType == "UseSecretCode" and empty($USER_DATA->IsSecretKey)) ? "border-red" : ""; ?>" <?php echo ($USER_DATA->AuthType == "UseSecretCode") ? "" : "readonly"; ?> id="SecretCodeInput" placeholder="Enter Secret Code" value="<?php echo $USER_DATA->IsSecretKey; ?>">
                                                                                <?php echo ($USER_DATA->AuthType == "UseSecretCode" and empty($USER_DATA->IsSecretKey)) ? "<span class='text-danger'>Secret code is required!</span>" : ""; ?>
                                                                                <div class="form-check mb-2" style="margin-top: 10px;">
                                                                                    <input class="form-check-input" type="radio" name="authType" value="secret" id="secretCodeRadio" <?php echo ($USER_DATA->AuthType == "UseSecretCode") ? "checked" : ""; ?>>
                                                                                    <label class="form-check-label" for="secretCodeRadio">Use Secret Code for Verification</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" name="UpdateSecretCode" class="btn btn-primary">Save Changes</button>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card shadow-none border mb-0 h-100">
                                                            <div class="card-body">
                                                                <h6 class="mb-2">Session Time</h6>
                                                                <p>Session time refers to the duration for which a user remains logged into the system before they are automatically logged out. This setting helps ensure the security and proper management of user sessions.</p>
                                                                <span style="padding-top: 1px;">
                                                                    <?php
                                                                    $isEndingAt = $conn->query("SELECT isEndingAt FROM `sessions` WHERE UserID = '$USERID'")->fetchColumn();
                                                                    ?>
                                                                    Your current session ends on <?php echo FormatDate($isEndingAt); ?>
                                                                </span>
                                                                <form method="POST">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="mb-3">
                                                                                <label class="form-label" style="width: 100%; display: flex;">
                                                                                    <span>Your Session time is</span>
                                                                                </label>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <input type="number" name="Time" class="form-control" value="<?php echo $USER_DATA->HasSessionTime ? (int) $USER_DATA->HasSessionTime : ''; ?>" placeholder="Enter time">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <select name="Units" class="form-control">
                                                                                            <option value="Minutes" <?php echo strpos($USER_DATA->HasSessionTime, 'Minutes') !== false ? 'selected' : ''; ?>>Minutes</option>
                                                                                            <option value="Hours" <?php echo strpos($USER_DATA->HasSessionTime, 'Hours') !== false ? 'selected' : ''; ?>>Hours</option>
                                                                                            <option value="Days" <?php echo strpos($USER_DATA->HasSessionTime, 'Days') !== false ? 'selected' : ''; ?>>Days</option>
                                                                                            <option value="Weeks" <?php echo strpos($USER_DATA->HasSessionTime, 'Weeks') !== false ? 'selected' : ''; ?>>Weeks</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" name="UpdateSessionTime" class="btn btn-primary">Save Changes</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($isTab == "Permission") : ?>
                                    <div class="col-md-12" style="padding: 10px;">
                                        <ol class="list-group list-group-numbered">
                                            <?php
                                            $permissions = $conn->query("SELECT * FROM `permissions` ORDER BY Category ASC, LabelName ASC");
                                            while ($row = $permissions->fetchObject()) { ?>

                                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold"><?php echo $row->Category; ?></div>
                                                        <?php echo $row->LabelName; ?>
                                                    </div>
                                                    <?php if (IsCheckPermission($USERID, $row->Permission)) : ?>
                                                        <span title="Permission was granted">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="2em" height="2em" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="m10.6 16.6l7.05-7.05l-1.4-1.4l-5.65 5.65l-2.85-2.85l-1.4 1.4zM12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22" />
                                                            </svg>
                                                        </span>
                                                    <?php else : ?>
                                                        <span title="Permission was not granted">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="2em" height="2em" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="M12 17q.425 0 .713-.288T13 16t-.288-.712T12 15t-.712.288T11 16t.288.713T12 17m-1-4h2V7h-2zm1 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22" />
                                                            </svg>
                                                        </span>

                                                    <?php endif; ?>

                                                </li>
                                            <?php } ?>

                                        </ol>
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
<?php if ($isTab == "Profile") : ?>
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
<?php if ($isTab == "Auth") : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailVerificationRadio = document.getElementById('emailVerificationRadio');
            const secretCodeRadio = document.getElementById('secretCodeRadio');

            emailVerificationRadio.addEventListener('change', function() {
                if (emailVerificationRadio.checked) {
                    $("#SecretCodeInput").attr('readonly', true);

                    $.ajax({
                        url: window.location.href,
                        type: 'POST',
                        data: {
                            UpdateAuthType: "Email"
                        },
                        success: function(response) {
                            ShowToast("Authentication type successfully updated");
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);

                        },
                        error: function(error) {
                            ShowToast('Failed to generate secret code:', error);
                        }
                    })
                }
            });

            secretCodeRadio.addEventListener('change', function() {
                if (secretCodeRadio.checked) {
                    $("#SecretCodeInput").attr('readonly', false);
                    $.ajax({
                        url: window.location.href,
                        type: 'POST',
                        data: {
                            UpdateAuthType: "UseSecretCode"
                        },
                        success: function(response) {
                            ShowToast("Authentication type successfully updated");
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);

                        },
                        error: function(error) {
                            ShowToast('Failed to generate secret code:', error);
                        }
                    })
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const toggleLink = document.getElementById('toggleSecretCode');
            const secretCodeInput = document.getElementById('SecretCodeInput');

            toggleLink.addEventListener('click', function() {
                if (secretCodeInput.type === 'password') {
                    secretCodeInput.type = 'text';
                    toggleLink.textContent = 'hide';
                } else {
                    secretCodeInput.type = 'password';
                    toggleLink.textContent = 'show';
                }
            });
        });
    </script>

<?php endif; ?>

</html>