<?php 

function admin_my_menu(){
    add_menu_page('Autokicker settings', 'Autokicker', 'manage_options', 'autokicker', 'autokicker_page');
    //add_submenu_page('autokicker', 'Facebook settings', 'Facebook settings', 'manage_options', 'facebook-settings', "facebook_settings_page");
    add_submenu_page('autokicker', 'Twitter settings', 'Twitter settings', 'manage_options', 'twitter-settings', "twitter_settings_page");

}

add_action( 'admin_menu', 'admin_my_menu' );

function autokicker_page(){
    ?>
    <div class="wrap">
        <form action="options.php" method="POST">
            <?php settings_fields( 'ak-settings' ); ?>           
            <?php do_settings_sections( 'autokicker' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function facebook_settings_page(){
    require_once(AUTOKICKER_PATH.'admin/facebook.php');
}

function twitter_settings_page(){
    require_once(AUTOKICKER_PATH.'admin/twitter.php');
}

add_action('admin_init', 'add_download_setting');
function add_download_setting(){
    register_setting( 'fb-settings', 'facebook_url' );
    register_setting( 'fb-settings', 'facebook_app_id' );
    register_setting( 'fb-settings', 'facebook_app_secret' );
    register_setting( 'tw-settings', 'twitter_url' );
    register_setting( 'tw-settings', 'twitter_api_key' );
    register_setting( 'tw-settings', 'twitter_api_secret' );
    register_setting( 'tw-settings', 'twitter_access_token' );
    register_setting( 'tw-settings', 'twitter_access_token_secret' );
    register_setting( 'ak-settings', 'repost_enqueue_time' );
    register_setting( 'ak-settings', 'posting_times' );
    add_settings_section( 'facebook-section', '', '', 'facebook-settings' );
    add_settings_section( 'twitter-section', '', '', 'twitter-settings' );
    add_settings_section( 'autokicker-section', '', '', 'autokicker' );
    add_settings_field(
        'repost_enqueue_time',
        'Interval (min)',
        'repost_enqueue_time_callback',
        'autokicker',
        'autokicker-section'
    );
    add_settings_field(
        'posting_times',
        'Times',
        'posting_times_callback',
        'autokicker',
        'autokicker-section'
    );
    add_settings_field(
        'facebook_url',
        'Facebook group id',
        'facebook_url_callback',
        'facebook-settings',
        'facebook-section'
    );
    add_settings_field(
        'facebook_app_id',
        'Facebook App ID',
        'facebook_app_id_callback',
        'facebook-settings',
        'facebook-section'
    );
    add_settings_field(
        'facebook_app_secret',
        'Facebook App Secret',
        'facebook_app_secret_callback',
        'facebook-settings',
        'facebook-section'
    );
    add_settings_field(
        'twitter_api_key',
        'Twitter API Key',
        'twitter_api_key_callback',
        'twitter-settings',
        'twitter-section'
    );
    add_settings_field(
        'twitter_api_secret',
        'Twitter API Secret',
        'twitter_api_secret_callback',
        'twitter-settings',
        'twitter-section'
    );
    add_settings_field(
        'twitter_access_token',
        'Twitter Access Token',
        'twitter_access_token_callback',
        'twitter-settings',
        'twitter-section'
    );

    add_settings_field(
        'twitter_access_token_secret',
        'Twitter Access Token Secret',
        'twitter_access_token_secret_callback',
        'twitter-settings',
        'twitter-section'
    );
    
}

function facebook_url_callback(){
    $val = get_option('facebook_url');
    $val = $val['input'];
    ?>
    <input class="fb-url" name="facebook_url[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}
function facebook_app_id_callback(){
    $val = get_option('facebook_app_id');
    $val = $val['input'];
    ?>
    <input class="fb-app-id" name="facebook_app_id[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}
function facebook_app_secret_callback(){
    $val = get_option('facebook_app_secret');
    $val = $val['input'];
    ?>
    <input class="fb-app-secret" name="facebook_app_secret[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

function twitter_url_callback(){
    $val = get_option('twitter_url');
    $val = $val['input'];
    ?>
    <input class="tw-url" name="twitter_url[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

function twitter_api_key_callback(){
    $val = get_option('twitter_api_key');
    $val = $val['input'];
    ?>
    <input class="tw-api-key" name="twitter_api_key[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

function twitter_api_secret_callback(){
    $val = get_option('twitter_api_secret');
    $val = $val['input'];
    ?>
    <input class="tw-api-secret" name="twitter_api_secret[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

function twitter_access_token_callback(){
    $val = get_option('twitter_access_token');
    $val = $val['input'];
    ?>
    <input class="tw-access-token" name="twitter_access_token[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

function twitter_access_token_secret_callback(){
    $val = get_option('twitter_access_token_secret');
    $val = $val['input'];
    ?>
    <input class="tw-access-token-secret" name="twitter_access_token_secret[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

function repost_enqueue_time_callback(){
    $val = get_option('repost_enqueue_time');
    $val = $val['input'];
    ?>
    <input class="repost-enqueue-time" name="repost_enqueue_time[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

function posting_times_callback(){
    $val = get_option('posting_times');
    $val = $val['input'];
    ?>
    <input class="posting-times" name="posting_times[input]" type="text" value="<?php echo esc_attr( $val ) ?>" />
    <?php
}

?>