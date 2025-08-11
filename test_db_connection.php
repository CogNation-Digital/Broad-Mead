<?php
// Simple database connection test for live server
error_reporting(E_ALL);
ini_set("display_errors", 1);

echo "<h2>Live Server Database Connection Test</h2>";
echo "<p><strong>Server:</strong> " . $_SERVER['SERVER_NAME'] . "</p>";

// Test the exact same connection as candidates page
$host = 'localhost';
$user = 'xuwl9qaw_mike';
$password = '@Michael1693250341';
$dbname = 'xuwl9qaw_v3';

echo "<p><strong>Testing connection to:</strong> $dbname</p>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div style='color: green; font-weight: bold;'>✅ Connection Successful!</div>";
    
    // Test if _candidates table exists
    $stmt = $pdo->query("SHOW TABLES LIKE '_candidates'");
    if ($stmt->rowCount() > 0) {
        echo "<div style='color: green;'>✅ _candidates table exists</div>";
        
        // Count candidates
        $count = $pdo->query("SELECT COUNT(*) FROM _candidates")->fetchColumn();
        echo "<div style='color: green;'>✅ Found $count candidates in the table</div>";
        
        // Show sample candidates
        echo "<h3>Sample Candidates:</h3>";
        $stmt = $pdo->query("SELECT id, Name FROM _candidates LIMIT 5");
        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>ID: " . $row['id'] . " - Name: " . $row['Name'] . "</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<div style='color: red;'>❌ _candidates table does not exist</div>";
        
        // Show available tables
        echo "<h3>Available Tables:</h3>";
        $stmt = $pdo->query("SHOW TABLES");
        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>❌ Connection Failed!</div>";
    echo "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
    
    // Provide suggestions
    echo "<h3>Possible Solutions:</h3>";
    echo "<ul>";
    echo "<li>Check if the database user 'xuwl9qaw_mike' has the correct permissions</li>";
    echo "<li>Verify the database name is 'xuwl9qaw_v3'</li>";
    echo "<li>Contact your hosting provider to confirm database details</li>";
    echo "<li>Check if the database exists in your hosting control panel</li>";
    echo "</ul>";
}
?>
