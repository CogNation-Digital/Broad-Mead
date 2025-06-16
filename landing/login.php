<?php
include "../includes/config.php";
require 'email/functions.php';

function SaveAuthCode($conn, $UserID, $code)
{
    global $KeyID;
    $insertQuery = "INSERT INTO auth_codes (UserID, KeyID, Code, ExpiresOn, Date) VALUES (:UserID, :KeyID, :Code, NOW() + INTERVAL 10 MINUTE, NOW())";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bindParam(':UserID', $UserID);
    $insertStmt->bindParam(':KeyID', $KeyID);
    $insertStmt->bindParam(':Code', $code);
    return $insertStmt->execute();
}

if (isset($_POST['Continue'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // Check AuthType
        if ($row->AuthType == "UseSecretCode") {
            $code = $row->IsSecretKey;
            if (SaveAuthCode($conn, $row->UserID, $code)) {
                header(header: "Location: $LINK/auth/verify/?auth_code=$KeyID&is_secret=true");
                exit;
            } else {
                $error = 1;
                $response = "Something went wrong. Please try again";
            }
        } else if ($row->AuthType == "Email") {
            // Generate 6-digit code
            $code = rand(100000, 999999);    
            if (sendVerificationEmail($row->Email, $row->Name, $code)) {
                if (SaveAuthCode($conn, $row->UserID, $code)) {
                    header("Location: $LINK/auth/verify/?auth_code=$KeyID&is_secret=false");
                    exit;
                } else {
                    $error = 1;
                    $response = "Something went wrong. Please try again";
                }
            } else {
                $error = 1;
                $response = "Email verification failed. Please try again";
            }
        }
    } else {
        $error = 1;
        $response = "Email address or password incorrect";
    }
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
                        <h4 class="text-center" style="font-size: 30px; font-weight: bolder; margin-bottom: 30px;">Login to your account</h4>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email">Email address</label>
                                <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email Address">
                            </div>
                            <div class="mb-3">
                                <label for="password" style="display: flex; width: 100%; justify-content: space-between;">
                                    <span>Password</span>
                                    <a href="#" id="togglePassword">Show Password</a>
                                </label>
                                <input type="password" name="password" class="form-control" id="floatingInput1" placeholder="Password">
                            </div>
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check"><input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked=""> <label class="form-check-label text-muted" for="customCheckc1">Remember me?</label></div>
                                <h6 class="text-secondary f-w-400 mb-0"><a href="forgot-password">Forgot Password?</a></h6>
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
                button.textContent = 'Signing In' + dots[index];
                index = (index + 1) % dots.length;
            }

            setInterval(changeText, 500);
        }, 1000);

    });

    // Get the password input and toggle link
    const passwordInput = document.getElementById('floatingInput1');
    const togglePasswordLink = document.getElementById('togglePassword');

    // Add event listener to the toggle link
    togglePasswordLink.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default anchor action

        // Toggle the input type between password and text
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordLink.textContent = 'Hide Password';
        } else {
            passwordInput.type = 'password';
            togglePasswordLink.textContent = 'Show Password';
        }
    });
</script>

</html>