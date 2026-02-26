<?php

$serverName = "";
$DBUserName = "cs2team29";
$dbpass = "eCDVXBXdLlV2mSauOg6fUiBZ9";
$dBName = "cs2team29_db";


$conn = mysqli_connect($serverName,$DBUserName, $dbpass,$dBName);


if (!$conn) {
    die("no connection: " . mysqli_connect_error());

}