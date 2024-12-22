<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\WorkerAttendaces;
use App\Models\WorkerGroup;
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
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now();

        $dates = [];
        while ($startOfMonth <= $today) {
            $dates[] = $startOfMonth->format('Y-m-d');
            $startOfMonth->addDay();
        }
        return view('absensi.index', compact('activity', 'dates', "activityID"));
    }

    public function getDates()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now();

        $dates = [];

        while ($startOfMonth <= $today) {
            $dates[] = [
                'date' => $startOfMonth->format('Y-m-d'),
                'day' => $startOfMonth->format('l'),
            ];
            $startOfMonth->addDay();
        }
        return response()->json(['data' => $dates]);
    }

    public function tambahAbsensi($activityID)
    {
        $worker = WorkerGroup::with("tukang")->doesntHave("workerAttendances")->get();

        return view('absensi.tambah-absensi', compact('worker', 'activityID'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'worker_group_id' => 'required|array',
            'durasi_kerja' => 'required|array',
            'worker_group_id.*' => 'exists:worker_groups,id',
            'durasi_kerja.*' => 'in:0,0.5,1',
        ]);


        foreach ($validated['worker_group_id'] as $key => $worker_id) {
            WorkerAttendaces::create([
                'worker_group_id' => $worker_id,
                'durasi_kerja' => $validated['durasi_kerja'][$key],
                'tanggal' => Carbon::now()->format('y-m-d'),
            ]);
        }

        return redirect()->back();
    }
}
