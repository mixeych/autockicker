<?php
session_start();
require_once 'config.php';
$permissions = [
    'email',
    'public_profile',
    'user_friends',
    'user_about_me',
    'user_events',
    'user_posts',
    'user_website',
    'manage_pages',
    'publish_pages',
    'publish_actions'
    ];

$loginUrl = $helper->getLoginUrl($url_get_access.'fb-callback.php', $permissions);
if (!$_SESSION['fb_access_token']){
    header('Location: '.$loginUrl);
} else {
    $access_tokin = $_SESSION['fb_access_token'];
}