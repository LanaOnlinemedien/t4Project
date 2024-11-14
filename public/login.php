<?php
    global $conUsers;
    require("../controller/dbLogin.php");

    $error = "";

    if(isset($_POST["submit"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $stmt = $conUsers->prepare("SELECT * FROM users WHERE username=:username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userExists) {
            $passwordHashed = $userExists['password'];

            $checkPassword = password_verify($password, $passwordHashed);

            if($checkPassword === false){
                $error = "Falsches Passwort";
            }
            if($checkPassword === true){
                session_start();
                $_SESSION["username"] = $userExists['username'];
                header("Location:index.php");
                exit();
            }
        }
        else {
            $error = "Der Benutzer existiert nicht.";
        }
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
            <div class="col-2 d-flex justify-content-center m-0.5 mt-2">
                <button type="submit" name="submit" class="btn btn-outline-dark w-100">Login</button>
            </div>
            <div class="col-2 d-flex justify-content-center m-0.5 mt-2">
                <button type="button" onclick="window.location.href='register.php'" name="register" class="btn btn-outline-dark w-100">Registrieren</button>
            </div>
        </div>
    </form>
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


