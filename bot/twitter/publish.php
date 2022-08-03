<?php
    require_once("keys.php");
    require_once("vendor/autoload.php");

    function publishTwitter($text, $imageName) {
        $imageLocation = __DIR__ . "/images/" . $imageName;

        $credentials = array(
            'consumer_key' => CONSUMER_KEY,
            'consumer_secret' => CONSUMER_SECRET,
            'token_identifier' => ACCESS_TOKEN,
            'token_secret' => ACCESS_TOKEN_SECRET,
        );

        $twitter = new \Coderjerk\BirdElephant\BirdElephant($credentials);
        $image = $twitter->tweets()->upload($imageLocation);

        $media = (new \Coderjerk\BirdElephant\Compose\Media)->mediaIds(
            [
                $image->media_id_string
            ]
        );

        echo "Tweeted: " . $text . "<br>";
    
        $tweet = (new \Coderjerk\BirdElephant\Compose\Tweet)->text($text)
            ->media($media);
        $twitter->tweets()->tweet($tweet);
    }
?>