<?php
include 'DBConn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// APPROVE USER[cite: 7, 8]
if (isset($_GET['approve_id'])) {
    $id = $_GET['approve_id'];
    $conn->query("UPDATE tblUser SET status = 'Approved' WHERE userID = $id");
    header("Location: admin.php");
}

// DELETE USER[cite: 7, 8]
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    $conn->query("DELETE FROM tblUser WHERE userID = $id");
    header("Location: admin.php");
}

// DELETE CLOTHING ITEM
if (isset($_GET['delete_cloth'])) {
    $id = $_GET['delete_cloth'];
    $conn->query("DELETE FROM tblClothes WHERE clothID = $id");
    header("Location: admin.php");
}
?>