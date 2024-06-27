<?php
session_start(); // Start the session

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $_SESSION['message'] = 'You must login to the service in order to make a reservation.';
    echo $_SESSION['loggedin'];
    header("Location: ../register_login/register_login.php");
    
    exit;
}

// If logged in, proceed to bookListing.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = htmlspecialchars($_POST['id']);
    $title = htmlspecialchars($_POST['title']);
    $area = htmlspecialchars($_POST['area']);
    $address = htmlspecialchars($_POST['address']);
    $rooms_number = htmlspecialchars($_POST['rooms_number']);
    $price_per_night = htmlspecialchars($_POST['price_per_night']);
    echo $area;
    // Redirect to bookListing.php with the necessary data
    header("Location: ../book/bookListing.php?id=$id&title=$title&area=$area&address=$address&rooms_number=$rooms_number&price_per_night=$price_per_night");
    exit;
}
