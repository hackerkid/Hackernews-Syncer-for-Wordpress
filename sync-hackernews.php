<?php
/**
  * Plugin Name: Sync Hacker News
  * Plugin URI: http://vishnuks.com/
  * Description: Advanced Hacker news sync
  * Version: 1.0
  * Author: Vishnu ks
  * Author URI: http://vishnuks.com/
  **/


add_action('admin_menu', 'my_plugin_menu');
add_action( 'admin_init', 'my_plugin_settings' );
//register_activation_hook( __FILE__, 'jal_install' );

function my_plugin_settings() {
register_setting( 'my-plugin-settings-group', 'accountant_name' );
register_setting( 'my-plugin-settings-group', 'accountant_phone' );
register_setting( 'my-plugin-settings-group', 'accountant_email' );
register_setting( 'my-plugin-settings-group', 'accountant_wife' );
} 


function my_plugin_menu() {
add_menu_page('My Plugin Settings', 'Hacker News', 'administrator', 'Hacker-News-Sync', 'hacker_news_syncer', 'dashicons-admin-generic');
}
function hacker_news_syncer() {
	
	hns_insert_post();
?>

<div class="wrap">
<h2>Staff Details</h2>
 
<form method="post" action="options.php">
<?php settings_fields( 'my-plugin-settings-group' ); ?>
<?php do_settings_sections( 'my-plugin-settings-group' ); ?>
<table class="form-table">
<tr valign="top">
<th scope="row">Accountant Name</th>
<td><input type="text" name="accountant_name" value="<?php echo esc_attr( get_option('accountant_name') ); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row">Accountant Phone Number</th>
<td><input type="text" name="accountant_phone" value="<?php echo esc_attr( get_option('accountant_phone') ); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row">Accountant Email</th>
<td><input type="text" name="accountant_email" value="<?php echo esc_attr( get_option('accountant_email') ); ?>" /></td>
</tr>
</table>
<?php submit_button(); ?>
 
</form>
</div> 

<?php
}

function hns_insert_post()
{


	$my_post = array(
  	'post_title'    => 'Epic Post',
  	'post_content'  => 'This is my post.',
  	'post_status'   => 'publish',
  	'post_author'   => 1,
  	'post_category' => array(2)
	);

	wp_insert_post( $my_post );
}

<?php

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'hacketnewposts';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}



/*
function my_project_updated_send_email(	) {

	$my_post = array(
  	'post_title'    => 'My post',
  	'post_content'  => 'This is my post.',
  	'post_status'   => 'publish',
  	'post_author'   => 1,
  	'post_category' => array(8,39)
	);

	wp_insert_post( $my_post );

}

add_action( 'publish_post', 'my_project_updated_send_email' );
*/
?>