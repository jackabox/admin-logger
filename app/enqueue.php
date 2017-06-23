<?php namespace Adtrak\Logger;

/** @var \Billy\Framework\Enqueue $enqueue */

$enqueue->admin([
	'as'  => 'adtrak-logger',
	'src' => Helper::assetUrl('css/logs.css')
]);
