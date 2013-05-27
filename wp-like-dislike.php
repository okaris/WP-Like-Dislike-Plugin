<?php
/*
Plugin Name: WP Like Dislike
Plugin URI: http://okaris.com/wp-like-dislike
Description: This plugin helps you create a powerful like & dislike system for your posts.
Version: 1.0a
Author: Okaris
Author URI: http://okaris.com
License: GPL2
*/


global $wpld_db_version;
$wpld_db_version = "1.0";

register_activation_hook( __FILE__, 'wpld_install' );
register_deactivation_hook(__FILE__, 'wpld_deactivate');
register_uninstall_hook(__FILE__, 'wpld_uninstall');

function wpld_install() {
   global $wpdb;
   global $wpld_db_version;

   $table_name = $wpdb->prefix . "wpld";
      
   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  liked tinyint(4),
  disliked tinyint(4),
  post_id int(10),
  comment_id int(10),
  member_id int(10),
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  ip_address VARCHAR(255),
  hashkey VARCHAR (32),
  UNIQUE KEY id (id),
  UNIQUE hashkey (hashkey)
    );";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
 
   add_option( "wpld_db_version", $wpld_db_version );
}

function wpld_deactivate() {

}

function wpld_uninstall() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpld';

    $wpdb->query("DROP TABLE IF EXISTS $table_name ");
}

function wpld_like_or_dislike($post_id, $action = 'like')
{
  global $current_user;
  $user_id = $current_user->ID;
  $liked = 0;
  $disliked = 0;
  $comment_id = '';
  $time = date('Y-m-d H:i:s');
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $hashkey = md5($this->member_id.$entry_id);

  if ($action == 'like') {
    $liked = 1;
  }
  else if ($action == 'dislike')
  {
    $disliked = 1;
  }

  $table_name = $wpdb->prefix . "wpld";

  $wpdb->query( $wpdb->prepare( "
    INSERT INTO $table_name
    ( id, liked, disliked, post_id, comment_id, member_id, time, ip_address, hashkey)
    VALUES 
    ( %s, %d, %d, %s, %d, %d, %d, %s, %s, %s) 
    ON DUPLICATE KEY UPDATE liked = %d, disliked = %d", 
        '', $liked, $disliked, $post_id, $comment_id, $user_id, $time, $ip_address, $hashkey, $liked, $disliked ));

  $previous_like_value = get_post_meta( $post_id, $key = 'likes', $single = true );
  if ($action == 'like') {
  update_post_meta( $post_id, 'likes', $previous_like_value + 1 );
  }
  else if ($action == 'dislike')
  {
  update_post_meta( $post_id, 'likes', $previous_like_value - 1 );
  }
}

?>

