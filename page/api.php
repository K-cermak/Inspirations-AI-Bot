<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    require_once 'db.php';
    session_start();

    do {
        if (!isset($_GET["api"])) {
            die("Error: API key not found.");
        }
        if (getVariable("apiKey") != $_GET["api"]) {
            die("Error: API key not correct.");
        }

        if (!isset($_GET["action"])) {
            die("Error: Action not found.");
        }

        $action = $_GET["action"];
        if ($action == "getId") {
            $post = getRandomPost();
            echo json_encode($post);
            break;
        }

        if ($action == "delete") {
            if (!isset($_GET["id"])) {
                die("Error: ID not found.");
            }
            $id = $_GET["id"];
            publishPost($id);
            $image = getImage($id);
            unlink("images/" . $image);
            echo "done";
            break;
        }

        if ($action == "getVar") {
            if (!isset($_GET["var"])) {
                die("Error: Variable not found.");
            }
            $var = $_GET["var"];
            echo getVariable($var);
            break;
        }

    } while (false);

?>