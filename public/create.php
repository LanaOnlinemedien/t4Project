<?php
session_start();
require "controller/dbCon.php";
global $con;

$error = ""; // Variable für Fehler
$success = ""; // Variable für Erfolgsmeldungen

if (isset($_POST["create"])) {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $rating = (int)$_POST["rating"];
    $genre = $_POST["genre"];
    $annotation = $_POST["annotation"];

    $folder = "cover/";
    $coverFile = $_FILES["cover"]["name"];
    $file = $_FILES["cover"]["tmp_name"];
    $imageFiletype = strtolower(pathinfo($coverFile, PATHINFO_EXTENSION));

    $newCoverName = uniqid() . '.' . $imageFiletype;
    $targetFile = $folder . $newCoverName;

    // Überprüfen, ob der Benutzer eingeloggt ist
    if (!isset($_SESSION['user_id'])) {
        $error = "Fehler: Benutzer ist nicht eingeloggt.";
        // Optionale Weiterleitung zur Login-Seite
        header("location:login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    try {
        // Überprüfe Dateiformat
        if (!in_array($imageFiletype, ["jpg", "png", "jpeg"])) {
            throw new Exception("Falsches Dateiformat! Nur JPG, PNG und JPEG sind erlaubt.");
        }

        // Überprüfe Dateigröße
        if ($_FILES["cover"]["size"] > 10000000) {
            throw new Exception("Bild zu groß! Maximal 10 MB erlaubt.");
        }

        // Bild verschieben
        if (!move_uploaded_file($file, $targetFile)) {
            throw new Exception("Fehler beim Hochladen der Datei.");
        }

        // Datenbankeintrag erstellen
        $query = "INSERT INTO books (user_id, cover, title, author, rating, genre, annotation) 
                  VALUES (:user_id, :cover_path, :title, :author, :rating, :genre, :annotation)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':cover_path', $newCoverName);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':annotation', $annotation);

        if ($stmt->execute()) {
            $success = "Eintrag erfolgreich erstellt!";
        } else {
            throw new Exception("Eintrag konnte nicht erstellt werden.");
        }

        // Weiterleitung
        header("location:display.php");
        exit();

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>




<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buch erstellen</title>
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <h3 class="text-center mb-4">Neues Buch</h3>
        <form id="createEntry" action="create.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="cover" class="form-label">Cover hochladen</label>
                                    <input type="file" id="cover" name="cover" class="form-control mb-2" accept=".jpg, .png, .jpeg">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="title" class="form-label">Titel</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Titel">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="author" class="form-label">Autor</label>
                                    <input type="text" id="author" name="author" class="form-control" placeholder="Autor">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label for="rating" class="form-label">Sternebewertung</label>
                                    <select id="rating" name="rating" class="form-select">
                                        <option value="1">1 Stern</option>
                                        <option value="2">2 Sterne</option>
                                        <option value="3">3 Sterne</option>
                                        <option value="4">4 Sterne</option>
                                        <option value="5">5 Sterne</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="genre" class="form-label">Genre</label>
                                    <input type="text" id="genre" name="genre" class="form-control" placeholder="Genre">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mt-3">
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <label for="annotation">Anmerkungen</label>
                            <textarea class="form-control" id="annotation" name="annotation" placeholder="Anmerkungen"></textarea>
                        </div>
                    </div>
                </div>
                <div class="container mt-3">
                    <div class="row justify-content-center">
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-dark w-100" onclick="window.location.href='display.php'">Schließen</button>
                        </div>
                        <div class="col-4">
                            <button type="submit" name="create" form="createEntry" class="btn btn-dark w-100">Speichern</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
</body>
</html>
