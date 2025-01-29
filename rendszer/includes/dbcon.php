<?php
require ("config.php");
$con=mysqli_connect("localhost",DB_USER,DB_PASSWORD,DB_NAME);
mysqli_query($con, "SET NAMES 'utf8'");	
if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
?>