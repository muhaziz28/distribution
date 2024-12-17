<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialUpdateLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Gate::allows('read-material')) return view('material.index');
        return view('error.unauthorize');
    }

    public function data(Request $request)
    {
        if (Gate::allows('read-material')) {
            $result = Material::with(['bahan.satuan', 'vendor'])
                ->addSelect([
                    'previous_qty' => MaterialUpdateLog::select('previous_qty')
                        ->whereColumn('material_update_logs.material_id', 'materials.id')
                        ->latest()
                        ->limit(1)
                ])
                ->addSelect([
                    'new_qty' => MaterialUpdateLog::select('new_qty')
                        ->whereColumn('material_update_logs.material_id', 'materials.id')
                        ->latest()
                        ->limit(1)
                ])
                ->get();

            return DataTables::of($result)->addIndexColumn()->toJson();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }
}
