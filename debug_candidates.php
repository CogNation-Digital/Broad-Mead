<?php
// Debug script for testing candidate database connections
session_start();
require_once 'includes/config.php';

if (!isset($_COOKIE['USERID'])) {
    echo "Please login first to run this debug script.";
    exit;
}

echo "<h2>Database Connection Debug for Live Server</h2>";
echo "<p><strong>Server Name:</strong> " . $_SERVER['SERVER_NAME'] . "</p>";

// Test main config connection
echo "<h3>1. Testing Main Config Connection (\$conn)</h3>";
try {
    $testQuery = $conn->query("SELECT COUNT(*) as count FROM `_candidates`");
    $result = $testQuery->fetchObject();
    echo "<div style='color: green;'>✅ Main connection works! Found " . $result->count . " candidates</div>";
    
    // Show first few candidates
    echo "<h4>Sample candidates from main connection:</h4>";
    $sampleQuery = $conn->query("SELECT id, Name FROM `_candidates` LIMIT 5");
    echo "<ul>";
    while ($row = $sampleQuery->fetchObject()) {
        echo "<li>ID: " . $row->id . " - Name: " . $row->Name . "</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Main connection failed: " . $e->getMessage() . "</div>";
}

// Test candidates page connection method
echo "<h3>2. Testing Candidates Page Connection Method</h3>";

$serverName = $_SERVER['SERVER_NAME'];

// Environment-aware database configuration (same as in candidates index.php)
if ($serverName === 'localhost') {
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname1 = 'broadmead';
    $dbname2 = 'broadmead_v3';
} elseif ($serverName === 'broad-mead.com') {
    $host = 'localhost';
    $user = 'xuwl9qaw_mike';
    $password = '@Michael1693250341';
    $dbname1 = 'xuwl9qaw_v3';  // Use the same database for both connections on live server
    $dbname2 = 'xuwl9qaw_v3';
} else {
    echo "<div style='color: red;'>❌ Unknown server environment: " . $serverName . "</div>";
    exit;
}

try {
    $db_1 = new PDO('mysql:host=' . $host . ';dbname=' . $dbname1, $user, $password);
    $db_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div style='color: green;'>✅ Database 1 ($dbname1) connection successful</div>";
} catch (PDOException $e) {
    echo "<div style='color: red;'>❌ Database 1 ($dbname1) connection failed: " . $e->getMessage() . "</div>";
}

try {
    $db_2 = new PDO('mysql:host=' . $host . ';dbname=' . $dbname2, $user, $password);
    $db_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div style='color: green;'>✅ Database 2 ($dbname2) connection successful</div>";
    
    // Test candidates query on db_2
    $candidatesQuery = $db_2->query("SELECT COUNT(*) as count FROM `_candidates`");
    $result = $candidatesQuery->fetchObject();
    echo "<div style='color: green;'>✅ Found " . $result->count . " candidates in database 2</div>";
    
} catch (PDOException $e) {
    echo "<div style='color: red;'>❌ Database 2 ($dbname2) connection failed: " . $e->getMessage() . "</div>";
}

// Test URL parameters
echo "<h3>3. Testing URL Parameters</h3>";
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>GET parameters:</p>";
echo "<pre>" . print_r($_GET, true) . "</pre>";

// Test specific candidate lookup
if (isset($_GET['testid'])) {
    echo "<h3>4. Testing Specific Candidate Lookup</h3>";
    $testId = $_GET['testid'];
    echo "<p>Testing with candidate ID: " . htmlspecialchars($testId) . "</p>";
    
    try {
        $stmt = $conn->prepare("SELECT * FROM `_candidates` WHERE id = ?");
        $stmt->execute([$testId]);
        $candidate = $stmt->fetchObject();
        
        if ($candidate) {
            echo "<div style='color: green;'>✅ Candidate found: " . $candidate->Name . "</div>";
            echo "<pre>" . print_r($candidate, true) . "</pre>";
        } else {
            echo "<div style='color: orange;'>⚠️ No candidate found with ID: " . htmlspecialchars($testId) . "</div>";
        }
    } catch (Exception $e) {
        echo "<div style='color: red;'>❌ Error looking up candidate: " . $e->getMessage() . "</div>";
    }
}

echo "<h3>Instructions:</h3>";
echo "<p>1. If you see green checkmarks above, the database connections are working</p>";
echo "<p>2. To test a specific candidate, add ?testid=CANDIDATE_ID to this URL</p>";
echo "<p>3. Check that the database names match your live server setup</p>";
echo "<p>4. If there are errors, contact your hosting provider for the correct database credentials</p>";
?>
