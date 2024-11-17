<?php

require "../controller/dbCon.php";
global $conBooks;

if(isset($_POST["saveBook"])){
    $title = $_POST["title"];
    $author = $_POST["author"];
    $rating = (int) $_POST["rating"];
    $genre = $_POST["genre"];
    $annotation = $_POST["annotation"];

    $folder = "cover/";
    $coverFile = $_FILES["cover"]["name"];
    $file = $_FILES["cover"]["tmp_name"];
    $imageFiletype = strtolower(pathinfo($coverFile, PATHINFO_EXTENSION));

    $newCoverName = uniqid() . '.' . $imageFiletype;
    $targetFile = $folder . $newCoverName;

    if($imageFiletype != "jpg" && $imageFiletype != "png" && $imageFiletype != "jpeg"){
        $error[] = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                      Falsches Dateiformat!
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
    }
    if($_FILES["cover"]["size"] > 10000000){
        $error[] = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                      Bild zu groß!
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
    }
    if(!isset($error)){
        move_uploaded_file($file, $targetFile);
        $query = "INSERT INTO books (cover, title, author, rating, genre, annotation) VALUES (:cover_path, :title, :author, :rating, :genre, :annotation)";
        $stmt = $conBooks->prepare($query);
        $stmt->bindParam(':cover_path', $newCoverName);
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

?>

<div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="createFormModal">Buch hinzufügen</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form id="createEntry" action="createForm.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <label for="uploadCover">Cover hochladen</label>
                            <input type="file" accept=".jpg, .png, jpeg" class="form-control" id="cover" name="cover">
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <label for="title">Titel</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Titel">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mt-2">
                                    <label for="author">Autor</label>
                                    <input type="text" class="form-control" id="author" name="author" placeholder="Autor">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label for="rating">Sternebewertung</label>
                                    <select id="rating" class="form-select" name="rating">
                                        <option value="1">1 Stern</option>
                                        <option value="2">2 Sterne</option>
                                        <option value="3">3 Sterne</option>
                                        <option value="4">4 Sterne</option>
                                        <option value="5">5 Sterne</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="genre">Genre</label>
                                    <input type="text" class="form-control" id="genre" name="genre" placeholder="Genre">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <label for="annotation">Anmerkungen</label>
                            <textarea class="form-control" id="annotation" name="annotation" placeholder="Anmerkungen"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Schließen</button>
        <button type="submit" name="saveBook" form="createEntry" class="btn btn-dark">Speichern</button>
    </div>
</div>
