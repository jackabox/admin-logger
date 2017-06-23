<?php namespace Adtrak\Logger;

/** @var \Billy\Framework\Loader $loader */

$admin = new Controllers\AdminController;

$loader->action([
	'method' 	=> 'admin_menu',
	'uses' 		=> [$admin, 'menu'],
]);


$logger = new Controllers\LogController;

$loader->action([
	'method' 	=> 'save_post',
	'uses' 		=> [$logger, 'savePost'],
    'priority'  => 90,
    'args'      => 3
]);

$loader->action([
	'method' 	=> 'edit_post',
	'uses' 		=> [$logger, 'editPost'],
    'priority'  => 90,
    'args'      => 2
]);

$loader->action([
	'method' 	=> 'delete_post',
	'uses' 		=> [$logger, 'deletePost'],
    'priority'  => 10,
]);

$loader->action([
    'method'    => 'switch_theme',
    'uses'      => [$logger, 'switchTheme'],
    'priority'  => 10,
    'args'      => 2
]);
