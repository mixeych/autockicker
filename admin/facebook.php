<?php 

?>
<div class="wrap">
        <form action="options.php" method="POST">
            <?php settings_fields( 'fb-settings' ); ?>           
            <?php do_settings_sections( 'facebook-settings' ); ?>
            <?php submit_button(); ?>
        </form>
        <?php 
        $url_callback = admin_url();
        $url_get_access = plugins_url()."/autokicker/facebook/";
        		global $helper;
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
			    echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
        ?>
    
</div>