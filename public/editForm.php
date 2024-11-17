<?php
    require '../controller/dbCon.php';
    global $conBooks;

if (isset($_POST['saveChanges'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $rating = (int)$_POST['rating'];
    $genre = $_POST['genre'];
    $annotation = $_POST['annotation'];

    $folder = "cover/";
    $coverFile = $_FILES['cover']['name'];
    $file = $_FILES['cover']['tmp_name'];
    $imageFiletype = strtolower(pathinfo($coverFile, PATHINFO_EXTENSION));

    if ($_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        if (!in_array($imageFiletype, ['jpg', 'jpeg', 'png'])) {
            $_SESSION['message'] = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                      Falsches Dateiformat!
                                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
            header("Location: index.php");
            exit();
        }
        if ($_FILES['cover']['size'] > 10000000) {
            $_SESSION['message'] = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                      Bild ist zu groß!
                                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
            header("Location: index.php");
            exit();
        }

        $query = "SELECT cover FROM books WHERE id = :book_id LIMIT 1";
        $stmt = $conBooks->prepare($query);
        $stmt->bindParam(':book_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $oldCover = $row['cover'];
            if (file_exists($folder . $oldCover)) {
                unlink($folder . $oldCover);
            }
        }

        $newCoverName = uniqid() . '.' . $imageFiletype;
        if (!move_uploaded_file($file, $folder . $newCoverName)) {
            $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                      Fehler beim Hochladen des Bildes!
                                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
            header("Location: index.php");
            exit();
        }
        $coverPath = $newCoverName;
    } else {
        $query = "SELECT cover FROM books WHERE id = :book_id LIMIT 1";
        $stmt = $conBooks->prepare($query);
        $stmt->bindParam(':book_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $coverPath = $row['cover'];
        } else {
            $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                      Kein Eintrag mit dieser ID gefunden!
                                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
            header("Location: index.php");
            exit();
        }
    }

    $query = "UPDATE books 
              SET cover = :cover_path, title = :title, author = :author, rating = :rating, genre = :genre, annotation = :annotation 
              WHERE id = :book_id";
    $stmt = $conBooks->prepare($query);
    $stmt->bindParam(':cover_path', $coverPath, PDO::PARAM_STR);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':author', $author, PDO::PARAM_STR);
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
    $stmt->bindParam(':annotation', $annotation, PDO::PARAM_STR);
    $stmt->bindParam(':book_id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                  Eintrag erfolgreich geändert!
                                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                  Eintrag nicht geändert!
                                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
    }

    header("Location: index.php");
    exit();
}


if(isset($_GET['id'])){
        $book_id = $_GET['id'];
        $query = "SELECT * FROM books WHERE id = :book_id";

        $stmt = $conBooks->prepare($query);
        $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
        <form id="editEntry" action="editForm.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
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
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Titel" value="<?= $row['title']?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mt-2">
                                    <label for="author">Autor</label>
                                    <input type="text" class="form-control" id="author" name="author" placeholder="Autor" value="<?= $row['author']?>">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label for="rating">Sternebewertung</label>
                                    <select id="rating" class="form-select" name="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?= $i ?>" <?= $i == $row['rating'] ? 'selected' : '' ?>><?= $i ?> Stern<?= $i > 1 ? 'e' : '' ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="genre">Genre</label>
                                    <input type="text" class="form-control" id="genre" name="genre" placeholder="Genre" value="<?= $row['genre']?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <label for="annotation">Anmerkungen</label>
                            <textarea class="form-control" id="annotation" name="annotation" placeholder="Anmerkungen"><?= htmlspecialchars($row['annotation']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
<?php
        }else{
            $_SESSION['message'] =
                "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                      ID wurde nicht gefunden.
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
        }
    }
?>

