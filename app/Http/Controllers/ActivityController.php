<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\WorkerAttendances;
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


    public function detailActivity($id)
    {

        try {
            $data = WorkerAttendances::with('activity')
                ->where('activity_id', $id)
                ->get();

            return view('block.detailActivity', compact('data', 'id'));
        } catch (Exception $e) {
            // return redirect()->back();
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
