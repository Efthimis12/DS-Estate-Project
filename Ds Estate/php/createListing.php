<!DOCTYPE html>
<html>
<head>
    <title>Create New Listing</title>
    <link rel="stylesheet" href="/DS ESTATE/style/styleCreateListing.css">
    <script>
        function validateForm() {
            const title = document.getElementById('title').value;
            const area = document.getElementById('area').value;
            const roomsNumber = document.getElementById('rooms_number').value;
            const pricePerNight = document.getElementById('price_per_night').value;

            const titleAreaRegex = /^[A-Za-z, ]+$/;
            const positiveIntegerRegex = /^[1-9]\d*$/;

            if (!titleAreaRegex.test(title)) {
                alert('Title must contain only characters, commas, and spaces.');
                return false;
            }

            if (!titleAreaRegex.test(area)) {
                alert('Area must contain only characters, commas, and spaces.');
                return false;
            }

            if (!positiveIntegerRegex.test(roomsNumber)) {
                alert('Number of Rooms must be a positive integer.');
                return false;
            }

            if (!positiveIntegerRegex.test(pricePerNight)) {
                alert('Price per Night must be a positive integer.');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <header id="header1">
        <?php include 'navbar.php'; ?>
    </header>
    <section id="insert_new_Listing">
        <h1 id="title_h1">Insert New Listing</h1>
        <form action="createListing.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
            <label for="picture">Picture:</label>
            <input type="file" id="picture" name="picture" accept="image/*" required><br><br>

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" maxlength="40" required><br><br>
            
            <label for="area">Area:</label>
            <input type="text" id="area" name="area" maxlength="30" required><br><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" maxlength="40" required><br><br>

            <label for="rooms_number">Number of Rooms:</label>
            <input type="number" id="rooms_number" name="rooms_number" required><br><br>

            <label for="price_per_night">Price per Night:</label>
            <input type="number" id="price_per_night" name="price_per_night" required><br><br>

            <button type="submit">Insert Listing</button>
        </form>
        <?php
            if (isset($_GET['error']) && $_GET['error'] === 'duplicate_listing') {
                echo "<script>
                    window.onload = function() {
                        alert('This listing already exists!');
                    };
                </script>";
            }

            if (isset($_GET['error']) && $_GET['error'] === 'validation_error') {
                echo "<script>
                    window.onload = function() {
                        alert('Validation error: " . $_GET['message'] . "');
                    };
                </script>";
            }
        ?>
    </section>
</body>
<?php
include 'connection.php';
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function generateUniqueId($length = 10) {
    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

function listingExists($conn, $title, $rooms_number) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM listings WHERE title = :title AND rooms_number = :rooms_number");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':rooms_number', $rooms_number);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

function validateInput($title, $area, $rooms_number, $price_per_night) {
    $titleAreaRegex = "/^[A-Za-z, ]+$/";
    $positiveIntegerRegex = "/^[1-9]\d*$/";

    if (!preg_match($titleAreaRegex, $title)) {
        return "Title must contain only characters, commas, and spaces.";
    }

    if (!preg_match($titleAreaRegex, $area)) {
        return "Area must contain only characters, commas, and spaces.";
    }

    if (!preg_match($positiveIntegerRegex, $rooms_number)) {
        return "Number of Rooms must be a positive integer.";
    }

    if (!preg_match($positiveIntegerRegex, $price_per_night)) {
        return "Price per Night must be a positive integer.";
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = generateUniqueId();
    $picture = file_get_contents($_FILES['picture']['tmp_name']);
    $title = $_POST['title'];
    $area = $_POST['area'];
    $address = $_POST['address'];
    $rooms_number = $_POST['rooms_number'];
    $price_per_night = $_POST['price_per_night'];

    $validationResult = validateInput($title, $area, $rooms_number, $price_per_night);
    if ($validationResult !== true) {
        header("Location: createListing.php?error=validation_error&message=" . urlencode($validationResult));
        exit();
    }

    try {
        if (listingExists($conn, $title, $rooms_number)) {
            header("Location: ../index.php?error=duplicate_listing");
            exit();
        } else {
            $stmt = $conn->prepare("INSERT INTO listings (id, picture, title, area, address, rooms_number, price_per_night) VALUES (:id, :picture, :title, :area, :address, :rooms_number, :price_per_night)");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':picture', $picture);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':area', $area);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':rooms_number', $rooms_number);
            $stmt->bindParam(':price_per_night', $price_per_night);
            $stmt->execute();
            
            header("Location: ../index.php");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<footer><?php include 'footer.php'; ?></footer>
</html>