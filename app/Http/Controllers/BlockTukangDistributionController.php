<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Models\WorkerAssigments;
use Exception;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

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

            $validate = Validator::make($request->all(), [
                'worker_id' => 'required',
                'join_date' => 'required'
            ], [
                'worker_id.required' => 'Pekerja tidak boleh kosong',
                'join_date.required' => 'Tanggal berkerja tidak boleh kosong'
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ]);
            }

            $workerAssign = new WorkerAssigments();
            $workerAssign->block_id = $blockID;
            $workerAssign->worker_id = $request->worker_id;
            $workerAssign->join_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->join_date)->format('Y-m-d');

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
                'message' => 'Tukang berhasil dihapus'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
