<?php

namespace App\Http\Controllers;

use App\Models\BlockMaterialDistribution;
use App\Models\BlockTukangDistribution;
use App\Models\Material;
use App\Models\MaterialUpdateLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function return(Request $request, $id)
    {
        try {
            $validate = $this->validateRequest($request);
            if ($validate) return $validate;

            $blockTukang = BlockMaterialDistribution::find($id);

            $result = $blockTukang->distributed_qty - $request->returned_qty;
            if ($result < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ]);
            }

            DB::beginTransaction();
            $blockTukang->returned_qty = $request->returned_qty;
            $blockTukang->returned_date = now();
            $blockTukang->distributed_qty = $result;
            $blockTukang->save();

            $material = Material::find($blockTukang->material_id);
            MaterialUpdateLog::create([
                'material_id'  => $blockTukang->material_id,
                'previous_qty' => $material->qty,
                'new_qty'      => $material->qty + $request->returned_qty,
                'updated_by'   => Auth::user()->id,
                "note"         => "Retur"
            ]);

            $material->qty = $material->qty + $request->returned_qty;
            $material->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dikembalikan'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function validateRequest($request)
    {
        $validate = Validator::make($request->all(), [
            'returned_qty' => 'required|integer',
        ], [
            'returned_qty.required' => 'Jumlah retur tidak boleh kosong',
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
