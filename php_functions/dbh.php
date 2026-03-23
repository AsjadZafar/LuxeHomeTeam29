<?php

$serverName = "localhost";
$DBUserName = "root";
$dbpass = "";
$dBName = "ecommerce";


$conn = mysqli_connect($serverName,$DBUserName, $dbpass,$dBName);


if (!$conn) {
    die("no connection: " . mysqli_connect_error());

}