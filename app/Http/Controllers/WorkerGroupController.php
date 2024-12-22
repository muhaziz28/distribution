<?php

namespace App\Http\Controllers;

use App\Models\WorkerGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class WorkerGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data($activityID)
    {
        $result = WorkerGroup::with('tukang')->where('activity_id', $activityID)->get();

        return DataTables::of($result)->addIndexColumn()->toJson();
    }

    public function store(Request $request)
    {
        try {
            $validate = $this->validateRequest($request);
            if ($validate) return $validate;

            WorkerGroup::create([
                'tukang_id'     => $request->worker_id,
                'activity_id'   => $request->activity_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
            ]);
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
            $result = WorkerGroup::find($request->id);
            $result->delete();

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

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'worker_id'     => 'required',
            'activity_id'   => 'required',
        ], [
            'worker_id.required' => 'Tukang tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        return null;
    }
}
