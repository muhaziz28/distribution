<?php

namespace App\Http\Controllers;

use App\Models\BlockAttedancesDistribution;
use App\Models\WorkerAttendances;
use Illuminate\Http\Request;
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
                return $row->durasi_kerja * $row->upah;
            })

            ->rawColumns(['action'])
            ->make(true);
    }
}
