<?php
global $conBooks;
require "../controller/dbCon.php";

if(isset($_POST['deleteBook'])){
    $book_id = $_POST['deleteBook'];

    $query = "DELETE FROM books WHERE id = :book_id";
    $stmt = $conBooks->prepare($query);
    $stmt->bindParam(':book_id', $book_id);

    if($stmt->execute()){
        $_SESSION['message'] =
            "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    Eintrag erfolgreich gelöscht!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        header ("Location: index.php");
        exit();
    } else {
        $_SESSION['message'] =
            "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    Eintrag nicht gelöscht!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        header ("Location: index.php");
        exit();
    }
}
?>





