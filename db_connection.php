<?php
$servername = "localhost";
$username = "uapv2300382";
$password = "wdMl44";

try {
    // Establish the database connection
    $conn = new PDO("pgsql:host=$servername;dbname=etd", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally, you can output a message indicating successful connection
    // echo "Connected successfully";
} catch(PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
}

  
?>
