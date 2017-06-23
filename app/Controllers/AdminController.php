<?php namespace Adtrak\Logger\Controllers;

use Adtrak\Logger\View;
use Adtrak\Logger\Models\Log;
use Billy\Framework\Facades\DB;

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
        $limit = 40;
        $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
        $offset = $limit * ($pagenum - 1);
        $total = Log::count();
        $totalPages = ceil($total / $limit);

        # get the location results, offset and take only the limit
        $types = Log::query();
        $types = $types->select(DB::raw('DISTINCT(type) AS type'))->get(['type'])->toArray();

        $users = Log::query();
        $users = $users->select(DB::raw('DISTINCT(user_id) AS user'))->get(['user'])->toArray();

        $logs = Log::query();

        $filters['page'] = $_GET['page'];

        if (isset($_REQUEST['filter_user']) && $_REQUEST['filter_user']) {
            $logs = $logs->where('user_id', '=', $_REQUEST['filter_user']);
            $filters['user'] = $_REQUEST['filter_user'];
        } else {
            $filters['user'] = '';
        }

        if (isset($_REQUEST['filter_type']) && $_REQUEST['filter_type']) {
            $logs = $logs->where('type', '=', $_REQUEST['filter_type']);
            $filters['type'] = $_REQUEST['filter_type'];
        } else {
            $filters['type'] = '';
        }

        $logs = $logs->orderBy('created_at', 'desc')
             ->skip($offset)
             ->take($limit)
             ->get();

        View::render('admin-logs.twig', [
            'logs' => $logs,
            'types' => $types,
            'users' => $users,
            'filters' => $filters
        ]);
    }
}
