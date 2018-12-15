<?php
session_start();
require_once("inc_OnlineStoresDB.php");
require_once("class_OnlineStore.php");
if (class_exists("OnlineStore")) {
    //Go call constructor for dollar store
    //$Store = new OnlineStore();
    if (isset($_SESSION['currentStore'])) {
        echo "Unserializing object.<br>";
        $Store = unserialize($_SESSION['currentStore']);
    }
    else {
        echo "Instantiating new object.<br>";
         $Store = new OnlineStore();
    }
}
else {
    $errorMsgs[] = "The <em>OnlineStore</em> class is not available!";
    $Store = NULL;
}
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
    <?php
    $TableName = "inventory";
    if (count($errorMsgs) == 0) {
        $SQLstring = "SELECT * FROM $TableName" .
            " WHERE storeID='COFFEE'";
        //Use straight arrow to give $DBConnect a method of query
        $QueryResult = $DBConnect->query($SQLstring);
        if (!$QueryResult) {
            $errorMsgs[] = "Unable to perform the query.<br>" . 
                " Error code: " . $DBConnect->errno . ": " .
                $DBConnect->error;
        }
        else {
            echo "<table width='100%'>\n";
            echo "<tr>";
            echo "<th>Product</th>\n";
            echo "<th>Description</th>\n";
            echo "<th>Price Each</th>\n";
            echo "</tr>";
            //array exploded in assoc array
            //Gets data record from fetch_assoc; Get associative array
            while (($row = $QueryResult->fetch_assoc()) != NULL) {
                echo "<tr><td>" . htmlentities($row['name']) . "</td>\n";
                echo "<td>" . htmlentities($row['description']) . "</td>\n";
                //f=floating point number
                //printf allows for styles to display
                printf("<td>$%.2f</td></tr>\n", $row['price']);
            }
            echo "</table>";
            $_SESSION['currentStore'] = serialize($Store);
        }
    }
    if (count($errorMsgs) > 0) {
        foreach($errorMsgs as $msg) {
            echo "<p>" . $msg . "</p>\n";
        }
    }  
?>
</body>

</html>
