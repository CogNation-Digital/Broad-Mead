<?php
include "../includes/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateToken($length = 32)
{
    // Generate a random string of the specified length
    return bin2hex(random_bytes($length));
}

if (isset($_POST['Continue'])) {
    $email = $_POST['email'];

    // Check if email exists in the users table
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $userID = $row->UserID;
        $userName = $row->Name; // Assuming you have a 'name' field in your users table

        $token = generateToken(); // Generate a unique token
        $expiresAt = date('Y-m-d H:i:s', strtotime('+2 hour')); // Token expiration (1 hour from now)

        try {
            // Mail settings
            $host = "broad-mead.com";
            $user = "nocturnalrecruitment@broad-mead.com";
            $pass = "@Michael1693250341";
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $user;
            $mail->Password = $pass;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('nocturnalrecruitment@broad-mead.com', 'Nocturnal Recruitment');
            $mail->addAddress($email, $userName); // Use the user's email and name

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Request to reset your password';

            // Load the HTML template
            $htmlBody = file_get_contents('password_reset.html');
            $htmlBody = str_replace('{{ reset_link }}', 'https://broad-mead.com/password-reset?token=' . $token, $htmlBody);

            $mail->Body = $htmlBody;

            // Send the email
            $mail->send();

            // Insert token into the database
            $query = "INSERT INTO reset_password_tokens (user_id, token, created_at, expires_at, is_used) 
                      VALUES (:user_id, :token, NOW(), :expires_at, 0)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $userID);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires_at', $expiresAt);
            $stmt->execute();

            // Success message
            $message = "We have sent a password reset link to your email address. Please check your inbox (and spam folder) to reset your password.";
        } catch (Exception $e) {
            // Error handling
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // Email does not exist in the database
        $message = "Email address does not exist.";
    }

    // Output message or handle redirection here
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
                        <h4 class="text-center" style="font-size: 30px; font-weight: bolder; margin-bottom: 30px;">Reset your password</h4>
                        <?php if (isset($message)) { ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php } ?>
                        <form method="POST">
                            <div class="mb-3"><input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email Address"></div>
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                            </div>
                            <div class="d-grid mt-4">
                                <button id="continueButton" type="submit" name="Continue" class="btn btn-primary">Next</button>
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
                button.textContent = 'Processing' + dots[index];
                index = (index + 1) % dots.length;
            }

            setInterval(changeText, 500);
        }, 1000);

    });
</script>

</html>