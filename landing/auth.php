<?php
include "../includes/config.php";
require 'email/functions.php';


if ($_GET['is_secret'] == "true") {
    $header = "Enter your security code";
} else {
    $header = "Enter Verification Code";
}

if (isset($_POST['continue'])) {
    $auth_code = $_GET['auth_code'];
    $code = $_POST['code'];

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM auth_codes WHERE KeyID = :auth_code AND Code = :code AND ExpiresOn > NOW() - INTERVAL 10 MINUTE");
    $stmt->bindParam(':auth_code', $auth_code);
    $stmt->bindParam(':code', $code);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetchObject();
        $UserID = $row->UserID;
        $stmt = $conn->prepare("SELECT HasSessionTime, Name, ClientKeyID FROM `users` WHERE UserID = :UserID");
        $stmt->bindParam(':UserID', $UserID);
        $stmt->execute();
        $row = $stmt->fetchObject();

        $HasSessionTime = $row->HasSessionTime;
        $Name = $row->Name;
        $ClientKeyID = $row->ClientKeyID;

        // Calculate the ending time based on the session time
        $HasStartedAt = new DateTime();
        $isEndingAt = clone $HasStartedAt;

        if (preg_match('/(\d+)\s*(\w+)/i', $HasSessionTime, $matches)) {
            $timeValue = $matches[1];
            $timeUnit = strtolower($matches[2]);

            // Modify the DateTime object based on the unit
            switch ($timeUnit) {
                case 'minute':
                case 'minutes':
                    $isEndingAt->modify("+$timeValue minutes");
                    break;
                case 'hour':
                case 'hours':
                    $isEndingAt->modify("+$timeValue hours");
                    break;
                case 'day':
                case 'days':
                    $isEndingAt->modify("+$timeValue days");
                    break;
                case 'week':
                case 'weeks':
                    $isEndingAt->modify("+$timeValue weeks");
                    break;
                default:
                    // Handle unknown units or default case if necessary
                    throw new Exception("Invalid time unit: $timeUnit");
            }
        } else {
            $response = "Error, something went wrong. Please try again";
        }
        $insertQuery = $conn->prepare("INSERT INTO `sessions` (`UserID`, `IsStatus`, `HasStartedAt`, `isEndingAt`) VALUES (:UserID, :IsStatus, :HasStartedAt, :isEndingAt)");
        $IsStatus = 'Active';
        $HasStartedAtFormatted = $HasStartedAt->format('Y-m-d H:i:s');
        $isEndingAtFormatted = $isEndingAt->format('Y-m-d H:i:s');
        $insertQuery->bindParam(':UserID', $UserID);
        $insertQuery->bindParam(':IsStatus', $IsStatus);
        $insertQuery->bindParam(':HasStartedAt', $HasStartedAtFormatted);
        $insertQuery->bindParam(':isEndingAt', $isEndingAtFormatted);
        $expiryTime = time() + strtotime($HasSessionTime);
        if ($insertQuery->execute()) {
            $Fd = FormatDate($date);
            $Notification = "$Name successfully logged in on $Fd";
            Notify($UserID, $ClientKeyID, $Notification);
            setcookie('USERID', $UserID, $expiryTime, '/');
            header("location: $LINK/home");
        } else {
            $response = "Error starting session. Please try again";
        }
    } else {
        // Authentication failed
        $error = 1;
        $response = "Authentication failed. Please try again.";
    }
}





?>


<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="<?php echo $theme; ?>">
    <?php include "../includes/toast.php" ?>

    <div class="auth-main"  >
        <div class="auth-wrapper">
            <div class="auth-sidecontent" >
            </div>
            <div class="auth-form" >
                <div class="card my-5" style="border: none; background-color: transparent !important;">
                    <div class="card-body" >
                        <a href="<?php echo $LINK; ?>/login">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none">
                                <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <div class="d-grid my-3">
                            <center>
                                <img src="<?php echo $ICON; ?>" alt="images" style="width: 100px; height: 90px;" class="img-fluid img-auth-side">
                            </center>
                        </div>
                        <h4 class="text-center" style="font-size: 30px; font-weight: bolder; margin-bottom: 30px;">
                            <?php
                            echo $header;
                            ?>
                        </h4>
                        <form method="POST">
                            <div class="mb-3"><input type="text" name="code" class="form-control" id="floatingInput" placeholder="<?php echo $header; ?>"></div>
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                            </div>
                            <div class="d-grid mt-4">
                                <button id="continueButton" type="submit" name="continue" class="btn btn-primary">Continue</button>
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
</script>

</html>