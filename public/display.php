<?php
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

