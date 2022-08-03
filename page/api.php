<?php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    } while (false);

?>