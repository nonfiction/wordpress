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
    $timber = \Timber\Timber::init();

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

/**
 * If you have a callback you want to run on multiple actions, pass them here.
 *
 * @param array $tags
 * @param callable $function_to_add
 * @param int $priority
 * @param int $accepted_args
 */
function add_actions( array $tags, $function_to_add, $priority = 10, $accepted_args = 1 ) {
  foreach ( $tags as $tag ) {
    add_action( $tag, $function_to_add, $priority, $accepted_args );
  }
}

function add_ajax_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
  add_action( "wp_ajax_{$tag}", $function_to_add, $priority, $accepted_args );
  add_action( "wp_ajax_nopriv_{$tag}", $function_to_add, $priority, $accepted_args );
}


function sanitize_param($param) {

  // Get recursive in arrays
  if ( is_array($param) ) { 
    foreach($param as $key => $val) {
      $param[$key] = sanitize_param($val);
    }

  } else {

    // Sanitize the input as a string
    $param = sanitize_text_field( $param );

    // Empty strings or the word "null" are null
    if ( ( $param == '' ) || ( strtolower($param) == 'null' ) ) {
      $param = null;

    // The word "false" becomes boolean
    } elseif ( strtolower($param) == 'false' ){
      $param = false;

    // The word "true" becomes boolean
    } elseif ( strtolower($param) == 'true' ) {
      $param = true;

    // String that look like a number become one
    } elseif ( is_numeric($param) ) { 
      $param = $param + 0; 
    }

  }

  return $param;

}

// Get value from $_REQUEST by key and cast as intended type
function get_param($name, $default = null) {
  return sanitize_param( $_REQUEST[$name] ?? $default );
}

// Convert an associative array to comma-separated string
// one:1,two:2,three:3
function csv($input = []) {
  $output = [];
  foreach( $input as $key => $val) {
    if ($val) {
      $output[] = "{$key}:{$val}";
    }
  }
  return join(',', $output);
}

function validate_date($date, $format = 'Y-m-d') {
  $d = \DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
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


// return map( $results, fn($id, $body) => [ $id => new Traveller($body) ] );
// ...or...
// return map( $results, function( $id, $body ) {
//   return [ $id => new Traveller( $body ) ];
// } );
function map(array $list, callable $cb, $group_by = false) {

  $all = [];
  foreach (($list ?? []) as $key => $val) {

    // Try to find id/key within $val, fallback on $key
    $id = $val['id'] ?? $val['_id'] ?? $val['key'] ?? $val['_key'] ?? $key;

    // Get pair from callback
    $pair = $cb( $id, $val ) ?? [ $key => $val ];
    if (!$pair) continue;
    $pair_key = array_key_first( $pair );

    // Optionally group pairs by one of the keys
    if ($group_by) {
      $group = $val[$group_by];
      $all[$group] ??= [];
      $all[$group][$pair_key] = $pair[$pair_key];

    // Otherwise, just return associative array organized by pair's key
    } else {
      $all[$pair_key] = $pair[$pair_key];
    }

  }
  return $all;

}


// Recursively change all keys from camelCase to camel_case
function underscore_keys( $old = [] ) {
  if ( ! is_array($old) ) return $old;
  $new = [];
  foreach( $old as $key => $val ) {
    $val = ( is_array($val) ) ? underscore_keys( $val ) : $val;
    $key = ( is_numeric($key) ) ? $key : underscore( $key );
    $new[$key] = $val;
  }
  return $new;
}


// Similar to empty() but anything numeric (including 0) is not empty
function is_empty( $value = false ) {
  if ( is_numeric($value) ) return false;
  if ( empty($value) ) return true;
  return false;
}

function isnt_empty( $value = false ) {
  return (! is_empty($value) );
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


function nosp( $string ) {
  return preg_replace( '/\s+/', '', strval($string) );
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

function dump($value) {
  echo "<br>";
  echo "<hr>";
  echo "<pre>";
  var_dump($val);
  echo "</pre>";
  echo "<hr>";
  echo "<br>";
}
