<?php
/** @var  \Billy\Framework\Enqueue $enqueue */

use Illuminate\Database\Capsule\Manager as Capsule;
use Adtrak\Logger\Helper;

$version = get_option('al_version');

if ($version === false) {
    if(! Capsule::schema()->hasTable('al_logs')) {
        Capsule::schema()->create('al_logs', function($table) {
            $table->increments('id');
            $table->text('description')->nullable(true);
            $table->string('type', 225)->nullable(true);
            $table->string('ip', 225);
            $table->integer('user_id')->unsigned();
		    // $table->foreign('user_id')->references('ID')->on('users');
            $table->timestamps();
        });
    }

    add_option('al_version', Helper::get('version'));
}
