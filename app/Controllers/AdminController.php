<?php namespace Adtrak\Logger\Controllers;

use Adtrak\Logger\View;
use Adtrak\Logger\Models\Log;

class AdminController {

	public function __construct()
    {

    }

    public function menu()
    {
        \add_menu_page(
            'Logs',
            'Logs',
            'manage_options',
            'adtrak-logger',
            [$this, 'showLogs']
        );
    }

    public function showLogs()
    {
        $logs = Log::all();

        View::render('admin-logs.twig', [
            'logs' => $logs
        ]);
    }
}
