<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Block;
use App\Models\BlockAttedancesDistribution;
use App\Models\WorkerAssigments;
use App\Models\WorkerAttendances;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class BlockAttendancesDistributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data(Request $request, $blockID)
    {
        $query = Activities::with("workerAttendances")
            ->where('block_id', $blockID)
            ->get();

        $data = $query->groupBy('date')->map(function ($activities, $date) {
            return [
                'date'  => $date,
                'total' => $activities->sum('total'),
                'activities' => $activities->map(function ($activity) {
                    return [
                        'id'                => $activity->id,
                        'block_id'          => $activity->block_id,
                        'is_block_activity' => $activity->is_block_activity,
                        'activity_name'     => $activity->activity_name,
                        'total'             => $activity->total,
                    ];
                })->values()
            ];
        })->values();

        return DataTables::of($data)
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    // Halaman tambah
    public function addtendancesItem($blockID)
    {
        $workerAssigments = WorkerAssigments::with(['tukang'])
            ->where('block_id', $blockID)
            ->get();

        $today = Carbon::today();

        $presentWorker = WorkerAttendances::whereDate('created_at', $today)
            ->whereIn('worker_id', $workerAssigments->pluck('worker_id'))
            ->pluck('worker_id')
            ->toArray();

        $workerNotPresentToday = $workerAssigments->filter(function ($item) use ($presentWorker) {
            return !in_array($item->worker_id, $presentWorker);
        });

        return view('block.attendances-item', compact('workerNotPresentToday', 'blockID'));
    }

    public function store(Request $request)
    {
        try {
            $blockID = $request->input('blockID');
            $activity = $request->input('activity', []);
            $workers = $request->input('workers', []);

            DB::beginTransaction();

            $todayDate = \Carbon\Carbon::now()->format('Y-m-d');

            $activityData = Activities::where('block_id', $blockID)
                ->whereDate('date', $todayDate)
                ->first();

            if (!$activityData) {
                $activityData = new Activities();
                $activityData->block_id = $blockID;
                $activityData->is_block_activity = $activity['is_block_activity'] ?? 0;
                $activityData->activity_name = $activity['activity_name'] ?? '';
                $activityData->date = $todayDate;
                $activityData->save();
            }

            foreach ($workers as $worker) {
                if (isset($worker['worker_id']) && isset($worker['is_checked']) && isset($worker['durasi_kerja']) && isset($worker['upah'])) {
                    $existingAttendance = WorkerAttendances::where('activity_id', $activityData->id)
                        ->where('worker_id', $worker['worker_id'])
                        ->first();

                    if (!$existingAttendance) {
                        $workerData = new WorkerAttendances();
                        $workerData->activity_id = $activityData->id;
                        $workerData->worker_id = $worker['worker_id'];
                        $workerData->durasi_kerja = $worker['durasi_kerja'];
                        $workerData->upah = $worker['upah'];
                        $workerData->pinjaman = $worker['pinjaman'] ?? null;
                        $workerData->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Data berhasil disimpan"
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
