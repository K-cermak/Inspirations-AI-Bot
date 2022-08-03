<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    include '../db.php';
    require_once __DIR__ . '/vendor/autoload.php';

    // facebook credentials array
    $creds = array (
        'app_id' => getVariable("fbAppId"),
        'app_secret' => getVariable("fbAppSecret"),
        'default_graph_version' => getVariable("fbGraphVersion"),
        'persistent_data_handler' => 'session'
    );

    print_r($creds);

    $facebook = new Facebook\Facebook( $creds );
    $helper = $facebook->getRedirectLoginHelper();
    $oAuth2Client = $facebook->getOAuth2Client();

    if (isset($_GET['code'])) {
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error ' . $e;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error ' . $e;
        }

        if (!$accessToken->isLongLived()) { // exchange short for long
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken( $accessToken );
            } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
                echo 'Error getting long lived access token ' . $e;
            }
        }

        $accessToken = (string) $accessToken;
        saveVariable("fbApiKey", $accessToken);
        saveVariable("fbLoginTime", date("j.n.Y H:i:s"));
        echo '<meta http-equiv="refresh" content="0; url=../index.php" />';
    } else {
        $permissions = [
            'public_profile', 
            'instagram_basic', 
            'pages_show_list', 
            'instagram_manage_insights', 
            'instagram_manage_comments', 
            'ads_management', 
            'business_management', 
            'instagram_content_publish', 
            'pages_read_engagement'
        ];
        $loginUrl = $helper->getLoginUrl(getVariable("returnUrl"), $permissions );

        echo '<meta http-equiv="refresh" content="0; url='.$loginUrl.'" />';
    }