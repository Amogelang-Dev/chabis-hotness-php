<?php
// Database Configuration for Chabis Hotness
$host = "localhost";
$dbUser = "root";       // Default XAMPP/WAMP user
$dbPass = "";           // Default XAMPP/WAMP password (leave empty)
$dbName = "chabis_db"; // Updated to the new sauce entity database

// Create Connection
$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

// Check Connection Integrity
if (!$conn) {
    die("Terminal Error: Critical failure connecting to Chabis Hotness HQ. " . mysqli_connect_error());
}

// Ensure character set is UTF-8 for special characters in ingredient lists
mysqli_set_charset($conn, "utf8mb4");

// System-wide Global Constants
define('APP_NAME', 'Chabis Hotness');
define('CURRENCY', 'ZAR');
?>