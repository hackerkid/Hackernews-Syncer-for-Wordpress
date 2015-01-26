<?php


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

function my_plugin_settings() {
register_setting( 'my-plugin-settings-group', 'accountant_name' );
register_setting( 'my-plugin-settings-group', 'accountant_phone' );
register_setting( 'my-plugin-settings-group', 'accountant_email' );
register_setting( 'my-plugin-settings-group', 'accountant_wife' );
} 

function hns_display_form()
{

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

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'hacketnewposts';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}


function hns_insert_posts()
{

$opts = array(
        'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-language: en\r\n" .
            "Cookie: foo=bar\r\n",
            'proxy' => 'tcp://172.31.1.4:8080',
            )
);

$context = stream_context_create($opts);

$wow = file_get_contents("https://hacker-news.firebaseio.com/v0/topstories.json?print=pretty", false, $context);
$ans = json_decode($wow, true);
$length = count($ans);
echo $length;
for ($i=0; $i < $length ; $i++ ) { 
	$key = $ans[$i];
	$url = 'https://hacker-news.firebaseio.com/v0/item/'.$key.'.json?print=pretty';
	$post = file_get_contents($url, false, $context);
	$content = json_decode($post);
	echo $content->{'by'};
	echo "\n";
	echo $content->{'title'};	
	echo "\n";
	echo $content->{'score'};
	echo "\n";
}


}

function hns_select()
{
	$fivesdrafts = $wpdb->get_results( 
	"
	SELECT id 
	FROM $wpdb->hacketnewposts
	"
	);

	foreach ( $fivesdrafts as $fivesdraft ) 
	{
		echo id;
		echo "\n";
	}

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
