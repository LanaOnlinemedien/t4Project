<?php
session_start();

require "controller/dbCon.php";
require "search.php";
global $con;


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$search = isset($_GET['search']) ? $_GET['search'] : '';

$stmt = $con->prepare("SELECT * FROM books WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$all_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
$all_books = searchBooks($search, $user_id, $con);

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
            <div class="col-1 d-flex justify-content-center">
                <button type="button" id="addEntryBtn" class="btn p-0 m-0" onclick="window.location.href='create.php'">
                    <img src="assets/plus.svg" alt="createEntry" class="img-fluid"/>
                </button>
            </div>
            <div class="col-8 d-flex align-items-center">
                <form method="GET" action="display.php" class="w-100">
                    <div class="input-group">
                        <input
                                type="search"
                                class="form-control"
                                name="search"
                                id="search"
                                placeholder="Suche nach Büchern"
                                autocomplete="off"
                        />
                        <button type="submit" class="btn btn-white">
                            <img src="assets/search-heart.svg" alt="search" />
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-1 d-flex justify-content-center">
                <button onclick="window.location.href='display.php'" type="button" class="btn p-0 m-0">
                    <img src="assets/x.svg" alt="back" class="img-fluid"/>
                </button>
            </div>
        </div>
    </div>

    <!-- error-messages, success-messages, messages-->
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-10">
                <?php
                // success
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                    unset($_SESSION['message']);
                }

                // error
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                    unset($_SESSION['error']);
                }

                // success
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                    unset($_SESSION['success']);
                }
                ?>
            </div>
        </div>
    </div>

    <!-- display books-->
    <div class="container mt-2">
        <div class="row justify-content-center">
            <?php if (!empty($all_books)): ?>
            <?php foreach ($all_books as $book): ?>
            <div class="col-5">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-3">
                            <img id="imageCover" src="cover/<?php echo $book['cover'] ?>" class="img-fluid rounded-start h-100" alt="cover" style="object-fit: cover">
                        </div>
                        <div class="col-9">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $book['title']?></h5>
                                <p class="card-text"><small class="text-body-secondary"><?php echo $book['author']?></small></p>
                                <p class="card-text"><?php echo $book['annotation']?></p>
                                <p>
                                    <?php
                                    for ($i=0; $i < $book['rating']; $i++) {
                                        echo '<img src="assets/star-fill.svg" alt="full Star" style="margin-right: 10px;"/>';
                                    }
                                    for ($i = $book['rating']; $i < 5; $i++) {
                                        echo '<img src="assets/star.svg" alt="empty Star" style="margin-right: 10px;"/>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="row justify-content-end">
                                    <div class="col-1 me-3">
                                        <button type="button" id="editEntryBtn" class="btn" onclick="window.location.href='update.php?book_id=<?php echo htmlspecialchars($book['book_id'], ENT_QUOTES, 'UTF-8'); ?>'">
                                            <img src="assets/pen.svg" alt="editEntry"/>
                                        </button>

                                    </div>
                                    <div class="col-1 me-5">
                                        <form method="post" action="delete.php">
                                            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['book_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <button type="submit" id="deleteEntryBtn" name="deleteBook" class="btn">
                                                <img src="assets/trash3-fill.svg" alt="deleteEntry" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Keine Ergebnisse gefunden.</p>
            <?php endif; ?>
        </div>
    </div>

<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>

