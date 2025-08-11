


<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$user_id = $_SESSION['user_id'] ?? null;

// Enhanced email footer function for mailshots
function getEmailFooter($consultantEmail = '', $consultantName = 'Recruitment Team', $consultantNumber = '', $consultantTitle = 'Consultant') {
    // Direct Google Drive URLs for email images
    $driveBaseUrl = 'https://drive.google.com/uc?export=view&id=';
    $images = [
        'logo' => $driveBaseUrl . '1nTWBGbLYzj6XxxkFZwvPAQgBsuFFCVbD',        // Nocturnal logo
        'linkedin' => $driveBaseUrl . '10bVdFqZdGSloE2DoavlBzm5IXNTm7qBj',    // LinkedIn badge
        'facebook' => $driveBaseUrl . '1SR3INbhT1SC0CXmKG-1EFRlSOe3QDULC',    // Facebook logo
        'instagram' => $driveBaseUrl . '1t8o-XT-w9xphxzCfaHN9VimI6zhXrXRJ',   // Instagram badge
        'cyber' => $driveBaseUrl . '1_HUGtuMrnmuw6WPvhOHHLuqMZKwaXdqr',       // Cyber Essentials
        'rec' => $driveBaseUrl . '1kck0O1jAvG6QlahYJC_oPeWCAuApsqKI'          // REC Corporate
    ];
    
    return '
    <div style="max-width: 600px; font-family: Arial, sans-serif; line-height: 1.4; margin: auto; border-top: 2px solid #333; padding-top: 20px; margin-top: 30px;">
        
        <!-- Signature Line -->
        <div style="margin-bottom: 20px;">
            <p style="margin: 0; color: #333; font-size: 14px;">Best regards,<br>
            <strong>' . htmlspecialchars($consultantName) . '</strong><br>
            ' . htmlspecialchars($consultantTitle) . '</p>
        </div>
        
        <!-- Nocturnal Logo -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="' . $images['logo'] . '" alt="Nocturnal Recruitment Solutions" style="max-width: 280px; height: auto; display: block; margin: 0 auto;">
        </div>

        <!-- Contact Information -->
        <div style="text-align: center; margin-bottom: 20px; font-size: 14px; color: #333;">
            <div style="margin-bottom: 8px;">
                <span style="color: #666;">📍</span>
                <a href="https://maps.google.com/?q=Office+16,+321+High+Road,+RM6+6AX" style="color: #0066cc; text-decoration: none;">Nocturnal Recruitment, Office 16, 321 High Road, RM6 6AX</a>
            </div>
            <div style="margin-bottom: 8px;">
                <span style="color: #666;">☎️</span> <a href="tel:02080502708" style="color: #333; text-decoration: none;">0208 050 2708</a>
                <span style="margin-left: 20px; color: #666;">📱</span> <a href="tel:07827519020" style="color: #333; text-decoration: none;">07827 519020</a>
            </div>
            <div style="margin-bottom: 15px;">
                <span style="color: #666;">✉️</span> <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: none;">info@nocturnalrecruitment.co.uk</a>
                <span style="margin-left: 20px; color: #666;">🌐</span> <a href="https://www.nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: none;">www.nocturnalrecruitment.co.uk</a>
            </div>
        </div>

        <!-- Social Media and Certifications -->
        <div style="text-align: center; margin-bottom: 20px;">
            <table style="margin: 0 auto; border-collapse: collapse;">
                <tr>
                    <td style="text-align: center; padding: 5px;">
                        <a href="https://www.linkedin.com/company/nocturnal-recruitment-solutions/" target="_blank">
                            <img src="' . $images['linkedin'] . '" alt="LinkedIn" style="height: 30px; width: auto; border: none; display: block;">
                        </a>
                    </td>
                    <td style="text-align: center; padding: 5px;">
                        <a href="https://www.facebook.com/nocturnalrecruitment/" target="_blank">
                            <img src="' . $images['facebook'] . '" alt="Facebook" style="height: 30px; width: auto; border: none; display: block;">
                        </a>
                    </td>
                    <td style="text-align: center; padding: 5px;">
                        <a href="https://www.instagram.com/nocturnalrecruitment/" target="_blank">
                            <img src="' . $images['instagram'] . '" alt="Instagram" style="height: 30px; width: auto; border: none; display: block;">
                        </a>
                    </td>
                    <td style="text-align: center; padding: 5px;">
                        <img src="' . $images['cyber'] . '" alt="Cyber Essentials Certified" style="height: 35px; width: auto; border: none; display: block;">
                    </td>
                    <td style="text-align: center; padding: 5px;">
                        <img src="' . $images['rec'] . '" alt="REC Corporate Member" style="height: 35px; width: auto; border: none; display: block;">
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Company Registration -->
        <div style="text-align: center; color: #333333; font-size: 14px; font-weight: bold; margin-bottom: 20px;">
            Company Registration – 11817091
        </div>

        <!-- Disclaimer -->
        <div style="font-size: 11px; color: #666666; line-height: 1.4; border-top: 1px solid #dddddd; padding-top: 15px; margin-top: 20px;">
            <strong style="color: #c41e3a;">Disclaimer:</strong> This email is intended only for the use of the addressee named above and may be confidential or legally privileged. If you are not the addressee, you must not read it and must not use any information contained in nor copy it nor inform any person other than <a href="https://www.nocturnalrecruitment.co.uk" style="color: #c41e3a; text-decoration: none; font-weight: bold;">Nocturnal Recruitment</a> or the addressee of its existence or contents. If you have received this email in error, please delete it and notify our team at <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: none;">info@nocturnalrecruitment.co.uk</a>
        </div>
    </div>';
}

// TODO:Change these mail details to your own
// ? This is a sample function to send emails using PHPMailer with Titan SMTP settings
// ? Make sure to install PHPMailer via Composer: composer require phpmailer/phpmailer
// ? Ensure you have the correct SMTP settings for your Gmail email account

function sendTitanMail($to, $subject, $body, $fromEmail, $fromName = 'Recruitment Team') {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'learn@natec.icu';
        $mail->Password = '@WhiteDiamond0100'; //TODO: Replace with your actual password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);
        $mail->addReplyTo('info@nocturnalrecruitment.co.uk', 'Information');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'mailshot') {
    if (empty($_POST['selected_candidates'])) {
        $error_message = "Please select at least one candidate.";
    } elseif (empty($_POST['subject'])) {
        $error_message = "Email subject is required.";
    } elseif (empty($_POST['template'])) {
        $error_message = "Please select an email template.";
    } else {
        $candidate_ids = $_POST['selected_candidates'];
        $subject = $_POST['subject'];

        $template = $_POST['template'];

        $templates = [
            'job_alert' => [
                'subject' => 'New Job Opportunities Matching Your Profile',
                'body' => "Hello [Name],\n\nWe have new job opportunities that match your profile. Log in to your account to view them:\n\n[LoginLink]\n\nBest regards,\nThe Recruitment Team"
            ],
            'newsletter' => [
                'subject' => 'Our Latest Industry Insights',
                'body' => "Hello [Name],\n\nCheck out our latest newsletter with industry insights and job tips:\n\n[NewsletterLink]\n\nBest regards,\nThe Recruitment Team"
            ],
            'event_invitation' => [
                'subject' => 'Invitation to Recruitment Event',
                'body' => "Hello [Name],\n\nYou are invited to our upcoming recruitment event. Please RSVP here:\n\n[EventLink]\n\nBest regards,\nThe Recruitment Team"
            ],
            'follow_up' => [
                'subject' => 'Following Up on Your Application',
                'body' => "Hello [Name],\n\nFollowing up on your recent application. Any updates?\n\nBest regards,\nThe Recruitment Team"
            ],
            'welcome' => [
                'subject' => 'Welcome to Our Candidate Network',
                'body' => "Hello [Name],\n\nWelcome to our candidate database! We will contact you when we find a match.\n\nBest regards,\nThe Recruitment Team"
            ]
        ];

        $template_details = $templates[$template] ?? [
            'subject' => $subject,
            'body' => "Hello [Name],\n\nThank you for being part of our network.\n\nBest regards,\nThe Recruitment Team"
        ];

        $final_subject = empty($subject) ? $template_details['subject'] : $subject;
        $base_body = $template_details['body'];
        $from_email = "learn@natec.icu";

        $success_count = 0;
        $error_count = 0;
        $error_details = [];

        foreach ($candidate_ids as $candidate_id) {
            try {
                $stmt = $db_2->prepare("SELECT Name, Email FROM _candidates WHERE id = ?");
                $stmt->execute([$candidate_id]);
                $candidate = $stmt->fetch(PDO::FETCH_OBJ);

                if (!$candidate) {
                    $stmt = $db_1->prepare("SELECT CONCAT(first_name, ' ', last_name) as Name, email as Email FROM _candidates WHERE id = ?");
                    $stmt->execute([$candidate_id]);
                    $candidate = $stmt->fetch(PDO::FETCH_OBJ);
                }

                if ($candidate && filter_var($candidate->Email, FILTER_VALIDATE_EMAIL)) {
                    $to = $candidate->Email;
                    $name = $candidate->Name ?: 'Candidate';

                    $personalized_body = str_replace(
                        ['[Name]', '[LoginLink]', '[NewsletterLink]', '[EventLink]'],
                        [$name, 'https://broad-mead.com/login', 'https://broad-mead.com/newsletter', 'https://broad-mead.com/events'],
                        $base_body
                    );

                    // Convert plain text to HTML and add footer
                    $html_body = nl2br(htmlspecialchars($personalized_body)) . getEmailFooter('', 'Recruitment Team', '', 'Team');

                    $result = sendTitanMail($to, $final_subject, $html_body, $from_email);

                    if ($result === true) {
                        $success_count++;
                        $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at) VALUES (?, ?, ?, ?, ?, 'sent', NULL, NOW())");
                        $log_stmt->execute([$candidate_id, $to, $final_subject, $template, $html_body]);
                    } else {
                        $error_count++;
                        $error_details[] = "Failed to send to: $to - $result";
                        $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at) VALUES (?, ?, ?, ?, ?, 'failed', ?, NOW())");
                        $log_stmt->execute([$candidate_id, $to, $final_subject, $template, $html_body, $result]);
                    }
                } else {
                    $error_count++;
                    $candidate_email = $candidate->Email ?? 'N/A';
                    $error_details[] = "Invalid email for candidate ID: $candidate_id (Email: $candidate_email)";
                    $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at) VALUES (?, ?, ?, ?, ?, 'invalid', NULL, NOW())");
                    $log_stmt->execute([$candidate_id, $candidate_email, $final_subject, $template, 'Email validation failed - no message sent']);
                }
            } catch (Exception $e) {
                $error_count++;
                $error_details[] = "Error processing candidate ID: $candidate_id - " . $e->getMessage();
                $candidate_email = isset($candidate) && isset($candidate->Email) ? $candidate->Email : 'N/A';
                $log_stmt = $db_2->prepare("INSERT INTO mailshot_logs (candidate_id, email, subject, template, body, status, error, sent_at) VALUES (?, ?, ?, ?, ?, 'error', ?, NOW())");
                $log_stmt->execute([$candidate_id, $candidate_email, $final_subject, $template, 'Exception occurred - no message sent', $e->getMessage()]);
            }
        }

        if ($error_count === 0) {
            $success_message = "Mailshot sent successfully to $success_count candidates.";
        } else {
            $error_message = "Mailshot partially sent: $success_count succeeded, $error_count failed.";
            if (!empty($error_details)) {
                $error_message .= "\n\nError details:\n" . implode("\n", array_slice($error_details, 0, 5));
                if (count($error_details) > 5) {
                    $error_message .= "\n... and " . (count($error_details) - 5) . " more errors.";
                }
            }
        }
    }
}
?>



