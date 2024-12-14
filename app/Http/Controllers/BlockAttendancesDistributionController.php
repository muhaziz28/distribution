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
        $query = WorkerAttendances::with(['block', 'tukang', 'activity'])
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
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-warning edit" data-id="' . $row->id . '">
                        <i class="fas fa-pen mr-2"></i>Return</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
