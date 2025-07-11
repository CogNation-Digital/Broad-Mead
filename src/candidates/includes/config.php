<?php
$lifetime = 60 * 60 * 24 * 7; // 1 week i
session_set_cookie_params($lifetime);
ini_set('session.gc_maxlifetime', $lifetime);
session_start();
$serverName = $_SERVER['SERVER_NAME'];
// Database connection class
class Database
{

    private $host;
    private $dbname;
    private $username;
    private $password;
    private $conn;

    public function __construct()
    {
        $this->setDatabaseConfig();
    }

    private function setDatabaseConfig()
    {
        global $serverName;
        if ($serverName === 'localhost') {
            // Local database configuration
            $this->host = 'localhost';
            $this->dbname = 'broadmead_v3';
            $this->username = 'root';
            $this->password = '';
        } elseif ($serverName === 'broad-mead.com') {
            // Online server database configuration
            $this->host = 'localhost';
            $this->dbname = 'xuwl9qaw_v3';
            $this->username = 'xuwl9qaw_mike';
            $this->password = '@Michael1693250341';
        } else {
            throw new Exception("Unknown server environment.");
        }
    }

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            header("Location: /error"); // Adjust the error page URL as needed
            exit();
        }
        return $this->conn;
    }
}

// Example usage
$db = new Database();
$conn = $db->getConnection();


$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    header("Location: error");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = 0;


// Generate a UUID with a secure method
function generateUUID($length = 50)
{
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($charset);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $charset[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function RandomID()
{
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$KeyID = generateUUID();
$RandomID = RandomID();
$SearchID = $RandomID;

function FormatNumber($num)
{
    if ($num >= 1000000000) {
        return round($num / 1000000000, 1) . 'B';
    } elseif ($num >= 1000000) {
        return round($num / 1000000, 1) . 'M';
    } elseif ($num >= 1000) {
        return round($num / 1000, 1) . 'K';
    } else {
        return $num;
    }
}

function NoData()
{
    return "<div class='text-danger'>No Data Found</div>";
}

$HOST_SERVER =  getenv('HTTP_HOST');
$PROTOCAL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$DIRECTORY = ($serverName === 'localhost') ? "/broadmead" : "";
$LINK = $PROTOCAL . $_SERVER['HTTP_HOST'] . $DIRECTORY;
$TITLE = "Broad-Mead - Simplified Recruitment Software | Applicant Tracking System + CRM Software";
$ICON = $LINK . "/assets/images/icon.png";
$LOGO = $LINK . "/assets/images/logo.png";
$ProfilePlaceholder = $LINK . '/assets/images/default.webp';
$_blank = $LINK . '/assets/_blank.png';
$FontFamily = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";

if (isset($_POST['theme'])) {
    $theme = $_POST['theme'];
    $expiryTime = time() + strtotime('+60 days');
    setcookie('theme', $theme,  $expiryTime, '/');
}

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';

$File_Dictory = $_SERVER['DOCUMENT_ROOT'] . $DIRECTORY . '/assets/files/';
function TruncateText($text, $maxLength, $ellipsis = '...')
{
    if (strlen($text) > $maxLength) {
        $text = wordwrap($text, $maxLength);
        $text = substr($text, 0, strpos($text, "\n"));
        $text .= $ellipsis;
    }
    return $text;
}
function FormatDate($date)
{
    // Remove the 'T' if present in the date string
    $date = str_replace('T', ' ', $date);

    $dateTime = new DateTime($date);

    // Check if the input date string includes time (H:i)
    if (strpos($date, ' ') !== false && preg_match('/\d{2}:\d{2}/', $date)) {
        return $dateTime->format('d F Y H:i');
    } else {
        return $dateTime->format('d F Y');
    }
}





date_default_timezone_set('Europe/London');

$date = date('Y-m-d H:i:s');
$DATE = date("Y-m-d");
$today = $DATE;
function IsCheckPermission($USERID, $PERMISSION)
{
    $query = "SELECT * FROM `userpermissions` WHERE UserID = :UserID AND Permission = :Permission";
    $stmt = $GLOBALS['conn']->prepare($query);
    $stmt->bindParam(':UserID', $USERID);
    $stmt->bindParam(':Permission', $PERMISSION);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return false;
    } else {
        return true;
    }
}

function Notify($USERID, $ClientID, $Notification)
{
    global $date;
    $query = "INSERT INTO `notifications`(`ClientKeyID`, `hasUseID`, `Notification` , `Date`) VALUES  (:ClientKeyID, :hasUseID, :Notification, :Date)";
    $stmt = $GLOBALS['conn']->prepare($query);
    $stmt->bindParam(':ClientKeyID', $ClientID);
    $stmt->bindParam(':hasUseID', $USERID);
    $stmt->bindParam(':Notification', $Notification);
    $stmt->bindParam(':Date', $date);
    $stmt->execute();
}

function TimeAgo($value)
{
    $Date = new DateTime($value);
    $now = new DateTime();
    $interval = $now->diff($Date);

    $seconds = $interval->days * 24 * 60 * 60 +
        $interval->h * 60 * 60 +
        $interval->i * 60 +
        $interval->s;

    if ($seconds < 60) {
        return $seconds . 's ago';
    } elseif ($seconds < 3600) {
        return floor($seconds / 60) . 'm ago';
    } elseif ($seconds < 86400) {
        return floor($seconds / 3600) . 'h ago';
    } else {
        return $Date->format('d/m/y');
    }
}




function getPageCategory($page)
{
    $categories = [
        "home" => "Dashboard",
        "create_key_job_area" => "Create Key Job Area",
        "view_key_job_area" => "View Key Job Area",
        "edit_key_job_area" => "Edit Key Job Area",
        "key_job_area" => "Key Job Area",
        "create_weekly_kpis" => "Create Weekly KPI",
        "edit_weekly_kpis" => "Edit Weekly KPI",
        "weekly_kpis" => "Weekly KPIs",
        "calendar" => "Calendar",
        "clients" => "Clients",
        "create_client/?ClientID" => "Create Branch",
        "create_client" => "Create Client",
        "view_client/?isBranch=true&isTab=Details" => "Branch details",
        "view_client/?isBranch=true&isTab=Documents" => "Branch's documents",
        "view_client/?isBranch=true&isTab=KeyPeople" => "Branch's Key People",
        "view_client/?isBranch=true&isTab=Interview" => "Branch's Interview",
        "view_client/?isBranch=true&isTab=Vacancy" => "Branch's Vacancy",
        "view_client/?isBranch=true&isTab=Shifts" => "Branch's Shifts",
        "view_client/?isBranch=true&isTab=Timesheets" => "Branch's Timesheets",
        "view_client/?isBranch=true&isTab=Invoices" => "Branch's Invoices",
        "view_client/?isBranch=true&" => "Branch details",
        "view_client/?isTab=Details" => "Client Details",
        "view_client/?isTab=Documents" => "Client's Documents",
        "view_client/?isTab=KeyPeople" => "Client's Key People",
        "view_client/?isTab=Branches" => "Client's Branches",
        "view_client/?isTab=Interview" => "Client's Interviews",
        "view_client/?isTab=Vacancy" => "Client's Vacancy",
        "view_client/?isTab=Shifts" => "Client's Shifts",
        "view_client/?isTab=Timesheets" => "Client's Timesheets",
        "view_client/?isTab=Invoices" => "Client's Invoices",
        "invoices" => "Invoices",
        "view_client" => "Client details",
        "edit_client/?isBranch=true" => "Edit Branch",
        "edit_client" => "Edit Client",
        "view_branches" => "Client's Branches",
        "create_interview/?isNew=false" => "Edit Interview",
        "create_interview" => "Create Interview",
        "interviews" => "Interviews",
        "shifts" => "Shifts",
        "create_log/?isNew=false&" => "Edit Communication Log",
        "create_log" => "Create Communication Log",
        "vacancies" => "Vacancies",
        "view_vacancy/" => "View Vacancy",
        "create_vacancy/?isNew=false" => "Edit Vacancy",
        "create_vacancy" => "Create Vacancy",
        "generate_timesheet/?ID" => "Timesheet",
        "generate_timesheet/" => "Timesheet",
        "timesheets" => "Timesheets",
        "view_candidate" => "View Candidate",
        "create_candidate" => "Create Candidate",
        "edit_candidate" => "Edit Candidate",
        "candidates" => "Candidates",
        "report" => "Reports",
        "expired_documents" => "Expired Documents",
        "profile" => "Profile",
        "notifications" => "Notifications",
        "emails" => "Emails",
        "email_list" => "Email List",
        "email_template" => "Email Template",
        "compose_email" => "Compose Email",
        "sending_email" => "Sending Email...",
        "activity_log" => "Activity Log",
        "users" => "Users",
        "update_permissions" => "Update Permissions",
        "shift_types" => "Shift Types",


    ];

    foreach ($categories as $pattern => $category) {
        if (strpos($page, $pattern) !== false) {
            return $category;
        }
    }
    return "";
}

function SaveLog($USERID, $page)
{
    global $conn, $ClientKeyID;
    $date = date('Y-m-d H:i:s');
    $checkQuery = $conn->prepare("SELECT * FROM `logs` WHERE `UserID` = :UserID ORDER BY `id` DESC LIMIT 1");
    $checkQuery->bindParam(":UserID", $USERID);
    $checkQuery->execute();
    $data = $checkQuery->fetchObject();

    $Name = getPageCategory($page);
    if (!empty($Name)) {
        if ($data) {
            if ($data->PageName === $Name) {
                // Update the existing log entry
                $updateQuery = $conn->prepare("UPDATE `logs` SET `PageUrl` = :Log, `Date` = :Date WHERE `id` = :id");
                $updateQuery->bindParam(":Log", $page);
                $updateQuery->bindParam(":Date", $date);
                $updateQuery->bindParam(":id", $data->id);
                $updateQuery->execute();
            } else {
                // Insert a new log entry
                $insertQuery = $conn->prepare("INSERT INTO `logs`(`ClientKeyID`,`UserID`, `PageName`, `PageUrl`, `Date`) VALUES (:ClientKeyID, :UserID, :Name, :Log, :Date)");
                $insertQuery->bindParam(":ClientKeyID", $ClientKeyID);

                $insertQuery->bindParam(":UserID", $USERID);
                $insertQuery->bindParam(":Name", $Name);
                $insertQuery->bindParam(":Log", $page);
                $insertQuery->bindParam(":Date", $date);

                if (!empty($page)) {
                    $insertQuery->execute();
                }
            }
        } else {
            // Insert a new log entry if no previous entry exists
            $insertQuery = $conn->prepare("INSERT INTO `logs`(`ClientKeyID`,`UserID`, `PageName`, `PageUrl`, `Date`) VALUES (:ClientKeyID, :UserID, :Name, :Log, :Date)");
            $insertQuery->bindParam(":ClientKeyID", $ClientKeyID);
            $insertQuery->bindParam(":UserID", $USERID);
            $insertQuery->bindParam(":Name", $Name);
            $insertQuery->bindParam(":Log", $page);
            $insertQuery->bindParam(":Date", $date);

            if (!empty($page)) {
                $insertQuery->execute();
            }
        }
    }
}






$current_url = $_SERVER['REQUEST_URI'];
$current_page = basename(parse_url($current_url, PHP_URL_PATH));





if (isset($_COOKIE['USERID'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
    $stmt->execute([$_COOKIE['USERID']]);
    $USER_DATA = $stmt->fetch(PDO::FETCH_OBJ);
    $USERID = $USER_DATA->UserID;

    $ProfilePicture = empty($USER_DATA->ProfileImage) ? $ProfilePlaceholder : $USER_DATA->ProfileImage;
    $NAME = empty($USER_DATA->Name) ? "Name not found" : $USER_DATA->Name;
    $EMAIL = empty($USER_DATA->Email) ? "Email not found" : $USER_DATA->Email;
    $ClientKeyID = empty($USER_DATA->ClientKeyID) ? 0 : $USER_DATA->ClientKeyID;
    $Position = empty($USER_DATA->Position) ? "Position not found" : $USER_DATA->Position;
    $PageURL = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    SaveLog($USERID, $PageURL);



    $_CLIENT_DATA = $conn->query("SELECT * FROM organizations WHERE ClientKeyID = '$ClientKeyID'")->fetchObject();

    $BANKDATA = $conn->query("SELECT * FROM `bankaccounts` WHERE ClientKeyID = '$ClientKeyID'")->fetchObject();



    $GET_NOTIFICATION_COUNT = "SELECT * FROM `notifications` WHERE ClientKeyID = :ClientKeyID AND DATE(Date) = :Date";

    if (!IsCheckPermission($USERID, "IS_RECEIVE_ALL_NOTIFICATIONS")) {
        $GET_NOTIFICATION_COUNT .= " AND hasUseID = :UserID";
    }
    $GET_NOTIFICATION_COUNT_STMT = $conn->prepare($GET_NOTIFICATION_COUNT);
    $GET_NOTIFICATION_COUNT_STMT->bindParam(':ClientKeyID', $ClientKeyID);
    $GET_NOTIFICATION_COUNT_STMT->bindParam(':Date', $DATE);

    if (!IsCheckPermission($USERID, "IS_RECEIVE_ALL_NOTIFICATIONS")) {
        $GET_NOTIFICATION_COUNT_STMT->bindParam(':UserID', $USERID);
    }

    $GET_NOTIFICATION_COUNT_STMT->execute();
    $NOTIFICATION_COUNT = $GET_NOTIFICATION_COUNT_STMT->rowCount();


    // $SessionEndTime = $conn->query("SELECT isEndingAt FROM `sessions` WHERE UserID = '$USERID' ORDER BY id DESC LIMIT 1")->fetchColumn();
    // // if sessionendtime is not available or past the current time
    // if (!$SessionEndTime || strtotime($SessionEndTime) < strtotime($date)) {
    //     $DeleteSessions = $conn->query("DELETE FROM sessions WHERE UserID = '$USERID' ");
    //     setcookie('USERID', '', time() - 3600, '/');
    //     header("Location: $LINK/login");
    // }


    function DeniedAccess()
    {
        echo '<div class="alert alert-danger alert-dismissible">Permission was not granted.</div>';
    }

    if (isset($_POST['dateRange'])) {
        $dateRange = $_POST['dateRange'];
        list($fromDate, $toDate) = explode(' to ', $dateRange);

        $stmt = $conn->prepare("SELECT * FROM `_date_ranges` WHERE UserID = ?");
        $stmt->bindParam(1, $USERID);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE `_date_ranges` SET FromDate = ?, ToDate = ? WHERE UserID = ?");
            $stmt->bindParam(1, $fromDate, PDO::PARAM_STR);
            $stmt->bindParam(2, $toDate, PDO::PARAM_STR);
            $stmt->bindParam(3, $USERID);
            $stmt->execute();
            $response =  "Date range saved successfully.";
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO `_date_ranges` (UserID, FromDate, ToDate) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $USERID);
            $stmt->bindParam(2, $fromDate, PDO::PARAM_STR);
            $stmt->bindParam(3, $toDate, PDO::PARAM_STR);
            $stmt->execute();
            $response =  "Date range saved successfully.";
        }
    }

    $GET_DATES_RANGES = $conn->prepare("SELECT * FROM `_date_ranges` WHERE UserID = :userID");
    $GET_DATES_RANGES->bindParam(':userID', $USERID);
    $GET_DATES_RANGES->execute();

    $monday = date('Y-m-d', strtotime('Monday this week'));
    $sunday = date('Y-m-d', strtotime('Sunday this week'));

    $DATES_RANGES = $GET_DATES_RANGES->fetchAll(PDO::FETCH_OBJ);
    $FromDate = (!empty($DATES_RANGES[0]->FromDate)) ? $DATES_RANGES[0]->FromDate : $monday;
    $ToDate = (!empty($DATES_RANGES[0]->ToDate)) ? $DATES_RANGES[0]->ToDate : $sunday;

    $DIRECTORYFILE = "$DIRECTORY/assets/files/";
    $DIRECTORYIMAGE = "$DIRECTORY/assets/images/";
    $File_Directory = $_SERVER['DOCUMENT_ROOT'] . $DIRECTORYFILE;
    $Image_Directory = $_SERVER['DOCUMENT_ROOT'] . $DIRECTORYIMAGE;

    // echo $File_Directory;


    function LastModified($ID, $USERID, $Modification)
    {
        global $PageURL, $ClientKeyID;
        // Prepare the SQL query with placeholders
        $query = "INSERT INTO `datamodifications` (`ClientKeyID`,`KeyID`, `Modification`, `UserID`,`PageUrl`, `Date`) VALUES (:ClientKeyID,:KeyID, :Modification, :UserID, :PageUrl, :Date)";

        // Prepare the statement
        $stmt = $GLOBALS['conn']->prepare($query);
        $date = date('Y-m-d H:i:s');
        // Bind the parameters
        $stmt->bindParam(':KeyID', $ID);
        $stmt->bindParam(':ClientKeyID', $ClientKeyID);
        $stmt->bindParam(':Modification', $Modification);
        $stmt->bindParam(':UserID', $USERID);
        $stmt->bindParam(':PageUrl', $PageURL);
        $stmt->bindParam(':Date', $date);

        // Execute the statement
        $stmt->execute();
    }
}



require $_SERVER['DOCUMENT_ROOT'] . "$DIRECTORY/vendor/autoload.php";
function SendEmail($email, $subject, $message, $hasTemplate, $templateUrl)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $host = "broad-mead.com";
        $user = "nocturnalrecruitment@broad-mead.com";
        $pass = "@Michael1693250341";

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('nocturnalrecruitment@broad-mead.com', 'Nocturnal Recruitment');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';  // Set UTF-8 encoding
        $mail->addCustomHeader('Content-Type', 'text/html; charset=UTF-8'); // Optional but recommended
        $mail->Subject = $subject;

        if ($hasTemplate) {
            $templateContent = file_get_contents($templateUrl);
            if ($templateContent !== false) {
                // Replace {message} placeholder with the actual message content
                $mail->Body = str_replace('{message}', $message, $templateContent);
            } else {
                throw new Exception("Failed to fetch email template.");
            }
        } else {
            $mail->Body = $message;
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log the error message
        error_log('Mail Error: ' . $e->getMessage());
        return false;
    }
}

function SaveEmailLogs($email, $description, $status)
{
    global $conn, $USERID, $ClientKeyID, $PageURL;
    $date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO `_email_logs` (ClientKeyID,Email, Description, Status, PageUrl, CreatedBy, Date) VALUES (:ClientKeyID, :Email, :Description, :Status, :PageUrl, :CreatedBy, :Date)");
    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
    $stmt->bindParam(':Email', $email);
    $stmt->bindParam(':Description', $description);
    $stmt->bindParam(':Status', $status);
    $stmt->bindParam(':PageUrl', $PageURL);
    $stmt->bindParam(':CreatedBy', $USERID);
    $stmt->bindParam(':Date', $date);
    $stmt->execute();
}
function GetHours($start, $end)
{
    $startTime = new DateTime($start);
    $endTime = new DateTime($end);
    $interval = $startTime->diff($endTime);
    $hours = round($interval->h + ($interval->i / 60), 2);
    return  $hours;
}

$documenttype = array(
    "Application Documents",
    "CV Documents",
    "DBS Documents",
    "Mandatory Training",
    "Proof of residence",
    "RTW Documents"
);

$clientype = array(
    "Private Company",
    "VMS",
    "1-1 Client",
    "VMS - Matrix",
    "VMS - Comensura"
);

$candidate_status = array("Active", "Archived", "Inactive", "Pending Compliance");
$clients_status = array("Active", "Archived", "Inactive", "Targeted");

function CreatedBy($ID)
{
    global $conn;
    $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '$ID' ")->fetchColumn();
    return $CreatedBy;
}
$Currency = "£";
if (isset($_POST['CreateShift'])) {
    $VacancyID = $_POST['VacancyID'];
    if (isset($_POST['CandidateID'])) {
        $CandidateID = $_POST['CandidateID'];
        header("location: $LINK/view_vacancy/?VacancyID=$VacancyID&hasCandidateID=$CandidateID&isTab=Shifts");
    } else {
        header("location: $LINK/view_vacancy/?VacancyID=$VacancyID&isTab=Shifts");
    }
}


if (isset($_GET['logout']) || isset($_POST['logout'])) {
    $Notification = "$NAME has logged out.";
    Notify($USERID, $ClientKeyID, $Notification);

    $delete = "DELETE FROM auth_codes WHERE UserID = '$USERID'";
    $conn->exec($delete);
    $DeleteSessions = $conn->query("DELETE FROM sessions WHERE UserID = '$USERID' ");
    setcookie('USERID', '', time() - 3600, '/');

    header("location: $LINK/home");
}

if (isset($_POST['CreateTimesheet'])) {
    // Get the period from the POST request
    $Period = $_POST['Period']; // example 15 July 2024 to 21 July 2024

    // Get the candidate from the POST request
    $CandidateID = $_POST['candidate'];
    $TimesheetNo = $_POST['TimesheetID'];
    $VacancyID = $_POST['VacancyID'];

    $TimesheetID = $RandomID;

    //Check if candidate is in __vacancy_candidates

    $check = "SELECT * FROM `__vacancy_candidates` WHERE VacancyID = '$VacancyID' AND CandidateID = '$CandidateID'";
    $stmt = $conn->prepare($check);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount > 0) {
        // Prepare and execute the SELECT query
        $query = "SELECT * FROM `vacancies` WHERE VacancyID = :VacancyID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':VacancyID', $VacancyID);
        $stmt->execute();
        $row = $stmt->fetchObject();
        $hasClientID = $row->hasClientID;
        $hasBranchID = $row->hasBranchID;

        // Prepare the INSERT query
        $query = "INSERT INTO `_timesheet`(`ClientKeyID`, `TimesheetID`, `TimesheetNo`, `hasClientID`, `hasBranchID`, `CandidateID`, `VacancyID`, `CreatedBy`, `Date`)  VALUES (:ClientKeyID, :TimesheetID, :TimesheetNo, :hasClientID, :hasBranchID, :CandidateID, :VacancyID, :CreatedBy, :Date)";

        $stmt = $conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':ClientKeyID', $ClientKeyID);
        $stmt->bindParam(':TimesheetID', $TimesheetID);
        $stmt->bindParam(':TimesheetNo', $TimesheetNo);
        $stmt->bindParam(':hasClientID', $hasClientID);
        $stmt->bindParam(':hasBranchID', $hasBranchID);
        $stmt->bindParam(':CandidateID', $CandidateID);
        $stmt->bindParam(':VacancyID', $VacancyID);
        $stmt->bindParam(':CreatedBy', $USERID);
        $stmt->bindParam(':Date', $date);

        // Execute the INSERT query
        $stmt->execute();


        // Separate the date range into from and to dates
        $dates = explode(" to ", $Period);
        $date_from = DateTime::createFromFormat('d F Y', trim($dates[0]));
        $date_to = DateTime::createFromFormat('d F Y', trim($dates[1]));

        $date_from_formatted = $date_from->format('Y-m-d');
        $date_to_formatted = $date_to->format('Y-m-d');



        //Check for shifts 
        $query = "SELECT * FROM `_shifts` WHERE CandidateID = '$CandidateID' AND VacancyID = '$VacancyID' AND ShiftDate BETWEEN '$date_from_formatted' AND '$date_to_formatted'";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetchObject()) {
            // Fetch shift details
            $ShiftDate = $row->ShiftDate;
            $shiftID = $row->ShiftID;
            $shiftType = $row->Type;
            $startTime = $row->StartTime;
            $endTime = $row->EndTime;
            $hours = $row->Hours;
            $payRate = $row->PayRate;
            $supplyRate = $row->SupplyRate;
            $margin = $row->Margin;

            // Prepare the insert query
            $insertQuery = $conn->prepare("INSERT INTO `__time_sheets`(`TimesheetID`, `TimesheetDate`, `ShiftType`, `StartTime`, `EndTime`, `Hours`, `Margin`, `PayRate`, `SupplyRate`, `CreatedBy`, `Date`) VALUES (:TimesheetID, :TimesheetDate, :ShiftType, :StartTime, :EndTime, :Hours, :Margin, :PayRate, :SupplyRate, :CreatedBy, :Date)");

            // Bind the parameters
            $insertQuery->bindParam(':TimesheetID', $TimesheetID);
            $insertQuery->bindParam(':TimesheetDate', $ShiftDate);
            $insertQuery->bindParam(':ShiftType', $shiftType);
            $insertQuery->bindParam(':StartTime', $startTime);
            $insertQuery->bindParam(':EndTime', $endTime);
            $insertQuery->bindParam(':Hours', $hours);
            $insertQuery->bindParam(':Margin', $margin);
            $insertQuery->bindParam(':PayRate', $payRate);
            $insertQuery->bindParam(':SupplyRate', $supplyRate);
            $insertQuery->bindParam(':CreatedBy', $USERID);
            $insertQuery->bindParam(':Date', $date);


            // Execute the insert query
            $insertQuery->execute();

            $candidateNameStmt = $conn->prepare("SELECT Name FROM `_candidates` WHERE  CandidateID = :CandidateID");
            $candidateNameStmt->bindParam(':CandidateID', $CandidateID);
            $candidateNameStmt->execute();
            $candidateName = $candidateNameStmt->fetchColumn();

            $Modification = "Created timesheet";
            LastModified($TimesheetID, $USERID, $Modification);
            $Notification = "$NAME successfully created  a new timesheet for $candidateName Timesheet No. $TimesheetNo";

            Notify($USERID, $ClientKeyID, $Notification);
        }

        header("location: $LINK/generate_timesheet/?ID=$TimesheetID");
    } else {
        $response = "Error 901: The selected candidate was not found in vacancy. Please add the candidate to the vacancy.";
        $error = 1;
    }
}
$InterView_Status = ["Pending", "Active", "Accepted", "Rejected", "Interviewed"];


function EmailListName($ID)
{
    global $conn;
    $stmt = $conn->prepare("SELECT Title FROM `_email_list` WHERE ListID = :ListID");
    $stmt->bindParam(':ListID', $ID);
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        return "Email List Not Found";
    } else {
        return $stmt->fetchColumn();
    }
}


function EmailSent($ID, $Type)
{
    global $conn;
    return 0;
}
