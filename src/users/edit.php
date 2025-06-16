<?php include "../../includes/config.php";
if (!isset($_COOKIE['USERID'])) {
    # code...
    header("location: $LINK/login ");
}

$UserID = $_GET['ID'];
$_user_data__ = $conn->query("SELECT * FROM `users` WHERE UserID = '$UserID' ")->fetchObject();

if (isset($_POST['submit'])) {
    $Name = trim($_POST['Name']);
    $Email = trim($_POST['Email']);
    $Position = trim($_POST['Position']);
    $Department = trim($_POST['Department']);


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
        try {
            // Prepare the SQL statement to update user information
            $sql = "UPDATE users SET Name = :Name, Email = :Email, Department = :Department, Position = :Position WHERE UserID = :UserID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':UserID', $UserID);
            $stmt->bindParam(':Name', $Name);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Department', $Department);
            $stmt->bindParam(':Position', $Position);

            // Execute the statement
            if ($stmt->execute()) {
                // Prepare the notification message
                $NOTIFICATION = "User '$Name' has been successfully updated by $NAME. The new user is from the $Department department and holds the position of $Position.";
                Notify($USERID, $ClientKeyID, $NOTIFICATION);

                // Set success response
                $response = "User details have been successfully updated.";
            } else {
                $response = "An error occurred while saving the data.";
                $error = 1;
            }
        } catch (PDOException $e) {
            // Handle any exceptions that occur during the update
            $response = "Error: " . $e->getMessage();
            $error = 1;
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
                                <h5 class="mb-0">Edit User</h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <?php if (IsCheckPermission($USERID, "EDIT_USERS")) : ?>
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
                                                                <input type="text" class="form-control" value="<?php echo $_user_data__->Name; ?>" name="Name">
                                                            </div>
                                                            <div class="mb-3 mt-3">
                                                                <label class="form-label">Enter user's email address</label>
                                                                <input type="text" class="form-control" value="<?php echo $_user_data__->Email; ?>" name="Email">
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
                                                                        <input type="text" value="<?php echo $_user_data__->Position; ?>" class="form-control" name="Position">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="mb-3"><label class="form-label">Department</label>
                                                                        <input type="text" class="form-control" value="<?php echo $_user_data__->Department; ?>" name="Department">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <button class="btn btn-primary" name="submit" style="width: 120px;">Upate</button>
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