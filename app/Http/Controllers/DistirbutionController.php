<?php

namespace App\Http\Controllers;

use App\Models\BlockMaterialDistribution;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DistirbutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function distribute(Request $request)
    {
        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse) return $validationResponse;

            if ($request->distributed_qty <= 0) {
                return response()->json(
                    ['success' => false, 'message' => "Jumlah material tidak boleh bernilai 0"],
                    400
                );
            }

            DB::beginTransaction();
            $blockMaterial = new BlockMaterialDistribution();
            $blockMaterial->block_id = $request->block_id;
            $blockMaterial->material_id = $request->material_id;
            $blockMaterial->distributed_qty = $request->distributed_qty;
            $blockMaterial->distributed_date = now();
            $blockMaterial->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Material berhasil didistribusikan",
            ]);
        } catch (Exception $e) {
            return response()->json(
                ['success' => false, 'message' => $e->getMessage()],
                500
            );
        }
    }

    private function validateRequest($request)
    {
        $validate = Validator::make($request->all(), [
            'block_id' => 'required|exists:blocks,id',
            'material_id' => 'required|exists:materials,id',
            'distributed_qty' => 'required',
        ], [
            'block_id.required' => 'Block tidak boleh kosong',
            'block_id.exist' => 'Block tidak ditemukan',
            'material_id.required' => 'Material tidak boleh kosong',
            'material_id.exist' => 'Material tidak ditemukan',
            'distributed_qty' => "Jumlah material tidak boleh kosong",
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
