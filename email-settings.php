<?php
/**
 * Nocturnal Recruitment Email Settings
 * Centralized email configuration for consistent branding across the application
 */

// SMTP Configuration
define('SMTP_HOST', 'mail.nocturnalrecruitment.co.uk');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'info@nocturnalrecruitment.co.uk');
define('SMTP_PASSWORD', '@Michael1693250341'); // Consider using environment variables for passwords
define('SMTP_SECURE', 'tls');

// Email Identity
define('EMAIL_FROM', 'info@nocturnalrecruitment.co.uk');
define('EMAIL_FROM_NAME', 'Nocturnal Recruitment');
define('EMAIL_REPLY_TO', 'info@nocturnalrecruitment.co.uk');

// Company Information
define('COMPANY_NAME', 'Nocturnal Recruitment');
define('COMPANY_ADDRESS', 'Office 16, 321 High Road, RM6 6AX');
define('COMPANY_PHONE', '0208 050 2708');
define('COMPANY_MOBILE', '0755 357 0871');
define('COMPANY_EMAIL', 'chax@nocturnalrecruitment.co.uk');
define('COMPANY_WEBSITE', 'www.nocturnalrecruitment.co.uk');
define('COMPANY_REGISTRATION', '11817091');

/**
 * Get standardized email footer HTML
 * @return string HTML footer with company branding and disclaimer
 */
function getNocturnalEmailFooter() {
    return '
    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #f0f0f0; font-family: Arial, sans-serif;">
        <table style="width: 100%; max-width: 600px;">
            <tr>
                <td style="text-align: center; padding-bottom: 20px;">
                    <img src="https://nocturnalrecruitment.co.uk/logo.png" alt="Nocturnal Recruitment" style="max-width: 200px; height: auto;">
                </td>
            </tr>
            <tr>
                <td style="text-align: center; color: #333; font-size: 14px; line-height: 1.6;">
                    <strong>' . COMPANY_NAME . '</strong><br>
                    ' . COMPANY_ADDRESS . '<br><br>
                    
                    <div style="margin: 15px 0;">
                        <a href="tel:' . COMPANY_PHONE . '" style="color: #007bff; text-decoration: none;">' . COMPANY_PHONE . '</a> &nbsp;&nbsp;
                        <a href="tel:' . COMPANY_MOBILE . '" style="color: #007bff; text-decoration: none;">' . COMPANY_MOBILE . '</a><br>
                        <a href="mailto:' . COMPANY_EMAIL . '" style="color: #007bff; text-decoration: none;">' . COMPANY_EMAIL . '</a><br>
                        <a href="https://' . COMPANY_WEBSITE . '" style="color: #007bff; text-decoration: none;">' . COMPANY_WEBSITE . '</a>
                    </div>
                    
                    <div style="margin: 20px 0; font-size: 12px; color: #666;">
                        <strong>Company Registration – ' . COMPANY_REGISTRATION . '</strong>
                    </div>
                    
                    <div style="margin-top: 25px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; font-size: 11px; color: #666; text-align: left;">
                        <strong>Disclaimer*</strong> This email is intended only for the use of the addressee named above and may be confidential or legally privileged. If you are not the addressee, you must not read it and must not use any information contained in nor copy it nor inform any person other than Nocturnal Recruitment or the addressee of its existence or contents. If you have received this email in error, please delete it and notify our team at <a href="mailto:' . EMAIL_REPLY_TO . '" style="color: #007bff;">' . EMAIL_REPLY_TO . '</a>
                    </div>
                </td>
            </tr>
        </table>
    </div>';
}

/**
 * Configure PHPMailer with standard settings
 * @param PHPMailer $mail The PHPMailer instance to configure
 * @return void
 */
function configureNocturnalMailer($mail) {
    // Server settings
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->Username = $user;
    $mail->Password = $pass;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    
    
    // Default sender
    $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
    $mail->addReplyTo(EMAIL_REPLY_TO, EMAIL_FROM_NAME);
    
    // Set UTF-8 encoding
    $mail->CharSet = 'UTF-8';
    $mail->addCustomHeader('Content-Type', 'text/html; charset=UTF-8');
}
?>
