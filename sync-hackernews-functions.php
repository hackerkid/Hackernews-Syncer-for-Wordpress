<?php


function hns_insert_post($title, $content, $category)
{

	
	$my_post = array(
  	'post_title'    => $title,
  	'post_content'  => $content,
  	'post_status'   => 'publish',
  	'post_author'   => 1,
  	'post_category' => array($category)
	);

	wp_insert_post( $my_post );
}

function my_plugin_settings() {
register_setting( 'my-plugin-settings-group', 'minimum-karma' );
register_setting( 'my-plugin-settings-group', 'category' );
register_setting( 'my-plugin-settings-group', 'frequency' );
} 

function hns_display_form()
{

?>
<div class="wrap">
<h2>Advanced Hacker News Sync Settings</h2>
 
<form method="post" action="options.php">
<?php settings_fields( 'my-plugin-settings-group' ); ?>
<?php do_settings_sections( 'my-plugin-settings-group' ); ?>
<table class="form-table">
<tr valign="top">
<th scope="row">Minimum Karma of the links</th>
<td><input type="text" name="minimum-karma" value="<?php echo esc_attr( get_option('minimum-karma', 50) ); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">When should be updated</th>
<td><select type="text" name="frequency" value="<?php echo esc_attr( get_option('frequency', 'hourly') ); ?>" >
<option <?php if(get_option('frequency', 'hourly') == "hourly") { echo 'selected="selected"';}?> value = "hourly">Hourly</option> 
<option <?php if(get_option('frequency', 'hourly') == "twicedaily") { echo 'selected="selected"';}?> value = "twicedaily">Twice Daily</option> 
<option <?php if(get_option('frequency', 'hourly') == "daily") { echo 'selected="selected"';}?> value = "daily">Daily</option> 
</select>


</td>
</tr>

<tr valign="top">
<th scope="row"> Category of the post </th>
<td>
<select name='category' id='cat' class='postform' >
		
<?php
	 $categories = get_categories(); 
	 foreach ($categories as $category) {
?>
	<option class="level-0" <?php if(get_option('category') == $category->cat_ID) { echo 'selected="selected"';}?> value="<?php echo $category->cat_ID; ?>"><?php echo $category->cat_name; ?></option>
<?php
	
	}
?>


</select>

</td>
</tr>


</table>
<?php submit_button(); ?>
 
</form>
</div> 
<?php

}

function hns_jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = 'hackernewsposts';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}


function hns_core_engine()
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
$minimum = get_option('minimum-karma', 50);
echo $length;
for ($i=0; $i < 20 ; $i++ ) { 
	$key = $ans[$i];
	$url = 'https://hacker-news.firebaseio.com/v0/item/'.$key.'.json?print=pretty';
	$post = file_get_contents($url, false, $context);
	$content = json_decode($post);
	//echo $content->{'by'};
	//echo "\n";
	$title = $content->{'title'};
	$body = $content->{'url'};
	echo $content->{'title'};	
	echo "\n";
	$score = $content->{'score'};
	$id = $content->{'id'};
	echo $id;
	echo "\n";
	
	if(hns_select($id) or $score < $minimum) {
		echo "awesome NOTHING to do";
	}

	else {
		$category = get_option('category');	
		$body = "<a href = '$body'>$title</a>";
		hns_insert_post($title, $body, $category);
		hns_add_this_id($id);
	}

}


}

function hns_add_this_id($id)
{
	global $wpdb;
	$wpdb->query("INSERT into hackernewsposts (id) VALUES('$id')");
}

function hns_select($id)
{	global $wpdb;
	$fivesdrafts = $wpdb->get_results( 
	"
	SELECT id 
	FROM hackernewsposts where id = '$id'
	"
	);
	$count = 0;
	foreach ( $fivesdrafts as $fivesdraft ) 
	{
		echo $fivesdraft->id;
		echo "\n";
		$count++;
	}

	return $count;

}


function hns_prefix_setup_schedule() {
	$freq = get_option('frequency', 'hourly');
	if ( ! wp_next_scheduled( 'hns_prefix_hourly_event' ) ) {
		wp_schedule_event( time(), $freq, 'hns_prefix_hourly_event');
	}
}



function hns_prefix_do_this_hourly() {
	hns_core_engine();
}


?>
