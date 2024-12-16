<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Block;
use App\Models\BlockAttedancesDistribution;
use App\Models\WorkerAssigments;
use App\Models\WorkerAttendances;
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
        $query = WorkerAttendances::with(['tukang', 'activity'])
            ->where('worker_id', $blockID)
            ->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return $row->activity ? $row->activity->date : '-';
            })
            ->addColumn('total', function ($row) {
                return $row->durasi_kerja * $row->upah - $row->pinjaman;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Halaman tambah
    public function addtendancesItem($blockID)
    {
        $workerAttendances = WorkerAssigments::with(['tukang'])
            ->where('block_id', $blockID)
            // ->whereHas('activity', function ($query) use ($blockID) {
            //     $query->where('block_id', $blockID);
            // })
            ->get();

        return view('block.attendances-item', compact('workerAttendances', 'blockID'));
    }

    public function store(Request $request)
    {
        try {
            $blockID = $request->input('blockID');
            $activity = $request->input('activiy', []);
            $workers = $request->input('workers', []);

            DB::beginTransaction();
            $activityData = new Activities();
            $activityData->block_id = $blockID;
            $activityData->is_block_activity = isset($activity['is_block_activity']) ? $activity['is_block_activity'] : 0;
            $activityData->activity_name = $activity['activity_name'] ?? '';
            $activityData->date = \Carbon\Carbon::now()->format('Y-m-d');
            $activityData->total = 0;
            $activityData->save();

            foreach ($workers as $worker) {
                if (isset($worker['worker_id']) && isset($worker['is_checked']) && isset($worker['durasi_kerja']) && isset($worker['upah'])) {
                    $workerData = new WorkerAttendances();
                    $workerData->activity_id = $activityData->id;
                    $workerData->worker_id = $worker['worker_id'];
                    $workerData->durasi_kerja = $worker['durasi_kerja'];
                    $workerData->upah = $worker['upah'];
                    $workerData->pinjaman = $worker['pinjaman'] ?? null;
                    $workerData->save();
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Data berhasil disimpan"
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
