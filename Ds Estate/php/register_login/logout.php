<?php
session_start();

include '../connection.php'; // Include your database connection

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete the user's record from the is_logged_in table
        $stmt = $conn->prepare("TRUNCATE TABLE is_logged_in ");
        
        $stmt->execute();
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }

session_destroy(); // Destroy the session completely
header("Location: ../../index.php"); // Redirect to the homepage after logout
exit;
