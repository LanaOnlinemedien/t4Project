<?php
global $conUsers;

$dsn = 'mysql:host=localhost;dbname=userdb';
$username = 'root';
$password = '';

$conUsers = new PDO($dsn, $username, $password);




