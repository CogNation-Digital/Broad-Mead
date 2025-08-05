<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

echo "<h2>POST Debug Information</h2>";
echo "<h3>Raw POST Data:</h3>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

echo "<h3>Raw GET Data:</h3>";
echo "<pre>" . print_r($_GET, true) . "</pre>";

echo "<h3>Server Request Method:</h3>";
echo "<pre>" . $_SERVER['REQUEST_METHOD'] . "</pre>";

echo "<h3>Form Detection:</h3>";
echo "<pre>";
echo "send_mailshot isset: " . (isset($_POST['send_mailshot']) ? 'YES' : 'NO') . "\n";
echo "POST method: " . ($_SERVER['REQUEST_METHOD'] === 'POST' ? 'YES' : 'NO') . "\n";
echo "Combined check: " . (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_mailshot'])) ? 'YES' : 'NO') . "\n";
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_mailshot'])) {
    echo "<h3 style='color: green;'>✓ Mailshot Processing Would Trigger</h3>";
} else {
    echo "<h3 style='color: red;'>✗ Mailshot Processing Would NOT Trigger</h3>";
}
?>
