<?php
session_start();
require "../controller/dbCon.php";
global $conBooks;

if(isset($_POST["saveBook"])){
    if($_FILES["cover"]["error"] === 4){
       $_SESSION['message'] =
           "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
              Bild existiert nicht!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    } else {
        $fileName = $_FILES["cover"]["name"];
        $fileSize = $_FILES["cover"]["size"];
        $tmpName = $_FILES["cover"]["tmp_name"];

        $validImageExtensions = ["jpeg", "jpg", "png"];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtensions)) {
            echo "<script>  alert('Falsches Dateiformat'); </script>";
        } else if($fileSize > 2097152) {
            $_SESSION['message'] =
            "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
              Bild existiert nicht!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        } else{
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            move_uploaded_file($tmpName, 'cover/' . $newImageName);
            $title = $_POST["title"];
            $author = $_POST["author"];
            $rating = (int) $_POST["rating"];
            $genre = $_POST["genre"];
            $annotation = $_POST["annotation"];

            $query = "INSERT INTO books (cover,title, author, rating, genre, annotation) VALUES (:cover_path, :title, :author, :rating, :genre, :annotation)";
            $stmt = $conBooks->prepare($query);
            $stmt->bindParam(':cover_path', $newImageName);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':genre', $genre);
            $stmt->bindParam(':annotation', $annotation);

            if($stmt->execute()){
                $_SESSION['message'] =
                    "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                      Eintrag erfolgreich!
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            } else{
                $_SESSION['message'] =
                    "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                      Eintrag nicht erstellt!
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
            header("location:index.php");
            exit();
        }
    }
}
