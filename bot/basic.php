<?php
    require_once("tokens.php");
    require_once("twitter/publish.php");

    //send post request to URL_BASE with API_KEY and action=getId
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, URL_BASE . "?api=" . API_KEY . "&action=getId");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    $post = json_decode($result, true);

    if ($post == "") {
        die("Error: No posts found.");
    } else {
        $id = $post["id"];
        $image = $post["fileName"];
        $igText = $post["ig"];
        $twitterText = $post["twitter"];

        //download file from IMAGE_PATH and save it to images folder
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, IMAGE_PATH . $image);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $file = fopen("twitter/images/" . $image, "w");
        fwrite($file, $result);
        fclose($file);

        //publish tweet with image and text
        publish($twitterText, $image);


        //delete image
        unlink("twitter/images/" . $image);
    }


    //send to instagram api

    //wait 1 minuty
    //delete request
?>