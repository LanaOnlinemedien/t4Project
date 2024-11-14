<?php
global $conUsers;
require("../controller/dbLogin.php");

    $error = "";

if(isset($_POST["submit"])){

    $username = $_POST["username"];
    $password = PASSWORD_HASH($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conUsers->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $userAlreadyExists = $stmt->fetchColumn();

    if(!$userAlreadyExists){
        registerUser($username, $password);
    } else{
        $error = "Benutzer existiert bereits!";
    }
}
function registerUser($username, $password){
    global $conUsers;
    $stmt = $conUsers->prepare("INSERT INTO users(username, password) VALUES (:username, :password)");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
    header("Location: index.php");
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col text-center pt-3">
            <h1>Registrierung</h1>
        </div>
    </div>
    <form action="register.php" method="POST">
        <div class="row justify-content-center mb-2">
            <div class="col-4">
                <input
                    type="text"
                    placeholder="Name"
                    name="username"
                    class="form-control"
                    autocomplete="off"
                    required
                />
            </div>
        </div>
        <div class="row justify-content-center mb-2">
            <div class="col-4">
                <input
                    type="password"
                    placeholder="Passwort"
                    name="password"
                    class="form-control"
                    autocomplete="off"
                    required
                />
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-2 d-flex justify-content-center m-0.5 mt-2">
                <button type="submit" name="submit" class="btn btn-outline-dark w-100">Registrieren</button>
            </div>
            <div class="col-2 d-flex justify-content-center m-0.5 mt-2">
                <button type="button" onclick="window.location.href='login.php'" name="login" class="btn btn-outline-dark w-100">Login</button>
            </div>
        </div>
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
    </form>
</div>
</body>
</html>
