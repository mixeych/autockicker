<?php
function postToWall($app_id, $app_secret, $access_tokin, $page_id, $param_r, $fb){
    var_dump($app_id);
    $ch = curl_init('https://graph.facebook.com/v2.5/oauth/access_token?grant_type=fb_exchange_token&client_id='.$app_id.'&client_secret='.$app_secret.'&fb_exchange_token='.$access_tokin);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result_curl = curl_exec($ch);
    $object_fb = json_decode($result_curl);
    $generateLongLivedAccessToken = $object_fb->access_token;

    curl_close($ch);

    $ch1 = curl_init('https://graph.facebook.com/v2.5/me?access_token='.$generateLongLivedAccessToken);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    $result_curl_id = curl_exec($ch1);

    $object_fb_id = json_decode($result_curl_id);
    $getUserID = $object_fb_id->id;
    curl_close($ch1);
    
    $ch2 = curl_init('https://graph.facebook.com/v2.2/'.$getUserID.'/accounts?access_token='.$generateLongLivedAccessToken);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    $result_curl_page_access = curl_exec($ch2);

    $object_fb_page_access = json_decode($result_curl_page_access);
    $getPageAccessTocin = $object_fb_page_access->data[0]->access_token;
    curl_close($ch2);

    $fbApp = new Facebook\FacebookApp($app_id, $app_secret);
    $request = new Facebook\FacebookRequest($fbApp, $getPageAccessTocin, 'POST', '/'.$page_id.'/feed', $param_r);
    try {
      $response = $fb->getClient()->sendRequest($request);
      return $response;
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      return $result_err = 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      return $result_err = 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
}
