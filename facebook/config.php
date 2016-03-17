<?php
if (!isset($_SESSION['fb_access_token'])){
    session_start();
}
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__.'/Facebook/autoload.php';
require_once __DIR__.'/Facebook/FacebookRequest.php';
require_once __DIR__.'/Facebook/FacebookApp.php';
require_once __DIR__.'/Facebook/FacebookBatchRequest.php';
require_once __DIR__.'/Facebook/FacebookBatchResponse.php';
require_once __DIR__.'/Facebook/FacebookClient.php';
require_once __DIR__.'/Facebook/FacebookResponse.php';
require_once __DIR__.'/Facebook/SignedRequest.php';


require_once 'test_r.php';

use Facebook\FacebookRequest;

global $app_id, $app_secret, $page_id, $fb, $param_r, $helper;
$val = get_option('facebook_app_id');
$app_id = trim($val['input']);

$val = get_option('facebook_app_secret');
$app_secret = trim($val['input']);

$val = get_option('facebook_url');
$page_id = trim($val['input']);

$fb = new Facebook\Facebook([
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();
$url_callback = admin_url('admin.php?page=facebook-settings');
//$url_callback = 'http://zend.ci.ua/wptest/wp-admin/admin.php?page=facebook-settings';
$url_get_access = plugins_url()."/autokicker/facebook/";
