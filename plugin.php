<?php
/**
 * @wordpress-plugin
 * Plugin Name: 	Admin Logger
 * Plugin URI: 		https://github.com/adtrak/admin-logger
 * Description: 	Tracks login, theme changes, plugin changes and post/page updates.
 * Version: 		1.0.3
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

require __DIR__ . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/adtrak/admin-logger',
    __FILE__,
    'admin-logger'
);

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('8c38432528db4bcd4c7c59e320c5c7a913e4e803');

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');
