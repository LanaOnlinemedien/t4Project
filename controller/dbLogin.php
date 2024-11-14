<?php
global $conUsers;

$dsn = 'mysql:host=localhost;dbname=userdb';
$username = 'root';
$password = '';

$conUsers = new PDO($dsn, $username, $password);

if(!$conUsers){
    die("Connection failed".mysqli_connect_error());
}




