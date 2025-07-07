<?php

// Check if user is logged in and has proper permissions
function checkUserPermission($required_permission) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
    
    // Database connection for permission check
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'broadmead_v3'; // Assuming permissions are in v3 database
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check user permissions
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as has_permission 
            FROM userpermissions 
            WHERE user_id = ? AND permission_name = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $required_permission]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['has_permission'] == 0) {
            // User doesn't have permission
            header('HTTP/1.0 403 Forbidden');
            echo "<div style='text-align: center; margin-top: 50px;'>
                    <h2>Access Denied</h2>
                    <p>You don't have permission to view this page.</p>
                    <p>Required permission: $required_permission</p>
                    <a href='dashboard.php'>Return to Dashboard</a>
                  </div>";
            exit();
        }
        
        return true;
        
    } catch (PDOException $e) {
        error_log("Permission check error: " . $e->getMessage());
        header('HTTP/1.0 500 Internal Server Error');
        echo "<div style='text-align: center; margin-top: 50px;'>
                <h2>System Error</h2>
                <p>Unable to verify permissions. Please try again later.</p>
              </div>";
        exit();
    }
}

// Check for VIEW_CLIENTS permission
checkUserPermission('VIEW_CLIENTS');

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname1 = 'broadmead';
$dbname2 = 'broadmead_v3';
$dsn1 = 'mysql:host=' . $host . ';dbname=' . $dbname1;
$dsn2 = 'mysql:host=' . $host . ';dbname=' . $dbname2;

try {
    $db_1 = new PDO($dsn1, $user, $password);
    $db_1->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_2 = new PDO($dsn2, $user, $password);
    $db_2->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<b>Error: </b> " . $e->getMessage();
    exit;
}