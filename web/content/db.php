<?php
$db_file = dirname( __FILE__ ) . '/plugins/query-monitor/wp-content/db.php';
if ( file_exists( $db_file ) ) {
  require_once $db_file;
}
