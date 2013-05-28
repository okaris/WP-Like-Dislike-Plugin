<?php
require_once '../../../wp-config.php';

$post_id = $_POST['post_id'];
$wpld_action = $_POST['wpld_action'];

//get setting data
$is_logged_in = is_user_logged_in();
$current_user = wp_get_current_user();
$user_id = (int)$current_user->ID;
$liked = 0;
$disliked = 0;
$comment_id = '';
$time = date('Y-m-d H:i:s');
$ip_address = $_SERVER['REMOTE_ADDR'];
$hashkey = md5($user_id.$post_id);

if ($wpld_action == 'like') {
	$liked = 1;
}
else if ($wpld_action == 'dislike')
{
	$disliked = 1;
}

$table_name = $wpdb->prefix . "wpld";

$affected_rows = $wpdb->query( $wpdb->prepare( "
	INSERT IGNORE INTO $table_name
	( id, liked, disliked, post_id, comment_id, member_id, time, ip_address, hashkey)
	VALUES 
	( %s, %d, %d, %d, %d, %d, %s, %s, %s) 
	ON DUPLICATE KEY UPDATE liked = %d, disliked = %d", 
	'', $liked, $disliked, $post_id, $comment_id, $user_id, $time, $ip_address, $hashkey, $liked, $disliked));

if ($affected_rows != 0 && $affected_rows!==FALSE) {
	$previous_like_value = get_post_meta( $post_id, $key = 'likes', $single = true );
	if ($wpld_action == 'like') {
		update_post_meta( $post_id, 'likes', $previous_like_value + 1 );
		echo "Liked";
		die();
	}
	else if ($wpld_action == 'dislike')
	{
		update_post_meta( $post_id, 'likes', $previous_like_value - 1 );
		echo "Disliked";
		die();
	}
	echo "Mysql query succeeded but there was a problem updating post meta";
	die();

}else{
	echo "No Change";
	die();
}