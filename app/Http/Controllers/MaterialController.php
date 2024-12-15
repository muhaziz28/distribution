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

    // public function store(Request $request)
    // {
    //     if (!Gate::allows('create-material')) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'You are unauthorized to access this resource'
    //         ]);
    //     }

    //     try {
    //         $validationResponse = $this->validateRequest($request);
    //         if ($validationResponse) return $validationResponse;

    //         $result = Vendor::create([
    //             'nama_vendor' => $request->nama_vendor,
    //             'alamat'      => $request->alamat,
    //             'kontak'      => $request->kontak,
    //         ]);
    //         $message = $result->nama_vendor . ' berhasil ditambahkan.';

    //         return response()->json(['success' => true, 'message' => $message]);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage()]);
    //     }
    // }


    // public function update(Request $request)
    // {
    //     if (Gate::allows('update-material')) {
    //         try {
    //             $validationResponse = $this->validateRequest($request);
    //             if ($validationResponse) return $validationResponse;

    //             $vendor = Vendor::findOrFail($request->id);
    //             $vendor->update([
    //                 'nama_vendor' => $request->nama_vendor,
    //                 'alamat'      => $request->alamat,
    //                 'kontak'      => $request->kontak,
    //             ]);

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => $vendor->nama_vendor . ' berhasil diupdate'
    //             ]);
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => $e->getMessage()
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'You are unauthorized to access this resource'
    //         ]);
    //     }
    // }

    // public function destroy(Request $request)
    // {
    //     if (Gate::allows('delete-material')) {
    //         try {
    //             $result = Vendor::find($request->id);
    //             $result->delete();

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => $result->nama_vendor . ' berhasil dihapus'
    //             ]);
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => $e->getMessage()
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'You are unauthorized to access this resource'
    //         ]);
    //     }
    // }
}
