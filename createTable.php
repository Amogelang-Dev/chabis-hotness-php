<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ClothingStore_db"; // Required name

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Database if it doesn't exist
$sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sqlCreateDB);
$conn->select_db($dbname);

// SQL to create tblUser
$tableUser = "CREATE TABLE IF NOT EXISTS tblUser (
    userID INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending'
)";

// SQL to create tblAdmin
$tableAdmin = "CREATE TABLE IF NOT EXISTS tblAdmin (
    adminID INT(11) AUTO_INCREMENT PRIMARY KEY,
    adminName VARCHAR(50) NOT NULL,
    adminPassword VARCHAR(255) NOT NULL
)";

// Execute table creation
$conn->query($tableUser);
$conn->query($tableAdmin);

echo "Database and Tables initialized successfully.";
$conn->close();
?>