<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tukang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class TukangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tukang.index');
    }

    public function data()
    {
        if (!Gate::allows('read-tukang')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        $tukangs = Tukang::all();

        return DataTables::of($tukangs)->addIndexColumn()->toJson();
    }

    public function dataForWorker()
    {
        $tukangs = Tukang::doesntHave('workerGroup')->get();

        return DataTables::of($tukangs)->addIndexColumn()->toJson();
    }


    public function store(Request $request)
    {
        if (!Gate::allows('create-tukang')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        try {
            $validate = $this->validateRequest($request);
            if ($validate) return $validate;

            $tukang = Tukang::create([
                'nama_tukang' => $request->nama_tukang,
                'no_hp' => $request->no_hp,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tukang ' . $tukang->nama_tukang . ' has been created',
                'data' => $tukang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        if (!Gate::allows('update-tukang')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        try {
            $validate = $this->validateRequest($request);
            if ($validate) return $validate;

            $tukang = Tukang::find($request->id);
            $tukang->nama_tukang = $request->nama_tukang;
            $tukang->no_hp = $request->no_hp;
            $tukang->save();

            return response()->json([
                'success' => true,
                'message' => 'Tukang ' . $tukang->nama_tukang . ' has been updated',
                'data' => $tukang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function destroy(Request $request)
    {
        if (!Gate::allows('delete-tukang')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        try {
            $tukang = Tukang::find($request->id);
            $tukang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tukang ' . $tukang->nama_tukang . ' berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function validateRequest($request)
    {
        $validate = Validator::make($request->all(), [
            'nama_tukang'   => 'required|string|max:255',
            'no_hp'         => 'nullable|max_digits:15',
        ], [
            'name.required'     => 'Nama tukang wajib diisi',
            'no_hp.max_digits'  => 'Nomor Hp tidak valid',
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
