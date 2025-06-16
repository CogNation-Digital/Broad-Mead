<?php
include "../includes/config.php";

$error = '';
$success = '';

$now = date('Y-m-d H:i:s');
// Check if a token is present in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid
    $query = "SELECT * FROM reset_password_tokens WHERE token = :token ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() == 0) {
        // If the token is invalid, expired, or already used
        $error = "Invalid or expired password reset token.";
    } else {
        // If token is valid, proceed with password reset
        if (isset($_POST['Continue'])) {
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];

            // Check if passwords match
            if ($password !== $cpassword) {
                $error = "Passwords do not match.";
            } else {
                // Validate password strength (optional)
                if (strlen($password) < 8) {
                    $error = "Password must be at least 8 characters long.";
                } else {
                    // Hash the password

                    // Update the user's password
                    $updateQuery = "UPDATE users SET Password = :password WHERE UserID = :userID";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bindParam(':password', $password);
                    $updateStmt->bindParam(':userID', $user['user_id']);
                    $updateStmt->execute();

                    // Mark the token as used
                    $updateTokenQuery = "DELETE FROM reset_password_tokens WHERE token = :token";
                    $updateTokenStmt = $conn->prepare($updateTokenQuery);
                    $updateTokenStmt->bindParam(':token', $token);
                    $updateTokenStmt->execute();

                    if ($updateStmt->execute()) {

                        $success = "Your password has been successfully reset. You can now log in with your new password.";
                        header("Location: login");
                    } else {
                        $error = "Failed to update your password. Please try again";
                    }
                    // Redirect or show success message
                }
            }
        }
    }
} else {
    // If token is not present in URL, redirect to login
    header("Location: login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../includes/toast.php" ?>

    <div class="auth-main">
        <div class="auth-wrapper">
            <div class="auth-sidecontent">
            </div>
            <div class="auth-form">
                <div class="card my-5" style="border: none; background-color: transparent !important;">
                    <div class="card-body">
                        <div class="d-grid my-3">
                            <center>
                                <img src="<?php echo $ICON; ?>" alt="images" style="width: 100px; height: 90px;" class="img-fluid img-auth-side">
                            </center>
                        </div>
                        <h4 class="text-center" style="font-size: 30px; font-weight: bolder; margin-bottom: 30px;">Update your password</h4>
                        <form method="POST">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error ?></div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?php echo $success ?></div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" id="floatingInput" placeholder="New Password" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="cpassword" class="form-control" id="floatingInput1" placeholder="Confirm Password" required>
                            </div>
                            <div class="mb-3">
                                <a href="javascript:void(0);" id="togglePassword">Show Password</a>
                            </div>
                            <div class="d-grid mt-4">
                                <button id="continueButton" type="submit" name="Continue" class="btn btn-primary">Continue</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-4">
                                <h6 class="f-w-500 mb-0" style="color: grey; font-size: 12px;">By clicking "Continue" you agree to our <a href="terms">Terms of service</a> and <a href="privacy">Privacy Policy</a></h6>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include "../includes/js.php" ?>
<script>
    document.getElementById('continueButton').addEventListener('click', function() {
        var button = this;
        setTimeout(() => {
            button.disabled = true;

            let dots = ['. ', '.. ', '... '];
            let index = 0;

            function changeText() {
                button.textContent = 'Resetting Password' + dots[index];
                index = (index + 1) % dots.length;
            }

            setInterval(changeText, 500);
        }, 1000);
    });

    // Get the elements
    const togglePassword = document.getElementById("togglePassword");
    const password = document.getElementById("floatingInput");
    const cpassword = document.getElementById("floatingInput1");

    // Add click event listener to the "Show Password" link
    togglePassword.addEventListener("click", function() {
        // Toggle the type attribute to show or hide the password
        if (password.type === "password" && cpassword.type === "password") {
            password.type = "text";
            cpassword.type = "text";
            togglePassword.textContent = "Hide Password";
        } else {
            password.type = "password";
            cpassword.type = "password";
            togglePassword.textContent = "Show Password";
        }
    });
</script>

</html>