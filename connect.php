<?php 

$servername = "wghp5";  // Change if your database is hosted remotely
$username = "cohtech1_collins_okoroafor";  // Use your database username
$password = "28C!fbiTb-n6";  // Use your database password
$database = "cohtech1_coresystechng_db"; // Use your database name

$connect = mysqli_connect($hostname, $username, $password, $database);

if(!$connect) {
  echo 'error connecting to database' . mysqli_connect_error();
}

?>