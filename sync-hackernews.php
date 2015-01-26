<?php
/**
  * Plugin Name: Sync Hacker News
  * Plugin URI: http://vishnuks.com/
  * Description: Advanced Hacker news sync
  * Version: 1.0
  * Author: Vishnu ks
  * Author URI: http://vishnuks.com/
  **/

require_once("sync-hackernews-functions.php");

global $jal_db_version;
$jal_db_version = '1.0';
register_activation_hook( __FILE__, 'jal_install' );


add_action('admin_menu', 'my_plugin_menu');
add_action( 'admin_init', 'my_plugin_settings' );


function my_plugin_menu() {
	add_menu_page('My Plugin Settings', 'Hacker News', 'administrator', 'Hacker-News-Sync', 'hacker_news_syncer', 'dashicons-admin-generic');
}


function hacker_news_syncer() {
	
	//hns_insert_post();
	hns_select();
	hns_display_form();
}





?>
