<?php

namespace App\Http\Controllers;

use App\Models\WorkerAttendaces;
use App\Models\WorkerGroup;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WorkerAttendaceController extends Controller
{
    public function data($activityID)
    {
        $result = WorkerGroup::with("tukang", "workerAttendances")->where('activity_id', $activityID)->get();

        return DataTables::of($result)->addIndexColumn()->toJson();
    }
}
