<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Models\BlockTukangDistribution;
use App\Models\Tukang;
use App\Models\WorkerAssigments;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class BlockTukangDistributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data(Request $request, $blockTukangId)
    {
        $query = WorkerAssigments::with(['block', 'tukang'])
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

    public function store(Request $request, $blockID)
    {
        try {
            $block = Block::find($blockID);
            if (!$block) {
                return response()->json([
                    'success' => false,
                    'message' => "Block tidak ditemukan"
                ], 404);
            }

            $workerAssign = new WorkerAssigments();
            $workerAssign->block_id = $blockID;
            $workerAssign->tukang_id = $request->worker_id;
            $workerAssign->join_date = $request->join_date;

            $workerAssign->save();

            return response()->json([
                'success' => true,
                'message' => 'Tukang berhasil ditambahkan'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = WorkerAssigments::find($request->id);

            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Tukang has been deleted'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
