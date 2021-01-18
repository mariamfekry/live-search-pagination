<?php
$servername ="localhost";
$username="root";
$password="";
$dbname="search&pagination";


$conn = new PDO("mysql:host=localhost; dbname=search&pagination", "root", "");
if(!$conn){
die("connection failed: " .$conn->connect_error);
}



?>