<?php

namespace App\Http\Controllers;

use App\Models\BlockMaterialDistribution;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BlockMaterialDistributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data(Request $request, $blockID)
    {
        $data = BlockMaterialDistribution::with('material.bahan.satuan', 'material.materialPurchaseItem')->where('block_id', $blockID)->get();

        return DataTables::of($data)->addIndexColumn()->toJson();
    }
}
