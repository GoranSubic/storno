<?php

//include "settings.php"; //Connect to Database
include_once ('settings.php');

//konekcija na bazu
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

$deleterecords = "TRUNCATE TABLE tglava"; 
//empty the table of its current records mysql_query($deleterecords);
$res = $mysqli->query($deleterecords);

$deleterecords = "TRUNCATE TABLE tstavke"; 
//empty the table of its current records mysql_query($deleterecords);
$res = $mysqli->query($deleterecords);

$deleterecords = "TRUNCATE TABLE artikal"; 
//empty the table of its current records mysql_query($deleterecords);
$res = $mysqli->query($deleterecords);

?>