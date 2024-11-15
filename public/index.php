<?php
session_start();

require_once "../controller/dbCon.php";
global $conBooks;

$pdo = "SELECT * FROM books";
$all_books = $conBooks -> query($pdo);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booknook</title>
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
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
                <button type="button" class="btn" onclick="window.location.href='login.php'">
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
            <button type="button" id="addEntryBtn" class="btn" data-bs-toggle="modal" data-bs-target="#createModal">
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
        <div class="col-1 ">
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

<!-- output books -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <!-- wrapper book output -->
        <?php
            while($row = $all_books -> fetch(PDO::FETCH_BOTH)){
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
                                    echo '<img src="assets/star.svg" alt="empty Star"/>';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <div class="row justify-content-end">
                                <div class="col-1">
                                    <button type="button" id="editEntryBtn" class="btn" data-bs-toggle="modal" data-bs-target="#editModal">
                                        <img src="assets/pen.svg" alt="editEntry"/>
                                    </button>
                                </div>
                                <div class="col-1 me-5">
                                    <button type="submit" id="deleteEntryBtn" name="deleteBook" class="btn">
                                        <img src="assets/trash3-fill.svg" alt="editEntry"/>
                                    </button>
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

<!-- Modal: add book -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
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
    </div>
</div>

<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
