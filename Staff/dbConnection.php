<?php
// Define database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tests');

// Create a new MySQLi instance (object-oriented style)
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($con->connect_error) {
    die("Connection to test failed : " . $con->connect_error);
}
?>

