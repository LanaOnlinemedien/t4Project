<?php
session_start();
require 'controller/dbCon.php';
global $con;

// Fehler-Variable initialisieren
$error = '';

try {
    // Überprüfen, ob der Benutzer eingeloggt ist
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Sie müssen eingeloggt sein, um ein Buch zu bearbeiten.');
    }

    $user_id = $_SESSION['user_id']; // Aktive Benutzer-ID

    // Buch-ID aus GET oder POST lesen
    $book_id = null;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Beim ersten Laden der Seite die Buch-ID aus GET holen
        if (!isset($_GET['book_id']) || empty($_GET['book_id'])) {
            throw new Exception('Buch-ID fehlt. Bitte gehen Sie zurück und versuchen Sie es erneut.');
        }
        $book_id = (int)$_GET['book_id'];
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Beim Absenden des Formulars die Buch-ID aus POST holen
        if (!isset($_POST['book_id']) || empty($_POST['book_id'])) {
            throw new Exception('Buch-ID fehlt. Das Update kann nicht durchgeführt werden.');
        }
        $book_id = (int)$_POST['book_id'];
    }

    // Buch-Daten aus der Datenbank abrufen
    $query = "SELECT * FROM books WHERE book_id = :book_id AND user_id = :user_id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        throw new Exception('Das Buch wurde nicht gefunden oder Sie sind nicht berechtigt, es zu bearbeiten.');
    }

    // Wenn das Formular abgesendet wurde (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Werte aus dem Formular lesen
        $title = isset($_POST['title']) ? $_POST['title'] : $book['title'];
        $author = isset($_POST['author']) ? $_POST['author'] : $book['author'];
        $rating = (int)(isset($_POST['rating']) ? $_POST['rating'] : $book['rating']);
        $genre = isset($_POST['genre']) ? $_POST['genre'] : $book['genre'];
        $annotation = isset($_POST['annotation']) ? $_POST['annotation'] : $book['annotation'];

        // Cover-Upload
        $folder = "cover/";
        $coverPath = $book['cover']; // Standardmäßig das bestehende Cover verwenden

        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $coverFile = $_FILES['cover']['name'];
            $file = $_FILES['cover']['tmp_name'];
            $imageFiletype = strtolower(pathinfo($coverFile, PATHINFO_EXTENSION));

            // Überprüfen, ob das hochgeladene Bild ein gültiges Format hat
            if (!in_array($imageFiletype, ['jpg', 'jpeg', 'png'])) {
                throw new Exception('Falsches Dateiformat! Erlaubt sind nur JPG, JPEG und PNG.');
            }
            if ($_FILES['cover']['size'] > 10000000) {
                throw new Exception('Bild ist zu groß! Maximale Größe: 10 MB.');
            }

            // Altes Cover löschen, wenn vorhanden
            if (!empty($book['cover']) && file_exists($folder . $book['cover'])) {
                unlink($folder . $book['cover']);
            }

            // Neues Cover speichern
            $newCoverName = uniqid() . '.' . $imageFiletype;
            if (!move_uploaded_file($file, $folder . $newCoverName)) {
                throw new Exception('Fehler beim Hochladen des Bildes.');
            }
            $coverPath = $newCoverName;
        }

        // Buch in der Datenbank aktualisieren
        $updateQuery = "UPDATE books 
                        SET title = :title, author = :author, rating = :rating, genre = :genre, annotation = :annotation, cover = :cover 
                        WHERE book_id = :book_id AND user_id = :user_id";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bindParam(':title', $title, PDO::PARAM_STR);
        $updateStmt->bindParam(':author', $author, PDO::PARAM_STR);
        $updateStmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $updateStmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        $updateStmt->bindParam(':annotation', $annotation, PDO::PARAM_STR);
        $updateStmt->bindParam(':cover', $coverPath, PDO::PARAM_STR);
        $updateStmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            $_SESSION['message'] = "Eintrag erfolgreich geändert!";
            header("Location: display.php");
            exit();
        } else {
            throw new Exception('Eintrag konnte nicht geändert werden.');
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Buch bearbeiten</title>
        <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="container">
                <h3 class="text-center mb-4">Buch bearbeiten</h3>

                <!-- Fehlernachricht anzeigen -->
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="update.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-group">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-8">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                    <label for="uploadCover">Cover hochladen</label>
                                    <input type="file" accept=".jpg, .png, jpeg" class="form-control" id="cover" name="cover">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="title">Titel</label>
                                            <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="author">Autor</label>
                                            <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="rating">Sternebewertung</label>
                                            <select id="rating" name="rating" class="form-select">
                                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                    <option value="<?php echo $i; ?>" <?php echo $i == $book['rating'] ? 'selected' : ''; ?>>
                                                        <?php echo $i; ?> Stern<?php echo $i > 1 ? 'e' : ''; ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label for="genre">Genre</label>
                                            <input type="text" id="genre" name="genre" class="form-control" value="<?php echo htmlspecialchars($book['genre'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container mt-3">
                            <div class="row justify-content-center">
                                <div class="col-8">
                                    <label for="annotation">Anmerkungen</label>
                                    <textarea id="annotation" name="annotation" class="form-control"><?php echo htmlspecialchars($book['annotation'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="container mt-3">
                            <div class="row justify-content-center">
                                <div class="col-4">
                                    <button type="button" class="btn btn-outline-dark w-100" onclick="window.location.href='display.php'">Abbrechen</button>
                                </div>
                                <div class="col-4">
                                    <button type="submit" name="update" class="btn btn-dark w-100">Speichern</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
