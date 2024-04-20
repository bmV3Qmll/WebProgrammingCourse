<?php
$dbserver = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "assignment";

$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
?>