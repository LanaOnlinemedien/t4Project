<?php
require("controller/dbCon.php");
global $con;

$error = "";

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    try {
        $stmt = $con->prepare("SELECT * FROM users WHERE username=:username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userExists) {
            $passwordHashed = $userExists['password'];

            $checkPassword = password_verify($password, $passwordHashed);

            if ($checkPassword === false) {
                $error = "Falsches Passwort";
            } elseif ($checkPassword === true) {
                session_start();
                $_SESSION["username"] = $userExists['username'];
                $_SESSION['user_id'] = $userExists['user_id'];
                header("Location: display.php");
                exit();
            }
        } else {
            $error = "Der Benutzer existiert nicht.";
        }
    } catch (PDOException $e) {
        // Log or display a meaningful error message
        $error = "Datenbankfehler: " . $e->getMessage();
    } catch (Exception $e) {
        // Catch other types of errors
        $error = "Ein Fehler ist aufgetreten: " . $e->getMessage();
    }
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
<div class="container">
    <div class="row">
        <div class="col text-center pt-3">
            <h1>Login</h1>
        </div>
    </div>
    <form action="login.php" method="POST">
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
                <button type="submit" name="login" class="btn btn-dark w-100">Login</button>
            </div>
        </div>
    </form>
    <div class="row justify-content-center mt-2">
        <div class="col-4 text-center">
            <a href="register.php" class="text-decoration-none">Noch kein Konto? Jetzt Registrieren</a>
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


