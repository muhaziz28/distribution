<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Gate::allows('read-vendor')) return view('vendor.index');
        return view('error.unauthorize');
    }

    public function data(Request $request)
    {
        if (Gate::allows('read-vendor')) {
            $result = Vendor::search($request->search)->get();

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
        if (!Gate::allows('create-vendor')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }

        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse) return $validationResponse;

            $result = Vendor::create([
                'nama_vendor' => $request->nama_vendor,
                'alamat'      => $request->alamat,
                'kontak'      => $request->kontak,
            ]);
            $message = $result->nama_vendor . ' berhasil ditambahkan.';

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function update(Request $request)
    {
        if (Gate::allows('update-vendor')) {
            try {
                $validationResponse = $this->validateRequest($request);
                if ($validationResponse) return $validationResponse;

                $vendor = Vendor::findOrFail($request->id);
                $vendor->update([
                    'nama_vendor' => $request->nama_vendor,
                    'alamat'      => $request->alamat,
                    'kontak'      => $request->kontak,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => $vendor->nama_vendor . ' berhasil diupdate'
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
        if (Gate::allows('delete-vendor')) {
            try {
                $result = Vendor::find($request->id);
                $result->delete();

                return response()->json([
                    'success' => true,
                    'message' => $result->nama_vendor . ' berhasil dihapus'
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
            'nama_vendor' => 'required',
            'kontak'      => 'required|max_digits:15',
        ], [
            'nama_vendor.required' => 'Nama vendor wajib diisi',
            'kontak.required'      => 'Kontak vendor wajib diisi',
            'kontak.max_digits'    => 'Kontak tidak boleh lebih dari 15 karakter',
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
