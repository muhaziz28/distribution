<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data(Request $request, $blockID)
    {
        $query = Activities::where('block_id', $blockID)->get();

        return DataTables::of($query)->addIndexColumn()->toJson();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            Activities::create([
                'block_id'          => $request->block_id,
                'is_block_activity' => $request->is_block_activity,
                'activity_name'     => $request->activity_name,
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Data berhasil ditambahkan"
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $activity = Activities::find($request->id);

            $activity->is_block_activity = $request->is_block_activity;
            $activity->activity_name = $request->activity_name;
            $activity->save();

            return response()->json([
                'success' => true,
                'message' => "Data berhasil diubah"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $activity = Activities::find($request->id);
            $activity->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
