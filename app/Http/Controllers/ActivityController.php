<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\WorkerAttendances;
use Illuminate\Http\Request;
use Exception;
use Yajra\DataTables\DataTables;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data(Request $request, $blockID)
    {
        $query = Activities::with(['block', 'workerAttendances'])
            ->where('block_id', $blockID)
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('block', function ($row) {
                return $row->block ? $row->block->block : '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function store(Request $request, $blockID)
    {
        try {
            $activity = Activities::create([
                'block_id' => $blockID,
                'is_block_activity' => $blockID,
                'activity_name' => $blockID,
                // 'date' => $validated['date'],
                'total' => $blockID,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function detailActivity($id)
    {
        try {
            $data = Activities::with('workerAttendances')->find($id);
            return view('block.detailActivity', compact('data', 'id'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }


    public function destroy(Request $request)
    {
        try {
            $data = Activities::find($request->id);

            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Activity has been deleted'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
