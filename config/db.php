<?php
$hostname = 'localhost';
$database = 'futsal_system';
$username = 'root';
$password = 'prashan@2005';

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
  die('' . mysqli_connect_error());
}
