<!DOCTYPE html>
<html>
<head>
    <title>Ds_Estate_e21187</title>
    <link rel="stylesheet" href="style/styleIndex.css">
    <script>
        window.addEventListener('beforeunload', function () {
            navigator.sendBeacon('logout.php');
        });
    </script>
</head>    
<body>
    <header id="header1">
        <?php include 'php/navbar.php'; ?>
        <div class="fewWords">
            <h1 id="title" class="large_title">DS Estate</h1>
            <h3 id="subtitle">House renting with a single click!</h3>
            <p>Our page is dedicated to make short-term house renting a fun and easy experience!
                Either you want to find a house to rent or you are looking to rent your property 
                to someone else DS Estate got you covered!
            </p>
            <p>Login and discover the advantages of our  website!</p>
        </div>
        
        
    </header>
    <section id="section_feed">
        <div class="feed">
            <h3 id="feed_title_h3">Search over hundreds of houses to rent!</h3>
            <div id="listings">
                <?php include 'php/feed.php'; ?>
            </div>
        </div>
    </section>
    <footer><?php
        include 'php/footer.php';
    ?></footer>
    
</body>
</html>                          