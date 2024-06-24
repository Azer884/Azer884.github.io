<?php
// Start or resume session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define("DB_NAME", "login");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_HOST", "localhost");

// Connect to the database
if (!$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)) {
    die("Failed To Connect");
}