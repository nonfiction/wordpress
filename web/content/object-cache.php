<?php
$object_cache_file = dirname( __FILE__ ) . '/mu-plugins/wp-redis/object-cache.php';
if ( file_exists( $object_cache_file ) ) {
  WP_Predis\add_filters();
  require_once $object_cache_file;
}
