<?php
//
require_once($_SERVER['DOCUMENT_ROOT'].'/wptest/wp-load.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
require_once 'config.php';
global $accessToken;
try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (! isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    
   exit;
}
$oAuth2Client = $fb->getOAuth2Client();

$_SESSION['fb_access_token'] = (string) $accessToken;
update_option('fb_access_token', $accessToken);

header('Location: '.$url_callback);

