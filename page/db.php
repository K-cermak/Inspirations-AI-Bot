<?php
    require_once("tokens.php");

    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    function verifyLogin($name, $password) {
        global $db;
        $sql = "SELECT * FROM users WHERE name = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows == 0) {
            return "not-found";
        }
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            return true;
        } else {
            return "wrong-password";
        }
    }

    function insertNewPost($ig, $twitter, $fileName) {
        global $db;
        $sql = "INSERT INTO posts (ig, twitter, fileName) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sss", $ig, $twitter, $fileName);
        $stmt->execute();
        $stmt->close();
    }

    function numberOfPosts() {
        global $db;
        $sql = "SELECT * FROM posts WHERE published = 0";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows;
    }

    function getVariable($name) {
        global $db;
        $sql = "SELECT * FROM vars WHERE name = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $row = $result->fetch_assoc();
        return $row['value'];
    }

    function saveVariable($name, $value) {
        global $db;
        $sql = "UPDATE vars SET value = ? WHERE name = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ss", $value, $name);
        $stmt->execute();
        $stmt->close();
    }

    function getRandomPost() {
        global $db;
        $sql = "SELECT * FROM posts WHERE published = 0 ORDER BY RAND() LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $row = $result->fetch_assoc();
        return $row;
    }

    function publishPost($id) {
        global $db;
        $sql = "UPDATE posts SET published = 1 WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    function getImage($id) {
        global $db;
        $sql = "SELECT * FROM posts WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $row = $result->fetch_assoc();
        return $row['fileName'];
    }
?>