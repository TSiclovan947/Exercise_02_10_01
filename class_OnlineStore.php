<?php
class OnlineStore {
    private $DBConnect = NULL;
    private $DBName = "";
    private $storeID = "";
    private $inventory = array();
    private $shoppingCart = array();
    
    function __construct() {
        //echo "I am constructing an object.<br>";
        include("inc_OnlineStoresDB.php");
        $this->DBConnect = $DBConnect;
        $this->DBName = $DBName;
    }
    
    function __destruct() {
        if (!$this->DBConnect->connect_error) {
            echo "<p>Closing database <em>$this->DBName</em>.</p>\n";
            $this->DBConnect->close();
        }
    }
    
    function __wakeup() {
        include("inc_OnlineStoresDB.php");
        $this->DBConnect = $DBConnect;
        $this->DBName = $DBName;
    }
    
    //Part of public interface
    //set means looking at setter function (setter function for storeID)
    public function setStoreID($storeID) {
        //echo "\$storeID: $storeID";
        if ($this->storeID != $storeID) {
            $this->storeID = $storeID;
            $TableName = "inventory";
            $SQLstring = "SELECT * FROM $TableName" . 
                " WHERE storeID='" . 
                $this->storeID . "'";
            $QueryResult = $this->DBConnect->query($SQLstring);
            //Failure, query could not be executed
            if (!$QueryResult) {
                echo "<p>Unable to execute the query, " .
                    "error code: " . 
                    $this->DBConnect->errno . ": " . 
                    $this->DBConnect->error . "</p>\n";
            }
            else {
                $inventory = array();
                $shoppingCart = array();
                while (($row = $QueryResult->fetch_assoc()) != NULL) {
                    $this->inventory[$row['productID']] = array();
                    $this->inventory[$row['productID']]['name'] = $row['name'];
                    $this->inventory[$row['productID']]['description'] = $row['description'];
                    $this->inventory[$row['productID']]['price'] = $row['price'];
                    $this->shoppingCart[$row['productID']] = 0;
                }
//                echo "<pre>\n";
//                print_r($this->inventory);
//                print_r($this->shoppingCart);
//                echo "</pre>\n";
            }
        }
    }
    
    //Means this is a getter function
    public function getStoreInformation() {
        $retval = false;
        if ($this->storeID != "") {
            $TableName = "storeinfo";
            $SQLstring = "SELECT * FROM $TableName" . 
                " WHERE storeID='" . 
                $this->storeID . "'";
            $QueryResult = $this->DBConnect->query($SQLstring);
            if ($QueryResult) {
                $retval = $QueryResult->fetch_assoc();
            }
        }
        return $retval;
    }
    
    //Pull data from inventory array, format into table that can go on the screen, self contained
    //Add shopping cart functionality
    public function getProductList() {
        $retval = false;
        $subtotal = 0;
        //If inventory greater than 0
        if (count($this->inventory) > 0) {
            //if have inventory, want to set up a table
            echo "<table width='100%'>\n";
            echo "<tr>";
            echo "<th>Product</th>\n";
            echo "<th>Description</th>\n";
            echo "<th>Price Each</th>\n";
            echo "<th># in Cart</th>\n";
            echo "<th>Total Price</th>\n";
            echo "<th>&nbsp;</th>\n";
            echo "</tr>";
            //in inventory array
            //Will have name of associative ID and value in the other
            foreach ($this->inventory as $ID => $info) {
                echo "<tr>";
                echo "<tr><td>" . htmlentities($info['name']) . "</td>\n";
                echo "<td>" . htmlentities($info['description']) . "</td>\n";
                //f=floating point number
                //printf allows for styles to display
                printf("<td class='currency'>$%.2f</td>", $info['price']);
                echo "<td class='currency'>" . 
                    $this->shoppingCart[$ID] . "</td>";
                printf("<td class='currency'>$%.2f</td>", $info['price'] * $this->shoppingCart[$ID]);
                echo "<td><a href='" . 
                    $_SERVER['SCRIPT_NAME'] . 
                    "?PHPSESSID=" . session_id() . 
                    "&ItemToAdd=$ID'>Add Item</a></td>";
                $subtotal += ($info['price'] * $this->shoppingCart[$ID]);
                echo "</tr>\n";
            }
            echo "<tr>";
            echo "<td colspan='4'>Subtotal</td>";
            printf("<td class='currency'>$%.2f</td>", $subtotal);
            echo "</tr>";
            echo "</table>\n";
            $retval = true;
        }
        
        return($retval);
    }
    
    //utility function, not getter or setter
    //designed to add object to shopping Cart
    public function addItem() {
        $prodID = $_GET['ItemToAdd'];
        if (array_key_exists($prodID, $this->shoppingCart)) {
            $this->shoppingCart[$prodID] += 1;
        }
    }
}
?>