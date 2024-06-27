<!DOCTYPE html>
<html>
<head>
    <title>Listings Feed</title>
    <link rel="stylesheet" href="style/styleFeed.css">
</head>
<body>
    <?php 
    include 'connection.php';

    // Define how many results you want per page
    $results_per_page = 5;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Find out the number of results stored in database
        $stmt = $conn->prepare("SELECT COUNT(id) AS total FROM listings");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_results = $row['total'];
        $total_pages = ceil($total_results / $results_per_page);

        // Determine which page number visitor is currently on
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page > $total_pages) $page = $total_pages;
        if ($page < 1) $page = 1;

        // Determine the SQL LIMIT starting number for the results on the displaying page
        $start_from = ($page - 1) * $results_per_page;

        // Retrieve selected results from database and display them on page
        $stmt = $conn->prepare("SELECT id, picture, title, area, address, rooms_number, price_per_night FROM listings LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $start_from, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $listings = $stmt->fetchAll();

        foreach ($listings as $listing) {
            echo '<div class="listing">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($listing['picture']) . '" alt="Listing Image">';
            echo '<ul>';
            echo '<li><h4>' . htmlspecialchars($listing['title']) . '</h4></li>';
            echo '<li><p>Area: ' . htmlspecialchars($listing['area']) . '</p></li>';
            echo '<li><p>Address: ' . htmlspecialchars($listing['address']) . '</p></li>';
            echo '<li><p>Rooms: ' . htmlspecialchars($listing['rooms_number']) . '</p></li>';
            echo '<li><p>Price per night: $' . htmlspecialchars($listing['price_per_night']) . '</p></li>';
            echo '</ul>';
            echo '<form action="php/register_login/check_login.php" method="post">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($listing['id']) . '">';
            echo '<input type="hidden" name="title" value="' . htmlspecialchars($listing['title']) . '">';
            echo '<input type="hidden" name="area" value="' . htmlspecialchars($listing['area']) . '">';
            echo '<input type="hidden" name="address" value="' . htmlspecialchars($listing['address']) . '">';
            echo '<input type="hidden" name="rooms_number" value="' . htmlspecialchars($listing['rooms_number']) . '">';
            echo '<input type="hidden" name="price_per_night" value="' . htmlspecialchars($listing['price_per_night']) . '">';
            echo '<button type="submit">Click to Book</button>';
            echo '</form>';
            echo '</div>';
        }

        // Display pagination
        echo '<div class="pagination">';
        if ($page > 1) {
            echo '<a id="button_prev" href="?page=' . ($page - 1) . '">Previous</a>';
        }
        
        if ($page < $total_pages) {
            echo '<a id="button_next" href="?page=' . ($page + 1) . '">Next</a>';
        }
        echo '</div>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
</body>
</html>