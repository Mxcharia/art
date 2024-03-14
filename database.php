<?php

$hostName= "localhost";
$dbUser = "root";
$dbPassword= "";
$dbName = "artgallery";
$conn = mysqli_connect($hostName,$dbUser, $dbPassword, $dbName);

//checking if it returns false. If so, then the connection is not established
if(!$conn) {
    die("Something went wrong");
}



?>