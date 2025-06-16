<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "includes/config.php";

// Make sure $LINK is defined
if (!isset($LINK)) {
    $LINK = "http://localhost/mead"; // fallback default
}

if (!isset($_COOKIE['USERID'])) {
    header("Location: $LINK/login");
    exit();
} else {
    header("Location: $LINK/home");
    exit();
}
