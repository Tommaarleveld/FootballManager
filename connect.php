<?php
/*Online*/
$servername = "sql.cmi.hro.nl";
$username = "0882284";
$password = "lokomotief";

$database="0882284";


/* Localhost */
//$servername = "localhost";
//$username = "root";
//$password = "";
//
//$database="herkansingv1";
// Create connection

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_select_db($conn, $database);

?>