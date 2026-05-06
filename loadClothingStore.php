<?php
include 'DBConn.php'; // Include your connection logic

// 1. Clear existing users to ensure a fresh start[cite: 7]
$clearSql = "DELETE FROM tblUser";
if ($conn->query($clearSql) === TRUE) {
    echo "Existing data cleared successfully.<br>";
}

// 2. Open the text file for reading
$filename = "userData.txt";
$file = fopen($filename, "r");

if ($file) {
    echo "Reading $filename...<br>";
    
    // 3. Process each line
    while (($line = fgets($file)) !== false) {
        // Explode the line by comma into an array
        $userData = explode(",", trim($line));
        
        // Map array values to variables
        $username = $userData[0];
        $email = $userData[1];
        $password = $userData[2];
        $status = $userData[3];

        // 4. Insert into tblUser[cite: 7, 8]
        $sql = "INSERT INTO tblUser (username, email, password, status) 
                VALUES ('$username', '$email', '$password', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "Successfully loaded user: $username<br>";
        } else {
            echo "Error loading $username: " . $conn->error . "<br>";
        }
    }
    
    fclose($file);
    echo "<strong>Data script execution complete.</strong>";
} else {
    echo "Error: Could not open $filename.";
}

$conn->close();
?>