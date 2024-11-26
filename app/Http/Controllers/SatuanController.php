<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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

    public function data(Request $request)
    {
        if (Gate::allows('read-satuan')) {
            $result = Satuan::search($request->search)->get();
            Log::info($result);

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
        if (!Gate::allows('create-satuan')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }

        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse) return $validationResponse;

            $existingSatuan = Satuan::findIncludingTrashed($request->satuan)->first();

            if ($existingSatuan) {
                if ($existingSatuan->trashed()) {
                    $existingSatuan->restore();
                    $message = $existingSatuan->satuan . ' berhasil dipulihkan.';
                } else {
                    $message = 'Nama satuan sudah digunakan.';
                    return response()->json(['success' => false, 'message' => $message]);
                }
            } else {
                $newSatuan = Satuan::create(['satuan' => $request->satuan]);
                $message = $newSatuan->satuan . ' berhasil ditambahkan.';
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function update(Request $request)
    {
        if (Gate::allows('update-satuan')) {
            try {
                $satuan = Satuan::findOrFail($request->id);

                $isDuplicate = Satuan::checkDuplicate($request->satuan, $satuan->id)->exists();
                if ($isDuplicate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nama satuan sudah digunakan'
                    ]);
                }

                $satuan->update([
                    'satuan' => $request->satuan
                ]);

                return response()->json([
                    'success' => true,
                    'message' => $satuan->satuan . ' berhasil diupdate'
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

    public function restore(Request $request)
    {
        if (!Gate::allows('create-satuan')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }

        Log::info($request->all());
        try {
            $satuan = Satuan::withTrashed()
                ->where('satuan', $request->satuan)
                ->first();

            if (!$satuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data satuan tidak ditemukan.',
                ]);
            }

            if (!$satuan->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data satuan sudah aktif.',
                ]);
            }

            $satuan->restore();

            return response()->json([
                'success' => true,
                'message' => $satuan->satuan . ' berhasil dipulihkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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
