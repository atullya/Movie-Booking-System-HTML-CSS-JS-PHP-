<?php
$servername = "localhost";
$username = "root";
$pass = "root";
$dbname = "ranjana";

$conn = mysqli_connect($servername, $username, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>