<!DOCTYPE html>
<html>
<head>
    <title>Book Listing</title>
    <link rel="stylesheet" href="../../style/styleBookListing.css">
</head>
<body>
    <header id="header1">
        <?php include '../navbar.php'; ?>
    </header>
    
    <section id="bookListing">
        <h1>Book Listing</h1>
        <?php

        // Ensure the user is logged in
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
            echo '<p>Please log in to view this page.</p>';
            exit;
        }

        include '../connection.php';

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Retrieve the logged-in user's details
            $stmt = $conn->prepare("SELECT name, email FROM is_logged_in LIMIT 1");
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                echo '<p>Error retrieving user details. Please log in again.</p>';
                exit;
            }

            $name = $user['name'];
            $email = $user['email'];

            if (isset($_GET['id']) && isset($_GET['title']) && $_GET['area'] && isset($_GET['address']) && isset($_GET['rooms_number']) && isset($_GET['price_per_night'])) {
                $id = htmlspecialchars($_GET['id']);
                $title = htmlspecialchars($_GET['title']);
                $area = htmlspecialchars($_GET['area']);
                $address = htmlspecialchars($_GET['address']);
                $rooms_number = htmlspecialchars($_GET['rooms_number']);
                $price_per_night = htmlspecialchars($_GET['price_per_night']);


                echo '<div class="listing-details">';
                echo '<h2>' . htmlspecialchars($title) . '</h2>';
                echo '<p>Area: ' . htmlspecialchars($area) . '</p>';
                echo '<p>Address: ' . htmlspecialchars($address) . '</p>';
                echo '<p>Rooms: ' . htmlspecialchars($rooms_number) . '</p>';
                echo '<p>Price per night: $' . htmlspecialchars($price_per_night) . '</p>';
                echo '</div>';
                
                echo '<form action="checkAvailability.php" method="post">';
                echo '<input type="hidden" name="id" value="' . htmlspecialchars($id) . '">';
                echo '<input type="hidden" name="title" value="' . htmlspecialchars($title) . '">';
                echo '<input type="hidden" name="area" value="' . htmlspecialchars($area) . '">';
                echo '<input type="hidden" name="address" value="' . htmlspecialchars($address) . '">';
                echo '<input type="hidden" name="rooms_number" value="' . htmlspecialchars($rooms_number) . '">';
                echo '<input type="hidden" name="price_per_night" value="' . htmlspecialchars($price_per_night) . '">';
                echo '<label for="check_in">Check-in Date:</label>';
                echo '<input type="date" id="check_in" name="check_in" required><br><br>';
                echo '<label for="check_out">Check-out Date:</label>';
                echo '<input type="date" id="check_out" name="check_out" required><br><br>';
                echo '<button type="submit">Check Availability</button>';
                echo '</form>';
            } else {
                echo '<p>Invalid request. Missing parameters.</p>';
                // Optionally, print which parameters are missing for further debugging
                echo '<pre>';
                print_r($_GET);
                echo '</pre>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </section>
</body>
</html>