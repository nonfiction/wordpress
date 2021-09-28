<?php

namespace nf;
use \Timber;

class App {

  private static $path = __DIR__;
  private static $init = false;

  public static function init( $path = false ) {

    // Only run this once
    if ( static::$init ) return;
    static::$init = true;

    // Set path, default to parent directory
    static::$path = ($path) ? $path : dirname( __DIR__, 1 );

    // Load theme directory inside this plugin
    // register_theme_directory( static::$path . '/themes' );

    // Initalize Timber and set the directory
    $timber = new Timber\Timber();

    // Set the default template directories
    //Timber::$dirname = [ '../../blocks', '../../posts', '../../views'  ];
    Timber::$locations = [ 'templates', 'views' ];

    // Support HTML5 by default
    add_action( 'after_setup_theme', function() {
      add_theme_support( 'html5', ['comment-form','comment-list','search-form','gallery','caption'] );
    });

    // Add to settings menu: Reinitalize 
    add_action('admin_menu', function() {
      add_submenu_page( 'options-general.php', 'Flush', 'Flush', 'manage_options', 'flush', [ '\nf\App', 'reflush' ], 100);
    }, 100);

    // Automatically run reinitalize at least once
    add_action('init', function() {
      if ( is_blog_installed() ) {
        if ( '1' !== get_option( 'nf_flushed') ) {
          static::flush();
        }
      }
    });

  }


  public static function enqueue( $manifest_path = false, $path = false ) {
    if ($manifest_path) {
      $path = ($path) ? $path : static::$path;
      $path = str_replace( '//', '/', $path .'/'. $manifest_path );
      new Enqueue( $path );
    }
  }


  public static function import( $resource_paths = [], $path = false ) {
    $path = ($path) ? $path : static::$path;
    foreach( $resource_paths as $resource_path ) {
      $resource_path = str_replace( '//', '/', $path .'/'. $resource_path );
      foreach (glob($resource_path) as $file) {
        require_once $file;
      }
    }
  }

  // Customize dirname array 
  // Timber expects these paths relative to the current theme directory
  public static function views( $locations = [ 'templates', 'views'], $path = false ) {
    $path = ($path) ? $path : static::$path;
    Timber::$locations = array_map(fn($d) => str_replace( '//', '/', $path .'/'. $d ), $locations);     
  }

  public static function flush( $force = false ) {

    // Mark that this has been automatically done once 
    update_option( 'nf_flushed', '1' );

    // Flush post types and reset capablities
    PostType::activate_all();

    // Configure Admin user color setting
    wp_update_user( [ 'ID' => 1, 'admin_color' => 'midnight' ] );

    // Flush object cache
    wp_cache_flush();

  }

  public static function reflush() {
    static::flush();
    echo "<h1>Flush</h1>";
    echo "<p>...done!</p>";
  }

}


/* Utility Functions */

// Inflections available under nf namespace
function pluralize(   ...$args ) { return \ICanBoogie\pluralize(   ...$args ); }
function singularize( ...$args ) { return \ICanBoogie\singularize( ...$args ); }
function underscore(  ...$args ) { return \ICanBoogie\underscore(  ...$args ); }
function hyphenate(   ...$args ) { return \ICanBoogie\hyphenate(   ...$args ); }
function camelize(    ...$args ) { return \ICanBoogie\camelize(    ...$args ); }
function humanize(    ...$args ) { return \ICanBoogie\humanize(    ...$args ); }
function titleize(    ...$args ) { return \ICanBoogie\titleize(    ...$args ); }

// Custom pluralize inflection to ensure uniqueness
function unique_pluralize($word, $word_to_compare = false) {

  // If no word to compare is provided, use the word to pluralized
  $word_to_compare = ($word_to_compare) ? $word_to_compare : $word;

  // Pluralize the word
  $word = pluralize($word);

  // If the word matches the word to compare, add an s or es
  if ( $word == $word_to_compare ) {
    $word .= ( 's' == substr($word, -1) ) ? 'es' : 's';
  }

  return $word;
}


// Attempt to call an Intervention module
// https://github.com/soberwp/intervention
function add_intervention( $key, ...$args ) {
  add_action( 'init', function() use( $key, $args ) {
    if ( function_exists('\Sober\Intervention\intervention') ) {
      \Sober\Intervention\intervention( $key, ...$args );
    }
  });
}


// Read a json file and return an associative array
function import( $path ) {
  if ( is_array($path) ) return $path; 

  if ( !is_string($path) ) return [];
  
  if ( !file_exists($path) ) return [];
  
  if ( strpos($path, '.json') !== false ) {
    return json_decode( file_get_contents($path), true );
  }

  return [];
}

// Merge two json files or arrays together
function merge( $array_or_file_1 = [], $array_or_file_2 = [] ) {
  return array_merge( import($array_or_file_1), import($array_or_file_2) );
}

// Convert an associative array into a CSS string for a style="" attribute
function css($array) {
  return implode('; ', array_map(
    function($k, $v) { return $k . ': ' . $v; }, 
      array_keys($array), 
      array_values($array)
    )
  );
}


// return true if string haystack starts with needle
function starts_with( $haystack, $needle ) {
  $length = strlen( $needle );
  return substr( $haystack, 0, $length ) === $needle;
}

// return true if string haystack ends with needle
function ends_with( $haystack, $needle ) {
  $length = strlen( $needle );
  if( !$length ) {
    return true;
  }
  return substr( $haystack, -$length ) === $needle;
}


// Display the value in QueryMonitor or to the screen
function log($value, $var_dump = false) {
  do_action( 'qm/debug', $value );
  if ($var_dump) {
    echo "<br>";
    echo "<hr>";
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
    echo "<hr>";
    echo "<br>";
  }
}
