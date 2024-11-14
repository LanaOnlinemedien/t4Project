<?php

global $conBooks;

$dsn = 'mysql:host=localhost;dbname=bookdb';
$username = 'root';
$password = '';

$conBooks = new PDO($dsn, $username, $password);

if(!$conBooks){
    die("Connection failed".mysqli_connect_error());
}
