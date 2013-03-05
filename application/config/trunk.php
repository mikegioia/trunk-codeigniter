<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// all of the trunk defaults. these are overwritten in config.local.
//
$config[ 'environment' ] = ENVIRONMENT;
$config[ 'load_mysql' ] = FALSE;
$config[ 'load_mongo' ] = FALSE;
$config[ 'asset_version' ] = 1;
$config[ 'release_version' ] = '1.0.0';
$config[ 'html_title' ] = "My App";
$config[ 'tagline' ] = 'Tag line';
$config[ 'admin_mode' ] = FALSE;
$config[ 'js_environment' ] = 'development';
$config[ 'google_analytics_key' ] = '';
$config[ 'nav_buttons' ] = array();


// application constants
//
define( 'ERROR', 'error' );
define( 'INFO', 'info' );
define( 'SUCCESS', 'success' );

define( 'INT', 'int' );
define( 'STRING', 'string' );