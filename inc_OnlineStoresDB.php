<?php
$errorMsgs = array();
$hostname = 'localhost';
$username = "adminer";
$passwd = "after-water-49";
$DBName = 'onlinestores2';
//new = find object/class (mysqli class) and that is the constructor
$DBConnect = @new mysqli($hostname, $username, $passwd, $DBName);
//Thin error = DBConnect owns something
//Owns property called connect_error
if ($DBConnect->connect_error) {
    $errorMsgs[] = "Unable to connect to the database server." . 
        " Error code " . $DBConnect->connect_errno . 
        ": " . $DBConnect->connect_error;
}
?>