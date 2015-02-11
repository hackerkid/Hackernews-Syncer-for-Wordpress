<?php
/**
  * Plugin Name: Advanced Hacker News Syc
  * Plugin URI: https://github.com/hackerkid/Advanced-Hackernews-Wordpress-Sync
  * Description: Gets links from Hacker News satisfying a minimum Karma and post it to the blog automatically. 
  * Version: 1.0
  * Author: Vishnu ks
  * Author URI: http://vishnuks.com/
  **/

require_once("sync-hackernews-functions.php");

global $jal_db_version;
$jal_db_version = '1.0';
register_activation_hook( __FILE__, 'hns_jal_install' );

add_action('admin_menu', 'hns_menu');
add_action( 'admin_init', 'hns_settings' );
add_action( 'wp', 'hns_prefix_setup_schedule' );
add_action( 'hns_prefix_hourly_event', 'hns_prefix_do_this_hourly' );



function hns_menu() {
	add_menu_page('Hacker News Sync Settings', 'Hacker News', 'administrator', 'Hacker-News-Sync', 'hacker_news_syncer', 'dashicons-admin-generic');
}


function hacker_news_syncer() {
	
	
	hns_display_form();
 // hns_core_engine();


}





?>
