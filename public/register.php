<?php
require("controller/dbCon.php");
session_start();
global $con;

$error = "";

if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        $stmt = $con->prepare("SELECT * FROM users WHERE username=:username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $userAlreadyExists = $stmt->fetchColumn();

        if (!$userAlreadyExists) {
            registerUser($username, $password);
        } else {
            $error = "Benutzer existiert bereits!";
        }
    } catch (PDOException $e) {
        $error = "Datenbankfehler: " . $e->getMessage();
    } catch (Exception $e) {
        $error = "Ein unerwarteter Fehler ist aufgetreten: " . $e->getMessage();
    }
}

function registerUser($username, $password)
{
    global $con;

    try {
        $stmt = $con->prepare("INSERT INTO users(username, password) VALUES (:username, :password)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        $stmt->execute();

        // Hol die ID des neu erstellten Benutzers
        $user_id = $con->lastInsertId();

        // user_id und username in der Session speichern
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;

        // Weiterleitung nach erfolgreicher Registrierung
        header("Location: display.php");
        exit();
    } catch (PDOException $e) {
        global $error;
        $error = "Fehler beim Registrieren: " . $e->getMessage();
    }
}
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
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
            <div class="col-4 d-flex justify-content-center m-0.5 mt-2">
                <button type="submit" name="register" class="btn btn-dark w-100">Registrieren</button>
            </div>
        </div>
    </form>
    <div class="row justify-content-center mt-2">
        <div class="col-4 text-center">
            <a href="login.php" class="text-decoration-none">Bereits ein Konto? Login</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-4">
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
