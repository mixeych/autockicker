<?php 
/*
Plugin Name: Autokicker
Plugin URI: 
Author: ci group 
Author URI: 
Description: Autokicker
*/
require_once("tw.php");
require_once("menu-pages.php");
require_once("cron-class.php");
//require_once("facebook/config.php");
require_once("custom-metaboxes.php");

$plugin_dir = plugin_dir_path( __FILE__ );

define("AUTOKICKER_PATH", $plugin_dir);
global $autokickInterval;
$val = get_option('repost_enqueue_time');
$autokickInterval = $val['input']*60;

global $endpoint;
$val = get_option('posting_times');
$endpoint =  (int)$val['input'];

add_action('publish_post', 'publish_post_to_sn');
function publish_post_to_sn($id){
    global $autokickInterval;
    $post = get_post($id);
    if(empty($post)){
        return;
    }
    autokick_to_twitter($id);
}

add_action('autokick', 'autokick_to_twitter');
function autokick_to_twitter($id){
        global $endpoint;
        global $autokickInterval;
        global $fb;
        $val = get_option('facebook_app_id');
        $app_id = trim($val['input']);

        $val = get_option('facebook_app_secret');
        $app_secret = trim($val['input']);

        $val = get_option('facebook_url');
        $page_id = trim($val['input']);
        $cr = new TwitterShedules($id);
        $times = $cr->times;
        if($times >= $endpoint){
            return false;
        }
        $tw = new AutokickTwitter();
        $tw->doPost($id);
        $curr_time = time();
        $next_time = $curr_time+$autokickInterval;
        $cr->add_shedule($next_time);
        $post = get_post($id);
        $img_id = get_post_thumbnail_id($id);
        $img = wp_get_attachment_image_src($img_id);
        if(!$img){
            $img = '';
        }else{
            $img = $img[0];
        }
        $title = $post->post_title;
        $meta = get_post_meta($id, 'repeatable_fields', true);
        if(empty($meta)){
            $title = $post->post_title;
        }else{
            if(!empty($times) and $times>0){
                $i = $times-1;
                $title = $meta[$i]["name"];
            }
            if(empty($title)){
                $title = $post->post_title;
            }
        }
        $access_tokin = get_option('fb_access_token');
        $clean_messege = strip_tags($post->post_content);
        $param_r = array(
            'message' => $clean_messege, 
            'name' => $title, 
            'description' => $clean_messege,
            'link' => $post->guid,
            'picture' => $img
        );
        //$res = postToWall($app_id, $app_secret, $access_tokin, $page_id, $param_r, $fb);
        $times++;
        update_post_meta($id, 'autokick_times', $times);
}

function get_img_path($post_id){
    $post = get_post($post_id);
    if(empty($post)){
        return false;
    }
    $img_id = get_post_thumbnail_id($post->ID);
    if(empty($img_id)){
        return false;
    }
    $img = wp_get_attachment_image_src($img_id);
    $arr = explode('/', $img[0]);
    $basedir = wp_upload_dir();
    $basedir = $basedir['basedir'];
    $t = false;
    foreach ($arr as $elem){
        if($elem == 'uploads'){
            $t = true;
            continue;
        }
        if($t){
            $basedir .= '/'.$elem;
        }
    } 
    return $basedir;
}

function base_64_img($img_path){
    $type = pathinfo($img_path, PATHINFO_EXTENSION);
    if(!is_file($img_path)){
        return false;
    }
    $data = file_get_contents($img_path);
    $base64 = base64_encode($data);
    return $base64;
}


function string_to_twitter($content){
    $len = strlen($content);
    if(!$len){
        return false;
    }
    if($len<139){
        return $content;
    }
    $arr = explode(" ", $content);
    $res = '';
    $preres = '';
    foreach($arr as $word){
        $res_len = strlen($res);
        if($res_len == 139){
            return $res;
        }elseif($res_len < 139){
            $preres = $res;
        }elseif($res_len > 139){
            return $preres;
        }
        $res .= $word.' ';
    }
    return $res;
}

function clean_link($content){
    $reg = '/<a\b[^>]*>(.*?)<\/a>/';
    $match = preg_match($reg, $content);
    if(!$match){
        return $content;
    }
    $newContent;
    $newContent = preg_replace_callback($reg, 
    function($matches){
        $regL = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        $regS = '/[\'|"]/';
        $arr = preg_split($regS, $matches[0]);
        if(is_array($arr)){
            foreach($arr as $substr){
                $match = preg_match($regL, $substr);
                if($match){
                    return $substr;
                }
            }
        }
    } ,$content);
    return $newContent;
}