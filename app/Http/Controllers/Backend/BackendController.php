<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Modules\Reporting\Entities\Report;
use Carbon\Carbon;

class BackendController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $today = Carbon::today(); // Get the current date (today)

        $open_reports_count = Report::where('status',"!=","Selesai")->count();
        $today_reports_count = Report::whereDate('created_at', $today)->count();

        return view('backend.index',
            compact('today_reports_count','open_reports_count')
        );
    }
}
