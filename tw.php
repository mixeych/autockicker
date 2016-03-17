<?php 
require_once ('twitter/twitteroauth-0.6.2/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;
class AutokickTwitter
{
    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;

    public $connection;
    
    public function __construct() 
    {
        $host = 'api.twitter.com';
        $method = 'GET';
        $path = '/1.1/statuses/user_timeline.json';
        $val = get_option('twitter_api_key');
        $val = $val['input'];
        $this->consumer_key = $val;
        $val = get_option('twitter_api_secret');
        $val = $val['input'];
        $this->consumer_secret = $val;
        $val = get_option('twitter_access_token');
        $val = $val['input'];
        $this->access_token = $val;
        $val = get_option('twitter_access_token_secret');
        $val = $val['input'];
        $this->access_token_secret = $val;
        $this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret);
        $content = $this->connection->get('account/verify_credentials');
    }
    
    public function doPost($post_id)
    {
        $imgPath = get_img_path($post_id);
        $post = get_post($post_id);
        if(empty($post)){
            return false;
        }
        $meta = get_post_meta($post_id, 'repeatable_fields', true);
        if(empty($meta)){
            $title = $post->post_title;
        }else{
            $times = get_post_meta($post_id, 'autokick_times', true);
            if(!empty($times) and $times>0){
                $i = $times-1;
                $title = $meta[$i]["name"];
            }
            if(empty($title)){
                $title = $post->post_title;
            }
        }
        $content = $post->post_content;
        $link = $post->guid;
        $path = get_img_path($post_id);
        if($path){
            $media = $this->connection->upload('media/upload', array('media' => $path));
            $parameters = array(
                'status' => $title." - ".$link,
                'media_ids' => $media->media_id_string
            );
        }else{
            $parameters = array(
                'status' => $title." - ".$link,
            );
        }
        $result = $this->connection->post('statuses/update', $parameters);
        return $result;
    }
}
?>