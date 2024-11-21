<?php
session_start();

require_once "controller/dbCon.php";
global $con;

// Überprüfe, ob der Nutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Umleiten, falls nicht eingeloggt
    exit();
}

// Hole die user_id aus der Session
$user_id = $_SESSION['user_id'];

// Bereite die SQL-Abfrage vor, um nur Bücher des aktuellen Nutzers zu laden
$pdo = $con->prepare("SELECT * FROM books WHERE user_id = :user_id");
$pdo->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$pdo->execute();

// Bücher des Nutzers abrufen
$all_books = $pdo->fetchAll(PDO::FETCH_ASSOC);
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booknook Startseite</title>
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<header>
    <div class="container mb-5">
        <div class="row justify-content-between d-flex align-items-center mt-3">
            <div class="col-2">
                <object type="image/svg+xml" data="assets/logoNew.svg">
                    <img src="assets/logoNew.svg" alt="logo"/>
                </object>
            </div>
            <div class="col-1">
                <button type="button" class="btn" onclick="window.location.href='index.php'">
                    <img src="assets/box-arrow-right.svg" alt="logout"/>
                </button>
            </div>
        </div>
    </div>
</header>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-8">
            <object type="image/svg+xml" data="assets/bookelement.svg">
                <img src="assets/bookelement.svg" alt=""/>
            </object>
        </div>
    </div>
    <div class="row justify-content-center d-flex align-items-center mt-1">
        <div class="col-1">
            <button type="button" id="addEntryBtn" class="btn" onclick="window.location.href='create.php'">
                <img src="assets/plus.svg" alt="createEntry"/>
            </button>
        </div>
        <div class="col-8 d-flex align-items-center">
            <div class="input-group">
                <label for="search"></label>
                <input
                    type="search"
                    class="form-control"
                    name="search"
                    id="search"
                    autocomplete="off"
                >
                <div class="input-group-append">
                        <span class="input-group-text">
                            <img src="assets/search-heart.svg" alt="search"/>
                        </span>
                </div>
            </div>
        </div>
        <div class="col-1">
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="assets/funnel.svg" alt="filter"/>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- display books-->
<div class="container mt-4">
    <div class="row justify-content-center">
        <?php foreach ($all_books as $row) {
            ?>
        <div class="col-5">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-3">
                        <img id="imageCover" src="cover/<?php echo $row['cover'] ?>" class="img-fluid rounded-start h-100" alt="cover" style="object-fit: cover">
                    </div>
                    <div class="col-9">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['title']?></h5>
                            <p class="card-text"><small class="text-body-secondary"><?php echo $row['author']?></small></p>
                            <p class="card-text"><?php echo $row['annotation']?></p>
                            <p>
                                <?php
                                for ($i=0; $i < $row['rating']; $i++) {
                                    echo '<img src="assets/star-fill.svg" alt="full Star" style="margin-right: 10px;"/>';
                                }
                                for ($i = $row['rating']; $i < 5; $i++) {
                                    echo '<img src="assets/star.svg" alt="empty Star" style="margin-right: 10px;"/>';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="row justify-content-end">
                                <div class="col-1 me-3">
                                    <button type="button" id="editEntryBtn" class="btn" onclick="window.location.href='update.php'">
                                        <img src="assets/pen.svg" alt="editEntry"/>
                                    </button>
                                </div>
                                <div class="col-1 me-5">
                                    <form method="post" action="delete.php">
                                        <button type="submit" id="deleteEntryBtn" name="deleteBook" class="btn">
                                            <img src="assets/trash3-fill.svg" alt="deleteEntry"/>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
}
?>
    </div>
</div>

</body>
</html>

