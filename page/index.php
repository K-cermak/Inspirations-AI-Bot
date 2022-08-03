<?php
    require_once 'db.php';
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        die();
    }

    $errorMessage = "";
    $successMessage = "";
    
    if ($_POST) {
        $igText = $_POST['ig-text'];
        $twitterText = $_POST['twitter-text'];
        $name = generateRandomId();
        
        $file = $_FILES["file"]["tmp_name"];
        $fileName = $_FILES["file"]["name"];
        $fileType = $_FILES["file"]["type"];
        $fileSize = $_FILES["file"]["size"];
        $fileError = $_FILES["file"]["error"];

        //check if .png
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('png');


        if ($fileError === 0) {
            if (in_array($fileActualExt, $allowed)) {
                $fileNameNew = $name . "." . $fileActualExt;
                $fileDestination = 'uploads/' . $fileNameNew;
                move_uploaded_file($file, "images/" . $fileNameNew);

                insertNewPost($igText, $twitterText, $fileNameNew);

                $successMessage = "Příspěvek vytvořen úspěšně.";
            } else {
                $errorMessage = "Nepovolený typ souboru.";
            }
        } else {
            $errorMessage = "Chyba při nahrávání souboru.";
        }
    }

    function generateRandomId() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspirations AI - Instagram BOT</title>
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
<body>
    <script>
        function openModal(id) {
            let myModal = new bootstrap.Modal(document.querySelector('#' + id));
            myModal.show();
        }

        //disable form resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <style>
        .row {
            margin: 0;
        }

    </style>


    <header class="pt-3 pb-2 mb-4 dark bg-dark">
        <div class="row">
            <div class="col-md-6">
                <h1>Inspirations AI - Instagram BOT</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="logout.php" class="btn btn-danger">Odhlásit se</a>
            </div>
        </div>
    </header>

    <?php
        if ($errorMessage) {
            echo '<div class="container"><div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">' . $errorMessage . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>';
        }
        if ($successMessage) {
            echo '<div class="container"><div class="alert alert-success alert-dismissible fade show mt-4" role="alert">' . $successMessage . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>';
        }
    ?>

    <div class="container text-center">
        <h3>Počet zadaných příspěvků: <?php echo numberOfPosts(); ?></h3>
        <h3>Vystačí na cca: <?php
        $days = numberOfPosts() / 4; 
        echo round($days);
        if ($days == 1) {
            echo " den";
        } else if ($days < 5 && $days > 1) {
            echo " dny";
        } else {
            echo " dní";
        } ?></h3>
        <h3>Autorizace přes FB proběhla: <?php echo getVariable("fbLoginTime") ?></h3>

        <?php // date("j.n.Y H:i:s")  ?>


    </div>


    <div class="container text-center">
        <h4 class="btn btn-primary m-5" onclick="openModal('addNewPost')"><i class="bi bi-file-earmark-plus"></i> Vytvořit nový příspěvek</h4>
    </div>


    <div class="modal fade" id="addNewPost">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Vytvořit příspěvek</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="index.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="ig-text">Popisek pro IG:</label>
                            <textarea class="form-control" id="ig-text" name="ig-text" required>#art #artist #ai #inspiration #inspirations #images</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="twitter-text">Popisek pro Twitter:</label>
                            <input type="text" class="form-control" id="twitter-text" name="twitter-text" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="file">Soubor:</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".png" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Vytvořit příspěvek</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>