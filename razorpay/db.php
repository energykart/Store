<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "payment";
$con=mysqli_connect('host','username','password','dbname');
if (!$conn){
    die("Error". mysqli_connect_error());
}

?>