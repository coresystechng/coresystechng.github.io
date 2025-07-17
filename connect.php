<?php 

$hostname = "localhost";
$username = "collins";
$password = "1234";
$database = "coresystechng_db";

$connect = mysqli_connect($hostname, $username, $password, $database);

if(!$connect) {
  echo 'error connecting to database' . mysqli_connect_error();
}

?>