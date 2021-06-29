<?php
/**
 * Your base production configuration goes in this file. Environment-specific
 * overrides go in their respective config/environments/{{WP_ENV}}.php file.
 *
 * A good default policy is to deviate from the production config as little as
 * possible. Try to define as much of your configuration in this file as you
 * can.
 */

use Roots\WPConfig\Config;
use function Env\env;

// Directory containing all of the site's files
$root_dir = dirname(__DIR__);

// Document Root
$webroot_dir = $root_dir . '/web';


// Use Dotenv to set required environment variables and load .env file in root
$dotenv = Dotenv\Dotenv::createUnsafeImmutable($root_dir);
if (file_exists($root_dir . '/.env')) {
  $dotenv->load();
}

// Swarm host primary
$HOST = strtolower(env('HOST'));
Config::define('HOST', $HOST);
$HOST_SLUG = str_replace( [' ', '-', '.'], '_', $HOST );

$APP = strtok($HOST, '.');
Config::define('APP', $APP);
$APP_SLUG = str_replace( [' ', '-', '.'], '_', $APP );

// Set up our global environment constant and load its config first
// Default: production
define('WP_ENV', env('WP_ENV') ?: 'production');

// Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
// See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
  $_SERVER['HTTPS'] = 'on';
}

// URLs
if (env('WP_HOME')) {
  Config::define('WP_HOME', env('WP_HOME'));
  Config::define('WP_SITEURL', env('WP_SITEURL') ?: env('WP_HOME') . '/wp');
} else {
  $http_or_https = ( isset( $_SERVER['HTTPS'] ) ) ? 'https' : 'http';
  Config::define('WP_HOME', "${http_or_https}://" . env('HTTP_HOST'));
  Config::define('WP_SITEURL', "${http_or_https}://" . env('HTTP_HOST') . '/wp');
}

// Custom Content Directory
Config::define('ROOT_DIR', $root_dir);
Config::define('WEBROOT_DIR', $webroot_dir);
Config::define('CONTENT_DIR', '/content');
Config::define('WP_CONTENT_DIR', $webroot_dir . Config::get('CONTENT_DIR'));
Config::define('WP_CONTENT_URL', Config::get('WP_HOME') . Config::get('CONTENT_DIR'));

// DB settings
$DB_NAME_ENV = strtoupper('DB_NAME_'.WP_ENV);
Config::define('DB_NAME', env('DB_NAME') ?: (env($DB_NAME_ENV) ?: $HOST_SLUG));
Config::define('DB_USER', env('DB_USER') ?: $APP_SLUG);
Config::define('DB_PASSWORD', env('DB_PASSWORD'));
Config::define('DB_HOST', env('DB_HOST') ?: 'localhost');
Config::define('DB_CHARSET', 'utf8mb4');
Config::define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

// Authentication Unique Keys and Salts
foreach([ 
  'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 
  'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT', 
] as $key) {
  
  // If a key defined in the env, use that value
  if ( env($key) ) {
    Config::define( $key, env($key) );

  // Otherwise, generate a value
  } else {
    Config::define( $key, hash('sha256', $key . $HOST . env('DB_PASSWORD') ) );
  }
}

// Custom Settings
Config::define('AUTOMATIC_UPDATER_DISABLED', true);
Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: true);
// Disable the plugin and theme file editor in the admin
Config::define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', true);

// Query Monitor Settings
Config::define('QM_DARK_MODE', env('QM_DARK_MODE') ?: true);
Config::define('QM_ENABLE_CAPS_PANEL', env('QM_ENABLE_CAPS_PANEL') ?: true);

// https://querymonitor.com/blog/2018/07/silencing-errors-from-plugins-and-themes/
Config::define('QM_DISABLE_ERROR_HANDLER', env('QM_DISABLE_ERROR_HANDLER') ?: false);


// Debugging Settings
Config::define('WP_DEBUG_DISPLAY', false);
Config::define('SCRIPT_DEBUG', false);
ini_set('display_errors', '0');

// Environment configs
$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';
if (file_exists($env_config)) {
  require_once $env_config;
}

Config::apply();

// Bootstrap WordPress
if (!defined('ABSPATH')) {
  define('ABSPATH', $webroot_dir . '/wp/');
}
