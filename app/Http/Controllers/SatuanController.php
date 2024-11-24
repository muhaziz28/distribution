<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SatuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Gate::allows('read-satuan')) return view('satuan.index');
        return view('error.unauthorize');
    }

    public function data()
    {
        if (Gate::allows('read-satuan')) {
            $result = Satuan::get();
            return DataTables::of($result)->addIndexColumn()->toJson();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('create-satuan')) {
            try {
                $validationResponse = $this->validateRequest($request);
                if ($validationResponse) {
                    return $validationResponse;
                }

                $exitingOnTrash = Satuan::withTrashed()->where('satuan', $request->satuan)->first();

                if ($exitingOnTrash) {
                    $exitingOnTrash->restore();

                    return response()->json([
                        'success' => true,
                        'message' => $exitingOnTrash->satuan . ' berhasil ditambahkan'
                    ]);
                }
                $exiting = Satuan::where('satuan', $request->satuan)->first();
                if (!$exiting) {
                    $result = Satuan::create([
                        'satuan' => $request->satuan,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => $result->satuan . ' berhasil ditambahkan',
                        'data' => null
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nama satuan sudah digunakan'
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }

    public function update(Request $request)
    {
        if (Gate::allows('update-satuan')) {
            try {
                $validationResponse = $this->validateRequest($request);
                if ($validationResponse) {
                    return $validationResponse;
                }

                $satuan = Satuan::find($request->id);
                if ($satuan) {
                    $exiting = Satuan::where('satuan', $request->satuan)->first();
                    if ($exiting) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Nama satuan sudah digunakan'
                        ]);
                    }
                    $satuan->satuan = $request->satuan;
                    $satuan->save();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Satuan tidak ditemukan'
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => $satuan->satuan . ' berhasil diupdate',
                    'data' => null
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        if (Gate::allows('delete-satuan')) {
            try {
                $result = Satuan::find($request->id);
                $result->delete();

                return response()->json([
                    'success' => true,
                    'message' => $result->satuan . ' berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }

    private function validateRequest($request)
    {
        $validate = Validator::make($request->all(), [
            'satuan' => 'required'
        ], [
            'satuan.required' => 'Nama satuan wajib diisi'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ]);
        }

        return null;
    }
}
