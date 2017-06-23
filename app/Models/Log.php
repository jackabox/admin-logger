<?php namespace Adtrak\Logger\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Log extends Eloquent {

	protected $table = 'al_logs';

    protected $fillable = [
        'description',
        'type',
        'user_id'
    ];
}
