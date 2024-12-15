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
            ->where('tukang_id', $blockID)
            ->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_tukang', function ($row) {
                return $row->tukang ? $row->tukang->nama_tukang : '-';
            })
            ->addColumn('no_hp', function ($row) {
                return $row->tukang ? $row->tukang->no_hp : '-';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
}
