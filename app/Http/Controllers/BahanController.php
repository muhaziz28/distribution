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
            $bahan = Bahan::get();
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
        if (Gate::allows('create-bahan')) {
            try {
                $validate = Validator::make($request->all(), [
                    'nama_bahan' => 'required',
                    'qty' => 'integer',
                ], [
                    'nama_bahan.required' => 'Nama bahan tidak boleh kosong',
                    'qty.integer' => 'Qty harus berupa angka'
                ]);

                if ($validate->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validate->errors()->first()
                    ]);
                }

                $result = Bahan::create([
                    'nama_bahan' => $request->nama_bahan,
                    'qty' => $request->qty ?? 0,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => $result->nama_bahan . ' berhasil ditambahkan',
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
}
