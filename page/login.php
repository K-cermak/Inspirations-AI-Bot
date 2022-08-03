<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: index.php');
        die();
    }

    $errorMessage = "";
    if ($_POST) {
        require_once 'db.php';

        $username = $_POST['username'];
        $password = $_POST['password'];
        $loginResult = verifyLogin($username, $password);

        if ($loginResult === true) {
            $_SESSION['username'] = $username;
            $_SESSION['timeLogged'] = time();
            header('Location: index.php');
        } else if ($loginResult == "wrong-password") {
            $errorMessage = "<strong>Chyba přihlášení:</strong> Nesprávné heslo";
        } else if ($loginResult == "not-found") {
            $errorMessage = "<strong>Chyba přihlášení:</strong> Uživatel neexistuje";
        }
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspirations AI Login</title>
    <meta name="color-scheme" content="light dark">
    <meta name="robots" content="noindex" />
    <link rel="icon" href="https://mirror.k-cermak.com/data/logo-v3/favicon.svg">
    <link rel="mask-icon" href="https://mirror.k-cermak.com/data/logo-v3/favicon.svg" color="#000000">
    <link rel="apple-touch-icon" href="https://mirror.k-cermak.com/data/logo-v3/favicon-apple.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-dark-5@1.1.3/dist/css/bootstrap-nightfall.min.css" rel="stylesheet" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
    <body class="d-flex flex-column min-vh-100">
        <div class="container mt-5">      
            <?php
                if ($errorMessage) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $errorMessage . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
                if (isset($_GET["logout"])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Byli jste úspěšně odhlášeni.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
            ?>

            <div class="row mt-5">
                <div class="col-md-6 mx-auto">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Přihlášení k Inspirations AI správci</h3>
                        </div>
                        <div class="card-body">
                            <form action="login.php" method="post">
                                <div class="form-group">
                                    <label for="username">Uživatelské jméno:</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Uživatelské jméno" required>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="password">Heslo:</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Heslo" required>
                                </div>
                                <button type="submit" class="btn btn-primary mt-4">Přihlásit se</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- DELETE ALL PARAMS-->
    <script>window.history.replaceState('', '', window.location.pathname);</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>