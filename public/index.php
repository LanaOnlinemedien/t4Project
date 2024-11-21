<?php

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booknook</title>
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
                    <button type="button" class="btn" onclick="window.location.href='login.php'">
                        <img src="assets/box-arrow-right.svg" alt="logout"/>
                    </button>
                </div>
            </div>
        </div>
    </header>
    <div class="container mt-5">
        <div class="row d-flex align-items-center">
            <div class="col justify-content-center">
                <h3>Willkommen bei Booknook!</h3>
                <p>Verwalten Sie Ihre Büchersammlung mühelos:</p>
                <p>Speichern, bewerten und organisieren Sie Ihre Lieblingsbücher an einem Ort.</p>
                <p>Ihr digitales Bücherregal für mehr Übersicht und Inspiration!</p>
                <button type="button" class="btn btn-outline-dark" onclick="window.location.href='login.php'">
                    Zur Anmeldung
                </button>
            </div>
            <div class="col">
                <img src="assets/bookelement_short.svg" alt=""/>
            </div>
        </div>
    </div>
</body>
</html>
