<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsController extends Controller
{
    public function index()
    {
        $logs = Activity::paginate(7);
        return view('activitylogs', compact('logs'));
    }

}
