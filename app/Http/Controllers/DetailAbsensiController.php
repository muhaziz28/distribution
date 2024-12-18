<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DetailAbsensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($activityID)
    {
        $activity = Activities::find($activityID);
        return view('absensi.index', compact('activity'));
    }
}
