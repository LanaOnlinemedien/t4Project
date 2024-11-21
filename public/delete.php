<?php
session_start();
require "controller/dbCon.php";
global $con;


$message = '';
try {
    if (!isset($_POST['deleteBook']) || empty($_POST['book_id'])) {
        throw new Exception('Ungültige Anfrage. Buch-ID fehlt.');
    }

    $book_id = (int)$_POST['book_id'];

    $querySelect = "SELECT cover FROM books WHERE book_id = :book_id";
    $stmtSelect = $con->prepare($querySelect);
    $stmtSelect->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $stmtSelect->execute();

    $book = $stmtSelect->fetch(PDO::FETCH_ASSOC);
    if (!$book) {
        throw new Exception('Das Buch wurde nicht gefunden.');
    }

    $coverPath = "cover/" . $book['cover'];
    if (!empty($book['cover']) && file_exists($coverPath)) {
        if (!unlink($coverPath)) {
            throw new Exception('Das Cover konnte nicht aus dem Ordner gelöscht werden.');
        }
    }

    $queryDelete = "DELETE FROM books WHERE book_id = :book_id";
    $stmtDelete = $con->prepare($queryDelete);
    $stmtDelete->bindParam(':book_id', $book_id, PDO::PARAM_INT);

    if ($stmtDelete->execute()) {
        $message = 'Eintrag erfolgreich gelöscht.';
    } else {
        throw new Exception('Eintrag konnte nicht gelöscht werden.');
    }

    $_SESSION['message'] = $message;
    header("Location: display.php");
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: display.php");
    exit();
}
