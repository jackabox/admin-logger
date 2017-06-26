<?php namespace Adtrak\Logger;

/** @var \Billy\Framework\Loader $loader */


$version = get_option('al_version', false);
$admin = new Controllers\AdminController;

$loader->action([
	'method' 	=> 'admin_menu',
	'uses' 		=> [$admin, 'menu'],
]);


$logger = new Controllers\LogController;

$loader->action([
	'method' 	=> 'admin_init',
	'uses' 		=> [$logger, 'getUserData']
]);

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
]);

$loader->action([
    'method'    => 'switch_theme',
    'uses'      => [$logger, 'switchTheme'],
    'args'      => 2
]);

$loader->action([
    'method'    => 'wp_login',
    'uses'      => [$logger, 'userLogin'],
    'args'      => 2
]);

if ($version !== false) {
    $loader->action([
        'method'    => 'activated_plugin',
        'uses'      => [$logger, 'activatedPlugin'],
        'args'      => 10
    ]);
}

$loader->action([
    'method'    => 'deactivated_plugin',
    'uses'      => [$logger, 'activatedPlugin'],
    'args'      => 10
]);

$loader->action([
    'method'    => '_core_updated_successfully',
    'uses'      => [$logger, 'coreUpdated'],
    'args'      => 10
]);

$loader->action([
    'method'    => 'user_register',
    'uses'      => [$logger, 'userCreated']
]);
