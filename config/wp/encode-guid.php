<?php
namespace nf;

// Store the guid as a base64 encoded value, instead of as a plain-text URL
function encode_guid( $post_id ) {

  $guid = get_the_guid( $post_id );

  // The guid is unencoded if it still starts with "http"
  if ( substr( $guid, 0, 4 ) === 'http' ) {

    // Update it directly in the db, since Wordpress doesn't really provide another way
    global $wpdb;
    $wpdb->update($wpdb->posts, ['guid' => base64_encode( $guid )], ['ID' => $post_id]);

  }

}

add_action( 'save_post', 'nf\encode_guid');
add_action( 'add_attachment', 'nf\encode_guid');
add_action( 'edit_attachment', 'nf\encode_guid');
