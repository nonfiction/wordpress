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
Config::define( 'ROOT_DIR', dirname(__DIR__) );

// Use Dotenv to set required environment variables and load .env file in root
$dotenv = Dotenv\Dotenv::createUnsafeImmutable( Config::get('ROOT_DIR') );
if (file_exists(Config::get('ROOT_DIR') . '/.env')) {
  $dotenv->load();
}

// Set up our global environment constant and load its config first
Config::define( 'WP_ENV', env('WP_ENV') ?: 'production' );

// Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
// See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
  $_SERVER['HTTPS'] = 'on';
}

// Swarm host primary
Config::define( 'HOST', strtolower(env('HOST')) );
Config::define( 'HOST_SLUG', str_replace([' ', '-', '.'], '_', Config::get('HOST')) );

// App name
Config::define( 'APP', env('APP') ?: strtok(Config::get('HOST'), '.') );
Config::define( 'APP_SLUG', str_replace([' ', '-', '.'], '_', Config::get('APP')) );

// URLs
Config::define( 'WP_HOME', env('WP_HOME') ?: ( (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://' ) . env('HTTP_HOST') );
Config::define( 'WP_SITEURL', env('WP_SITEURL') ?: Config::get('WP_HOME') . '/wp' );

// Custom Content Directory
Config::define( 'WEBROOT_DIR', Config::get('ROOT_DIR') . '/web' );
Config::define( 'CONTENT_DIR', '/content' );
Config::define( 'WP_CONTENT_DIR', Config::get('WEBROOT_DIR') . Config::get('CONTENT_DIR'));
Config::define( 'WP_CONTENT_URL', Config::get('WP_HOME') . Config::get('CONTENT_DIR'));

// DB settings
Config::define( 'DB_NAME', env('DB_NAME') ?: (env(strtoupper('DB_NAME_'.Config::get('WP_ENV'))) ?: Config::get('HOST_SLUG')) );
Config::define( 'DB_USER', env('DB_USER') ?: Config::get('APP_SLUG') );
Config::define( 'DB_PASSWORD', env('DB_PASSWORD') );
Config::define( 'DB_HOST', env('DB_HOST') ?: 'localhost' );
Config::define( 'DB_CHARSET', 'utf8mb4' );
Config::define( 'DB_COLLATE', '' );
$table_prefix = env('DB_PREFIX') ?: 'wp_';

// Reddis Cache settings
$redis_server = [ 'host' => 'redis' ];

// Enable caching
Config::define( 'WP_CACHE', env('WP_CACHE') ?: true );

// Authentication Unique Keys and Salts
foreach([ 
  'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 
  'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT', 
] as $key) {
  // If a key defined in the env, use that value. Otherwise, generate a value
  Config::define( $key, env($key) ?: hash('sha256', $key . Config::get('HOST') . Config::get('DB_PASSWORD')) );
}

// Custom Settings
Config::define( 'AUTOMATIC_UPDATER_DISABLED', env('AUTOMATIC_UPDATER_DISABLED') ?: true );
Config::define( 'DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: true );
Config::define( 'DISALLOW_FILE_EDIT', env('DISALLOW_FILE_EDIT') ?: true ); // Disable the plugin and theme file editor in the admin
Config::define( 'DISALLOW_FILE_MODS', env('DISALLOW_FILE_MODS') ?: true ); // Disable plugin and theme updates and installation from the admin
Config::define( 'FS_METHOD', env('FS_METHOD') ?: 'direct' );

// Query Monitor Settings
Config::define( 'QM_DARK_MODE', env('QM_DARK_MODE') ?: true );
Config::define( 'QM_ENABLE_CAPS_PANEL', env('QM_ENABLE_CAPS_PANEL') ?: true );
Config::define( 'QM_DB_SYMLINK', env('QM_DB_SYMLINK') ?: false );
Config::define( 'QM_DISABLE_ERROR_HANDLER', env('QM_DISABLE_ERROR_HANDLER') ?: false ); // https://querymonitor.com/blog/2018/07/silencing-errors-from-plugins-and-themes/

// Debugging Settings
Config::define( 'WP_DEBUG_DISPLAY', false );
Config::define( 'SCRIPT_DEBUG', false );
ini_set( 'display_errors', '0' );

// Environment configs
if ( file_exists(__DIR__ . '/environments/' . Config::get('WP_ENV') . '.php') ) {
  require_once __DIR__ . '/environments/' . Config::get('WP_ENV') . '.php';
}

Config::apply();
