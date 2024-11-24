<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BahanController extends Controller
{
    public function index()
    {
        if (Gate::allows('read-bahan')) return view('bahan.index');
        return view('error.unauthorize');
    }

    public function data()
    {
        if (Gate::allows('read-bahan')) {
            $bahan = Bahan::forDataTable()->get();
            return DataTables::of($bahan)->addIndexColumn()->toJson();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }

    public function store(Request $request)
    {
        if (!Gate::allows('create-bahan')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }

        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse) return $validationResponse;

            $existingBahan = Bahan::findIncludingTrashed($request->bahan)->first();

            if ($existingBahan) {
                if ($existingBahan->trashed()) {
                    $existingBahan->restore();
                    $message = $existingBahan->nama_bahan . ' berhasil dipulihkan.';
                } else {
                    $message = 'Nama bahan sudah digunakan.';
                    return response()->json(['success' => false, 'message' => $message]);
                }
            } else {
                $newBahan = Bahan::create([
                    'nama_bahan' => $request->nama_bahan,
                    'qty'        => $request->qty,
                    'satuan_id'  => $request->satuan_id,
                ]);
                $message = $newBahan->nama_bahan . ' berhasil ditambahkan.';
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function update(Request $request)
    {
        if (Gate::allows('update-bahan')) {
            try {
                $bahan = Bahan::findOrFail($request->id);

                $isDuplicate = Bahan::checkDuplicate($request->nama_bahan, $bahan->id)->exists();
                if ($isDuplicate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nama bahan sudah digunakan'
                    ]);
                }

                $bahan->update([
                    'nama_bahan' => $request->nama_bahan,
                    'qty'         => $request->qty ?? 0,
                    'satuan_id'   => $request->satuan_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => $bahan->nama_bahan . ' berhasil diupdate',
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
        if (Gate::allows('delete-bahan')) {
            try {
                $result = Bahan::findOrFail($request->id);
                $result->delete();

                return response()->json([
                    'success' => true,
                    'message' => $result->nama_bahan . ' berhasil dihapus'
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
            'nama_bahan'    => 'required',
            'qty'           => 'integer',
            'satuan_id'     => 'required|integer'
        ], [
            'nama_bahan.required'   => 'Nama bahan tidak boleh kosong',
            'qty.integer'           => 'Qty harus berupa angka',
            'satuan_id.required'    => 'Satuan tidak boleh kosong'
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
