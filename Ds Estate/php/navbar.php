<?php
session_start(); // Start the session
$base_url = '/Ds_Estate_website/DS ESTATE'; // Calculate the base URL dynamically
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Listing</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/style/styleIndex.css">
    <script defer src="<?php echo $base_url; ?>/../../js/controller.js"></script>
</head>
<body>
    <nav>
        <div class="logo">DS Estate</div>
        <ul class="nav-links">
            <li><a href="<?php echo $base_url; ?>/index.php">Feed</a></li>
            <li><a href="<?php echo $base_url; ?>/php/createListing.php">Create Listing</a></li>
            <?php
                if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
                    echo '<li><a href="' . $base_url . '/php/register_login/register_login.php">Login/Register</a></li>';
                } else {
                    echo '<li><a href="' . $base_url . '/php/register_login/logout.php">Logout</a></li>';
                }
            ?>
            <li><a href="<?php echo $base_url; ?>/php/footer.php">Contact</a></li>
        </ul>
    </nav>
</body>
</html>