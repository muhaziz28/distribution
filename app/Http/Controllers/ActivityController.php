<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    

    public function data(Request $request, $blockTukangId)
    {
        $query = Activities::with(['block', 'tukang'])
            ->where('block_id', $blockTukangId)
            ->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_tukang', function ($row) {
                return $row->tukang ? $row->tukang->nama_tukang : '-';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-warning edit" data-id="' . $row->id . '">
                        <i class="fas fa-pen mr-2"></i>Return</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
