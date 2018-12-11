<?php
require_once("inc_OnlineStoresDB.php");
?>
<!doctype html>

<html>

<head>
    <title>Gourmet Coffee</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>Gourmet Coffee</h1>
    <h2>Description Goes Here</h2>
    <p>Welcome Message Goes Here</p>
    <p>Inventory Goes Here</p>
    <?php
    if (count($errorMsgs) > 0) {
        foreach($errorMsgs as $msg) {
            echo "<p>" . $msg . "</p>\n";
        }
    }  
?>
</body>

</html>
