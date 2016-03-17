<?php 
/*
Plugin Name: pages out
Plugin URI: 
Author: ci group 
Author URI: 
Description: pages out
*/

function remove_menus(){
  
  remove_menu_page( 'themes.php' );                 //Appearance
  remove_menu_page( 'plugins.php' );                //Plugins
  remove_menu_page( 'tools.php' );                  //Tools
  remove_menu_page( 'options-general.php' );        //Settings
        //Settings
  
}
add_action( 'admin_menu', 'remove_menus' );
?>