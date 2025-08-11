<?php
// Test the email footer appearance
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
require_once 'includes/config.php';

// Include the email footer function from mailshot_functionality.php
require_once 'mailshot_functionality.php';

// Get user info for testing
$loggedInUserEmail = '';
$loggedInUserName = '';
$USERID = $_COOKIE['USERID'] ?? null;

if ($USERID) {
    try {
        $stmt = $conn->prepare("SELECT Email, Name FROM users WHERE UserID = :userid");
        $stmt->bindParam(':userid', $USERID);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if ($user) {
            $loggedInUserEmail = strtolower($user->Email);
            $loggedInUserName = $user->Name ?? 'Consultant';
        }
    } catch (PDOException $e) {
        error_log("Error fetching user email: " . $e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Footer Test - Updated</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: #f5f5f5;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .test-info { 
            background: #e8f4fd; 
            padding: 15px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            border-left: 4px solid #007cba;
        }
        .email-preview {
            border: 2px solid #ddd;
            background: white;
            margin: 20px 0;
            border-radius: 5px;
            overflow: hidden;
        }
        .email-content {
            padding: 20px;
            background: white;
        }
        .status-good { color: #28a745; font-weight: bold; }
        .status-bad { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Footer Test - Updated Version</h1>
        
        <div class="test-info">
            <h3>📋 Test Information:</h3>
            <p><strong>User:</strong> <?php echo $loggedInUserName ?: 'Not logged in'; ?> (<?php echo $loggedInUserEmail ?: 'No email'; ?>)</p>
            <p><strong>Server:</strong> <?php echo $_SERVER['HTTP_HOST']; ?></p>
            <p><strong>Status:</strong> 
                <?php if ($USERID): ?>
                    <span class="status-good">✅ User logged in - Full test available</span>
                <?php else: ?>
                    <span class="status-bad">❌ Please login first for full test</span>
                <?php endif; ?>
            </p>
        </div>

        <h2>📬 Mailshot Email Footer Preview</h2>
        <p>This is how emails will look when sent through the mailshot system:</p>
        
        <div class="email-preview">
            <div class="email-content">
                <!-- Sample email content -->
                <p><strong>Subject:</strong> New Job Opportunities Matching Your Profile</p>
                <hr>
                <p>Dear John Smith,</p>
                <p>We have new job opportunities that match your profile. Please log in to your account to view them:</p>
                <p><a href="https://broad-mead.com/login">Login to your account</a></p>
                <p>If you have any questions, please don't hesitate to contact us.</p>
                
                <!-- Add the actual email footer from mailshot functionality -->
                <?php echo getEmailFooter('', 'Recruitment Team', '', 'Team'); ?>
            </div>
        </div>

        <h2>🔍 Image Verification</h2>
        <p>Testing individual images to verify they load correctly from Google Drive:</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0;">
            
            <div style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <h4>Main Logo</h4>
                <img src="https://drive.google.com/uc?export=view&id=1nTWBGbLYzj6XxxkFZwvPAQgBsuFFCVbD" 
                     alt="Nocturnal Logo" style="max-width: 100%; height: 60px;" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: red;">❌ Failed to load</div>
            </div>
            
            <div style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <h4>LinkedIn Badge</h4>
                <img src="https://drive.google.com/uc?export=view&id=10bVdFqZdGSloE2DoavlBzm5IXNTm7qBj" 
                     alt="LinkedIn" style="max-width: 100%; height: 30px;" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: red;">❌ Failed to load</div>
            </div>
            
            <div style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <h4>Facebook Logo</h4>
                <img src="https://drive.google.com/uc?export=view&id=1SR3INbhT1SC0CXmKG-1EFRlSOe3QDULC" 
                     alt="Facebook" style="max-width: 100%; height: 30px;" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: red;">❌ Failed to load</div>
            </div>
            
            <div style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <h4>Instagram Badge</h4>
                <img src="https://drive.google.com/uc?export=view&id=1t8o-XT-w9xphxzCfaHN9VimI6zhXrXRJ" 
                     alt="Instagram" style="max-width: 100%; height: 30px;" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: red;">❌ Failed to load</div>
            </div>
            
            <div style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <h4>Cyber Essentials</h4>
                <img src="https://drive.google.com/uc?export=view&id=1_HUGtuMrnmuw6WPvhOHHLuqMZKwaXdqr" 
                     alt="Cyber Essentials" style="max-width: 100%; height: 35px;" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: red;">❌ Failed to load</div>
            </div>
            
            <div style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <h4>REC Corporate</h4>
                <img src="https://drive.google.com/uc?export=view&id=1kck0O1jAvG6QlahYJC_oPeWCAuApsqKI" 
                     alt="REC Corporate" style="max-width: 100%; height: 35px;" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: red;">❌ Failed to load</div>
            </div>
        </div>

        <h2>✅ What's Been Updated</h2>
        <div style="background: #d4edda; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745;">
            <ul style="margin: 0;">
                <li><strong>Google Drive Integration</strong> - All images now load directly from your Google Drive</li>
                <li><strong>Professional email footer added</strong> - Includes all company branding and contact info</li>
                <li><strong>All logos and badges included</strong> - LinkedIn, Facebook, Instagram, Cyber Essentials, REC Corporate</li>
                <li><strong>Direct image URLs</strong> - Uses Google Drive direct links for reliable image display</li>
                <li><strong>Responsive design</strong> - Looks good on all devices and email clients</li>
                <li><strong>Legal disclaimer included</strong> - Professional compliance text</li>
            </ul>
        </div>

        <h2>🚀 Next Steps</h2>
        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
            <ol style="margin: 0;">
                <li>Upload the updated <code>mailshot_functionality.php</code> to your live server</li>
                <li>Test sending a mailshot email to yourself</li>
                <li>Verify all images display correctly in the received email</li>
                <li>Check that the email looks professional in different email clients (Gmail, Outlook, etc.)</li>
            </ol>
        </div>
    </div>
</body>
</html>

$loggedInUserEmail = '';
$loggedInUserName = '';
$USERID = $_COOKIE['USERID'] ?? null;

if ($USERID) {
    try {
        $stmt = $conn->prepare("SELECT Email, Name FROM users WHERE UserID = :userid");
        $stmt->bindParam(':userid', $USERID);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if ($user) {
            $loggedInUserEmail = strtolower($user->Email);
            $loggedInUserName = $user->Name ?? 'Consultant';
        }
    } catch (PDOException $e) {
        error_log("Error fetching user email: " . $e->getMessage());
    }
}

// Include the email footer function
function getEmailFooter($consultantEmail, $consultantName, $consultantNumber = '', $consultantTitle = 'Consultant') {
    global $LINK;
    $baseUrl = rtrim($LINK, '/');
    
    return '
    <div style="max-width: 600px; font-family: Arial, sans-serif; line-height: 1.4; margin: auto; border-top: 2px solid #333; padding-top: 20px; margin-top: 30px;">
        
        <!-- Debug Info -->
        <div style="background: #f0f0f0; padding: 10px; margin-bottom: 20px; font-size: 12px;">
            <strong>Debug Info:</strong><br>
            Base URL: ' . $baseUrl . '<br>
            Logo URL: ' . $baseUrl . '/images/image001.jpg<br>
            LinkedIn URL: ' . $baseUrl . '/images/Linked%20in%20badge.jpg<br>
        </div>
        
        <!-- Nocturnal Logo -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="' . $baseUrl . '/images/image001.jpg" alt="Nocturnal Recruitment Solutions" style="max-width: 280px; height: auto; border: 2px solid red;" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">
            <div style="display: none; font-weight: bold; font-size: 24px; margin-bottom: 10px; color: #333; background: yellow; padding: 10px;">NOCTURNAL RECRUITMENT SOLUTIONS (FALLBACK)</div>
        </div>

        <!-- Contact Information -->
        <div style="text-align: center; margin-bottom: 20px; font-size: 14px; color: #333;">
            <div style="margin-bottom: 10px;">
                <span style="color: #666;">📍</span>
                <a href="https://maps.google.com/?q=Office+16,+321+High+Road,+RM6+6AX" style="color: #0066cc; text-decoration: underline;">Nocturnal Recruitment, Office 16, 321 High Road, RM6 6AX</a>
            </div>
            <div style="margin-bottom: 10px;">
                <span style="color: #666;">☎️</span> <a href="tel:02080502708" style="color: #333; text-decoration: none;">0208 050 2708</a>
                <span style="margin-left: 20px; color: #666;">📱</span> <a href="tel:07827519020" style="color: #333; text-decoration: none;">07827 519020</a>
            </div>
            <div style="margin-bottom: 15px;">
                <span style="color: #666;">✉️</span> <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: underline;">info@nocturnalrecruitment.co.uk</a>
                <span style="margin-left: 20px; color: #666;">🌐</span> <a href="https://www.nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: underline;">www.nocturnalrecruitment.co.uk</a>
            </div>
        </div>

        <!-- Social Media and Certifications -->
        <div style="text-align: center; margin-bottom: 20px;">
            <!-- Social Media Icons -->
            <a href="https://www.linkedin.com/company/nocturnal-recruitment-solutions/" target="_blank" style="display: inline-block; margin: 0 5px;">
                <img src="' . $baseUrl . '/images/Linked%20in%20badge.jpg" alt="LinkedIn" style="height: 30px; width: auto; vertical-align: middle; border: 1px solid blue;" onerror="this.outerHTML=\'<span style=\\\"color: #0066cc; font-size: 12px; padding: 5px; border: 1px solid #0066cc; background: yellow;\\\">[LinkedIn - ERROR]</span>\';">
            </a>
            <a href="https://www.facebook.com/nocturnalrecruitment/" target="_blank" style="display: inline-block; margin: 0 5px;">
                <img src="' . $baseUrl . '/images/Facebook%20logo.jpg" alt="Facebook" style="height: 30px; width: auto; vertical-align: middle; border: 1px solid blue;" onerror="this.outerHTML=\'<span style=\\\"color: #0066cc; font-size: 12px; padding: 5px; border: 1px solid #0066cc; background: yellow;\\\">[Facebook - ERROR]</span>\';">
            </a>
            <a href="https://www.instagram.com/nocturnalrecruitment/" target="_blank" style="display: inline-block; margin: 0 5px;">
                <img src="' . $baseUrl . '/images/image012.jpg" alt="Instagram" style="height: 30px; width: auto; vertical-align: middle; border: 1px solid blue;" onerror="this.outerHTML=\'<span style=\\\"color: #0066cc; font-size: 12px; padding: 5px; border: 1px solid #0066cc; background: yellow;\\\">[Instagram - ERROR]</span>\';">
            </a>
            
            <br><br>
            
            <!-- Certifications -->
            <img src="' . $baseUrl . '/images/image008.png" alt="Cyber Essentials Certified" style="height: 40px; width: auto; margin: 0 5px; vertical-align: middle; border: 1px solid green;" onerror="this.outerHTML=\'<span style=\\\"color: #666; font-size: 12px; background: yellow; padding: 5px;\\\">[Cyber Essentials - ERROR]</span>\';">
            <img src="' . $baseUrl . '/images/image009.jpg" alt="REC Corporate Member" style="height: 40px; width: auto; margin: 0 5px; vertical-align: middle; border: 1px solid green;" onerror="this.outerHTML=\'<span style=\\\"color: #666; font-size: 12px; background: yellow; padding: 5px;\\\">[REC Corporate - ERROR]</span>\';">
        </div>
        
        <!-- Company Registration -->
        <div style="text-align: center; color: #333333; font-size: 14px; font-weight: bold; margin-bottom: 20px;">
            Company Registration – 11817091
        </div>

        <!-- Disclaimer -->
        <div style="font-size: 12px; color: #333333; line-height: 1.6; border-top: 1px solid #dddddd; padding-top: 15px; margin-top: 20px; text-align: justify;">
            <strong style="color: #c41e3a;">Disclaimer*</strong> This email is intended only for the use of the addressee named above and may be confidential or legally privileged. If you are not the addressee, you must not read it and must not use any information contained in nor copy it nor inform any person other than <a href="https://www.nocturnalrecruitment.co.uk" style="color: #c41e3a; text-decoration: none; font-weight: bold;">Nocturnal Recruitment</a> or the addressee of its existence or contents. If you have received this email in error, please delete it and notify our team at <a href="mailto:info@nocturnalrecruitment.co.uk" style="color: #0066cc; text-decoration: none;">info@nocturnalrecruitment.co.uk</a>
        </div>
    </div>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Footer Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .test-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Email Footer Test</h1>
        
        <div class="test-info">
            <h3>Test Information:</h3>
            <p><strong>Base URL:</strong> <?php echo $LINK; ?></p>
            <p><strong>User:</strong> <?php echo $loggedInUserName; ?> (<?php echo $loggedInUserEmail; ?>)</p>
            <p><strong>Expected Image URLs:</strong></p>
            <ul>
                <li>Main Logo: <?php echo $LINK; ?>/images/image001.jpg</li>
                <li>LinkedIn: <?php echo $LINK; ?>/images/Linked%20in%20badge.jpg</li>
                <li>Facebook: <?php echo $LINK; ?>/images/Facebook%20logo.jpg</li>
                <li>Instagram: <?php echo $LINK; ?>/images/image012.jpg</li>
                <li>Cyber Essentials: <?php echo $LINK; ?>/images/image008.png</li>
                <li>REC Corporate: <?php echo $LINK; ?>/images/image009.jpg</li>
            </ul>
        </div>

        <h2>Email Footer Preview:</h2>
        <div style="border: 2px solid #ccc; padding: 20px; background: white;">
            <?php echo getEmailFooter($loggedInUserEmail, $loggedInUserName); ?>
        </div>
        
        <div style="margin-top: 20px;">
            <h3>Instructions:</h3>
            <p>1. Check if all images display correctly above</p>
            <p>2. If images show with colored borders, they're loading</p>
            <p>3. If you see yellow fallback text, the images failed to load</p>
            <p>4. Check the debug info section for URL verification</p>
        </div>
    </div>
</body>
</html>
