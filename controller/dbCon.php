<?php

global $con;

$dsn = 'mysql:host=db;dbname=booknook';
$username = 'user';
$password = 'password';

try {
    $con = new PDO($dsn, $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
}

