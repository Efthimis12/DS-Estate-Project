<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="../../style/styleConfirmBooking.css">
</head>
<body>
    <header id="header1">
        <?php include '../navbar.php'; ?>
    </header>
    <section id="confirmation">
        <h1>Booking Confirmation</h1>
        <p>Thank you for your booking!</p>
    </section>
</body>
</html>
<?php

include '../connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the user details from the is_logged_in table
        $stmt = $conn->prepare("SELECT * FROM is_logged_in LIMIT 1");
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo '<p>Error retrieving user details. Please log in again.</p>';
            exit;
        }

        // Gather listing details from form submission
        
        // Retrieve listing details based on the provided listing_id
        $listing_id = htmlspecialchars($_POST['id']);
        $stmt = $conn->prepare("SELECT * FROM listings WHERE id = :listing_id");
        $stmt->bindParam(':listing_id', $listing_id);
        $stmt->execute();
        $listing = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$listing) {
            echo '<p>Error retrieving listing details.</p>';
            exit;
        }
        $check_in = htmlspecialchars($_POST['check_in']);
        $check_out = htmlspecialchars($_POST['check_out']);
        $total_price = htmlspecialchars($_POST['total_price']);
    
        // Generate a unique reservation ID
        $reservation_id = uniqid();

        // Prepare the INSERT statement
        $stmt = $conn->prepare("INSERT INTO reservations (reservation_id, listing_id, picture, title, address, rooms_number, price_per_night, user_id, name, surname, client_username, code, email, date_from, date_to, total_price) VALUES (:reservation_id, :listing_id, :picture, :title, :address, :rooms_number, :price_per_night, :user_id, :name, :surname, :client_username, :code, :email, :date_from, :date_to, :total_price)");

        // Bind the parameters
        $stmt->bindParam(':reservation_id', $reservation_id);
        $stmt->bindParam(':listing_id', $listing_id);
        $stmt->bindParam(':picture', $listing['picture'], PDO::PARAM_LOB);
        $stmt->bindParam(':title', $listing['title']);
        $stmt->bindParam(':address', $listing['address']);
        $stmt->bindParam(':rooms_number', $listing['rooms_number']);
        $stmt->bindParam(':price_per_night', $listing['price_per_night']);
        $stmt->bindParam(':user_id', $user['id']);
        $stmt->bindParam(':name', $user['name']);
        $stmt->bindParam(':surname', $user['surname']);
        $stmt->bindParam(':client_username', $user['client_username']);
        $stmt->bindParam(':code', $user['code']);
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':date_from', $check_in);
        $stmt->bindParam(':date_to', $check_out);
        $stmt->bindParam(':total_price', $total_price);

        // Execute the statement
        $stmt->execute();

        echo '<p class="confirmation-message">Great! Your reservation ID is <strong>' . htmlspecialchars($reservation_id) . '</strong>.</p>';
        echo '<a href="../../index.php" class="home-link">Return to Home</a>';

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
