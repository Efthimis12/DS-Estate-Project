<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../../style/styleCheckAvailability.css">
    <title>Check Availability</title>
</head>
<body>
    
    <header id="header1">
        <?php include '../navbar.php'; ?>
    </header>
    
    <section id="checkAvailability">
        <h1>Check Availability</h1>
        <?php
        // Function to generate random discount percentage between 5% and 20%
        function generateRandomDiscountPercentage() {
            return mt_rand(5, 20);
        }


        // Ensure the user is logged in
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
            echo '<p>Please log in to view this page.</p>';
            exit;
        }

        include '../connection.php';

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $id = htmlspecialchars($_POST['id']);
            $title = htmlspecialchars($_POST['title']);
            $area = htmlspecialchars($_POST['area']);
            $address = htmlspecialchars($_POST['address']);
            $rooms_number = htmlspecialchars($_POST['rooms_number']);
            $price_per_night = htmlspecialchars($_POST['price_per_night']);
            $check_in = htmlspecialchars($_POST['check_in']);
            $check_out = htmlspecialchars($_POST['check_out']);

            // Check availability logic
            $stmt = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE listing_id = :id AND ((date_from <= :check_in AND date_to > :check_in) OR (date_from < :check_out AND date_to >= :check_out))");
            $stmt->execute(['id' => $id, 'check_in' => $check_in, 'check_out' => $check_out]);
            $bookings = $stmt->fetchColumn();

            if ($bookings == 0) {
                // Calculate the total price
                $date1 = new DateTime($check_in);
                $date2 = new DateTime($check_out);
                $interval = $date1->diff($date2);
                $nights = $interval->days;
                $total_price = $nights * $price_per_night;

                // Generate a random discount percentage
                $discount_percentage = generateRandomDiscountPercentage();
                $discount_factor = $discount_percentage / 100;
                $discounted_price = $total_price - ($total_price* $discount_factor);

                // Retrieve the logged-in user's details
                $stmt = $conn->prepare("SELECT name, email FROM is_logged_in LIMIT 1");
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    echo '<p>Error retrieving user details. Please log in again.</p>';
                    exit;
                }

                $name = htmlspecialchars($user['name']);
                $email = htmlspecialchars($user['email']);

                echo '<div class="listing-details">';
                echo '<h2>' . htmlspecialchars($title) . '</h2>';
                echo '<p>Area: ' . htmlspecialchars($area) . '</p>';
                echo '<p>Address: ' . htmlspecialchars($address) . '</p>';
                echo '<p>Rooms: ' . htmlspecialchars($rooms_number) . '</p>';
                echo '<p>Price per night: $' . htmlspecialchars($price_per_night) . '</p>';
                echo '<p>Check-in Date: ' . htmlspecialchars($check_in) . '</p>';
                echo '<p>Check-out Date: ' . htmlspecialchars($check_out) . '</p>';
                echo '<p>Total Price: $' . htmlspecialchars($discounted_price) . '</p>';
                echo '<p>Discount Offered: ' . $discount_percentage . '%</p>';
                echo '</div>';
                
                echo '<form action="confirmBooking.php" method="post">';
                echo '<input type="hidden" name="id" value="' . htmlspecialchars($id) . '">';
                echo '<input type="hidden" name="title" value="' . htmlspecialchars($title) . '">';
                echo '<input type="hidden" name="area" value="' . htmlspecialchars($area) . '">';
                echo '<input type="hidden" name="address" value="' . htmlspecialchars($address) . '">';
                echo '<input type="hidden" name="rooms_number" value="' . htmlspecialchars($rooms_number) . '">';
                echo '<input type="hidden" name="price_per_night" value="' . htmlspecialchars($price_per_night) . '">';
                echo '<input type="hidden" name="check_in" value="' . htmlspecialchars($check_in) . '">';
                echo '<input type="hidden" name="check_out" value="' . htmlspecialchars($check_out) . '">';
                echo '<input type="hidden" name="total_price" value="' . htmlspecialchars($total_price) . '">';
                echo '<label for="name">Your Name:</label>';
                echo '<input type="text" id="name" name="name" value="' . htmlspecialchars($name) . '" required><br><br>';
                echo '<label for="email">Your Email:</label>';
                echo '<input type="email" id="email" name="email" value="' . htmlspecialchars($email) . '" required><br><br>';
                echo '<button type="submit">Confirm Booking</button>';
                echo '</form>';
            } else {
                echo '<p>The listing is not available for the selected dates. Please choose different dates.</p>';
                echo '<a href="javascript:history.back()">Go Back</a>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </section>
</body>
</html>