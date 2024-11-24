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
        if (!Gate::allows('read-tukangs')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to read tukangs'
            ]);
        }
        $tukangs = Tukang::all();

        return DataTables::of($tukangs)->addIndexColumn()->toJson();
    }


    public function store(Request $request)
    {
        if (!Gate::allows('create-tukangs')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to create tukangs'
            ]);
        }
        try {
            $validate = Validator::make($request->all(), [
                'nama_tukang' => 'required|string|max:255',
                'no_hp' => 'required|digits:12',
            ], [
                'name.required' => 'The nama tukang field is required',
                'no_hp.required' => 'The nomor hp field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ]);
            }

            $tukang = Tukang::create([
                'nama_tukang' => $request->nama_tukang,
                'no_hp' => $request->no_hp,
                'is_active' => false,
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
        if (!Gate::allows('update-tukangs')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update tukangs'
            ]);
        }
        try {
            $validate = Validator::make($request->all(), [
                'nama_tukang' => 'required',
                'no_hp' => 'required',
                'is_active' => 'required',
            ], [
                'name.required' => 'The nama tukang field is required',
                'no_hp.required' => 'The nomor hp field is required',
                'is_active.required' => 'The is active field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ]);
            }

            $tukang = Tukang::find($request->id);
            $tukang->nama_tukang = $request->nama_tukang;
            $tukang->no_hp = $request->no_hp;
            $tukang->is_active = $request->is_active;

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
        if (!Gate::allows('delete-tukangs')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete tukangs'
            ]);
        }
        try {
            $tukang = Tukang::find($request->id);
            $tukang->delete();

            return response()->json([
                'success' => true,
                'message' => 'User ' . $tukang->nama_tukang . ' has been deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
