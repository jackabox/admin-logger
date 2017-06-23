<?php
/**
 * @wordpress-plugin
 * Plugin Name: 	Logger
 * Plugin URI: 		https://github.com/adtrak/admin-logger
 * Description: 	Admin Logger.
 * Version: 		1.0.0b
 * Author: 			Jack Whiting
 * Author URI: 		https://jackwhiting.co.uk
 * License: 		GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     billy
 */

# if this file is called directly, abort
if (! defined( 'WPINC' )) die;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/getbilly/framework/bootstrap/autoload.php';
