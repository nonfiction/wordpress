<?php
/**
 * The advanced-cache.php drop-in file for Cache Enabler.
 *
 * The advanced-cache.php creation method uses this during the disk setup and
 * requirements check. You can copy this file to the wp-content directory and
 * edit the $cache_enabler_constants_file value as needed.
 *
 * @since   1.2.0
 * @change  1.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}


// Tweaked dropin for Cache Enabler
// Helps clear local disk cache on all nodes in docker swarm
//
function on_cache_clear( $clear = [] ) {
  if ( is_user_logged_in() ) {

    // Clear out the cache file
    $cache_dir = dirname( __file__ ) . '/cache/';
    array_map( 'unlink', glob( $cache_dir . 'nf_cache_*' ) );

    // Also clear out the cached settings file
    $settings_dir = dirname( __file__ ) . '/settings/cache-enabler/';
    array_map( 'unlink', glob( $settings_dir . '*.php' ) );

    // Save the current cache clear instructions
    $nf_cache = [ 'file' => 'nf_cache_'.time(), 'clear' => $clear, ];
    set_transient('nf_cache', $nf_cache); 
    touch( $cache_dir . $nf_cache['file'] );

  }
}

// Hooks triggered when cache cleared
add_action( 'cache_enabler_page_cache_cleared', function( $url, $id, $index ) {
  on_cache_clear([ 'url' => $url, 'post' => $id ]);
}, 10, 3 );


add_action( 'cache_enabler_site_cache_cleared', function( $url, $id, $index ) {
  on_cache_clear([ 'site' => $id ]);
}, 10, 3 );


add_action( 'cache_enabler_complete_cache_cleared', function() {
  on_cache_clear([ 'full' => true ]);
}, 10, 3);


// Try to clear cache on every page load if necessary
// Check transient (redis) if there's clearing to do
add_action('init', function() {

  $cache_dir = dirname( __file__ ) . '/cache/';
  $file = get_transient('nf_cache')['file'] ?? 'nf_cache_new'; 

  if ( ! file_exists( $cache_dir . $file ) ) {

    foreach( get_transient('nf_cache')['clear'] ?? [] as $type => $arg ) {

      if ( $type == 'url' ) {
        do_action( 'cache_enabler_clear_page_cache_by_url', $arg );

      } elseif ( $type == 'post' ) {
        do_action( 'cache_enabler_clear_page_cache_by_post', $arg );
      
      } elseif ( $type == 'site' ) {
        do_action( 'cache_enabler_clear_site_cache', $arg );

      } elseif ( $type == 'full' ) {
        do_action( 'cache_enabler_clear_complete_cache' );
      }

    }

    touch( $cache_dir . $file );

  }

});


$cache_enabler_dir = dirname( __FILE__ ) . '/plugins/cache-enabler';
$cache_enabler_constants_file = $cache_enabler_dir . '/constants.php';

if ( file_exists( $cache_enabler_constants_file ) ) {
  require $cache_enabler_constants_file;

  $cache_enabler_engine_file = $cache_enabler_dir . '/inc/cache_enabler_engine.class.php';
  $cache_enabler_disk_file   = $cache_enabler_dir . '/inc/cache_enabler_disk.class.php';

  if ( file_exists( $cache_enabler_engine_file ) && file_exists( $cache_enabler_disk_file ) ) {
    require_once $cache_enabler_engine_file;
    require_once $cache_enabler_disk_file;

    if ( Cache_Enabler_Engine::start() && ! Cache_Enabler_Engine::deliver_cache() ) {
      Cache_Enabler_Engine::start_buffering();
    }
  }
}
