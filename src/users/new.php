<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

if (isset($_POST['submit'])) {
    $Name = trim($_POST['Name']);
    $Email = trim($_POST['Email']);
    $Position = trim($_POST['Position']);
    $Department = trim($_POST['Department']);
    $Password = "12345";
    $IsSecretKey = $Password;
    $HasSessionTime = "50 Minutes";
    $AuthType = "UseSecretCode";



    // Check if the name, email, position, or department is empty
    if (empty($Name) || empty($Email) || empty($Position) || empty($Department)) {
        $response = "All fields are required.";
        $error = 1;
    }
    // Check if the email address is valid
    elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $response = "Invalid email format.";
        $error = 1;
    } else {
        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE Email = :email");
        $stmt->bindParam(':email', $Email);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            $response = "Email already exists.";
            $error = 1;
        } else {
            $UserID = $KeyID;
            // Prepare the SQL insert statement
            $sql = "INSERT INTO users (ClientKeyID, UserID, Name, Email, Password, IsSecretKey, HasSessionTime, AuthType, Department, Position, CreatedBy, Date) 
                    VALUES (:ClientKeyID, :UserID, :Name, :Email, :Password, :IsSecretKey, :HasSessionTime, :AuthType, :Department, :Position, :CreatedBy, :Date)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':ClientKeyID', $ClientKeyID);
            $stmt->bindParam(':UserID', $UserID);
            $stmt->bindParam(':Name', $Name);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Password', $Password);
            $stmt->bindParam(':IsSecretKey', $IsSecretKey);
            $stmt->bindParam(':HasSessionTime', $HasSessionTime);
            $stmt->bindParam(':AuthType', $AuthType);
            $stmt->bindParam(':Department', $Department);
            $stmt->bindParam(':Position', $Position);
            $stmt->bindParam(':CreatedBy', $USERID);
            $stmt->bindParam(':Date', $date);

            if ($stmt->execute()) {
                $NOTIFICATION = "User '$Name' has been successfully created by $NAME. The new user is from the $Department department and holds the position of $Position.";
                Notify($USERID, $ClientKeyID, $NOTIFICATION);
                header("location: $LINK/update_permissions/?ID=$UserID ");
            } else {
                $response = "An error occurred while saving the data.";
                $error = 1;
            }
        }
    }
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
                                <h5 class="mb-0">Create User</h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "CREATE_USERS")) : ?>
                                    <div class="card-body">
                                        <form method="POST">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="card shadow-none border mb-0 h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1 me-3">
                                                                    <h6 class="mb-0">Name and Email Address</h6>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 mt-3">
                                                                <label class="form-label">Enter user's full name</label>
                                                                <input type="text" class="form-control" name="Name">
                                                            </div>
                                                            <div class="mb-3 mt-3">
                                                                <label class="form-label">Enter user's email address</label>
                                                                <input type="text" class="form-control" name="Email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card shadow-none border mb-0 h-100">
                                                        <div class="card-body">
                                                            <h6 class="mb-2">Position and Department</h6>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="mb-3"><label class="form-label">Position</label>
                                                                        <input type="text" class="form-control" name="Position">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="mb-3"><label class="form-label">Department</label>
                                                                        <input type="text" class="form-control" name="Department">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <button class="btn btn-primary" name="submit" style="width: 120px;">Next</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php else : ?>
                                    <?php
                                    DeniedAccess();
                                    ?>
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


</html>